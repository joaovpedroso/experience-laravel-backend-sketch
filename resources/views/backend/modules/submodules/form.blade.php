@extends('layouts.app')

@section('content')
<div class="page-content">
  <div class="content">

    <!-- BREADCRUMB -->
    <ul class="breadcrumb">
      <li><p>VOCÊ ESTÁ AQUI</p></li>
      <li><a href="{{ route('config.index') }}">Configurações</a></li>
      <li><a href="{{ route('config.index') }}">Módulos</a></li>
      <li><a href="{{ route('modules.submodules.index', $module->id) }}">Sub-Módulos de {{ $module->name }}</a></li>
      @if (!isset($submodule))
      <li><a class="active">Cadastrar</a></li>
      @else
      <li><a class="active">Alterar: {{ $submodule->name }}</a></li>
      @endif
    </ul>

    <!-- TITLE-->
    <div class="page-title">
      <a href="{{ route('modules.submodules.index', $module->id) }}">
        <i class="icon-custom-left"></i>
      </a>
      <h3>
        @if (!isset($submodule)) Cadastrar novo
        @else Alterar @endif
        <span class="semi-bold">Sub-Módulo</span>
      </h3>
    </div>

    <!-- CONTENT -->
    <div class="row">
      <div class="col-md-8">
        <div class="grid simple">
          <div class="grid-title no-border">
            <h4>Informações do Sub-Módulo</h4>
          </div>

          <div class="grid-body no-border">
            {{-- FORM: NEW --}}
            @if (!isset($submodule))
            {!! Form::open([
              'route' => ['modules.submodules.store', $module->id],
            ]) !!}
            {{-- FORM: EDIT --}}
            @else
            {!! Form::model($submodule, [
                'method' => 'PATCH',
                'route' => ['modules.submodules.update', $module->id, $submodule->id]
            ]) !!}
            @endif

              <div class="form-group {{ $errors->first('name')? 'has-error' : '' }}">
                {!! Form::label('name', 'Nome do Sub-Módulo', ['class' => 'form-label required']) !!}
                {!! Form::text('name', null, ['class' => 'form-control']) !!}
                <small class="error">{{ $errors->first('name') }}</small>
              </div>

              <div class="form-group {{ $errors->first('url')? 'has-error' : '' }}">
                {!! Form::label('url', 'URL da Rota', ['class' => 'form-label required']) !!}
                <span class="help">(ex.: <strong>/submodules</strong>)</span>
                <div class="input-group transparent">
                  <span class="input-group-addon">{{ $module->url }}</span>
                  {!! Form::text('url', null, ['class' => 'form-control']) !!}
                </div>
                <small class="error">{{ $errors->first('url') }}</small>
              </div>

              <div class="form-actions">
                <div class="pull-right">
                  <button class="btn btn-success" type="submit">
                    <i class="fa fa-check"></i>
                    @if (!isset($submodule)) Cadastrar
                    @else Alterar @endif
                  </button>
                  <a class="btn btn-white" href="{{ route('modules.submodules.index', $module->id) }}">Cancelar</a>
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
