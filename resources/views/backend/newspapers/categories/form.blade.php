@extends('layouts.app')

@section('content')
<div class="page-content">
  <div class="content">

    <!-- BREADCRUMB -->
    <ul class="breadcrumb">
      <li><p>VOCÊ ESTÁ AQUI</p></li>
      <li><a href="{{ route('newspapers.categories.index') }}">Categorias</a></li>
      @if (!isset($category))
      <li><a class="active">Cadastrar</a></li>
      @else
      <li><a class="active">Alterar: {{ $category->titulo }}</a></li>
      @endif
    </ul>

    <!-- TITLE-->
    <div class="page-title">
      <a href="{{ route('newspapers.categories.index') }}">
        <i class="icon-custom-left"></i>
      </a>
      <h3>
        @if (!isset($category)) Cadastrar novo
        @else Alterar @endif
        <span class="semi-bold">Categoria</span>
      </h3>
    </div>

      <!-- CONTENT -->
    <div class="row">
      <div class="col-md-12">
        <div class="grid simple">
          <div class="grid-title no-border">
            <h4>Informações do Categoria</h4>
          </div>

          <div class="grid-body no-border">
            {{-- FORM: NEW --}}
            @if (!isset($category))
            {!! Form::open([
              'route' => 'newspapers.categories.store',
              'enctype' => 'multipart/form-data'
            ]) !!}
            {{-- FORM:EDIT --}}
            @else
            {!! Form::model($category, [
                'method' => 'PATCH',
                'route' => ['newspapers.categories.update', $category->id],
                'enctype' => 'multipart/form-data'
            ]) !!}
            @endif


            <div class="form-group {{ $errors->first('titulo') ? 'has-error' : '' }}">
              {!! Form::label('titulo', 'Nome', ['class' => 'form-label required']) !!}
              {!! Form::text('titulo', null, ['class' => 'form-control']) !!}
              <small class="error">{{ $errors->first('titulo') }}</small>
            </div>

            <div class="row"></div>

              <div class="form-actions">
                <div class="pull-right">
                  <button class="btn btn-success" type="submit">
                    <i class="fa fa-check"></i>
                    @if (!isset($category)) Cadastrar
                    @else Alterar @endif
                  </button>
                  <a class="btn btn-danger" href="{{ route('newspapers.categories.index') }}">Cancelar</a>
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

@endsection
