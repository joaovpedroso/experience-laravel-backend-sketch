# Criando o Back-end e o Front-end com Laravel

<img src="http://i.imgur.com/TIlFmyE.png" alt="Laravel 5.4" width="650px">
## Sumário

* Introdução
* O que é necessário aprender
* Arquitetrua Laravel
* Rotas
* Controladores
* Respostas HTTP
* Views
* Blade
* View Composers
* Validação
* Migration
* Eloquent ORM
* Autenticação
* Teste Unitário com Laravel
* Criando o Backend
* Criando o Frontend
* Integração do Backend com o Frontend


### [Referência do Sumário](https://github.com/Webschool-io/Curso-PHP-Laravel-Completo-E-Total)


<hr>

# Implementação Sequencial

## Atualizando o composer.json do projeto

> composer update

<hr>

## Criando o arquivo .env e alterando as linhas abaixo conforme a configuração do banco de dados local:

> DB_DATABASE=homestead
> DB_USERNAME=homestead
> DB_PASSWORD=secret

<hr>

## Gerando a APP-Key

> php artisan key:generate

<hr>

## Inserindo nosso user administrador

> database->seeds->UserTableSeeder

<hr>

## Criando uma tabela no banco de dados:

```
php artisan make:model Newspaper --migration
```

<hr>

## Editando a nossa migrate

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNewspapersTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {

		Schema::create('categories', function (Blueprint $table) {
				$table->increments('id');

				$table->string('titulo');
				$table->integer('status')->default(0);

				$table->timestamps();
				$table->softDeletes();

			});

		Schema::create('newspapers', function (Blueprint $table) {
				$table->increments('id');

				$table->date('data');
				$table->string('titulo');
				$table->text('descricao');
				$table->string('imagem');
				$table->string('fonte');

				$table->integer('status')->default(0);
				$table->string('legenda_imagem');

				$table->integer('category_id')->unsigned()->index();
				$table->foreign('category_id')->references('id')->on('categories');

				$table->timestamps();
				$table->softDeletes();

			});

		Schema::create('newspapers_images', function (Blueprint $table) {

				$table->increments('id');
				$table->integer('newspaper_id')->unsigned()->index();
				$table->foreign('newspaper_id')->references('id')->on('newspapers');
				$table->string('imagem', 250);
				$table->string('legenda', 250);
				$table->timestamps();

			});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop('newspapers_images');
		Schema::drop('newspapers');
		Schema::drop('categories');
	}
}

```

<hr>

## Editando nosso Model

```php
<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Newspaper extends Model {

	use SoftDeletes;
	protected $dates = ['deleted_at'];

	protected $fillable = [
		'data', 'titulo', 'descricao', 'imagem', 'fonte', 'status', 'legenda_imagem',
		'category_id'
	];

	/**
	 * Scopes!
	 */
	public function scopeDateFromTo($query, $begin = null, $end = null) {
		if (!is_null($begin) and !empty($begin)) {
			$begin = Carbon::createFromFormat('d/m/Y', $begin)->format('Y-m-d');
			$query->where("created_at", ">=", "$begin");
		}

		if (!is_null($end) and !empty($end)) {
			$end = Carbon::createFromFormat('d/m/Y', $end)->format('Y-m-d');
			$query->where("created_at", "<=", "$end");
		}
	}

	public function scopeSearchByCategory($query, $category = null) {
		if (!is_null($category) && !empty($category)) {
			$query->where('newspapers.category_id', $category);
		}
	}

	public function scopeSearchByNameDescription($query, $search = null) {
		if (!is_null($search) && !empty($search)) {
			$query->where('newspapers.titulo', 'like', '%'.$search.'%');
			$query->orWhere('newspapers.descricao', 'like', '%'.$search.'%');
		}
	}

	public function category() {
		return $this->belongsTo('App\Models\Category', 'category_id');
	}

	public function gallery() {
		return $this->hasMany('App\Models\GalleryNewspaper', 'newspaper_id');
	}
}

```
<hr>

## Criando um Controller:

```
php artisan make:controller NewspapersController
```

<hr>

## Criando nossa primeira Rota:

```
Route::get('/newspapers', 'Backend\NewspapersController@index')->name('newspapers.index');
```
<hr>

## Fazendo nosso CRUD

```php
<?php

namespace App\Http\Controllers\Backend;


use App\Http\Controllers\Controller as Controller;

use App\Models\Newspaper;
use App\Http\Requests;
use App\Models\Category;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManagerStatic as Image;
use App\Models\GalleryNewspaper;

