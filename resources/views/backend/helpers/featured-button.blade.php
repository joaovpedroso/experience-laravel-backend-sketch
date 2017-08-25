<script>
  $(document).on('click', '.featured', function() {
    var url = window.location.pathname + "/featured";

    var id = $(this).attr('data-id');
    var featured = $(this).hasClass('active-featured');

    console.log(featured);

    if (featured == false){
      featured = 1; $(this).addClass('active-featured');
    }else{
      featured = 0; $(this).removeClass('active-featured');
    }

    $.get(url, {id:id, featured:featured}, function(code) {
      if (code != 200)
      noty({
        text: "Ocorreu um problema! Tente novamente mais tarde.",
        type: 'error'
      });
    });
  });
</script>
