@extends('layouts.app')

@section('content')
    <div class="page-content">
        <div class="content">

            <!-- BREADCRUMB -->
            <ul class="breadcrumb">
                <li><p>VOCÊ ESTÁ AQUI</p></li>
                <li><a href="#">Configurações</a></li>
                <li><a class="active">Configurações dos Módulos</a></li>
            </ul>

            <!-- TITLE-->
            <div class="page-title">
                <a href="{{ route('config.painel') }}">
                    <i class="icon-custom-left"></i>
                </a>
                <h3>
                    Alterar <span class="semi-bold">Configurações</span>
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
                                                    'route' => ['modules.config.update', 1],
                                                    'files' => true
                                                ]) !!}
                            <h4> Slides </h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col-md-6 {{ $errors->first('banner_largura_max_recomendada')? 'has-errors' : '' }}">
                                        {!! Form::label('banner_largura_max_recomendada', 'Largura Recomendada', ['class' => 'form-label']) !!}
                                        {!! Form::number('banner_largura_max_recomendada', null, ['class' => 'form-control']) !!}
                                        <small class="error">{{ $errors->first('banner_largura_max_recomendada') }}</small>
                                    </div>

                                    <div class="form-group col-md-6 {{ $errors->first('banner_altura_max_recomendada')? 'has-errors' : '' }}">
                                        {!! Form::label('banner_altura_max_recomendada', 'Altura Recomendada', ['class' => 'form-label ']) !!}
                                        {!! Form::number('banner_altura_max_recomendada', null, ['class' => 'form-control']) !!}
                                        <small class="error">{{ $errors->first('banner_altura_max_recomendada') }}</small>
                                    </div>
                                </div>
                            </div>

                            <h4> Notícias </h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group col-md-6 {{ $errors->first('noticia_fotos_destaque')? 'has-errors' : '' }}">
                                        {!! Form::label('noticia_fotos_destaque', 'Habilitar Destaque', ['class' => 'form-label']) !!}
                                        {!! Form::select('noticia_fotos_destaque',['Sim'=>'Sim','Não'=>'Não'],null,['class' => 'form-control']) !!}
                                        <small class="error">{{ $errors->first('noticia_fotos_destaque') }}</small>
                                    </div>

                                    <div class="form-group col-md-6 {{ $errors->first('noticia_categoria')? 'has-errors' : '' }}">
                                        {!! Form::label('noticia_categoria', 'Habilitar Módulo Categoria', ['class' => 'form-label']) !!}
                                        {!! Form::select('noticia_categoria',['Sim'=>'Sim','Não'=>'Não'],null,['class' => 'form-control']) !!}
                                        <small class="error">{{ $errors->first('noticia_categoria') }}</small>
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


