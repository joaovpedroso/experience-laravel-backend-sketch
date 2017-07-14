<?php

namespace App\Http\Controllers\Camper;

use App\Http\Controllers\Config\HelperController;
use App\Http\Controllers\Controller;
use App\Http\Requests\CamperRequest;
use App\Models\Camp;
use App\Models\Camper;
use App\Models\CamperCamp;
use App\Models\Func;
use App\Models\GroupReflection;
use App\Models\Phone;
use Carbon\Carbon;
use function GuzzleHttp\Promise\is_fulfilled;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\View\View;
use Intervention\Image\Image;
use Intervention\Image\ImageManagerStatic;
use Validator;
use PDF;

class CamperController extends Controller
{

    private $moveTo = 'img/camper';

    /**
     * CamperController constructor.
     */
    public function __construct()
    {
        $this->middleware('role:camper');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $camper = Camper::name($request->name)
            ->startDate($request->start_date)
            ->endDate($request->end_date)
            ->dizimista($request->dizimista)
            ->reflexao($request->reflexao_id)
            ->paginate();

        $groupReflection = GroupReflection::pluck('name', 'id');

        return view('backend.camper.index', compact('camper', 'groupReflection'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $groupReflection = GroupReflection::pluck('name', 'id');
        $campers = Camper::all();
        $campOfCamper = Camp::pluck('name', 'id');
        $func_id = Func::pluck('name', 'id');
        $groupReflection = GroupReflection::pluck('name', 'id');


        return view('backend.camper.create', compact('groupReflection', 'campOfCamper', 'campers', 'func_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CamperRequest $request)
    {
        // Verificar se é duplicado
        $duplicate = Camper::withTrashed()->where('cpf', $request->cpf)->first();

        // Se duplicado, cancela.
        if (isset($duplicate)) {
            session()->flash('error', 'Existe CPF duplicado para este que tentou inserir, verifique.');
            return redirect()->back();
        }

        // pegar o dia, mes e ano.
        $parts = explode('/', $request->birth);

        // Formatar
        $request['birth'] = date('Y-m-d', strtotime(Carbon::createFromDate($parts[2], $parts[1], $parts[0])));

        if ($request->partCamp == 1) {
            $request['angels'] = [];
            $request['participou'] = [];
        }

        if ($request->partCamp <> 1) {
           if (!isset($request->participou[0])) {
               return redirect()->back()->withErrors('Não inserido nenhum acampamento como participante para este campista.');
           }
        }


        if ($request->hasFile('user_photo')) {
            //filename
            $filename = str_slug($request->name);
            $filename .= '-' . uniqid() . '.';
            $filename .= $request->file('user_photo')->getClientOriginalExtension();
            //destination folder
            $path = public_path() . "/img/camper/";
            //crop the image
            $width = intval($request->width);
            $height = intval($request->height);
            $x = intval($request->x);
            $y = intval($request->y);
            $img = ImageManagerStatic::make($request->file('user_photo'));
            $img->crop($width, $height, $x, $y);
            $img->resize(200, 200);
            $img->save($path.$filename, 80);
            //data to save
            $request['photo'] = 'img/camper/'.$filename;
        }

        $camper = Camper::create($request->except('_token'));

        // Inserir telefones

        if (isset($request->phone[0])) {
            $validator = Validator::make($request->all(), ['phone.*' => 'required']);
            if ($validator->fails()) {
                session()->flash('error', 'Usuário inserido com sucesso, mas ocorreu erro ao inserir o telefone. Verifique.');
                return redirect()->back()->withErrors($validator->errors());
            }

            // Se possuir deleta todos.
            if (Phone::where('camper_id', $camper->id)->first()) {
                Phone::where('camper_id', $camper->id)->delete();
            }

            // Vamos inserir novos.
            $inputs[] = Input::except(['_token']);
            $values = [];
            $cont = 0;

            foreach ($inputs[0]['phone'] as $key => $r) {

                $val2['phone'] = $inputs[0]['phone'][$cont];
                $val3['legend'] = $inputs[0]['legend'][$cont];
                $result[$key] = $val2 + $val3;

                $cont++;
            }

            foreach ($result as $key => $r) {
                ($r['legend'] != "") ? $legend = $r['legend'] : $legend = null;
                isset($r['phone']) ? $phone = $r['phone'] : $phone = null;

                $values = [
                    'phone' => $phone,
                    'legend' => $legend,
                    'camper_id' => $camper->id
                ];

                Phone::create($values);

            }
        }

        if (isset($request->participou[0])) {
            $validator = Validator::make($request->all(), ['participou.*' => 'required']);
            if ($validator->fails()) {
                session()->flash('error', 'Usuário foi atualizado, mas ocorreu erro ao atualizar os acampamentos trabalhados. Verifique.');
                return redirect()->back()->withErrors($validator->errors());
            }


            // Se possuir deleta todos.
            if (CamperCamp::where('camper_id', $camper->id)->where('has_worked','Não')->first()) {
                CamperCamp::where('camper_id', $camper->id)->where('has_worked','Não')->delete();
            }

            // Vamos inserir novos.
            $inputs[] = Input::except(['_token']);
            $values = [];
            $cont = 0;

            foreach ($inputs[0]['participou'] as $key => $r) {

                $val2['participou'] = $inputs[0]['participou'][$cont];
                $val3['angels'] = $inputs[0]['angels'];
                $result[$key] = $val2 + $val3;

                $cont++;
            }
            $contagem = 0;
            foreach ($result as $key => $r) {
                $contagem++;

                if(isset($r['angels'][$contagem]))
                    ($r['angels'][$contagem] != "") ? $legend = $r['angels'][$contagem] : $legend = null;
                else
                    $legend = null;

                isset($r['participou']) ? $phone = $r['participou'] : $phone = null;
                if (isset($phone)) {
                    $values = [
                        'camp_id' => $phone,
                        'angels' => $legend,
                        'camper_id' => $camper->id,
                        'has_worked' => 'Não'
                    ];
                }

                $nerrors = 0;

                if (isset($legend)) {
                    foreach ($legend as $angel) {
                        $angel_func = CamperCamp::where('camp_id', $phone)
                            ->where('camper_id', $angel)
                            ->first();

                        if ($angel_func) {
                            if ($angel_func->func_id <> 1) {
                                $nerrors = 1;
                                session()->flash('error', 'Um anjo cadastrado para este participante está com função incorreta, verifique.');
                            }
                            if ($angel_func->has_worked == "Não") {
                                $nerrors = 1;
                                session()->flash('error', 'Um anjo cadastrado para este participante não está cadastrado como trabalho neste acampamento.');
                            }
                        } else {
                            // Inserir o anjo caso ainda não tenha cadastro
                            CamperCamp::create([
                                'camper_id' => $angel,
                                'camp_id' => $phone,
                                'has_worked' => 'Sim',
                                'func_id' => 1
                            ]);
                        }

                    }}


                if ($nerrors == 1) {
                    $values['angels'] = [];
                }

                CamperCamp::create($values);

            }




        }





        if (isset($request->worked[0])) {
            $validator = Validator::make($request->all(), ['worked.*' => 'required', 'func.*' =>'required']);
            if ($validator->fails()) {
                session()->flash('error', 'Usuário foi atualizado, mas ocorreu erro ao atualizar os acampamentos trabalhados. Verifique.');
                return redirect()->back()->withErrors($validator->errors());
            }

            // Se possuir deleta todos.
            if (CamperCamp::where('camper_id', $camper->id)->where('has_worked', 'Sim')->first()) {
                CamperCamp::where('camper_id', $camper->id)->where('has_worked', 'Sim')->truncate();
            }

            // Vamos inserir novos.
            $inputs[] = Input::except(['_token']);
            $values = [];
            $cont = 0;

            foreach ($request->worked as $key => $r) {

                $val4['worked'] = $inputs[0]['worked'][$cont];
                $val5['func'] = $inputs[0]['func'][$cont];
                $resulti[$key] = $val5 + $val4;

                $cont++;
            }

            foreach ($resulti as $key => $r) {
                $legend = $r['func'];
                isset($r['worked']) ? $phone = $r['worked'] : $phone = null;

                $se = CamperCamp::where('camp_id', $phone)->where('camper_id', $camper->id)->first();

                if (isset($se->id)) {
                    session()->flash('error', 'Erro ao tentar inserir um ou mais acampamentos trabalhados, verifique.');
                } else {
                    $values = [
                        'has_worked' => 'Sim',
                        'camp_id' => $phone,
                        'func_id' => $legend,
                        'camper_id' => $camper->id
                    ];
                    CamperCamp::create($values);
                }
            }
        }


        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $camper = Camper::findOrFail($id);
        $camper['birth'] = date('d/m/Y', strtotime($camper->birth));

        $campers = Camper::where('id', '<>', $id)->get();
        $phone = Phone::where('camper_id', $id)->get();
        $groupReflection = GroupReflection::pluck('name', 'id');
        $campOfCamper = Camp::pluck('name', 'id');
        $campOfCamperAtu = CamperCamp::where('camper_id', $id)->where('has_worked', 'Não')->get();

//        if ($campOfCamperAtu)
//            $camperAtivo = CamperCamp::where('camper_id', $id)->where('camp_id', $campOfCamperAtu->camp_id)->first();
        $worked = CamperCamp::where('camper_id', $id)->where('has_worked', 'Sim')->get();

        $func_id = Func::pluck('name', 'id');


        return view('backend.camper.edit', compact('camper', 'worked', 'func_id', 'phone', 'groupReflection', 'campers', 'campOfCamper', 'campOfCamperAtu', 'camperAtivo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(CamperRequest $request, $id)
    {
//        if ($request->hasFile('user_photo')) {
//            $ret = $this->validAndUploadImg($request->file('user_photo'));
//
//            // Verificação se houve sucesso
//            if (($ret->getData()->type <> "success") && ($ret->getData()->type <> "not found")) {
//                return redirect()->back()->withErrors('Ocorreram erros no upload da imagem, verifique.');
//            }
//
//            // atribui a nova imagem a variavel e arruma a senha
//            $request['photo'] = $ret->getData()->localImg;
//        }


        $request['birth'] = date('Y-m-d', strtotime($request->birth));
        if ($request->partCamp == 1) {
            $request['angels'] = [];
            $request['participou'] = [];
        }

        if ($request->partCamp <> 1) {
            if (!isset($request->participou[0])) {
                session()->flash('error', 'Não inserido nenhum acampamento como participante para este campista.');
                return redirect()->back();
            }
        }
        if ($request->hasFile('user_photo')) {
            //filename
            $filename = str_slug($request->name);
            $filename .= '-' . uniqid() . '.';
            $filename .= $request->file('user_photo')->getClientOriginalExtension();
            //destination folder
            $path = public_path() . "/img/camper/";
            //crop the image
            $width = intval($request->width);
            $height = intval($request->height);
            $x = intval($request->x);
            $y = intval($request->y);
            $img = ImageManagerStatic::make($request->file('user_photo'));
            $img->crop($width, $height, $x, $y);
            $img->resize(200, 200);
            $img->save($path.$filename, 80);
            //data to save
            $request['photo'] = 'img/camper/'.$filename;
        }
        $camper = Camper::findOrFail($id);
        $camper->update($request->except('_token'));

        // Inserir telefones



        if (isset($request->phone[0])) {
            $validator = Validator::make($request->all(), ['phone.*' => 'required']);
            if ($validator->fails()) {
                session()->flash('error', 'Usuário foi atualizado, mas ocorreu erro ao atualizar o telefone. Verifique.');
                return redirect()->back()->withErrors($validator->errors());
            }


            // Se possuir deleta todos.
            if (Phone::where('camper_id', $camper->id)->first()) {
                Phone::where('camper_id', $camper->id)->delete();
            }

            // Vamos inserir novos.
            $inputs[] = Input::except(['_token']);
            $values = [];
            $cont = 0;

            foreach ($inputs[0]['phone'] as $key => $r) {

                $val2['phone'] = $inputs[0]['phone'][$cont];
                $val3['legend'] = $inputs[0]['legend'][$cont];
                $result[$key] = $val2 + $val3;

                $cont++;
            }

            foreach ($result as $key => $r) {
                ($r['legend'] != "") ? $legend = $r['legend'] : $legend = null;
                isset($r['phone']) ? $phone = $r['phone'] : $phone = null;

                $values = [
                    'phone' => $phone,
                    'legend' => $legend,
                    'camper_id' => $camper->id
                ];


                Phone::create($values);

            }
        }



        if (isset($request->participou[0])) {
            $validator = Validator::make($request->all(), ['participou.*' => 'required']);
            if ($validator->fails()) {
                session()->flash('error', 'Usuário foi atualizado, mas ocorreu erro ao atualizar os acampamentos trabalhados. Verifique.');
                return redirect()->back()->withErrors($validator->errors());
            }


            // Se possuir deleta todos.
            if (CamperCamp::where('camper_id', $camper->id)->where('has_worked','Não')->first()) {
                CamperCamp::where('camper_id', $camper->id)->where('has_worked','Não')->delete();
            }

            // Vamos inserir novos.
            $inputs[] = Input::except(['_token']);
            $values = [];
            $cont = 0;

            foreach ($inputs[0]['participou'] as $key => $r) {

                $val2['participou'] = $inputs[0]['participou'][$cont];
                $val3['angels'] = $inputs[0]['angels'];
                $result[$key] = $val2 + $val3;

                $cont++;
            }
            $contagem = 0;
            foreach ($result as $key => $r) {
                $contagem++;

                if(isset($r['angels'][$contagem]))
                    ($r['angels'][$contagem] != "") ? $legend = $r['angels'][$contagem] : $legend = null;
                else
                    $legend = null;

                isset($r['participou']) ? $phone = $r['participou'] : $phone = null;
                if (isset($phone)) {
                    $values = [
                        'camp_id' => $phone,
                        'angels' => $legend,
                        'camper_id' => $camper->id,
                        'has_worked' => 'Não'
                    ];
                }

                $nerrors = 0;

              if (isset($legend)) {
                foreach ($legend as $angel) {
                    $angel_func = CamperCamp::where('camp_id', $phone)
                        ->where('camper_id', $angel)
                        ->first();

                    if ($angel_func) {
                        if ($angel_func->func_id <> 1) {
                            $nerrors = 1;
                            session()->flash('error', 'Um anjo cadastrado para este participante está com função incorreta, verifique.');
                        }
                        if ($angel_func->has_worked == "Não") {
                            $nerrors = 1;
                            session()->flash('error', 'Um anjo cadastrado para este participante não está cadastrado como trabalho neste acampamento.');
                        }
                    } else {
                        // Inserir o anjo caso ainda não tenha cadastro
                        CamperCamp::create([
                            'camper_id' => $angel,
                            'camp_id' => $phone,
                            'has_worked' => 'Sim',
                            'func_id' => 1
                        ]);
                    }

                }}


                if ($nerrors == 1) {
                    $values['angels'] = [];
                }

                 CamperCamp::create($values);

            }




        }

        //Angels
        // Verificar se os anjos estão como trabalhados e como função anjo
//        if (isset($request->angels)) {
//
//
//        } else {
//            $request['angels'] = null;
//        }
//
//        // Salvar
//        $request['has_worked'] = "Não";
//
//        $request['camper_id'] = $camper->id;
//
//         CamperCamp::where('camper_id', $camper->id)->where('has_worked', 'Não')->delete();
//
//        if (!is_null($request->camp_id)) {
////            if ($update)
////                $update->update($request->all());
////            else {
//                CamperCamp::create($request->all());
////            }
//
//        }


        if (isset($request->worked[0])) {
            $validator = Validator::make($request->all(), ['worked.*' => 'required', 'func.*' =>'required']);
            if ($validator->fails()) {
                session()->flash('error', 'Usuário foi atualizado, mas ocorreu erro ao atualizar os acampamentos trabalhados. Verifique.');
                return redirect()->back()->withErrors($validator->errors());
            }


            // Se possuir deleta todos.
            if (CamperCamp::where('camper_id', $id)->where('has_worked', 'Sim')->first()) {
                CamperCamp::where('camper_id', $id)->where('has_worked', 'Sim')->delete();
            }
            //return $id;
            // Vamos inserir novos.
            $inputs[] = Input::except(['_token']);
            $values = [];
            $cont = 0;

            foreach ($request->worked as $key => $r) {

                $val4['worked'] = $inputs[0]['worked'][$cont];
                $val5['func'] = $inputs[0]['func'][$cont];
                $resulti[$key] = $val5 + $val4;

                $cont++;
            }

            foreach ($resulti as $key => $r) {
                $legend = $r['func'];
                isset($r['worked']) ? $phone = $r['worked'] : $phone = null;

                $se = CamperCamp::where('camp_id', $phone)->where('camper_id', $camper->id)->first();

                if (isset($se->id)) {
                    session()->flash('error', 'Erro ao tentar inserir um ou mais acampamentos trabalhados, verifique.');
                } else {
                    $values = [
                        'has_worked' => 'Sim',
                        'camp_id' => $phone,
                        'func_id' => $legend,
                        'camper_id' => $camper->id
                    ];
                    CamperCamp::create($values);
                }
            }
        }

        session()->flash('success', 'Campista alterado com sucesso.');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        if ($request->selected) {
            $entries = explode(',', $request->selected);

            DB::transaction(function () use ($entries, $request) {
                foreach ($entries as $entry) {
                    $camp = Camper::findOrFail($entry);
                    $camp->delete();
                }
            });

            $restore = "<a href='" . route('camper.restore', 0) . "?entries=" . $request->selected . "'>Desfazer</a>";
        } else {
            $camper = Camper::findOrFail($id);
            DB::transaction(function () use ($camper) {
                $camper->delete();
            });

            $restore = "<a href='" . route('camper.restore', $id) . "'>Desfazer</a>";
        }

        session()->flash('success', "Campista excluido com sucesso. $restore");
      //  return redirect()->back();
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore($id, Request $request)
    {
        //when restoring a lot of entries
        if ($entries = $request->entries) {
            $entries = explode(',', $entries);

            DB::transaction(function () use ($entries) {
                foreach ($entries as $entry) {
                    $camper = Camper::onlyTrashed()->findOrFail($entry);

                    DB::transaction(function () use ($camper) {
                        $camper->restore();
                    });
                }
            });
        } else {
            $camper = Camper::onlyTrashed()->findOrFail($id);

            DB::transaction(function () use ($camper) {
                $camper->restore();
            });
        }

        session()->flash('success', 'Restaurado com sucesso.');
        return redirect()->back();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function trash(Request $request)
    {
        // Pegar todas as comunidades
        $camper = Camper::onlyTrashed()->paginate(config('helpers.qtdPerPag'));
        $trash = count($camper);

        // retornar view
        return view('backend.camper.index', compact('trash', 'camper'));
    }

    protected function validAndUploadImg(UploadedFile $request = null): JsonResponse
    {
        // Verifica se existe
        if (!$request) {
            return response()->json(['msg' => 'Imagem não uploaded', 'type' => 'not found', 'localImg' => null]);
        }

        // Verifica se possui imagem
        if ($request->isValid()) {

            // Efetuar o upload da imagem
            $helper = new HelperController();

            // Chamar metodo
            $ret = $helper->moveImg($this->moveTo, $request);

            // Retorna o JSON
            return response()->json($ret->getData());
        }
    }

    public function insertCampWorked(Request $request)
    {
        // Verificar se algum campo está vazio
        if (is_null($request->worked) || is_null($request->func)) {
            session()->flash('error', 'Erro, campos não preenchido.');
            return redirect()->back();
        }

        //Verifica se ele ja trabalhou neste acampamento
        $se = CamperCamp::where('camp_id', $request->worked)->where('camper_id', $request->camper_id_modal)->first();
        if ($se) {
            session()->flash('error', 'Erro, campista já possui um registro no acampamento que você tentou incluir.');
            return redirect()->back();
        }


        // Inserir
        CamperCamp::create([
            'camper_id' => $request->camper_id_modal,
            'camp_id' => $request->worked,
            'func_id' => $request->func,
            'has_worked' => 'Sim'
        ]);

        // Mensagem e retorn
        return redirect()->back();
    }

    public function deleteWorked($cod)
    {
        CamperCamp::find($cod)->delete();
        return redirect()->back();
    }

    public function listarPDF($cod)
    {
        $camper = Camper::findOrFail($cod);
        $camperCampFez = CamperCamp::where('has_worked', 'Não')->where('camper_id', $cod)->first();
        $count = 1;
        $camperWorked = CamperCamp::where('has_worked', 'Sim')->where('camper_id', $cod)->orderBy('created_at', 'desc')->get();

        //$pdf = App::make('dompdf.wrapper');
        //   $view = \Illuminate\Support\Facades\View::make('backend.pdf.campistas_pdf', ['camper' => $camper]);
        // $html = (string) $view;
        $nomePDF = $camper->name." - ".date('d-m-Y');
        $pdf = \niklasravnsborg\LaravelPdf\Facades\Pdf::loadView('backend.pdf.campistas_pdf', ['camper' => $camper
            , 'camperCampFez' => $camperCampFez,
            'count' => $count,
            'camperWorked' => $camperWorked], [] ,[
                'title' => $nomePDF
        ]);
        return $pdf->stream($nomePDF);

//        return view('backend.pdf.campistas_pdf', ['camper' => $camper
//            , 'camperCampFez' => $camperCampFez,
//            'count' => $count,
//            'camperWorked' => $camperWorked]);

    }


}
