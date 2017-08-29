<img src="http://i.imgur.com/TIlFmyE.png" alt="Laravel 5.4" width="650px">

<hr>

# Criando as views

Agora precisamos criar nossas views, com isso, podemos realizar nossos postagens no blog. 

<hr>

### Inserido o BLADE no index do blog

na pasta **backend->newspapers**, temos dois arquivos, com o código abaixo coloque no arquivo **index.blade.php**

```PHP
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
</div>
@endsection

@if (!isset($trash))
@section('js')

@endsection
@endif
```

### Inserido o BLADE no form do blog

Este arquivo serve para realizar as postagens no blog, basta colocar o código abaixo:

```PHP
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

### Fazendo o FRONT do Cadastro de Categorias

Dentro da mesma pasta do blog, temos a pasta categories, contendo dois arquivo, um para o index, no qual será listado nossos dados e outro para o form onde será realizado os cadastros, para o index coloque o código abaixo:

```PHP
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

@endsection
@endif
```

### FRONT para o cadastro de categorias

Agora para o front do cadastro das categorias, vamos utilizar o código abaixo:

```PHP
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
