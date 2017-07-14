@extends('layouts.app')

@section('content')
    <div class="page-content">
        <div class="content">

            <!-- BREADCRUMB -->
            <ul class="breadcrumb">
                <li><p>VOCÊ ESTÁ AQUI</p></li>
                <li><a href="{{ route('users.index') }}">Usuários</a></li>
                <li><a class="active">Alterar: {{ $user->name }}</a></li>
            </ul>

            <!-- TITLE-->
            <div class="page-title">
                <a href="{{ route('users.index') }}">
                    <i class="icon-custom-left"></i>
                </a>
                <h3>
                    Alterar <span class="semi-bold">Usuário</span>
                </h3>
            </div>

            <!-- CONTENT -->
            <div class="row">
                <div class="col-md-12">
                    <div class="grid simple">
                        <div class="grid-title no-border">

                        </div>
                        <div class="grid-body no-border">


                            {!! Form::model($user, [
                                                    'method' => 'PATCH',
                                                    'route' => ['users.update', $user->id],
                                                    'files' => true
                                                ]) !!}


                                @include('backend.user.form', ['submitButtonText' => 'Alterar',
                                                                'var' => $user])


                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection


