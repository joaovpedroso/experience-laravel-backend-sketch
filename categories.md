## Fazendo o backend de Categorias do blog

> Para que possamos iniciar nossa implementação do **blog**, decidi primeiramente realizar a implementação do cadastro de categorias.

<hr>

### Criando a tabela no banco de dados

No terminal, vamos digitar o camando abaixo:

```PHP
php artisan make:model Category --migration
```

Na pasta app->Models->Category.php, vamos incluir o seguinte código:

```
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model {
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'titulo'
    ];

    public function posts() {
        return $this->hasMany('App\Models\Newspaper', 'category_id')->where('status', 1);
    }

}
```
>Na pasta **database->migrations** temos o arquivo de **categories**, podem apagar este arquivo, pois não vamos utiliza-lo.

### Criando o nosso Controller para as Categorias do Blog

Após criar o nosso model, podemos enfim fazer nosso controller, ele que vai cuidar do CRUD desse nosso backend de Categorias.

Para criarmos o nosso controller, vamos fazer diretamente no terminal, com o seguinte comando:

```
php artisan make:controller CategoriesController
```

> Para que tenhamos uma organização melhor dos nossos códigos, é necessário mover o arquivo Categories qu está na pasta Controller, para a pasta Backend. Assim seguiremos um padrão e dividiremos o backend e o frontend.

Agora que temos o nosso controller criado, vamos substituir todo o código dentro com o código abaixo:

```PHP
<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller as Controller;

use App\Models\Category;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class CategoriesController extends Controller {

    protected $rules = [
        'titulo' => 'required',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        //query
        $categories = Category::orderBy('created_at', 'DESC');

        //filters
        // titulo
        if ($nome = str_replace(" ", "%", Input::get("nome"))) {
            $categories = $categories->where('titulo', 'like', "%$nome%");
        }

        //status
        $status = Input::get('status');
        if ((isset($status)) and ($status != '')) {
            $categories = $categories->where('status', '=', $status);
        }

        //categories data for graphs (without paginate)
        $allCategories = $categories->get();

        //execute
        $categories = $categories->paginate(config('helpers.results_per_page'));

        return view("backend.newspapers.categories.index", [
                'categories' => $categories,
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('backend.newspapers.categories.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {

        $this->validate($request, $this->rules);

        $input = $request->all();

        $category = Category::create($input);

        $request->session()->flash('success', 'Categoria criada com sucesso!');
        return redirect()->route('newspapers.categories.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $category = Category::findOrFail($id);

        return view('backend.newspapers.categories.form', [
                'category' => $category
            ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {

        $category = Category::findOrFail($id);

        $input = $request->all();

        $category->fill($input)->save();

        $request->session()->flash('success', 'Categoria alterada com sucesso!');
        return redirect()->route('newspapers.categories.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id) {

        //when selected some entries to delete
        if ($request->selected) {

            $entries = explode(',', $request->selected);

            DB::transaction(function () use ($entries) {
                    foreach ($entries as $entry) {
                        $category = Category::findOrFail($entry);
                        $category->update(array('status' => '0'));
                        $category->delete();

                        //log
                        Log::create([
                                'action'     => 'DELETE',
                                'data_id'    => $category->id,
                                'data_title' => $category->titulo
                            ]);

                    }
                });
            //restore
            $restore = "<a href='".route('newspapers.categories.restore', 0)."?entries=".$request->selected."'>Desfazer</a>";
        }

        //when chosen to delete just one entry
         else {
            $category = Category::findOrFail($id);
            $category->update(array('status' => '0'));

            DB::transaction(function () use ($category) {

                    $category->delete();

                    //log
                    Log::create([
                            'action'     => 'DELETE',
                            'data_id'    => $category->id,
                            'data_title' => $category->titulo
                        ]);

                });
            //restore
            $restore = "<a href='".route('newspapers.categories.restore', $id)."'>Desfazer</a>";
        }

        //return
        session()->flash('success', "Categoria(s) excluída(s) com sucesso. $restore");
        return redirect()->route('newspapers.categories.index');

    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id) {
        //when restoring a lot of entries
        if ($entries = Input::get('entries')) {
            $entries = explode(',', $entries);

            DB::transaction(function () use ($entries) {
                    foreach ($entries as $entry) {
                        Category::withTrashed()->where('id', $entry)->restore();

                        $category = Category::find($entry);

                        //log
                        Log::create([
                                'action'     => 'RESTORE',
                                'data_id'    => $category->id,
                                'data_title' => $category->titulo
                            ]);
                    }
                });
        }

        //when restoring 1 entry
         else {

            DB::transaction(function () use ($id) {
                    Category::withTrashed()->where('id', $id)->restore();

                    $category = Category::find($id);

                    //log
                    Log::create([
                            'action'     => 'RESTORE',
                            'data_id'    => $category->id,
                            'data_title' => $category->titulo
                        ]);

                });
        }

        session()->flash('success', 'Categoria(s) restaurada(s) com sucesso.');
        return redirect()->route('newspapers.categories.index');
    }
    /**
     * Display a listing of soft deletes.
     *
     * @return \Illuminate\Http\Response
     */
    public function trash() {
        $categories = Category::onlyTrashed()->paginate(config('helpers.results_per_page'));

        return view('backend.newspapers.categories.index', [
                'categories' => $categories,
                'trash'      => true,
            ]);
    }

    /**
     * Update the status of specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function status() {
        $id     = (int) Input::get('id');
        $status = (int) Input::get('status');

        $code = 418;//I'm a teapot!

        if ($id and preg_match('/(0|1)/', $status)) {
            $category                     = Category::findOrFail($id);
            $category->status             = $status;
            if ($category->save()) {$code = 200;
            }

            //log
            Log::create([
                    'action'     => 'STATUS',
                    'data_id'    => $category->id,
                    'data_title' => $category->titulo
                ]);

        }

        return $code;
    }

    /**
     * Change the order of categories at navbar
     */
    public function order(Request $request) {
        $code = 418;//I'm a teapot!

        foreach ($request->item as $order => $id) {
            $category                     = Category::find($id);
            $category->order              = $order;
            if ($category->save()) {$code = 200;
            }
        }

        return $code;
    }

}

```

