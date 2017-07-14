@extends('layouts.app')

@section('content')
<div class="page-content">
  <div class="content">

    <!-- BREADCRUMB -->
    <ul class="breadcrumb">
      <li><p>VOCÊ ESTÁ AQUI</p></li>
      <li><a href="/">Conteúdo</a></li>
      <li><a href="{{ route('content.news.index') }}">Notícias</a></li>
      <li><a class="active">Adicionar Fotos: {{ $news->title }}</a></li>
    </ul>

    <!-- TITLE-->
    <div class="page-title">
      <a href="{{ route('content.news.index') }}">
        <i class="icon-custom-left"></i>
      </a>
      <h3>
        Adicionar <span class="semi-bold">Fotos</span>
      </h3>
    </div>

    <!-- CONTENT -->
    <div class="row">
      <div class="col-md-12">
        <div class="grid simple">
          <div class="grid-title no-border">
            <h4>Enviar Fotos</h4>
          </div>

          <div class="grid-body no-border">
            <div class="row-fluid">
              <form action="{{ route('content.news.upload', $news->id) }}" method="post" class="dropzone no-margin dz-clickable" enctype="multipart/form-data">
                {!! csrf_field() !!}
                <div class="dz-default dz-message"><span>Arraste os arquivos aqui para enviar</span></div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- PHOTOS -->
    @if (count($news->photos))
    <div class="row">
      <div class="col-md-12">
        <div class="grid simple">
          <div class="grid-title no-border">
            <h4>
              Fotos da Notícia
              <span class="text-muted help">/ Clique para definir como foto de capa</span>
            </h4>
          </div>

          <div class="grid-body no-border">
            <div class="row" id="sortable">
              @foreach ($news->photos->sortBy('order') as $photo)
                <div class="col-md-3" id="item_{{ $photo->id }}">
                  <a href="javascript:;" class="photos js-photo-active {{ $photo->featured? 'active' : '' }}" data-id="{{ $photo->id }}">
                    <img src="{{ '/news/photos/' . $photo->file }}" alt="" class="img-responsive">
                  </a>

                  <span class="move handle">
                    <i class="fa fa-arrows"></i>
                  </span>

                  <form action="{{ route('content.news.photos.delete', $photo->id) }}" method="post" class="img-delete">
                    {!! csrf_field() !!}
                    {!! method_field('DELETE') !!}
                    <button type="submit"><i class="fa fa-times"></i></button>
                  </form>

                  <form action="{{ route('content.news.photos.caption', $photo->id) }}" method="post">
                    {!! csrf_field() !!}
                    <input type="text" name="caption" class="form-control" placeholder="Legenda" value="{{ $photo->subtitle? $photo->subtitle : old('caption') }}">
                    <div class="row m-t-5">
                      <div class="col-md-3 p-l-0">
                        <button class="btn btn-success btn-block">OK</button>
                      </div>
                    </div>
                  </form>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>
    @endif

  </div>
</div>
@endsection

@section('js')
<script>
  $(document).ready(function() {
    $('#sortable').sortable({
      handle: '.handle',
      update: function(event, ui) {
        var url = "/content/noticias/photos/order";
        var data = $(this).sortable('serialize');

        $.post(url, data, function(res) {
          if (res != 200) {
            noty({text: "Ocorreu um erro! Tente novamente.", type: 'error'});
          }
        });
      }
    });
  });
</script>
@endsection
