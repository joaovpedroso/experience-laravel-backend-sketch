@extends('layouts.app')

@section('content')
<div class="page-content">
  <div class="content">

    <!-- BREADCRUMB -->
    <ul class="breadcrumb">
      <li><p>VOCÊ ESTÁ AQUI</p></li>
      <li><a href="{{ route('config.index') }}">Configurações</a></li>
      <li><a class="active">Conteúdo</a></li>
    </ul>

    <!-- CONTENT -->
    <div class="row">
      <div class="col-md-12 m-b-10">

        <div class="row config">
          <!-- MODULES FOR SUPER ADMINS -->
          @if (Auth::user()->isSuperAdmin())
            <div class="col-md-3 m-b-30">
              <a href="{{ route('config.editorials.index') }}">
                <div class="tiles white">
                  <div class="p-t-20 text-center">
                    <i class="fa fa-fw fa-newspaper-o text-module fa-4x"></i>
                  </div>
                  <div class="p-t-15 p-b-15 text-center">
                    <h4 class="text-black">Editorias</h4>
                  </div>
                </div>
              </a>
            </div>

            <div class="col-md-3 m-b-30">
              <a href="{{ route('config.guide.categories.index') }}">
                <div class="tiles white">
                  <div class="p-t-20 text-center">
                    <i class="fa fa-fw fa-list text-module fa-4x"></i>
                  </div>
                  <div class="p-t-15 p-b-15 text-center">
                    <h4 class="text-black">Categorias do Guia</h4>
                  </div>
                </div>
              </a>
            </div>

            <div class="col-md-3 m-b-30">
              <a href="{{ route('config.positions.index') }}">
                <div class="tiles white">
                  <div class="p-t-20 text-center">
                    <i class="fa fa-fw fa-briefcase text-module fa-4x"></i>
                  </div>
                  <div class="p-t-15 p-b-15 text-center">
                    <h4 class="text-black">Cargos de Trabalho</h4>
                  </div>
                </div>
              </a>
            </div>
          @endif
        </div>

      </div> <!-- /.col-md -->
    </div> <!-- ./row -->

  </div>
</div>
@endsection