### Criando nossa as Rotas

Agora precisamos ligar (rota) nossos métodos do controller para a view. Na pasta **routes->web.php** vamos inserir dentro do nosso grupo de rotas o código abaixo:

````PHP
//Category
        Route::get('/newspapers/categories', 'Backend\CategoriesController@index')->name('newspapers.categories.index');

        Route::get('/newspapers/categories/create', 'Backend\CategoriesController@create')->name('newspapers.categories.create');

        Route::get('/newspapers/categories/{categories}/restore',
            'Backend\CategoriesController@restore')->name('newspapers.categories.restore');
        Route::post('/newspapers/categories/order', 'Backend\CategoriesController@order');
        Route::get('/newspapers/categories/trash', 'Backend\CategoriesController@trash')
            ->name('newspapers.categories.trash');

        Route::post('/newspapers/categories/create/store', 'Backend\CategoriesController@store')->name('newspapers.categories.store');

        Route::get('/newspapers/categories/edit/{id}', 'Backend\CategoriesController@edit')->name('newspapers.categories.edit');

        Route::get('/newspapers/categories/destroy', 'Backend\CategoriesController@destroy')->name('newspapers.categories.destroy');

        Route::get('/newspapers/categories/restore', 'Backend\CategoriesController@restore')->name('newspapers.restore');

        Route::patch('/newspapers/categories/update/{id}', 'Backend\CategoriesController@update')->name('newspapers.categories.update');
```

### Criando nosso FRONT

Agora que temos nosso model, nosso controller e nossas rotas, precisamos fazer o front, para isto acessamos a pasta **resource/views** e lá iremos trabalhar com o nosso FRONT.

Antes de criamos arquivos, vamos pensar em nosso **MVC**, criaremos uma pasta chamada **backend**, dentro da pasta views.

Dentro da nossa pasta, **backend**, vamos criar uma segunda pasta, chamada **newspaper**. Nela ficará nosso **FRONT** do cadastro das categorias juntamente com o **FRONT** do **Blog**. Para separmos melhor isso, vamos criar dentro de **newspapers**, uma pasta chamada **categories**.

Na pasta categories, vamos criar dois arquivos, da seguinte forma:

* form.blade.php
* index.blade.php

Dentro do arquivo form.blade.php, coloque o código abaixo:

```HTML
@extends('layouts.app')

