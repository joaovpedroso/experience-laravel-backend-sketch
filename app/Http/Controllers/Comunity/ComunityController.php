<?php
/**
 * Copyright (c) 2017. Código criado inteiramente por Lucas Mota.
 * @link Facebook: https://www.facebook.com/lucas.mota.5059
 */

namespace App\Http\Controllers\Comunity;

use App\Http\Controllers\Controller;
use App\Http\Requests\ComunityRequest;
use App\Models\Camper;
use App\Models\Comunity;
use App\Models\GroupReflection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class ComunityController extends Controller
{
    /**
     * ComunityController constructor.
     */
    public function __construct()
    {
        $this->middleware('role:comunity');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Pegar todas as comunidades
        $comunity = Comunity::name($request->name)->paginate(config('helpers.qtdPerPag'));

        // retornar view
        return view('backend.comunity.index', compact('comunity'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.comunity.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(ComunityRequest $request)
    {
        // Inserir no banco os novos registros
        Comunity::create($request->except('_token'));

        // Adicionar nova mensagem e retornar
        session()->flash('success', "Comunidade criada com sucesso.");
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
        $comunity = Comunity::findOrFail($id);

        return view('backend.comunity.edit', compact('comunity'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(ComunityRequest $request, $id)
    {
        // Pegar a comunidade
        $comunity = Comunity::findOrFail($id);

        // atualizar
        $comunity->update($request->except('_token'));

        // Adicionar nova mensagem e retornar
        session()->flash('success', "Comunidade alterada com sucesso.");
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
                    $comunity = Comunity::findOrFail($entry);
                    if (GroupReflection::where('comunity_id', $entry)->first()) {
                        session()->flash('error', 'Este registro está ligado com outro registro, portanto não poderá ser deletado.');
                        return redirect()->back();
                    }
                    $comunity->delete();
                }
            });

            $restore = "<a href='" . route('comunity.restore', 0) . "?entries=" . $request->selected . "'>Desfazer</a>";
        } else {
            $comunity = Comunity::findOrFail($id);
            if (GroupReflection::where('comunity_id', $id)->first()) {
                session()->flash('error', 'Este registro está ligado com outro registro, portanto não poderá ser deletado.');
                return redirect()->back();
            }
            DB::transaction(function () use ($comunity) {
                $comunity->delete();
            });

            $restore = "<a href='" . route('comunity.restore', $id) . "'>Desfazer</a>";
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
                    $comunity = Comunity::onlyTrashed()->findOrFail($entry);

                    DB::transaction(function () use ($comunity) {
                        $comunity->restore();
                    });
                }
            });
        } else {
            $comunity = Comunity::onlyTrashed()->findOrFail($id);

            DB::transaction(function () use ($comunity) {
                $comunity->restore();
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
        $trash = Comunity::onlyTrashed()
            ->name($request->name)->first();
        $comunity = Comunity::onlyTrashed()
            ->name($request->name)->paginate();

        // retornar view
        return view('backend.comunity.index', compact('trash', 'comunity'));
    }

    public function listarPDF(Request $request) {
        $comunity = Comunity::name($request->name)->get();
        $pdf = \niklasravnsborg\LaravelPdf\Facades\Pdf::loadView('backend.pdf.comunity_table_pdf', ['comunity' => $comunity]);
        return $pdf->stream();

    }
}
