<script>
  $.ajaxSetup({headers: null});

  var options = {
    cepInput: ".js-zipcode",
    streetInput: ".js-address",
  }

  var cep = new Cep("form", options);
</script>
