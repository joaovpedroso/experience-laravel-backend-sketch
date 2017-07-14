@extends('layouts.app')

@section('content')
<div class="page-content">
  <div class="content">

    <!-- BREADCRUMB -->
    <ul class="breadcrumb">
      <li><p>VOCÊ ESTÁ AQUI</p></li>
      <li><a href="{{ route('config.index') }}">Configurações</a></li>
      <li><a href="{{ route('config.index') }}">Módulos</a></li>
      <li><a class="active">Sub-Módulos de {{ $module->name }}</a></li>
    </ul>

    <!-- TITLE-->
    <div class="page-title">
      <a href="{{ route('modules.index') }}">
        <i class="icon-custom-left"></i>
      </a>
      <h3>Sub-Módulos</h3>
    </div>

    <!-- CONTENT -->
    <div class="row">
      <div class="col-md-12">
        <div class="grid simple">
          <div class="grid-title no-border">
            <h4>Lista de <span class="semi-bold">Sub-Módulos</span> de <span class="semi-bold">{{ $module->name }}</span></h4>

            <a href="{{ route('modules.submodules.create', $module->id) }}" class="btn btn-success btn-sm btn-small pull-right">
              <i class="fa fa-plus"></i>
              Cadastrar
            </a>
          </div>

          <div class="grid-body no-border">

            <!-- no results -->
            @if (count($submodules) == 0)
              <p>Nenhum sub-módulo encontrado.</p>
            @else

            <!-- table -->
            <table class="table table-striped table-hover table-flip-scroll cf">
              <thead class="cf">
                <tr>
                  <th width="35">&nbsp;</th>
                  <th>Nome</th>
                  <th>URL</th>
                  <th width="72">Status</th>
                  <th width="96">Opções</th>
                </tr>
              </thead>
              <tbody id="sortable">
                @foreach ($submodules as $submodule)
                <tr id="item_{{ $submodule->id }}">
                  <td class="handle"><i class="fa fa-arrows"></i></td>
                  <td>{{ $submodule->name }}</td>
                  <td>{{ $module->url.$submodule->url }}</td>
                  <td>
                    <input type="checkbox" data-id="{{ $submodule->id }}" class="js-switch" @if ($submodule->status == 'Ativo') checked @endif />
                  </td>
                  <td>
                    <button class="btn btn-small dropdown-toggle" data-toggle="dropdown">Ações <span class="caret"></span></button>
                    <ul class="dropdown-menu module-options">
                      <li>
                        <a href="{{ route('modules.submodules.edit', [$module->id, $submodule->id]) }}">
                          <i class="fa fa-edit"></i> Editar
                        </a>
                      </li>

                      <li class="divider"></li>

                      <li class="btn-delete">
                        {!! Form::open([
                          'method' => 'DELETE',
                          'route' => ['modules.submodules.destroy', $module->id, $submodule->id]
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

            @endif

          </div> <!-- /.grid-body -->
        </div> <!-- /.grid -->
      </div> <!-- /.col -->
    </div> <!-- /.row -->

  </div>
</div>
@endsection

@section('js')
  <script>
      $(document).on('change', '.js-switch', function() {
          var url = window.location.pathname + "/status";

          var id = $(this).attr('data-id');
          var status = $(this).prop('checked');

          if (status == true) status = 'Ativo';
          else status = 'Inativo';

          $.get(url, {id:id, status:status}, function(code) {
              if (code != 200)
                  noty({
                      text: "Ocorreu um problema! Tente novamente mais tarde.",
                      type: 'error'
                  });
          });
      });
  </script>

  <script>
    $(document).ready(function() {
      $('#sortable').sortable({
        axis: 'y',
        handle: '.handle',
        update: function(event, ui) {
          var url = window.location.pathname + "/order";
          var data = $(this).sortable('serialize');

          $.post(url, data, function(res) {
            if (res != 200) {
              noty({text: "Ocorreu um erro! Tente novamente.", type: 'error'});
            }
          });
        }
      });
    });
  </script>
@endsection
