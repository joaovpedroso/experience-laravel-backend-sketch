<?php

namespace App\Http\Controllers\Config;

use App\Configurate;
use App\Http\Controllers\Controller;
use App\Info;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Spatie\Permission\Models\Permission;

class ModulesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //query
        $modules = Module::orderBy('position', 'asc')->get();

        return view("backend.modules.index", [
            'modules' => $modules,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.modules.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Definir slug para módulo
        $request['slug'] = str_slug($request->name);
        Permission::create([
            'name' => str_slug($request->name),
            'translate' => "Gerenciar $request->name"
        ]);


        Module::create($request->all());

        session()->flash('success', 'Módulo criado com sucesso!');
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
        $module = Module::findOrFail($id);

        return view('backend.modules.form', [
            'module' => $module
        ]);
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
        $module = Module::findOrFail($id);

        $module->fill($request->all())->save();

        session()->flash('success', 'Módulo alterado com sucesso!');
        return redirect()->route('config.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Auth::user()->isSuperAdmin()) abort(403);

        $module = Module::findOrFail($id);
        $module->delete();

        session()->flash('success', 'Módulo excluído com sucesso!');
        return redirect()->back();
    }

    /**
     * Update the status of specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function status()
    {
        $id = (int)Input::get('id');
        $status = Input::get('status');

        $code = 418; //I'm a teapot!

        if ($id and (($status == 'Ativo') || ($status == 'Inativo'))) {
            $module = Module::findOrFail($id);
            $module->status = $status;
            if ($module->save()) $code = 200;
        }

        return $code;
    }

    /**
     * Change the order of modules at navbar
     */
    public function order(Request $request)
    {
        $code = 418; //I'm a teapot!

        foreach ($request->item as $order => $id) {
            $module = Module::find($id);
            $module->order = $order;
            if ($module->save()) $code = 200;
        }

        return $code;
    }

    public function configModules()
    {
        $config = Configurate::first();
        return view('backend.modules.configuration', compact('config'));
    }

    public function updateConfigModules(Request $request)
    {
        $config = Configurate::first();
        $config->update($request->all());

        session()->flash('success', 'Configurações atualizadas com sucesso.');
        return redirect()->back();
    }


    public function painel()
    {
        return view('backend.config.index');
    }

    public function info() {
        $config = Info::first();
        return view('backend.info.index', compact('config'));
    }

    public function infoUpdate(Request $request) {
        $config = Info::first();
        $config->update($request->all());


        return redirect()->back();
    }
}