class NewspapersController extends Controller
{
    /**
     * upload_max_filesize = 10M
     * post_max_size = 20M
    */

    protected $rules = [
        'data' => 'required',
        'titulo'   => 'required',
        //'video_file' => 'mimes:mp4,x-flv,x-mpegURL,MP2T,3gpp,quicktime,x-msvideo,x-ms-wmv'
    ];


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        //query
        $newspapers = Newspaper::orderBy('newspapers.data', 'DESC')
            ->dateFromTo(Input::get('begin_date'), Input::get('end_date'));

        //filters
        // titulo
        if ($titulo = str_replace(" ", "%", Input::get("titulo")))
            $newspapers = $newspapers->where('newspapers.titulo', 'like', "%$titulo%");


        //status
        $status = Input::get('status');
        if ((isset($status)) and ($status != ''))
            $newspapers = $newspapers->where('newspapers.status', '=', $status);

        $category_id = Input::get('category_id');
        if ((isset($category_id)) and ($category_id != ''))
            $newspapers = $newspapers->where('newspapers.category_id', '=', $category_id);

        $newspapers = $newspapers->join('categories', 'categories.id', '=', 'newspapers.category_id');
        $newspapers = $newspapers->select('newspapers.*', 'categories.titulo as categoria');

        //newspapers data for graphs (without paginate)
        $allNewspapers = $newspapers->get();

        $categorias = Category::all();


        //execute
        $newspapers = $newspapers->paginate(config('helpers.results_per_page'));

