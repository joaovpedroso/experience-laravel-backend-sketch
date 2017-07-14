<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Content\Editorial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

class EditorialsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.editorials.index', [
            'editorials' => Editorial::paginate(config('helpers.results_per_page')),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.editorials.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);

        $input = $request->all();
        $input['slug'] = $this->generateSlug($request->name);

        Editorial::create($input);

        //create the json file
        $this->refreshJson();

        session()->flash('success', 'Editoria criada com sucesso!');
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
        return view('backend.editorials.edit', [
            'editorial' => Editorial::findOrFail($id),
        ]);
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
        $this->validate($request, [
            'name' => 'required'
        ]);

        $input = $request->all();
        $input['slug'] = $this->generateSlug($request->name);

        $editorial = Editorial::findOrFail($id);
        $editorial->update($input);

        $this->refreshJson();

        session()->flash('success', 'Editoria alterada com sucesso!');
        return redirect()->back();
    }

    /**
     * Create or update the json file
     */
    public function refreshJson()
    {
        $content = Editorial::active()->get(['name', 'slug', 'id'])->toArray();
        $file = 'editorials.json';

        Storage::put($file, json_encode($content));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (auth()->user()->id != 1) abort(403);

        $editorial = Editorial::findOrFail($id);
        $editorial->delete();

        $this->refreshJson();

        session()->flash('success', 'Editoria excluída com sucesso!');
        return redirect()->back();
    }

    /**
     * Update the status of specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function status() {
        $id = (int) Input::get('id');
        $status = (int) Input::get('status');

        $code = 418; //I'm a teapot!

        if ( $id and preg_match('/(0|1)/', $status) ) {
            $department = Editorial::findOrFail($id);
            $department->status = $status;
            if ($department->save()) $code = 200;
        }

        $this->refreshJson();

        return $code;
    }

    /**
     * Set the slug
     */
    protected function generateSlug($slug)
    {
        $slug = str_slug($slug);

        $slugs = Editorial::where('slug', 'like', "$slug%")->get();
        if ($slugs->count() === 0) {
            return $slug;
        }

        $lastSlug = $slugs->sortBy('id')->last()->slug;
        $lastSlugNumber = intval(str_replace($slug . '-', '', $lastSlug));

        return $slug . '-' . ($lastSlugNumber + 1);
    }
}
