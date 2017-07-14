@extends('layouts.app')

@section('content')
    <div class="page-content">
        <div class="content">

            <!-- BREADCRUMB -->
            <ul class="breadcrumb">
                <li><p>VOCÊ ESTÁ AQUI</p></li>
                <li><a class="active">Alterar Perfil</a></li>
            </ul>

            <!-- CONTENT -->
            <div class="row">
                <div class="col-md-12">
                    <div class="grid simple">
                        <div class="grid-title no-border">
                            <h4>
                                Alterar Perfil de <span class="semi-bold">{{ Auth::user()->name }}</span>
                            </h4>
                        </div>

                        <div class="grid-body no-border">
                            {!! Form::open([
                              'method' => 'PATCH',
                              'route' => 'profile.update',
                              'files' => true
                            ]) !!}

                            {{-- name --}}
                            <div class="form-group {{ $errors->first('name')? 'has-error' : '' }}">
                                {!! Form::label('name', 'Nome', ['class' => 'form-label required']) !!}
                                {!! Form::text('name', Auth::user()->name, ['class' => 'form-control']) !!}
                                <small class="error">{{ $errors->first('name') }}</small>
                            </div>

                            {{-- email --}}
                            <div class="form-group {{ $errors->first('email')? 'has-error' : '' }}">
                                {!! Form::label('email', 'E-mail', ['class' => 'form-label required']) !!}
                                {!! Form::text('email', Auth::user()->email, ['class' => 'form-control']) !!}
                                <small class="error">{{ $errors->first('email') }}</small>
                            </div>

                            {{-- profile picture --}}
                            <div class="form-group {{ $errors->first('photo')? 'has-error' : '' }}">
                                {!! Form::label('name', 'Foto de perfil', ['class' => 'form-label required']) !!}
                                <div class="clearfix"></div>

                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-new thumbnail" style="max-width:300px; max-height:auto;">
                                        @if (Auth::user()->photo)
                                            <img data-src="{{ asset('img/profile/' . Auth::user()->photo) }}" alt="">
                                        @else
                                            <img data-src="http://placehold.it/200x200/eee/aaa/?text=Imagem" alt="">
                                        @endif
                                    </div>

                                    <div class="fileinput-preview fileinput-exists thumbnail"
                                         style="max-width:600px; max-height:auto;"></div>

                                    <div>
                                <span class="btn btn-default btn-file">
                                  <span class="fileinput-new">Selecionar imagem</span>
                                  <span class="fileinput-exists">Trocar</span>
                                    {!! Form::file('photo') !!}
                                </span>
                                        <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Remover</a>
                                    </div>
                                </div>
                                <small class="error">{{ $errors->first('photo') }}</small>
                            </div>

                            {{-- coordinates --}}
                            <input type="hidden" name="width" id="width" value="">
                            <input type="hidden" name="height" id="height" value="">
                            <input type="hidden" name="x" id="x" value="">
                            <input type="hidden" name="y" id="y" value="">

                            {{-- save button --}}
                            <div class="form-actions">
                                <div class="pull-right">
                                    <button class="btn btn-success" type="submit">
                                        <i class="fa fa-check"></i>
                                        Alterar
                                    </button>
                                    <a class="btn btn-white" href="\">Cancelar</a>
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
    <script>
        $('.fileinput').on('change.bs.fileinput', function (event) {
            $('.fileinput-preview img').cropper({
                aspectRatio: 1 / 1,
                zoomable: false,
                minCropBoxWidth: 200,
                minCropBoxHeight: 200,
                crop: function (e) {
                    $('#x').val(e.x);
                    $('#y').val(e.y);
                    $('#width').val(e.width);
                    $('#height').val(e.height);
                }
            });
        });
    </script>
@endsection
