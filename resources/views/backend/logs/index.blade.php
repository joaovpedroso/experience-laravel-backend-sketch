@extends('layouts.app')

@section('content')
    <div class="page-content">
        <div class="content">

            <!-- BREADCRUMB -->
            <ul class="breadcrumb">
                <li><p>VOCÊ ESTÁ AQUI</p></li>
                <li><a class="active">Logs</a></li>
            </ul>

            <!-- TITLE -->
            <div class="page-title">
                <a href="javascript:;" onclick="goBack()">
                    <i class="icon-custom-left"></i>
                </a>
                <h3>Logs</h3>
            </div>


            <!-- FILTERS -->
            <div class="row">
                <div class="col-md-12">
                    <div class="grid simple">
                        <div class="grid-title no-border">
                            <h4>Filtros</h4>
                        </div>

                        <div class="grid-body no-border">
                            <form action="{{ route('logs.index') }}" method="get" id="filter-form">
                                <div class="row">

                                    <div class="col-md-3 col-xs-6">
                                        <div class="input-append default date no-padding col-md-12">
                                            <input type="text" name="start_date" class="form-control date-mask"
                                                   placeholder="Data Início">
                                            <span class="add-on add-on-sm">
                      <span class="arrow"></span>
                      <i class="fa fa-th"></i>
                    </span>
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-xs-6">
                                        <div class="input-append default date no-padding col-md-12">
                                            <input type="text" name="end_date" class="form-control date-mask"
                                                   placeholder="Data Fim">
                                            <span class="add-on add-on-sm">
                      <span class="arrow"></span>
                      <i class="fa fa-th"></i>
                    </span>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <select name="actions[]" class="multi" data-placeholder="Ação" multiple>
                                            @foreach ($actions as $action)
                                                <option value="{{ $action->first()->description }}">{{ trans('logs.'.$action->first()->description) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 m-t-10">
                                        <select name="users[]" class="multi" data-placeholder="Usuário" multiple>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->first()->causer_id }}">{{ $user->first()->cause }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6 m-t-10">
                                        <select name="modules[]" class="multi" data-placeholder="Módulo" multiple>
                                            @foreach ($modules as $module)
                                                <option value="{{ $module->first()->subject_type }}">
                                                    {{ trans('logs.'.class_basename(get_class($module->first()->subject))) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-12 m-t-10">
                                        <input type="text" name="title" class="form-control"
                                               placeholder="ID ou título do registro">
                                    </div>

                                </div>

                                <div class="row m-t-10">
                                    <div class="col-md-6">
                                        @if ($_GET)
                                            <a href="{{ route('logs.index') }}"
                                               class="btn btn-small btn-default btn-df-xs">
                                                <i class="fa fa-times"></i> Limpar filtros
                                            </a>
                                        @endif
                                    </div>

                                    <div class="col-md-6">
                                        <button type="submit" class="btn btn-primary btn-small pull-right btn-df-xs">
                                            <i class="fa fa-search"></i> &nbsp; Filtrar
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


            <!-- LOGS -->
            <div class="row">
                <div class="col-md-12">
                    <div class="grid simple">
                        <div class="grid-title no-border">
                            <h4>Lista de <span class="semi-bold">Logs</span></h4>
                        </div>

                        <div class="grid-body no-border">
                            <!-- if there is no results -->
                            @if (count($logs) == 0)
                                <h5>Nenhum log encontrado.</h5>
                            @else

                            <!-- the table -->
                                <table class="table table-striped table-flip-scroll cf">
                                    <thead class="cf">
                                    <tr>
                                        <th width="30px">Data</th>
                                        <th>Usuário</th>
                                        <th>Módulo</th>
                                        <th>Ação</th>
                                        <th>ID</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($logs as $log)
                                        <tr>
                                            <td align="left">{{ date('d/m/Y H:i:s', strtotime($log->created_at)) }}</td>
                                            <td align="left">{{ $log->causer['name'] }}</td>
                                            {{-- translates @ /resources/lang/pt-BR/logs.php --}}
                                            <td>{{ trans('logs.'.class_basename(get_class($log->subject))) }}</td>
                                            <td>{{ trans('logs.'.$log->description) }}</td>
                                            <td>

                    <span data-toggle="tooltip" data-original-title="Nome: {{ $log->subject['name'] }}">
                      {{ $log->subject_id }} <i class="fa fa-info-circle"
                    </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                                <!-- paginator -->
                                @include('helpers.paginator', ['var' => $logs])

                            @endif

                        </div> <!-- /.grid-body -->
                    </div> <!-- /.grid -->
                </div> <!-- /.col -->
            </div> <!-- /.row -->

        </div>
    </div>
@endsection
