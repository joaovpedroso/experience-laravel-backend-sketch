<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\SubModule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class SubModulesController extends Controller
{
    public function index($module_id)
    {
        //query
        $submodules = SubModule::where('module_id', '=', $module_id)
            ->orderBy('position', 'asc')
            ->get();

        return view("backend.modules.submodules.index", [
            'submodules' => $submodules,
            'module' => Module::findOrFail($module_id),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($module_id)
    {
        return view('backend.modules.submodules.form', [
            'module' => Module::findOrFail($module_id),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $module_id)
    {

        Submodule::create([
            'module_id' => $module_id,
            'name' => $request->name,
            'url' => $request->url
        ]);

        session()->flash('success', 'Sub-Módulo criado com sucesso!');
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($module_id, $id)
    {
        return view('backend.modules.submodules.form', [
            'module' => Module::findOrFail($module_id),
            'submodule' => Submodule::findOrFail($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $module_id, $id)
    {
        $submodule = SubModule::findOrFail($id);

        $input = $request->all();

        $submodule->fill($input)->save();


        session()->flash('success', 'Sub-Módulo alterado com sucesso!');
        return redirect()->route('modules.submodules.index', $module_id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $module_id, $id)
    {
        $submodule = Submodule::findOrFail($id);
        $submodule->delete();

        $request->session()->flash('success', 'Sub-Módulo excluído com sucesso!');
        return redirect()->back();
    }

    /**
     * Update the status of specified resource from storage.
     * @return \Illuminate\Http\Response
     */
    public function status() {
        $id = (int) Input::get('id');
        $status = Input::get('status');

        $code = 418; //I'm a teapot!

        if ( $id and (($status == 'Ativo') || ($status == 'Inativo')) ) {
            $submodule = SubModule::findOrFail($id);
            $submodule->status = $status;
            if ($submodule->save()) $code = 200;
        }

        return $code;
    }

    /**
     * Change the order of modules at navbar
     */
    public function order(Request $request) {
        $code = 418; //I'm a teapot!

        foreach ($request->item as $order => $id) {
            $module = Submodule::find($id);
            $module->order = $order;
            if ($module->save()) $code = 200;
        }

        return $code;
    }

    /**
     * Update the information on all portals
     */
    public function updateOnAllPortals($url, $input)
    {
        $portals = Portal::all();

        foreach ($portals as $portal) {
            if (! $portal->db_host and ! $portal->db_name) continue;

            //connect
            $connection = [
                'driver' => 'pgsql',
                'host' => $portal->db_host,
                'database' => $portal->db_name,
                'username' => $portal->db_username,
                'password' => $portal->db_password,
                'charset' => 'utf8',
                'prefix' => '',
                'schema' => 'public',
            ];

            config(["database.connections.portal" => $connection]);

            //update
            $nav = Nav::where('url', $url)->first();
            $nav->update([
                'name' => $input['label'],
                'url' => $input['url']
            ]);
        }
    }
}