@section('content')
<div class="page-content">
  <div class="content">

    <!-- BREADCRUMB -->
    <ul class="breadcrumb">
      <li><p>VOCÊ ESTÁ AQUI</p></li>
      <li><a href="{{ route('newspapers.categories.index') }}">Categorias</a></li>
      @if (!isset($category))
      <li><a class="active">Cadastrar</a></li>
      @else
      <li><a class="active">Alterar: {{ $category->titulo }}</a></li>
      @endif
    </ul>

    <!-- TITLE-->
    <div class="page-title">
      <a href="{{ route('newspapers.categories.index') }}">
        <i class="icon-custom-left"></i>
      </a>
      <h3>
        @if (!isset($category)) Cadastrar novo
        @else Alterar @endif
        <span class="semi-bold">Categoria</span>
      </h3>
    </div>

      <!-- CONTENT -->
    <div class="row">
      <div class="col-md-12">
        <div class="grid simple">
          <div class="grid-title no-border">
            <h4>Informações do Categoria</h4>
          </div>

          <div class="grid-body no-border">
            {{-- FORM: NEW --}}
            @if (!isset($category))
            {!! Form::open([
              'route' => 'newspapers.categories.store',
              'enctype' => 'multipart/form-data'
            ]) !!}
            {{-- FORM:EDIT --}}
            @else
            {!! Form::model($category, [
                'method' => 'PATCH',
                'route' => ['newspapers.categories.update', $category->id],
                'enctype' => 'multipart/form-data'
            ]) !!}
            @endif


            <div class="form-group {{ $errors->first('titulo') ? 'has-error' : '' }}">
              {!! Form::label('titulo', 'Nome', ['class' => 'form-label required']) !!}
              {!! Form::text('titulo', null, ['class' => 'form-control']) !!}
              <small class="error">{{ $errors->first('titulo') }}</small>
            </div>

            <div class="row"></div>

              <div class="form-actions">
                <div class="pull-right">
                  <button class="btn btn-success" type="submit">
                    <i class="fa fa-check"></i>
                    @if (!isset($category)) Cadastrar
                    @else Alterar @endif
                  </button>
                  <a class="btn btn-danger" href="{{ route('newspapers.categories.index') }}">Cancelar</a>
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

@endsection

```
E dentro do arquivo index.blade.php, coleque o código abaixo:

```HMTL
@extends('layouts.app')

@section('content')
<div class="page-content">
  <div class="content">

    <!-- BREADCRUMB -->
    <ul class="breadcrumb">
      <li><p>VOCÊ ESTÁ AQUI</p></li>
      @if (isset($trash))
      <li><a href="{{ route('newspapers.categories.index') }}">Categorias</a></li>
      <li><a class="active">excluídos</a></li>
      @else
      <li><a href="{{ route('newspapers.index') }}">Blog</a></li>
      <li><a href="{{ route('newspapers.categories.index') }}" class="active">Categorias</a></li>
      @endif
    </ul>

    <!-- TITLE -->
    <div class="page-title">
      <div class="row">
        <div class="col-md-6">
          <h3>Categorias</h3>
        </div>

        <div class="col-md-6 p-t-15">
          <div class="pull-right">
            @if (!isset($trash))

              <a href="{{ route('newspapers.categories.create') }}" class="btn btn-success btn-small no-ls">
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
            <form action="{{ route('newspapers.categories.index') }}" autocomplete="off" method="get" id="filter-form">

              <div class="row">

                <div class="col-md-4">
                  <label for="">Nome</label>
                  <input type="text" name="nome" class="form-control" placeholder="Nome">
                </div>

              </div>

              <div class="row m-t-10">
                <div class="col-md-6">
                  @if ($_GET)
                    <a href="{{ route('newspapers.categories.index') }}" class="btn btn-small btn-default">
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
            Lista de <span class="semi-bold">Categorias</span>
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
        @if (count($categories) == 0)
          <h5>Nenhuma categoria encontrada.</h5>
        @else

        <!-- the table -->
        <table class="table table-striped table-hover table-flip-scroll cf">
          <thead class="cf">
            <tr>
              <th>Nome</th>
              <th width="96">Opções</th>
            </tr>
          </thead>
          <tbody id="sortable">
            @foreach ($categories as $category)
            <tr>
             <td>{{ $category->titulo }}</td>
              <td>
                @if (!isset($trash))
                <div class="btn-group">
                <button class="btn btn-small dropdown-toggle btn-demo-space"
                data-toggle="dropdown" href="#" aria-expanded="false">
                 Ações <span class="caret"></span> </button>
                  <ul class="dropdown-menu module-options">
                    <li>
                      <a href="{{ route('newspapers.categories.edit', $category->id) }}">
                        <i class="fa fa-edit"></i> Editar
                      </a>
                    </li>
                    <li class="divider"></li>
                    <li class="btn-delete">
                      {!! Form::open([
                        'method' => 'DELETE',
                        'route' => ['newspapers.categories.destroy', $category->id]
                      ]) !!}
                        <button type="submit">
                          <i class="fa fa-trash"></i> Excluir
                        </button>
                      {!! Form::close() !!}
                    </li>
                  </ul>
                </div>
                @else
                  <a href="{{ route('newspapers.categories.restore', $category->id) }}" class="btn btn-success btn-small">Restaurar</a>
                @endif
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>

        <!-- paginator -->
        <div class="pages">
          <div class="pull-left results">
            <strong>{{ $categories->total() }}</strong> registro(s)
          </div>

          <div class="pull-right">
            {!! $categories->links() !!}
          </div>
        </div>

        @endif

      </div> <!-- /.grid-body -->
    </div> <!-- /.grid -->
    <!-- LISTING END -->

  </div>
</div>
@endsection

@if (!isset($trash))
@section('js')
  @include('backend.helpers.status-button')
@endsection
@endif

```

