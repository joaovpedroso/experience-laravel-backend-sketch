<div class="row">
  <div class="col-md-12">
    {{-- name --}}
    <div class="form-group {{ $errors->first('name')? 'has-error' : '' }}">
      {!! Form::label('name', 'Nome da Categoria', ['class' => 'form-label required']) !!}
      {!! Form::text('name', null, ['class' => 'form-control']) !!}
      <small class="error">{{ $errors->first('name') }}</small>
    </div>
  </div>
</div>

<div class="form-actions">
  <div class="pull-right">
    <button class="btn btn-success" type="submit">
      <i class="fa fa-check"></i>
      {{ $submitButtonText }}
    </button>
    <a class="btn btn-white" href="{{ route('editorials.index') }}">Cancelar</a>
  </div>
</div>
