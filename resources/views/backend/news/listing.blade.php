<!-- table -->
<table class="table table-striped table-hover table-flip-scroll cf">
  <thead class="cf">
    <tr>
      <th width="42">
        <div class="checkbox check-default check-select">
          <input id="checkall" type="checkbox" value="1" class="checkall">
          <label for="checkall"></label>
        </div>
      </th>
      <th width="30">Data</th>
      <th>Nome</th>
      <th width="90">Destaque</th>
      <th width="72">Status</th>
      <th width="96">Opções</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($news as $new)
    <tr>
      <td class="v-align-middle">
        <div class="checkbox check-default check-select">
          <input id="{{ $new->id }}" type="checkbox" name="selected[]" value="{{ $new->id }}">
          <label for="{{ $new->id }}"></label>
        </div>
      </td>
      <td>{{ $new->created_at->format('d/m/Y H:i') }}</td>
      <td>

        @if ($new->getLog())
          <span data-toggle="popover" data-placement="top" data-html="true"
            data-content="{{ $new->getLog() }}">
            <i class="fa fa-info-circle"></i>&nbsp;
          </span>
        @endif
        {{ $new->title }}
      </td>
      <td class="text-center">
        <input type="checkbox" data-id="{{ $new->id }}" class="js-featured" @if ($new->featured) checked @endif />
      </td>
      <td class="text-center">
        <input type="checkbox" data-id="{{ $new->id }}" class="js-switch" @if ($new->status) checked @endif />
      </td>
      <td>
        <button class="btn btn-small dropdown-toggle" data-toggle="dropdown">Ações <span class="caret"></span></button>
        <ul class="dropdown-menu module-options">
          <li>
            <a href="{{ route('content.news.photos', $new->id) }}">
              <i class="fa fa-picture-o"></i> Adicionar Fotos
            </a>
          </li>

          <li>
            <a href="{{ route('content.news.edit', $new->id) }}">
              <i class="fa fa-edit"></i> Editar
            </a>
          </li>

          <li class="divider"></li>

          <li class="divider"></li>

          @if ( ! isset($trash))
            <li class="btn-delete">
              {!! Form::open([
                'method' => 'DELETE',
                'route' => ['content.news.destroy', $new->id]
              ]) !!}
                <button type="submit">
                  <i class="fa fa-trash"></i> Excluir
                </button>
              {!! Form::close() !!}
            </li>
          @else
            <li class="btn-delete">
              <a href="{{ route('content.news.restore', $new->id) }}">
                <i class="fa fa-history"></i> Restaurar
              </a>
            </li>
          @endif
        </ul>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
