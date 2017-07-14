<?php

namespace App\Http\Controllers\GroupReflection;

use App\Http\Controllers\Controller;
use App\Http\Requests\GroupReflectionRequest;
use App\Models\Comunity;
use App\Models\GroupReflection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GroupReflectionController extends Controller
{
    /**
     * GroupReflectionController constructor.
     */
    public function __construct()
    {
        $this->middleware('role:group reflection');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Pegar todos os grupos de reflexão
        $groupReflection = GroupReflection::name($request->name)
            ->comunity($request->comunity_id)
            ->coordenador($request->coordenador)
            ->paginate(config('helpers.qtdPerPag'));
        $comunity = Comunity::pluck('name', 'id');

        // chamar a view
        return view('backend.groupReflection.index', compact('groupReflection', 'comunity'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Pegar todas as comunidades para o select na view
        $comunity = Comunity::pluck('name', 'id');

        return view('backend.groupReflection.create', compact('comunity'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(GroupReflectionRequest $request)
    {
        // Inserir no banco
        GroupReflection::create($request->all());

        // Retornar
        session()->flash('success', 'Grupo de reflexão cadastrado com sucesso.');
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
        // Pegar todas as comunidades para o select na view
        $comunity = Comunity::pluck('name', 'id');

        $groupReflection = GroupReflection::findOrFail($id);

        return view('backend.groupReflection.edit', compact('comunity', 'groupReflection'));
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
        // Pegar o atual
        $group = GroupReflection::findOrFail($id);

        // Atualizar
        $group->update($request->all());

        //Retornar
        session()->flash('success', 'Grupo de Reflexão atualizado com sucesso.');
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
                    $groupReflection = GroupReflection::findOrFail($entry);
                    $groupReflection->delete();
                }
            });

            $restore = "<a href='" . route('groupReflection.restore', 0) . "?entries=" . $request->selected . "'>Desfazer</a>";
        } else {
            $groupReflection = GroupReflection::findOrFail($id);
            DB::transaction(function () use ($groupReflection) {
                $groupReflection->delete();
            });

            $restore = "<a href='" . route('groupReflection.restore', $id) . "'>Desfazer</a>";
        }

        session()->flash('success', "Comunidade excluido com sucesso. $restore");
        return redirect()->back();
    }

    /**
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
                    $groupReflection = GroupReflection::onlyTrashed()->findOrFail($entry);

                    DB::transaction(function () use ($groupReflection) {
                        $groupReflection->restore();
                    });
                }
            });
        } else {
            $groupReflection = GroupReflection::onlyTrashed()->findOrFail($id);

            DB::transaction(function () use ($groupReflection) {
                $groupReflection->restore();
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
        $groupReflection = GroupReflection::onlyTrashed()
            ->name($request->name)->paginate();
        $trash = count($groupReflection);

        // retornar view
        return view('backend.groupReflection.index', compact('trash', 'groupReflection'));
    }

    public function listarPDF(Request $request)
    {
        $group = GroupReflection::name($request->name)
            ->comunity($request->comunity_id)
            ->get();
        $pdf = \niklasravnsborg\LaravelPdf\Facades\Pdf::loadView('backend.pdf.group_table_pdf', ['group' => $group]);
        return $pdf->stream();
    }
}
