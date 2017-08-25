@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{ asset('backend/assets/plugins/autocomplete/autocomplete.css') }}">
<link rel="stylesheet" href="{{ asset('css/dropzone/css/dropzone.css')}}">

@endsection
@section('content')

<!-- Modal de confirmação -->
<div class="modal fade modal-file" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Tem certeza?</h4>
      </div>

      <div class="modal-body">
        Tem certeza que deseja excluir este arquivo? Ele não poderá ser restaurado.
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default cancelar" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-danger confirm-delete" data-file="" data-folder="" data-id="">Excluir arquivo</button>
      </div>

    </div>
  </div>
</div>

<div class="page-content">
  <div class="content">

    <!-- BREADCRUMB -->
    <ul class="breadcrumb">
      <li><p>VOCÊ ESTÁ AQUI</p></li>
      <li><a href="{{ route('newspapers.index') }}">Blog</a></li>
      @if (!isset($newspaper))
      <li><a class="active">Cadastrar</a></li>
      @else
      <li><a class="active">Alterar: {{ $newspaper->titulo }}</a></li>
      @endif
    </ul>

    <!-- TITLE-->
    <div class="page-title">
      <a href="{{ route('newspapers.index') }}">
        <i class="icon-custom-left"></i>
      </a>
      <h3>
        @if (!isset($newspaper)) Cadastrar nova
        @else Alterar @endif
        <span class="semi-bold">Matéria</span>
      </h3>
    </div>

      <!-- CONTENT -->
    <div class="row">
      <div class="col-md-12">
        <div class="grid simple">
          <div class="grid-title no-border">
            <h4>Informações da Matéria</h4>
          </div>

          <div class="grid-body no-border">
            {{-- FORM: NEW --}}
            @if (!isset($newspaper))
            {!! Form::open([
              'route' => 'newspapers.store',
              'files' => true
            ]) !!}
            {{-- FORM:EDIT --}}
            @else
            {!! Form::model($newspaper, [
                'method' => 'PATCH',
                'route' => ['newspapers.update', $newspaper->id],
                'files' => true
            ]) !!}
            @endif

            <div class="row">

               <div class="form-group col-md-6 {{ $errors->first('data')? 'has-error' : '' }}">
                  <label for="data" class="required">Data </label>
                  <div class="form-control input-append default date no-padding">
                    {!! Form::text('data', null, ['class' => 'form-control']) !!}
                  </div>
                  <small class="error">{{ $errors->first('data') }}</small>
                </div>

                <div class="form-group col-md-6 {{ $errors->first('category_id') ? 'has-error' : '' }}">
                  {!! Form::label('category_id', 'Categorias', ['class' => 'form-label required']) !!}
                  {!! Form::select('category_id', $categories, null, ['class' => 'form-control']) !!}
                  <small class="error">{{ $errors->first('category_id') }}</small>
                </div>
            </div>

            <div class="form-group {{ $errors->first('titulo') ? 'has-error' : '' }}">
              {!! Form::label('titulo', 'Titulo', ['class' => 'form-label required']) !!}
              {!! Form::text('titulo', null, ['class' => 'form-control']) !!}
              <small class="error">{{ $errors->first('titulo') }}</small>
            </div>

            <div class="form-group {{ $errors->first('descricao')? 'has-error' : '' }}">
            {!! Form::label('descricao', 'Descrição', ['class' => 'form-label']) !!}
            {!! Form::textarea('descricao', @$newspaper->descricao, ['id' => 'editor1', 'class' => 'form-control']) !!}
            <small class="error">{{ $errors->first('descricao') }}</small>
            </div>

            <div class="form-group {{ $errors->first('fonte') ? 'has-error' : '' }}">
              {!! Form::label('fonte', 'Fonte', ['class' => 'form-label']) !!}
              {!! Form::text('fonte', null, ['class' => 'form-control']) !!}
              <small class="error">{{ $errors->first('fonte') }}</small>
            </div>

              <div class="form-group {{ $errors->first('imagem') ? 'has-error' : '' }}">
                {!! Form::label('name', 'Imagem de Capa', ['class' => 'form-label']) !!}
                <span>(Tamanho recomendado: 870 pixels de largura mínima)</span>
                <div class="clearfix"></div>

                  <div>
                    <span class="btn btn-default btn-file">
                      <span class="fileinput-new">Selecionar imagem</span>
                      {!! Form::file('imagem') !!}
                    </span>
                  </div>
                <small class="error">{{ $errors->first('imagem') }}</small>
              </div>

         <div class="form-group {{ $errors->first('legenda_imagem') ? 'has-error' : '' }}">
            {!! Form::label('legenda_imagem', 'Legenda da Imagem', ['class' => 'form-label']) !!}
            {!! Form::text('legenda_imagem', null, ['class' => 'form-control']) !!}
            <small class="error">{{ $errors->first('legenda_imagem') }}</small>
          </div>

          <div class="form-actions">
            <div class="pull-right">
              <button class="btn btn-success" type="submit">
                <i class="fa fa-check"></i>
                @if (!isset($newspaper)) Cadastrar
                @else Alterar @endif
              </button>
              <a class="btn btn-danger" href="{{ route('newspapers.index') }}">Cancelar</a>
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
<script src="{{ asset('backend/assets/plugins/ckeditor/ckeditor.js') }}"></script>
<script>
window.onload = function() {
  CKEDITOR.replace( 'editor1',{
     customConfig: '{{ asset("backend/assets/plugins/ckeditor/config.js") }}'
  });
};

$('.datepicker').datepicker();
</script>

<script src="https://rawgit.com/enyo/dropzone/master/dist/dropzone.js" type="text/javascript"></script>

@endsection
