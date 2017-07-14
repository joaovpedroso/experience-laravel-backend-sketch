<h4>Atividades recentes</h4>

  <hr>

  @if (count(Auth::user()->logs) == 0)
    <p>Nenhuma atividade.</p>
  @endif

  @foreach (Auth::user()->logs->take(6) as $log)
    <div class="row">
      <div class="post col-md-12">
        <div class="info-wrapper">
          <div class="username">
            <span class="dark-text capitalize">{{ $log->created_at->diffForHumans() }}</span> no módulo <span class="dark-text">{{ $log->module->label }}</span>:
          </div>

          <div class="info">
            {{ trans('logs.' . $log->action, ['title' => $log->data_title]) }}.
          </div>

          <div class="more-details">
            <ul class="post-links">
              <li><span class="text-info">{{ $log->created_at->format('d/m/Y \à\s H:i') }}</span></li>
              <li><span class="muted">IP: {{ $log->ip }}</span></li>
            </ul>
          </div>
          <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
      </div>
    </div>

    <hr>
  @endforeach
