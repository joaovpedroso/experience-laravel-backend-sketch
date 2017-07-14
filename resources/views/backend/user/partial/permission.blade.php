@extends('layouts.app')

@section('content')
    <div class="page-content">
        <div class="content">

            <!-- BREADCRUMB -->
            <ul class="breadcrumb">
                <li><p>VOCÊ ESTÁ AQUI</p></li>
                <li><a href="{{ route('users.index') }}">Usuários</a></li>
                <li><a class="active">Alterar Permissões: {{ $user->name }}</a></li>
            </ul>

            <!-- TITLE-->
            <div class="page-title">
                <a href="{{ route('users.index') }}">
                    <i class="icon-custom-left"></i>
                </a>
                <h3>
                    Alterar <span class="semi-bold">Permissões</span>
                </h3>
            </div>

            <!-- CONTENT -->
            <div class="row">
                <div class="col-md-12">
                    <div class="grid simple">
                        <div class="grid-title no-border">
                            <h4>Permissões <span class="help help-inline">Adicione ou retire permissões para
                                    este usuário</span></h4>
                        </div>
                        <div class="grid-body no-border">
                            {!! Form::open(['url' => route('users.edit.permissionPost', $user->id), 'method' => 'patch']) !!}
                            <ul class="list-group row">
                                @foreach(\Spatie\Permission\Models\Permission::all() as $perm)
                                    <li class="list-group-item col-md-6">
                                        <div class="col-md-6"><strong>{{ $perm->translate }}</strong></div>
                                        <div class="col-md-6">
                                            <div class="checkbox check-default check-select col-md-5">
                                                <input id="{{ $perm->id }}" name="permissions[]" type="checkbox"
                                                       value="{{ $perm->name }}"
                                                        {{ ($user->hasPermissionTo($perm->name)) ? 'checked' : '' }}>
                                                <label for="{{ $perm->id }}"></label>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                            {!! Form::submit('Salvar', ['class' => 'btn']) !!}
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection


