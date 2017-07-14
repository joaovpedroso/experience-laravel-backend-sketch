<?php

namespace App\Http\Controllers\Camp;

use App\Http\Controllers\Controller;
use App\Models\TypeCamp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mockery\Matcher\Type;
use Validator;

class TypeCampController extends Controller
{
    /**
     * TypeCampController constructor.
     */
    public function __construct()
    {
        $this->middleware('role:type camp');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $camp = TypeCamp::paginate(config('helpers.qtdPerPag'));

        return view('backend.typeCamp.index', compact('camp'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.typeCamp.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required'
        ];

        $valid = Validator::make($request->all(), $rules);

        if ($valid->fails()) {
            return redirect()->back()->withErrors($valid->errors());
        }

        TypeCamp::create($request->all());

        session()->flash('success', 'Tipo de Acampamento inserido com sucesso.');
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
        $camp = TypeCamp::findOrFail($id);

        return view('backend.typeCamp.edit', compact('camp'));
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
        $rules = [
            'name' => 'required'
        ];

        $valid = Validator::make($request->all(), $rules);

        if ($valid->fails()) {
            return redirect()->back()->withErrors($valid->errors());
        }

        $camp = TypeCamp::findOrFail($id);
        $camp->update($request->all());

        session()->flash('success', 'Tipo de Acampamento inserido com sucesso.');
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
                    $camp = TypeCamp::findOrFail($entry);
                    $camp->delete();
                }
            });

            $restore = "<a href='" . route('typeCamp.restore', 0) . "?entries=" . $request->selected . "'>Desfazer</a>";
        } else {
            $camp = TypeCamp::findOrFail($id);
            DB::transaction(function () use ($camp) {
                $camp->delete();
            });

            $restore = "<a href='" . route('typeCamp.restore', $id) . "'>Desfazer</a>";
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
                    $camp = TypeCamp::onlyTrashed()->findOrFail($entry);

                    DB::transaction(function () use ($camp) {
                        $camp->restore();
                    });
                }
            });
        } else {
            $camp = TypeCamp::onlyTrashed()->findOrFail($id);

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
        $camp = TypeCamp::onlyTrashed()
            ->paginate(config('helpers.qtdPerPag'));
        $trash = count($camp);

        // retornar view
        return view('backend.typeCamp.index', compact('trash', 'camp'));
    }
}
