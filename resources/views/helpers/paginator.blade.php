<div class="pages">
  <div class="pull-left results">
    <strong>{{ $var->total() }}</strong> registro(s)
  </div>

  <div class="pull-right">
    {!! $var->appends(Request::except('page'))->links() !!}
  </div>
</div>
