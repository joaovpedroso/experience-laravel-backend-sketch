@extends('layouts.login')
@section('content')
{{-- LOGIN FORM --}}
<form action="{{ url('/login') }}" autocomplete="off" role="form" class="login-form" id="login-form" method="post" name="login-form">
  {!! csrf_field() !!}

  @if (count($errors) > 0)
    <div class="alert alert-danger">
      <ul>
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  @if (session('status'))
    <div class="alert alert-success">
      {{ session('status') }}
    </div>
  @endif

  <div class="form-group">
    <label class="form-label">E-mail</label>
    <input class="form-control" name="email" type="email" value="{{ old('email') }}" required>
  </div>

  <div class="form-group">
    <label class="form-label">Senha</label>
    <span class="help"></span>
    <input class="form-control" name="password" type="password" required>
  </div>

  <div class="row paddings">
    <div class="col-xs-6 nowrap">
      <div class="control-group">
        <a class="forgot" href="#">Esqueceu a senha?</a> &nbsp;&nbsp;
      </div>
    </div>

    <div class="col-xs-6 nowrap text-right">
      <div class="checkbox check-success">
        {{--<input name="remember" type="checkbox" id="checkbox1">--}}
        {{--<label for="checkbox1">Manter conectado</label>--}}
      </div>
    </div>
  </div>

  <div class="text-center m-t-10">
    <button class="btn btn-login" type="submit">Login</button>
  </div>
</form>


{{-- FORGOT PASSWORD --}}
<form method="post" action="{{ url('/password/email') }}" role="form" autocomplete="off" class="login-recovery" style="display:none">
  {!! csrf_field() !!}

  <div class="form-group">
    <label class="form-label">E-mail para recuperação</label>
    <input class="form-control" name="email" type="email" value="{{ old('email') }}" required>
  </div>

  <div class="control-group">
    <div class="checkbox check-success">
      <a class="forgot" href="#">&laquo; Voltar</a>
    </div>
  </div>

  <button class="btn btn-success btn-cons pull-right" type="submit">Recuperar</button>
  <div class="clearfix"></div>
</form>
@endsection

@section('js')
  <script>
      $('.forgot').click(function() {
          var recovery = $('.login-recovery');
          var form = $('.login-form');

          if (recovery.is(':visible')) {
              recovery.hide();
              form.fadeIn();
          }
          else {
              form.hide();
              recovery.fadeIn();
          }
      });
  </script>
@endsection