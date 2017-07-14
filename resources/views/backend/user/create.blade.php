@extends('layouts.app')

@section('content')
    <div class="page-content">
        <div class="content">

            <!-- BREADCRUMB -->
            <ul class="breadcrumb">
                <li><p>VOCÊ ESTÁ AQUI</p></li>
                <li><a href="{{ route('users.index') }}">Usuários</a></li>
                <li><a class="active">Cadastrar</a></li>
            </ul>

            <!-- TITLE-->
            <div class="page-title">
                <a href="{{ route('users.index') }}">
                    <i class="icon-custom-left"></i>
                </a>
                <h3>
                    Cadastrar novo <span class="semi-bold">Usuário</span>
                </h3>
            </div>

            <!-- CONTENT -->
            <div class="row">
                <div class="col-md-12">
                    <div class="grid simple">
                        <div class="grid-title no-border">

                        </div>

                        <div class="grid-body no-border">

                            {!! Form::open([
                                'route' => 'users.store',
                                'files' => true
                              ]) !!}


                            @include('backend.user.form', ['submitButtonText' => 'Cadastrar'])


                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