        return view("backend.newspapers.index", [
            'newspapers' => $newspapers,
            'categorias' => $categorias
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::select('id', 'titulo')->pluck('titulo', 'id');
        return view('backend.newspapers.form', [
            'categories' => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $input = $request->all();
        $input['data'] = date('Y-m-d', strtotime(str_replace('/','-',$request->data)));

        $this->validate($request, $this->rules);

         //upload file
        if ($request->hasFile('imagem')) {

            $path = 'images/blog/';

            list($largura, $altura) = getimagesize($request->file('imagem'));

            //return dump($largura, $altura);

            if ($largura > $altura) {

                $largura_max = 600;
                $altura_max = 449;

            }
            if ($largura < $altura) {

                $largura_max = 451;
                $altura_max = 600;

            }
            if ($largura == $altura) {

                $largura_max = 600;
                $altura_max = 600;

            }


            //return dump($largura, $largura_max, $altura, $altura_max);

            $file = $request->file('imagem')->getClientOriginalName();
            $image_name = time() . "-" . $file;


            $img = imagecreatefromjpeg($request->file('imagem'));
            $original_x = imagesx($img); //largura
            $original_y = imagesy($img); //altura
            $diretorio = $path . "/" . $image_name;
            // verifica se a largura ou altura da imagem é maior que o valor
            // máximo permitido
            if (($original_x > $largura_max) || ($original_y > $altura_max)) {
                // verifica o que é maior na imagem, largura ou altura?
                if ($original_x > $original_y) {
                    $altura_max = ($largura_max * $original_y) / $original_x;
                } else {
                    $largura_max = ($altura_max * $original_x) / $original_y;
                }
                $nova = imagecreatetruecolor($largura_max, $altura_max);
                imagecopyresampled($nova, $img, 0, 0, 0, 0, $largura_max, $altura_max, $original_x, $original_y);
                imagejpeg($nova, $diretorio);
                imagedestroy($nova);
                imagedestroy($img);
                // se for menor, nenhuma alteração é feita
            } else {
                imagejpeg($img, $diretorio);
                imagedestroy($img);
            }


            //atribuindo o valor da variavel no campo da tabela para o insert
            $input['imagem'] = $image_name;

        $newspaper = Newspaper::create($input);


        $request->session()->flash('success', 'Postagem criada com sucesso!');
        return redirect()->route('newspapers.index');
    }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $newspaper = Newspaper::findOrFail($id);

        $newspaper->data = date('d/m/Y', strtotime($newspaper->data));
        $categories = Category::select('id', 'titulo')->pluck('titulo', 'id');

        return view('backend.newspapers.form', [
            'newspaper' => $newspaper,
            'categories' => $categories,
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

        unset($this->rules['imagem']);
        $this->validate($request, $this->rules);

        $newspaper = Newspaper::findOrFail($id);

        $input = $request->all();

        if (empty($input['imagem']))
            unset($input['imagem']);

        $input['data'] = date('Y-m-d', strtotime(str_replace('/','-',$request->data)));

         //upload file
        if ($request->hasFile('imagem')) {

            //filename
            $filename = str_slug($request->titulo);
            $filename .= '-' . uniqid() . '.';
            $filename .= $request->file('imagem')->getClientOriginalExtension();

            $path = public_path() . "/images/blog/";

            if (!is_dir($path))
                mkdir($path, 0777, true);

            $img = Image::make($request->file('imagem'));

            $img->fit(870, 600, function ($constraint) {
                $constraint->upsize();
            });

            $img->save($path.$filename, 80);

            //data to save
            $input['imagem'] = $filename;
        }

        if(Input::hasFile('audio')){
            $file = Input::file('audio');
            $path = public_path() . "/audios/";

            #$filename = null;
            //filename
            $filename = str_slug($request->titulo);
            $filename .= '-' . uniqid() . '.';
            $filename .= $request->file('audio')->getClientOriginalExtension();

            if (!is_dir($path))
              @mkdir($path, 0777, true);

            $input['audio'] = $filename;

            $file->move($path, $filename);
        }

        $newspaper->fill($input)->save();


        $request->session()->flash('success', 'Postagem alterada com sucesso!');
        return redirect()->route('newspapers.index');
    }

   /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {

         //when selected some entries to delete
        if ($request->selected) {

            $entries = explode(',', $request->selected);

            DB::transaction(function() use($entries) {
                foreach ($entries as $entry) {
                    $newspaper = Newspaper::findOrFail($entry);
                    $newspaper->update(array('status' => '0'));
                    $newspaper->delete();
                }
            });
            //restore
            $restore = "<a href='".route('newspapers.restore', 0)."?entries=".$request->selected."'>Desfazer</a>";
        }

        //when chosen to delete just one entry
        else {
            $newspaper = Newspaper::findOrFail($id);
            $newspaper->update(array('status' => '0'));

            DB::transaction(function() use($newspaper) {

                $newspaper->delete();
            });
            //restore
            $restore = "<a href='".route('newspapers.restore', $id)."'>Desfazer</a>";
        }

        //return
        session()->flash('success', "Postagem(s) excluído(s) com sucesso. $restore");
        return redirect()->route('newspapers.index');

    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        //when restoring a lot of entries
        if ($entries = Input::get('entries')) {
            $entries = explode(',', $entries);

            DB::transaction(function() use($entries) {
                foreach ($entries as $entry) {
                    Newspaper::withTrashed()->where('id', $entry)->restore();

                    $newspaper = Newspaper::find($entry);
                }
            });
        }

        //when restoring 1 entry
        else {

            DB::transaction(function() use($id) {
                Newspaper::withTrashed()->where('id', $id)->restore();

                $newspaper = Newspaper::find($id);
            });
        }

        session()->flash('success', 'Postagem(s) restaurada(s) com sucesso.');
        return redirect()->route('newspapers.index');
    }
    /**
     * Display a listing of soft deletes.
     *
     * @return \Illuminate\Http\Response
     */
    public function trash()
    {
        $newspapers = Newspaper::onlyTrashed()->paginate(config('helpers.results_per_page'));

        return view('backend.newspapers.index', [
            'newspapers' => $newspapers,
            'trash' => true,
        ]);
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
            $newspaper = Newspaper::findOrFail($id);
            $newspaper->status = $status;
            if ($newspaper->save()) $code = 200;

        }

        return $code;
    }

     /**
     * Change the order of newspapers at navbar
     */
    public function order(Request $request) {
        $code = 418; //I'm a teapot!

        foreach ($request->item as $order => $id) {
            $newspaper = Newspaper::find($id);
            $newspaper->order = $order;
            if ($newspaper->save()) $code = 200;
        }

        return $code;
    }

    public function order_news(Request $request) {
        $id = (int) Input::get('id');
        $ordem = (int) Input::get('ordem');

        $code = 418; //I'm a teapot!

        $newspaper = Newspaper::findOrFail($id);
        $newspaper->ordem = $ordem;
        if ($newspaper->save()) $code = 200;

        return $code;
    }



      /**
     * GalleryNewspaper Images
     */
    public function gallery(Request $request, $id) {

        $newspaper = Newspaper::findOrFail($id);
        $galleries = GalleryNewspaper::where('newspaper_id', $id)->get();

        return view("backend.newspapers.gallery", [
            'newspaper' => $newspaper,
            'galleries' => $galleries
        ]);
    }

    public function save(Request $request)
    {

        $newspaper = Newspaper::findOrFail($request->id);

        //filename
        $filename = str_slug($newspaper->nome);
        $filename .= '-' . uniqid() . '.';
        $filename .= $request->file('file')->getClientOriginalExtension();

        //destination folder
        $path = public_path() . "/images/blog/fotos/".$request->id.'/';

        if (!is_dir($path))
          @mkdir($path, 0777, true);

        $img = Image::make($request->file('file'));

        $img->fit(870, 600, function ($constraint) {
                $constraint->upsize();
            });

        $img->save($path.$filename, 80);

        //data to save
        $data['imagem'] = $filename;
        $data['newspaper_id'] = $request->id;

        return GalleryNewspaper::create($data);

    }

    public function remove(Request $request) {

    $image = GalleryNewspaper::findOrFail( $request->id );
    $path = public_path() . "/images/blog/fotos/".$request->gallery.'/'.$request->image;

    // clear image
    @unlink($path);
    $image->delete();

    return $request->id;

    }

    public function remove_files(Request $request) {

        $file = Newspaper::findOrFail($request->id);
        $path = public_path() . '/' . $request->folder . '/' .$request->file;

        // clear image
        unlink($path);
        if($request->folder == 'videos')
            $file->update(array('video_file' => ''));
        else
            $file->update(array('audio' => ''));

        return $request->id;
    }

    public function legenda(Request $request) {

    $legenda = GalleryNewspaper::findOrFail( $request->id );
    $legenda->update(array('legenda' => $request->legenda));

    return $request->id;

    }

}

```

## Atualizando o banco de dados:

```
php artisan migrate:refresh --seed
```
<hr>


## Criando nossa View

> Criar os arquivos dentro de views com o seguinte caminho de pasta: backend->helpers

#### Fazer os arquivos:

```
featured-button.blade.php
flot-options.blade.php
status-button.blade.php
zipcode.fill.blade.php
```

> Criar também dentro de views o caminho de pasta: backend->newspapers

#### Fazer os arquivos:

```
form.blade.php
index.blade.php
```

## Adicionando o FRONT no nosso arquivo blade no index

```html
@extends('layouts.app')

@section('content')
<div class="page-content">
  <div class="content">

    <!-- BREADCRUMB -->
    <ul class="breadcrumb">
      <li><p>VOCÊ ESTÁ AQUI</p></li>
      @if (isset($trash))
      <li><a href="{{ route('newspapers.index') }}">Blog</a></li>
      <li><a class="active">excluídos</a></li>
      @else
      <li><a href="{{ route('newspapers.index') }}" class="active">Postagens</a></li>
      @endif
    </ul>

    <!-- TITLE -->
    <div class="page-title">
      <div class="row">
        <div class="col-md-6">
          <h3>Postagens</h3>
        </div>

        <div class="col-md-6 p-t-15">
          <div class="pull-right">
            @if (!isset($trash))
              <a href="{{ route('newspapers.create') }}" class="btn btn-success btn-small no-ls">
                <span class="fa fa-plus"></span> Cadastrar
              </a>
            @endif
          </div>
        </div>
      </div>
    </div>

    <!-- FILTERS -->
    @if (!isset($trash))
    <div class="row">
      <div class="col-md-12">
        <div class="grid simple">
          <div class="grid-title no-border">
            <h4>Filtros</h4>
          </div>

          <div class="grid-body no-border">
            <form action="{{ route('newspapers.index') }}" autocomplete="off" method="get" id="filter-form">

              <div class="row">

                <div class="col-md-4">
                  <input type="text" name="titulo" class="form-control"
                  placeholder="Titulo">
                </div>

                <div class="col-md-4">
                  <select name="category_id" class="form-control">
                    <option value="">Selecione a Categoria...</option>
                    @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id }}">{{ $categoria->titulo }}</option>
                    @endforeach
                  </select>
                </div>

              </div>

              <div class="row m-t-10">
                <div class="col-md-6">
                  @if ($_GET)
                    <a href="{{ route('newspapers.index') }}" class="btn btn-small btn-default">
                      <i class="fa fa-times"></i> Limpar filtros
                    </a>
                  @endif
                </div>

                <div class="col-md-6">
                  <button type="submit" class="btn btn-primary btn-small pull-right">
                    <i class="fa fa-search"></i> &nbsp; Filtrar
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    @endif
    <!-- FILTER END -->


    <!-- LISTING USERS -->
    <div class="grid simple">
      <div class="grid-title no-border">
        <div class="pull-left">
          <h4>
            Lista de <span class="semi-bold">Postagens</span>
            @if (isset($trash)) excluídos @endif
          </h4>
        </div>

        <div class="pull-left m-l-15">
          <div class="selected-options inline-block" style="visibility:hidden">
            @if (!isset($trash))
            <a href="#" class="btn btn-small btn-white delete" data-toggle="tooltip" data-original-title="Excluir selecionados">
              <i class="fa fa-fw fa-trash"></i>
              {{ csrf_field() }}
            </a>

            @else
            <a href="#" class="btn btn-small btn-white restore" data-toggle="tooltip" data-original-title="Restaurar selecionados">
              <i class="fa fa-fw fa-history"></i>
            </a>
            @endif
          </div>
        </div>
        <div class="clearfix"></div>
      </div>

      <div class="grid-body no-border">
        <!-- if there is no results -->
        @if (count($newspapers) == 0)
          <h5>Nenhuma postagem encontrada.</h5>
        @else

        <!-- the table -->
        <table class="table table-striped table-hover table-flip-scroll cf">
          <thead class="cf">
            <tr>
              <th>Data</th>
              <th>Imagem</th>
              <th>Titulo</th>
              <th>Categoria</th>
              <th width="96">Opções</th>
            </tr>
          </thead>
          <tbody id="sortable">
            @foreach ($newspapers as $newspaper)
            <tr>
             <td>{{ date('d/m/Y', strtotime($newspaper->data)) }}</td>
             <td><img src="/images/blog/{{ $newspaper->imagem }}" alt="" width="50px"></td>
             <td>{{ $newspaper->titulo }}</td>
             <td>{{ $newspaper->categoria }}</td>
              <td>
                @if (!isset($trash))
                <div class="btn-group">
                <button class="btn btn-small dropdown-toggle btn-demo-space"
                data-toggle="dropdown" href="#" aria-expanded="false">
                 Ações <span class="caret"></span> </button>
                  <ul class="dropdown-menu module-options">
                    <li>
                      <a href="{{ route('newspapers.edit', $newspaper->id) }}">
                        <i class="fa fa-edit"></i> Editar
                      </a>
                    </li>
                    <li class="divider"></li>
                    <li class="btn-delete">
                      {!! Form::open([
                        'method' => 'DELETE',
                        'route' => ['newspapers.destroy', $newspaper->id]
                      ]) !!}
                        <button type="submit">
                          <i class="fa fa-trash"></i> Excluir
                        </button>
                      {!! Form::close() !!}
                    </li>
                  </ul>
                </div>
                @else
                  <a href="{{ route('newspapers.restore', $newspaper->id) }}" class="btn btn-success btn-small">Restaurar</a>
                @endif
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>

        <!-- paginator -->
        <div class="pages">
          <div class="pull-left results">
            <strong>{{ $newspapers->total() }}</strong> registro(s)
          </div>

          <div class="pull-right">
            {!! $newspapers->links() !!}
          </div>
        </div>

        @endif

      </div> <!-- /.grid-body -->
    </div> <!-- /.grid -->
    <!-- LISTING END -->

  </div>
</div><!--  -->
@endsection

@if (!isset($trash))
@section('js')

  @include('backend.helpers.status-button')
@endsection
@endif

```

## Adicionando o FRONT no nosso arquivo blade form

```html

@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{ asset('backend/assets/plugins/autocomplete/autocomplete.css') }}">
<link rel="stylesheet" href="{{ asset('css/dropzone/css/dropzone.css')}}">

@endsection
@section('content')

<!-- Modal de confirmação -->
<div class="modal fade modal-file" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Tem certeza?</h4>
      </div>

