<?php

namespace App\Http\Controllers\Camper;

use App\Http\Controllers\Controller;
use App\Http\Requests\CamperCampRequest;
use App\Models\Camp;
use App\Models\Camper;
use App\Models\CamperCamp;
use App\Models\Func;
use Illuminate\Http\Request;

class CamperCampController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $camperCamp = CamperCamp::id($request->id)
            ->func($request->func_id)
            ->trab($request->has_worked)
            ->camp($request->camp_id)
        ->paginate(config('helpers.qtdPerPag'));

        $func_id = Func::pluck('name', 'id');
        $campOfCamper = Camp::pluck('name', 'id');

        return view('backend.camperCamp.index', compact('camperCamp', 'func_id', 'campOfCamper'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $camper = Camper::all();
        $campOfCamper = Camp::pluck('name', 'id');
        $func_id = Func::pluck('name', 'id');
        return view('backend.camperCamp.create', compact('campOfCamper', 'camper', 'func_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CamperCampRequest $request)
    {

        //Validação de repitido
        $tem = CamperCamp::where('camper_id', $request->camper_id)
            ->where('camp_id', $request->camp_id)
            ->count();

        if ($tem > 0){
            session()->flash('error', 'Participante já cadastrado, verifique.');
            return redirect()->back();
        }

        // Verificar se os anjos estão como trabalhados e como função anjo
        if (isset($request->angels)) {

            foreach ($request->angels as $angel) {
                $angel_func = CamperCamp::where('camp_id', $request->camp_id)
                    ->where('camper_id', $angel)
                    ->first();

                if ($angel_func) {
                    if ($angel_func->func_id <> 1) {
                        session()->flash('error', 'Um anjo cadastrado para este participante está com função incorreta, verifique.');
                        return redirect()->back();
                    }
                    if ($angel_func->has_worked == "Não") {
                        session()->flash('error', 'Um anjo cadastrado para este participante não está cadastrado como trabalho neste acampamento.');
                        return redirect()->back();
                    }
                } else {
                    // Inserir o anjo caso ainda não tenha cadastro
                    CamperCamp::create([
                        'camper_id' => $angel,
                        'camp_id' => $request->camp_id,
                        'has_worked' => 'Sim',
                        'func_id' => 1
                    ]);
                }

            }
        }

        // Salvar
        CamperCamp::create($request->all());

        session()->flash('success', 'Participante adicionado com sucesso.');
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
        $camperCamp = CamperCamp::findOrFail($id);
        $camper = Camper::all();
        $campOfCamper = Camp::pluck('name', 'id');
        $func_id = Func::pluck('name', 'id');

        return view('backend.camperCamp.edit', compact('camperCamp','camper','campOfCamper','func_id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Verificar se os anjos estão como trabalhados e como função anjo
        if (isset($request->angels)) {

            foreach ($request->angels as $angel) {
                $angel_func = CamperCamp::where('camp_id', $request->camp_id)
                    ->where('camper_id', $angel)
                    ->first();

                if ($angel_func) {
                    if ($angel_func->func_id <> 1) {
                        session()->flash('error', 'Um anjo cadastrado para este participante está com função incorreta, verifique.');
                        return redirect()->back();
                    }
                    if ($angel_func->has_worked == "Não") {
                        session()->flash('error', 'Um anjo cadastrado para este participante não está cadastrado como trabalho neste acampamento.');
                        return redirect()->back();
                    }
                } else {
                    // Inserir o anjo caso ainda não tenha cadastro
                    CamperCamp::create([
                        'camper_id' => $angel,
                        'camp_id' => $request->camp_id,
                        'has_worked' => 'Sim',
                        'func_id' => 1
                    ]);
                }

            }
        } else {
            $request['angels'] = null;
        }

        // Salvar
        $update = CamperCamp::findOrFail($id);
        $update->update($request->all());

        session()->flash('success', 'Participante alterado com sucesso.');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        CamperCamp::find($id)->delete();

        session()->flash('success', 'Registro deletado com sucesso.');
        return redirect()->back();
    }
}
