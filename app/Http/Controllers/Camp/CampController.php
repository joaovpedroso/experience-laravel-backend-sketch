<?php

namespace App\Http\Controllers\Camp;

use App\Http\Controllers\Controller;
use App\Http\Requests\CampRequest;
use App\Models\Camp;
use App\Models\Camper;
use App\Models\CamperCamp;
use App\Models\TypeCamp;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Psy\TabCompletion\Matcher\ClassAttributesMatcher;

class CampController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Pegar os tipos de acampamentos
        $type = TypeCamp::pluck('name', 'id');
        $camp = Camp::name()
            ->startDate($request->start_date)
            ->endDate($request->end_date)
            ->type($request->type_id)
            ->paginate(config('helpers.qtdPerPag'));

        return view('backend.camp.index', compact('type', 'camp'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Pegar os tipos de acampamentos
        $type = TypeCamp::pluck('name', 'id');

        return view('backend.camp.create', compact('type'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CampRequest $request)
    {

        // Verifcar se as datas estão de acordo.
        if ($request->start_date > $request->end_date) {
            session()->flash('error', 'Datas inválidas, verifique.');
            return redirect()->back()->withErrors('Datas inválidas, verifique.');
        }

        // Inserir o acampamento.
        Camp::create($request->all());

        //Return para view
        session()->flash('success', 'Acampamento inserido com sucesso.');
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
        // Pegar os tipos de acampamentos
        $type = TypeCamp::pluck('name', 'id');

        // Pegar o acampamento para editar
        $camp = Camp::findOrFail($id);

        return view('backend.camp.edit', compact('type', 'camp'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(CampRequest $request, $id)
    {
        // Verifcar se as datas estão de acordo.
        if ($request->start_date > $request->end_date) {
            session()->flash('error', 'Datas inválidas, verifique.');
            return redirect()->back()->withErrors('Datas inválidas, verifique.');
        }

        $camp = Camp::findOrFail($id);
        $camp->update($request->all());

        session()->flash('success', 'Acampamento alterado com sucesso.');
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
                    $camp = Camp::findOrFail($entry);
                    if (CamperCamp::where('camp_id', $entry)->first()) {
                        session()->flash('error', 'Este registro está ligado com outro registro, portanto não poderá ser deletado.');
                        return redirect()->back();
                    }
                    $camp->delete();
                }
            });

            $restore = "<a href='" . route('camp.restore', 0) . "?entries=" . $request->selected . "'>Desfazer</a>";
        } else {
            $camp = Camp::findOrFail($id);
            if (CamperCamp::where('camp_id', $id)->first()) {
                session()->flash('error', 'Este registro está ligado com outro registro, portanto não poderá ser deletado.');
                return redirect()->back();
            }
            DB::transaction(function () use ($camp) {
                $camp->delete();
            });

            $restore = "<a href='" . route('camp.restore', $id) . "'>Desfazer</a>";
        }

        session()->flash('success', "Comunidade excluido com sucesso. $restore");
        return redirect()->back();
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
                    $camp = Camp::onlyTrashed()->findOrFail($entry);

                    DB::transaction(function () use ($camp) {
                        $camp->restore();
                    });
                }
            });
        } else {
            $camp = Camp::onlyTrashed()->findOrFail($id);

            DB::transaction(function () use ($camp) {
                $camp->restore();
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
        $camp = Camp::onlyTrashed()
            ->name($request->name)->paginate(config('helpers.qtdPerPag'));
        $trash = count($camp);

        // retornar view
        return view('backend.camp.index', compact('trash', 'camp'));
    }

    public function listarPDF(Request $request)
    {
        $camp = Camp::name()
            ->startDate($request->start_date)
            ->endDate($request->end_date)
            ->type($request->type_id)
            ->get();

        $pdf = \niklasravnsborg\LaravelPdf\Facades\Pdf::loadView('backend.pdf.camp_table_pdf', ['camp' => $camp]);
        return $pdf->stream();

    }

    public function camisetasPDF($cod) {
        $camp = Camp::find($cod);


        $pdf = \niklasravnsborg\LaravelPdf\Facades\Pdf::loadView('backend.pdf.camisetas_pdf', ['camp' => $camp]);
        return $pdf->stream();
    }

}
