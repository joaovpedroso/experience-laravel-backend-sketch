@if (!isset($trash))
  <div class="row">
    <div class="col-md-12">
      <div class="grid simple">
        <div class="grid-title no-border">
          <h4>Filtros</h4>
        </div>

        <div class="grid-body no-border">
          <form action="{{ route('users.index') }}" method="get" id="filter-form">

            <div class="row">
              <div class="col-md-4 mb-10-xs">
                <input type="text" name="name" class="form-control" placeholder="Nome">
              </div>

              <div class="col-md-4 mb-10-xs">
                <input type="text" name="email" class="form-control" placeholder="E-mail">
              </div>





              <div class="col-md-4">
                <select name="status" class="form-control">
                  <option value="">Selecione o Status...</option>
                  <option value="Ativo">Ativo</option>
                  <option value="Inativo">Inativo</option>
                </select>
              </div>
            </div>


            <div class="row m-t-10">
              <div class="col-md-6">
                @if ($_GET)
                  <a href="{{ route('users.index') }}" class="btn btn-small btn-default btn-df-xs">
                    <i class="fa fa-times"></i> Limpar filtros
                  </a>
                @endif
              </div>

              <div class="col-md-6">
                <button type="submit" class="btn btn-primary btn-small pull-right btn-df-xs">
                  <i class="fa fa-search"></i> &nbsp; Filtrar
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endif
