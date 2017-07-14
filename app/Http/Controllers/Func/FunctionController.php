<?php

namespace App\Http\Controllers\Func;

use App\Http\Controllers\Controller;
use App\Models\Func;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FunctionController extends Controller
{
    /**
     * FunctionController constructor.
     */
    public function __construct()
    {
        $this->middleware('role:function');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Pegar todos os functions
        $func = Func::paginate(config('helpers.qtdPerPag'));

        return view('backend.function.index', compact('func'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       return view('backend.function.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Func::create($request->all());
        session()->flash('success', 'Função cadastrada com sucesso.');
        return redirect()->back();
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $func = Func::findOrFail($id);

        return view('backend.function.edit', compact('func'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $func = Func::findOrFail($id);
        $func->update($request->all());

        session()->flash('success', 'Função editada com sucesso.');
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
                    $func = Func::findOrFail($entry);
                    $func->delete();
                }
            });

            $restore = "<a href='" . route('function.restore', 0) . "?entries=" . $request->selected . "'>Desfazer</a>";
        } else {
            $func = Func::findOrFail($id);
            DB::transaction(function () use ($func) {
                $func->delete();
            });

            $restore = "<a href='" . route('function.restore', $id) . "'>Desfazer</a>";
        }

        session()->flash('success', "Tipo de Acampamento excluido com sucesso. $restore");
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
                    $func = Func::onlyTrashed()->findOrFail($entry);

                    DB::transaction(function () use ($func) {
                        $func->restore();
                    });
                }
            });
        } else {
            $func = Func::onlyTrashed()->findOrFail($id);

            DB::transaction(function () use ($func) {
                $func->restore();
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
        $func = Func::onlyTrashed()
            ->paginate(config('helpers.qtdPerPag'));
        $trash = count($func);

        // retornar view
        return view('backend.function.index', compact('trash', 'func'));
    }
}
