@extends('layouts.app')

@section('content')
    <div class="page-content">
        <div class="content">

            <!-- BREADCRUMB -->
            <ul class="breadcrumb">
                <li><p>VOCÊ ESTÁ AQUI</p></li>
                <li><a href="#">Configurações</a></li>
                <li><a class="active">Informações</a></li>
            </ul>

            <!-- TITLE-->
            <div class="page-title">
                <a href="{{ route('config.painel') }}">
                    <i class="icon-custom-left"></i>
                </a>
                <h3>
                    Alterar <span class="semi-bold">Informações</span>
                </h3>
            </div>

            <!-- CONTENT -->
            <div class="row">
                <div class="col-md-12">
                    <div class="grid simple">
                        <div class="grid-title no-border">

                        </div>
                        <div class="grid-body no-border">


                            {!! Form::model($config, [
                                                    'method' => 'PATCH',
                                                    'route' => ['config.info.update', 1],
                                                    'files' => true
                                                ]) !!}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group col-md-6 {{ $errors->first('name')? 'has-errors' : '' }}">
                                        {!! Form::label('name', 'Nome da Empresa', ['class' => 'form-label']) !!}
                                        {!! Form::text('name', null, ['class' => 'form-control']) !!}
                                        <small class="error">{{ $errors->first('name') }}</small>
                                    </div>

                                    <div class="form-group col-md-6 {{ $errors->first('address')? 'has-errors' : '' }}">
                                        {!! Form::label('address', 'Endereço', ['class' => 'form-label ']) !!}
                                        {!! Form::text('address', null, ['class' => 'form-control']) !!}
                                        <small class="error">{{ $errors->first('address') }}</small>
                                    </div>
                                    <div class="form-group col-md-6 {{ $errors->first('number')? 'has-errors' : '' }}">
                                        {!! Form::label('number', 'Número', ['class' => 'form-label ']) !!}
                                        {!! Form::text('number', null, ['class' => 'form-control']) !!}
                                        <small class="error">{{ $errors->first('number') }}</small>
                                    </div>
                                    <div class="form-group col-md-6 {{ $errors->first('complement')? 'has-errors' : '' }}">
                                        {!! Form::label('complement', 'Complemento', ['class' => 'form-label ']) !!}
                                        {!! Form::text('complement', null, ['class' => 'form-control']) !!}
                                        <small class="error">{{ $errors->first('complement') }}</small>
                                    </div>
                                    <div class="form-group col-md-6 {{ $errors->first('phone')? 'has-errors' : '' }}">
                                        {!! Form::label('phone', 'Telefone', ['class' => 'form-label']) !!}
                                        {!! Form::text('phone', null, ['class' => 'form-control tphone']) !!}
                                        <small class="error">{{ $errors->first('phone') }}</small>
                                    </div>
                                    <div class="form-group col-md-6 {{ $errors->first('cell_phone')? 'has-errors' : '' }}">
                                        {!! Form::label('cell_phone', 'Celular', ['class' => 'form-label']) !!}
                                        {!! Form::text('cell_phone', null, ['class' => 'form-control phone']) !!}
                                        <small class="error">{{ $errors->first('cell_phone') }}</small>
                                    </div>
                                    <div class="form-group col-md-6 {{ $errors->first('facebook')? 'has-errors' : '' }}">
                                        {!! Form::label('facebook', 'Link facebook', ['class' => 'form-label ']) !!}
                                        {!! Form::text('facebook', null, ['class' => 'form-control']) !!}
                                        <small class="error">{{ $errors->first('facebook') }}</small>
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions m-b-5">
                                <div class="pull-right">
                                    <button class="btn btn-success" type="submit">
                                        <i class="fa fa-check"></i>
                                        Alterar
                                    </button>
                                    <a class="btn btn-white" href="{{ route('users.index') }}">Cancelar</a>
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
    <script>
        $(document).ready(function () {
            var SPMaskBehavior = function (val) {
                    return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
                },
                spOptions = {
                    onKeyPress: function (val, e, field, options) {
                        field.mask(SPMaskBehavior.apply({}, arguments), options);
                    }
                };

            $('.phone').mask(SPMaskBehavior, spOptions);
            $('.tphone').mask('(99) 9999-9999');

        })
    </script>
@endsection