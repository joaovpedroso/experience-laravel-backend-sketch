@extends('layouts.app')

@section('content')
<div class="page-content">
  <div class="content">

    <!-- BREADCRUMB -->
    <ul class="breadcrumb">
      <li><p>VOCÊ ESTÁ AQUI</p></li>
      <li><a href="{{ route('content.news.index') }}">Notícias</a></li>
      <li><a class="active">Lixeira</a></li>
    </ul>

    <!-- TITLE-->
    <div class="page-title">
      <div class="row">
        <div class="col-md-6">
          <a href="{{ route('content.news.index') }}">
            <i class="icon-custom-left"></i>
          </a>
          <h3><span class="semi-bold">Notícias</span> excluídas</h3>
        </div>

        <div class="col-md-6 p-t-15">
          <div class="text-right text-center-xs">
          </div>
        </div>
      </div>
    </div>

    <!-- FILTERS -->
    @include('backend.news.partials.filters')

    <!-- CONTENT -->
    <div class="row">
      <div class="col-md-12">
      <div class="col-md-12">
        <div class="grid simple">
          <div class="grid-title no-border">
            <div class="pull-left">
              <h4>Lista de <span class="semi-bold">Notícias</span> excluídas</h4>
            </div>

            <div class="pull-left m-l-15">
              <div class="selected-options inline-block" style="visibility:hidden">
                <a href="#" class="btn btn-small btn-white restore" data-toggle="tooltip" data-original-title="Restaurar selecionados">
                  <i class="fa fa-fw fa-history"></i>
                </a>
              </div>
            </div>
            <div class="clearfix"></div>
          </div>

          <div class="grid-body no-border">
            <!-- no results -->
            @if ( ! count($news))
              <p>Nenhuma notícia excluída.</p>
            @else

            @include('backend.news.listing', ['trash' => true])

            <!-- paginator -->
            @include('helpers.paginator', ['var' => $news])

            @endif

          </div> <!-- /.grid-body -->
        </div> <!-- /.grid -->
      </div> <!-- /.col -->
    </div> <!-- /.row -->
    </div> <!-- /.row -->

  </div>
</div>
@endsection

@section('js')
  @include('helpers.status-button')
@endsection
