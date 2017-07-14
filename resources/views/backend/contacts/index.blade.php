@extends('layouts.app')

@section('content')
<div class="page-content">
  <div class="content">

    <!-- BREADCRUMB -->
    <ul class="breadcrumb">
      <li><p>VOCÊ ESTÁ AQUI</p></li>
      @if (isset($trash))
      <li><a href="{{ route('contacts.index') }}">Contatos</a></li>
      <li><a class="active">lixeira</a></li>
      @else
      <li><a class="active">Contatos</a></li>
      @endif
    </ul>

    <!-- TITLE -->
    <div class="page-title">
      <div class="row">
        <div class="col-md-6">
          <h3>Contatos</h3>
        </div>

        <div class="col-md-6 p-t-15">
          <div class="pull-right">
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
            <form action="{{ route('contacts.index') }}" method="get" id="filter-form">

              <div class="row">

                <div class="col-md-4">
                  <input type="text" name="name" class="form-control" placeholder="Nome">
                </div>
                <div class="col-md-4">
                  <input type="text" name="email" class="form-control" placeholder="E-mail">
                </div>



              </div>
                <div class="row">
                    {{-- date begin --}}
                    <div class="col-md-3 col-xs-6 m-t-10">
                        <div class="input-append default date no-padding col-md-12">
                            <input type="text" name="start_date" class="form-control" placeholder="Data Início">
                            <span class="add-on add-on-sm">
                  <span class="arrow"></span>
                  <i class="fa fa-th"></i>
                </span>
                        </div>
                    </div>

                    {{-- date end --}}
                    <div class="col-md-3 col-xs-6 m-t-10">
                        <div class="input-append default date no-padding col-md-12">
                            <input type="text" name="end_date" class="form-control" placeholder="Data Fim">
                            <span class="add-on add-on-sm">
                  <span class="arrow"></span>
                  <i class="fa fa-th"></i>
                </span>
                        </div>
                    </div>
                </div>

              <div class="row m-t-10">
                <div class="col-md-6">
                  @if ($_GET)
                    <a href="{{ route('contacts.index') }}" class="btn btn-small btn-default">
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
            Lista de <span class="semi-bold">Contatos</span>
            @if (isset($trash)) excluídos @endif
          </h4>
        </div>
        @if (count($contacts) > 0)
          <div class="pull-right"><a href="{{ route('export') }}">Exportar Excel</a></div>
        @endif

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
        @if (count($contacts) == 0)
          <h5>Nenhum contato encontrado.</h5>
        @else

        <!-- the table -->
        <table class="table table-striped table-hover table-flip-scroll cf">
          <thead class="cf">
            <tr>
              <th width="42">
                <div class="checkbox check-default check-select">
                  <input id="checkall" type="checkbox" value="1" class="checkall">
                  <label for="checkall"></label>
                </div>
              </th>
              <th>Data</th>
              <th>Nome</th>
              <th>E-mail</th>
              <th>Telefone</th>
              <th>Cidade/UF</th>
              <th width="96">Opções</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($contacts as $contact)
            <tr>
              <td class="v-align-middle">
                <div class="checkbox check-default check-select">
                  <input id="{{ $contact->id }}" type="checkbox" name="selected[]" value="{{ $contact->id }}">
                  <label for="{{ $contact->id }}"></label>
                </div>
              </td>
              <td><a href="{{ route('contacts.show', $contact->id) }}">
                  {{ $contact->created_at->format('d/m/Y') }} às {{ $contact->created_at->format('H:i') }}
                </a></td>
              <td> <a href="{{ route('contacts.show', $contact->id) }}">{{ $contact->name }}</a></td>
              <td> <a href="{{ route('contacts.show', $contact->id) }}">{{ $contact->email }}</a></td>
              <td> <a href="{{ route('contacts.show', $contact->id) }}">{{ $contact->phone }}</a></td>
              <td> <a href="{{ route('contacts.show', $contact->id) }}">{{ $contact->city_name .'/'. $contact->state }}</a></td>

              <td>
                {!! Form::open([
                        'method' => 'DELETE',
                        'route' => ['contacts.destroy', $contact->id]
                      ]) !!}
                <button type="submit" class="btn btn-delete">
                  <i class="fa fa-trash"></i> Excluir
                </button>
                {!! Form::close() !!}
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>

        <!-- paginator -->
        <div class="pages">
          <div class="pull-left results">
            <strong>{{ $contacts->total() }}</strong> registro(s)
          </div>

          <div class="pull-right">
            {!! $contacts->appends(Request::except('page'))->links() !!}
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
  @include('helpers.status-button')

  <script>

    {{-- Hide --}}
    $('.js-charts').css('display', 'none');

  </script>
@endsection
@endif

