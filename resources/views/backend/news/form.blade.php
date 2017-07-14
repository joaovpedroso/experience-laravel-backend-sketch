<div class="row">
  <div class="col-md-6">
    {{-- publish time --}}
    <div class="form-group">
      {!! Form::label('date', 'Data de Publicação', ['class' => 'form-label required']) !!}
      <span class="help">(dd/mm/yyyy hh:ii)</span>
      {!! Form::text('date', isset($news)? null : date('d/m/Y H:i'), ['class' => 'form-control datetime-mask']) !!}
    </div>

    {{-- title --}}
    <div class="form-group {{ $errors->first('title')? 'has-error' : '' }}">
      {!! Form::label('title', 'Título', ['class' => 'form-label required']) !!}
      {!! Form::text('title', null, ['class' => 'form-control', 'maxlength' => 70]) !!}
      <small class="error">{{ $errors->first('title') }}</small>
    </div>

    @if(\App\Configurate::first()->noticia_categoria == "Sim")
    <div class="form-group {{ $errors->first('editorial_id')? 'has-error' : '' }}">
      {!! Form::label('editorial_id', 'Editoria', ['class' => 'form-label required']) !!}
      {!! Form::select('editorial_id', $editorials, null, ['class' => 'form-control', 'placeholder' => 'Selecione uma Editoria...']) !!}
      <small class="error">{{ $errors->first('editorial_id') }}</small>
    </div>
    @endif

    {{-- source --}}
    <div class="form-group {{ $errors->first('source')? 'has-error' : '' }}">
      {!! Form::label('source', 'Fonte', ['class' => 'form-label']) !!}
      <span class="help">(Link ou Nome)</span>

      {!! Form::text('source', null, ['class' => 'form-control js-source']) !!}
      <small class="error">{{ $errors->first('source') }}</small>
    </div>

    {{-- video --}}
    <div class="form-group {{ $errors->first('video')? 'has-error' : '' }}">
      {!! Form::label('video', 'Vídeo', ['class' => 'form-label']) !!}
      <span class="help">(Youtube, Facebook ou Vimeo)</span>
      {!! Form::text('video', null, ['class' => 'form-control js-video']) !!}
      <small class="error">{{ $errors->first('video') }}</small>
    </div>

    <div class="js-video-output"></div>

    {{-- audio --}}
    <div class="form-group {{ $errors->first('audio')? 'has-error' : '' }}">
      {!! Form::label('audio', 'Áudio', ['class' => 'form-label']) !!}
      <span class="help">(Arquivo .MP3 ou .WMA)</span>
      <div class="fileinput fileinput-new block" data-provides="fileinput">
        <span class="btn btn-default btn-file">
          <span class="fileinput-new">Selecionar arquivo de áudio</span>
          <span class="fileinput-exists">Trocar</span>
          {!! Form::file('audio') !!}
        </span>
        <span class="fileinput-filename"></span>
        <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
      </div>
      <small class="error">{{ $errors->first('audio') }}</small>
    </div>

  </div>

  <div class="col-md-6">
    {{-- text --}}
    <div class="form-group {{ $errors->first('text')? 'has-error' : '' }}">
      {!! Form::label('text', 'Texto', ['class' => 'form-label required']) !!}
      {!! Form::textarea('text', null, ['class' => 'form-control', 'id' => 'ckeditor']) !!}
      <small class="error">{{ $errors->first('text') }}</small>
    </div>
  </div>
</div>

{{-- buttons --}}
<div class="form-actions">
  <div class="pull-right">
    <button class="btn btn-success" type="submit">
      <i class="fa fa-check"></i>
      {{ $submitButtonText }}
    </button>
    <a class="btn btn-white" href="{{ route('content.news.index') }}">Cancelar</a>
  </div>
</div>

@section('js')
<script src="{{ asset('assets/plugins/ckeditor/ckeditor.js') }}"></script>

<script>
window.onload = function() {
  CKEDITOR.replace( 'ckeditor',{
     customConfig: '{{ asset('assets/plugins/ckeditor/config.js') }}',
     height: 730
  });
};
</script>
@endsection
