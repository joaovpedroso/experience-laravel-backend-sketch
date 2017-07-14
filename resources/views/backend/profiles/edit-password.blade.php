@extends('layouts.app')

@section('content')
<div class="page-content">
  <div class="content">

    <!-- BREADCRUMB -->
    <ul class="breadcrumb">
      <li><p>VOCÊ ESTÁ AQUI</p></li>
      <li><a class="active">Alterar Senha</a></li>
    </ul>

    <!-- CONTENT -->
    <div class="row">
      <div class="col-md-12">
        <div class="grid simple">
          <div class="grid-title no-border">
            <h4>
              Alterar Senha de <span class="semi-bold">{{ Auth::user()->name }}</span>
            </h4>
          </div>

          <div class="grid-body no-border">
            {!! Form::open([
              'method' => 'PATCH',
              'route' => 'profile.password.update',
            ]) !!}

              <div class="form-group {{ $errors->first('old_password')? 'has-error' : '' }}">
                {!! Form::label('old_password', 'Senha antiga', ['class' => 'form-label required']) !!}
                {!! Form::password('old_password', ['class' => 'form-control']) !!}
                <small class="error">{{ $errors->first('old_password') }}</small>
              </div>

              <div class="form-group {{ $errors->first('password')? 'has-error' : '' }}">
                {!! Form::label('password', 'Nova senha', ['class' => 'form-label required']) !!}
                {!! Form::password('password', ['class' => 'form-control strong-password']) !!}
                  <div id="strong-text-password" style="font-weight:bold;padding:6px 12px;">

                  </div>
                <small class="error">{{ $errors->first('password') }}</small>
              </div>

              <div class="form-group">
                {!! Form::label('password_confirmation', 'Repetir nova senha', ['class' => 'form-label required']) !!}
                {!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
              </div>

              <div class="form-actions">
                <div class="pull-right">
                  <button class="btn btn-success" type="submit">
                    <i class="fa fa-check"></i>
                    Alterar
                  </button>
                  <a class="btn btn-white" href="/">Cancelar</a>
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

    {{ Html::script('js/password-score.js') }}
    {{ Html::script('js/password-score-options.js') }}
    {{ Html::script('js/password-strength-meter.js') }}
    <script>

$(document).ready(function() {
            $('.strong-password').strengthMeter('text', {
                container: $('#strong-text-password')
            });
        });

    </script>
    @endsection
