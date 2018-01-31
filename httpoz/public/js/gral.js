// urls
// .... 
// fin urls
var loadMensaje = function( params, token, id_contenedor_mensaje ) {
  $.ajax({
    type: 'POST',
    url: 'load_error',
    data:{
          'data': JSON.stringify(params),
          '_token': token/*$('input[name=_token]').val()*/
        },
    dataType : 'json',
    success: function( salida ) {
      //$("#error_modal").html(salida.data);
      $("#" + id_contenedor_mensaje).html(salida.data);
    },
    error: function ( salida, status, xhttpr ) {
      console.log('Error:', salida);
    },
  });
};
