@extends('layouts.app')

@section('content')
<div class="page-content">
  <div class="content">

    <!-- BREADCRUMB -->
    <ul class="breadcrumb">
      <li><p>VOCÊ ESTÁ AQUI</p></li>
      <li><a href="{{ route('content.news.index') }}">Notícias</a></li>
      <li><a class="active">Categorias</a></li>
    </ul>

    <!-- TITLE-->
    <div class="page-title">
      <a href="{{ route('content.news.index') }}">
        <i class="icon-custom-left"></i>
      </a>
      <h3>Categorias</h3>
    </div>

    <!-- CONTENT -->
    <div class="row">
      <div class="col-md-12">
        <div class="grid simple">
          <div class="grid-title no-border">
            <h4>Lista de <span class="semi-bold">Categorias</span></h4>

            <a href="{{ route('editorials.create') }}" class="btn btn-success btn-sm btn-small pull-right">
              <i class="fa fa-plus"></i>
              Cadastrar
            </a>
          </div>

          <div class="grid-body no-border">
            <!-- no results -->
            @if (count($editorials) == 0)
              <p>Nenhuma editoria encontrada.</p>
            @else

            <!-- table -->
            <table class="table table-striped table-hover table-flip-scroll cf">
              <thead class="cf">
                <tr>
                  <th>Nome</th>
                  <th width="72">Status</th>
                  <th width="96">Opções</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($editorials as $editorial)
                <tr>
                  <td>{{ $editorial->name }}</td>
                  <td>
                    <input type="checkbox" data-id="{{ $editorial->id }}" class="js-switch" @if ($editorial->status == 1) checked @endif />
                  </td>
                  <td>
                    <button class="btn btn-small dropdown-toggle" data-toggle="dropdown">Ações <span class="caret"></span></button>
                    <ul class="dropdown-menu module-options">
                      <li>
                        <a href="{{ route('editorials.edit', $editorial->id) }}">
                          <i class="fa fa-edit"></i> Editar
                        </a>
                      </li>

                      <li class="divider"></li>

                      <li class="btn-delete">
                        {!! Form::open([
                          'method' => 'DELETE',
                          'route' => ['editorials.destroy', $editorial->id]
                        ]) !!}
                          <button type="submit">
                            <i class="fa fa-trash"></i> Excluir
                          </button>
                        {!! Form::close() !!}
                      </li>
                    </ul>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>

            <!-- paginator -->
            @include('helpers.paginator', ['var' => $editorials])

            @endif

          </div> <!-- /.grid-body -->
        </div> <!-- /.grid -->
      </div> <!-- /.col -->
    </div> <!-- /.row -->

  </div>
</div>
@endsection

@section('js')
  @include('helpers.status-button')
@endsection
