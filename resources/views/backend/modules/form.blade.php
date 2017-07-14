@extends('layouts.app')

@section('content')
<div class="page-content">
  <div class="content">

    <!-- BREADCRUMB -->
    <ul class="breadcrumb">
      <li><p>VOCÊ ESTÁ AQUI</p></li>
      <li><a href="{{ route('config.index') }}">Configurações</a></li>
      <li><a href="{{ route('config.index') }}">Módulos</a></li>
      @if (!isset($module))
      <li><a class="active">Cadastrar</a></li>
      @else
      <li><a class="active">Alterar: {{ $module->label }}</a></li>
      @endif
    </ul>

    <!-- TITLE-->
    <div class="page-title">
      <a href="{{ route('modules.index') }}">
        <i class="icon-custom-left"></i>
      </a>
      <h3>
        @if (!isset($module)) Cadastrar novo
        @else Alterar @endif
        <span class="semi-bold">Módulo</span>
      </h3>
    </div>

    <!-- CONTENT -->
    <div class="row">
      <div class="col-md-12">
        <div class="grid simple">
          <div class="grid-title no-border">
            <h4>Informações do Módulo</h4>
          </div>

          <div class="grid-body no-border">
            {{-- FORM: NEW --}}
            @if (!isset($module))
            {!! Form::open([
              'route' => 'modules.store',
            ]) !!}
            {{-- FORM:EDIT --}}
            @else
            {!! Form::model($module, [
                'method' => 'PATCH',
                'route' => ['modules.update', $module->id]
            ]) !!}
            @endif

              <div class="form-group {{ $errors->first('label')? 'has-error' : '' }}">
                {!! Form::label('name', 'Nome do Módulo', ['class' => 'form-label required']) !!}
                {!! Form::text('name', null, ['class' => 'form-control']) !!}
                <small class="error">{{ $errors->first('label') }}</small>
              </div>

              <div class="form-group {{ $errors->first('url')? 'has-error' : '' }}">
                {!! Form::label('url', 'URL da Rota', ['class' => 'form-label required']) !!}
                <span class="help">(ex.: <strong>/modules</strong>)</span>
                {!! Form::text('url', null, ['class' => 'form-control']) !!}
                <small class="error">{{ $errors->first('url') }}</small>
              </div>

              <div class="form-group {{ $errors->first('icon')? 'has-error' : '' }}">
                {!! Form::label('icon', 'Ícone', ['class' => 'form-label required']) !!}
                <span class="help">(ver no <a href="https://fortawesome.github.io/Font-Awesome/icons/" target="_blank">FontAwesome</a>, ex.: <strong>home</strong>)</span>
                {!! Form::text('icon', null, ['class' => 'form-control']) !!}
                <small class="error">{{ $errors->first('icon') }}</small>
              </div>


              <div class="form-actions">
                <div class="pull-right">
                  <button class="btn btn-success" type="submit">
                    <i class="fa fa-check"></i>
                    @if (!isset($module)) Cadastrar
                    @else Alterar @endif
                  </button>
                  <a class="btn btn-white" href="{{ route('modules.index') }}">Cancelar</a>
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