      <div class="modal-body">
        Tem certeza que deseja excluir este arquivo? Ele não poderá ser restaurado.
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default cancelar" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-danger confirm-delete" data-file="" data-folder="" data-id="">Excluir arquivo</button>
      </div>

    </div>
  </div>
</div>

<div class="page-content">
  <div class="content">

    <!-- BREADCRUMB -->
    <ul class="breadcrumb">
      <li><p>VOCÊ ESTÁ AQUI</p></li>
      <li><a href="{{ route('newspapers.index') }}">Blog</a></li>
      @if (!isset($newspaper))
      <li><a class="active">Cadastrar</a></li>
      @else
      <li><a class="active">Alterar: {{ $newspaper->titulo }}</a></li>
      @endif
    </ul>

    <!-- TITLE-->
    <div class="page-title">
      <a href="{{ route('newspapers.index') }}">
        <i class="icon-custom-left"></i>
      </a>
      <h3>
        @if (!isset($newspaper)) Cadastrar nova
        @else Alterar @endif
        <span class="semi-bold">Matéria</span>
      </h3>
    </div>

      <!-- CONTENT -->
    <div class="row">
      <div class="col-md-12">
        <div class="grid simple">
          <div class="grid-title no-border">
            <h4>Informações da Matéria</h4>
          </div>

