<!-- INFOS -->

<div class="form-group {{ $errors->first('name')? 'has-errors' : '' }}">
    {!! Form::label('name', 'Nome do Usuário', ['class' => 'form-label required']) !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
    <small class="error">{{ $errors->first('name') }}</small>
</div>

<div class="form-group {{ $errors->first('email')? 'has-errors' : '' }}">
    {!! Form::label('email', 'E-mail', ['class' => 'form-label required']) !!}
    {!! Form::email('email', null, ['class' => 'form-control']) !!}
    <small class="error">{{ $errors->first('email') }}</small>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->first('password')? 'has-errors' : '' }}">
            {!! Form::label('password', 'Senha de Acesso', ['class' => 'form-label required']) !!}
            {!! Form::password('password', ['class' => 'form-control']) !!}
            <small class="error">{{ $errors->first('password') }}</small>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('password_confirmation', 'Repetir Senha de Acesso', ['class' => 'form-label required']) !!}
            {!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('status', 'Situação', ['class' => 'form-label required']) !!}
            {!! Form::select('status',['Ativo'=>'Ativo','Inativo'=>'Inativo'],null,['class' => 'form-control']) !!}
        </div>
    </div>
</div>

<div class="form-group">
    <label class="focustomrm-label" for="user_avatar">Foto de Perfil</label>
    <span class="help">Recomendável: 300 x 300 pixels</span>
    <div class="clearfix"></div>
    <div class="fileinput fileinput-new" data-provides="fileinput">
        <div class="fileinput-new thumbnail" style="width:128px; height:128px;">
            @if (isset($user->photo))
                <img src="/img/profile/{{$user->photo}}" alt="avatar">
            @else
                {{-- Foto de perfil --}}
            @endif
        </div>
        <div class="fileinput-preview fileinput-exists thumbnail"
             style="width: 128px; height: 128px; line-height: 128px;"></div>
        <div>
      <span class="btn btn-default btn-file">
        <span class="fileinput-new">Selecionar imagem</span>
        <span class="fileinput-exists">Trocar</span>
        <input type="hidden"><input type="file" name="user_avatar" id="user_avatar">
      </span>
            <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Remover</a>
        </div>
    </div>
    <small class="error"></small>
</div>


<div class="form-actions m-b-5">
    <div class="pull-right">
        <button class="btn btn-success" type="submit">
            <i class="fa fa-check"></i>
            {{ $submitButtonText }}
        </button>
        <a class="btn btn-white" href="{{ route('users.index') }}">Cancelar</a>
    </div>
</div>


@section('js')
    <script>
        function enableColumn() {
            if ($('.__idColumn').is(':checked') == true) {
                $('.__nameColumn').show();
            } else {
                $('.__nameColumn').hide();
            }
        }

        $('.__idColumn').click(function () {
            enableColumn();
        });

        $(document).ready(function () {
            enableColumn();

        });
    </script>
@endsection
