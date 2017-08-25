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

  @include('backend.helpers.status-button')
@endsection
@endif