          <div class="grid-body no-border">
            {{-- FORM: NEW --}}
            @if (!isset($newspaper))
            {!! Form::open([
              'route' => 'newspapers.store',
              'files' => true
            ]) !!}
            {{-- FORM:EDIT --}}
            @else
            {!! Form::model($newspaper, [
                'method' => 'PATCH',
                'route' => ['newspapers.update', $newspaper->id],
                'files' => true
            ]) !!}
            @endif

            <div class="row">

               <div class="form-group col-md-6 {{ $errors->first('data')? 'has-error' : '' }}">
                  <label for="data" class="required">Data </label>
                  <div class="form-control input-append default date no-padding">
                    {!! Form::text('data', null, ['class' => 'form-control']) !!}
                  </div>
                  <small class="error">{{ $errors->first('data') }}</small>
                </div>

                <div class="form-group col-md-6 {{ $errors->first('category_id') ? 'has-error' : '' }}">
                  {!! Form::label('category_id', 'Categorias', ['class' => 'form-label required']) !!}
                  {!! Form::select('category_id', $categories, null, ['class' => 'form-control']) !!}
                  <small class="error">{{ $errors->first('category_id') }}</small>
                </div>
            </div>

            <div class="form-group {{ $errors->first('titulo') ? 'has-error' : '' }}">
              {!! Form::label('titulo', 'Titulo', ['class' => 'form-label required']) !!}
              {!! Form::text('titulo', null, ['class' => 'form-control']) !!}
              <small class="error">{{ $errors->first('titulo') }}</small>
            </div>

