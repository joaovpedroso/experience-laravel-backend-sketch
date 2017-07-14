@extends('layouts.app')

@section('content')
    <div class="page-content">
        <div class="content">

            <!-- BREADCRUMB -->
            <ul class="breadcrumb">
                <li><p>VOCÊ ESTÁ AQUI</p></li>
                <li><a class="active">Usuários</a></li>
            </ul>

            <!-- TITLE -->
            <div class="page-title">
                <div class="row">
                    <div class="col-md-6">
                        <a href="#" onclick="goBack()">
                            <i class="icon-custom-left"></i>
                        </a>
                        <h3>Usuários</h3>
                    </div>

                    <div class="col-md-6 p-t-15">
                        <div class="text-right text-center-xs">
                            @if (!isset($trash))


                                <a href="{{ route('logs.index') . '?modules[]=App\Models\User' }}"
                                   class="btn btn-small btn-df-xs m-r-5" data-toggle="tooltip"
                                   data-original-title="Logs">
                                    <span class="fa fa-file-text-o"></span>
                                </a>

                                {{-- <a href="{{ route('users.trash') }}" class="btn btn-small btn-df-xs m-r-5" data-toggle="tooltip" data-original-title="Lixeira">
                                  <span class="fa fa-trash"></span>
                                </a> --}}

                                <a href="{{ route('users.create') }}"
                                   class="btn btn-success btn-df-xs btn-small no-ls">
                                    <span class="fa fa-plus"></span> Cadastrar
                                </a>

                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- FILTERS -->
        @include('backend.user.filters')


        <!-- LISTING USERS -->
            <div class="grid simple">
                <div class="grid-title no-border">
                    <div class="pull-left">
                        <h4>
                            Lista de <span class="semi-bold">Usuários</span>
                            @if (isset($trash)) excluídos @endif
                        </h4>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="grid-body no-border">
                    <!-- if there is no results -->
                    @if (count($users) == 0)
                        <h5>Nenhum usuário encontrado.</h5>
                    @else

                    <!-- the table -->
                        <table class="table table-striped table-hover table-flip-scroll cf">
                            <thead class="cf">
                            <tr>
                                <th>Nome</th>
                                <th>E-mail</th>
                                <th>Cadastrado em</th>
                                <th width="72">Status</th>

                                <th width="96">Opções</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ date('d/m/Y H:i:s', strtotime($user->created_at)) }}</td>
                                    <td>
                                        @if($user->status == 'Ativo')
                                            <span class="label label-success">Ativo</span>
                                        @else
                                            <span class="label label-danger">Inativo</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if (!isset($trash))
                                            <button class="btn btn-small dropdown-toggle" data-toggle="dropdown">
                                                Ações
                                                <span class="caret"></span></button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a href="{{ route('users.password', $user->id) }}">
                                                        <i class="fa fa-key"></i> Reenviar senha
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('users.edit', $user->id) }}">
                                                        <i class="fa fa-edit"></i> Editar
                                                    </a>
                                                </li>
                                                @if((auth()->user()->hasPermission('add permission users')) || (auth()->user()->hasRole('Admin')))
                                                    <li class="divider"></li>
                                                    <li>
                                                        <a href="{{ route('users.edit.permission', $user->id) }}">
                                                            <i class="fa fa-edit"></i> Editar Permissões
                                                        </a>
                                                    </li>
                                                @endif

                                            </ul>
                                        @else
                                            <a href="{{ route('users.restore', $user->id) }}"
                                               class="btn btn-success btn-small">Restaurar</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <!-- paginator -->
                        @include('helpers.paginator', ['var' => $users])

                    @endif

                </div> <!-- /.grid-body -->
            </div> <!-- /.grid -->
            <!-- LISTING END -->

        </div>
    </div>
@endsection
