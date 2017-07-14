@extends('layouts.app')

@section('content')
    <div class="page-content">
        <div class="content">

            <!-- BREADCRUMB -->
            <ul class="breadcrumb">
                <li><p>VOCÊ ESTÁ AQUI</p></li>
                <li><a href="/">Conteúdo</a></li>
                <li><a class="active">Notícias</a></li>
            </ul>

            <!-- TITLE-->
            <div class="page-title">
                <div class="row">
                    <div class="col-md-6">
                        <a href="javascript:;" onclick="goBack()">
                            <i class="icon-custom-left"></i>
                        </a>
                        <h3>Notícias</h3>
                    </div>

                    <div class="col-md-6 p-t-15">
                        <div class="text-right text-center-xs">
                            <a href="{{ route('logs.index') . '?modules[]=App\Models\Content\New' }}"
                               class="btn btn-small btn-df-xs m-r-5" data-toggle="tooltip" data-original-title="Logs">
                                <span class="fa fa-file-text-o"></span>
                            </a>

                            <a href="{{ route('content.news.trash') }}" class="btn btn-small btn-df-xs m-r-5"
                               data-toggle="tooltip" data-original-title="Lixeira">
                                <span class="fa fa-trash"></span>
                            </a>
                            @if(\App\Configurate::first()->noticia_categoria == "Sim")
                                <a href="{{ route('editorials.index') }}"
                                   class="btn btn-info btn-df-xs btn-small no-ls">
                                    <span class="fa fa-plus"></span> Cadastrar Categoria
                                </a>
                            @endif

                            <a href="{{ route('content.news.create') }}"
                               class="btn btn-success btn-df-xs btn-small no-ls">
                                <span class="fa fa-plus"></span> Cadastrar
                            </a>
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
                                    <h4>Lista de <span class="semi-bold">Notícias</span></h4>
                                </div>

                                <div class="pull-left m-l-15">
                                    <div class="selected-options inline-block" style="visibilty:hidden">
                                        <a href="#" class="btn btn-small btn-white delete" data-toggle="tooltip"
                                           data-original-title="Excluir selecionados">
                                            <i class="fa fa-fw fa-trash"></i>
                                            {{ csrf_field() }}
                                        </a>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>

                            <div class="grid-body no-border">
                                <!-- no results -->
                                @if ( ! count($news))
                                    <p>Nenhuma notícia encontrada.</p>
                                @else

                                    @include('backend.news.listing')

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