            <div class="form-group {{ $errors->first('descricao')? 'has-error' : '' }}">
            {!! Form::label('descricao', 'Descrição', ['class' => 'form-label']) !!}
            {!! Form::textarea('descricao', @$newspaper->descricao, ['id' => 'editor1', 'class' => 'form-control']) !!}
            <small class="error">{{ $errors->first('descricao') }}</small>
            </div>

            <div class="form-group {{ $errors->first('fonte') ? 'has-error' : '' }}">
              {!! Form::label('fonte', 'Fonte', ['class' => 'form-label']) !!}
              {!! Form::text('fonte', null, ['class' => 'form-control']) !!}
              <small class="error">{{ $errors->first('fonte') }}</small>
            </div>

              <div class="form-group {{ $errors->first('imagem') ? 'has-error' : '' }}">
                {!! Form::label('name', 'Imagem de Capa', ['class' => 'form-label']) !!}
                <span>(Tamanho recomendado: 870 pixels de largura mínima)</span>
                <div class="clearfix"></div>

                  <div>
                    <span class="btn btn-default btn-file">
                      <span class="fileinput-new">Selecionar imagem</span>
                      {!! Form::file('imagem') !!}
                    </span>
                  </div>
                <small class="error">{{ $errors->first('imagem') }}</small>
              </div>

         <div class="form-group {{ $errors->first('legenda_imagem') ? 'has-error' : '' }}">
            {!! Form::label('legenda_imagem', 'Legenda da Imagem', ['class' => 'form-label']) !!}
            {!! Form::text('legenda_imagem', null, ['class' => 'form-control']) !!}
            <small class="error">{{ $errors->first('legenda_imagem') }}</small>
          </div>

          <div class="form-actions">
            <div class="pull-right">
              <button class="btn btn-success" type="submit">
                <i class="fa fa-check"></i>
                @if (!isset($newspaper)) Cadastrar
                @else Alterar @endif
              </button>
              <a class="btn btn-danger" href="{{ route('newspapers.index') }}">Cancelar</a>
            </div>
          </div>
            {!! Form::close() !!}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('js')
<script src="{{ asset('backend/assets/plugins/ckeditor/ckeditor.js') }}"></script>
<script>
window.onload = function() {
  CKEDITOR.replace( 'editor1',{
     customConfig: '{{ asset("backend/assets/plugins/ckeditor/config.js") }}'
  });
};

$('.datepicker').datepicker();
</script>

<script src="https://rawgit.com/enyo/dropzone/master/dist/dropzone.js" type="text/javascript"></script>

@endsection


```


