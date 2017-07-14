<script>
  $(document).on('change', '.js-switch', function() {
    var url = window.location.pathname + "/status";
    var id = $(this).attr('data-id');
    var status = $(this).prop('checked');
    console.log(url);
    if (status == true) status = 1;
    else status = 0;

    $.get(url, {id:id, status:status}, function(code) {
      if (code != 200)
      noty({
        text: "Ocorreu um problema! Tente novamente mais tarde.",
        type: 'error'
      });
    });
  });
</script>
