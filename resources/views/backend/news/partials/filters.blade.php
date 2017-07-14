<div class="row">
  <div class="col-md-12">
    <div class="grid simple">
      <div class="grid-title no-border">
        <h4>Filtros</h4>
      </div>

      <div class="grid-body no-border">
        <form action="{{ route('content.news.index') }}" method="get" id="filter-form">
          <div class="row">
            <div class="col-md-6">
              <input type="text" name="keyword" class="form-control" placeholder="Título">
            </div>
            {{-- date begin --}}
            <div class="col-md-3 col-xs-6">
              <div class="input-append default date no-padding col-md-12">
                <input type="text" name="start_date" class="form-control" placeholder="Data Início">
                <span class="add-on add-on-sm">
                  <span class="arrow"></span>
                  <i class="fa fa-th"></i>
                </span>
              </div>
            </div>

            {{-- date end --}}
            <div class="col-md-3 col-xs-6">
              <div class="input-append default date no-padding col-md-12">
                <input type="text" name="end_date" class="form-control" placeholder="Data Fim">
                <span class="add-on add-on-sm">
                  <span class="arrow"></span>
                  <i class="fa fa-th"></i>
                </span>
              </div>
            </div>



          <div class="row">
            <div class="col-md-12 m-t-10">
              <!-- remove filters button -->
              @if ($_GET)
                <a href="{{ route('content.news.index') }}" class="btn btn-mini btn-default">
                  <i class="fa fa-times"></i> Limpar filtros
                </a>
              @endif

              <!-- submit filtering -->
              <button type="submit" class="btn btn-primary btn-small btn-cons pull-right">Filtrar</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
