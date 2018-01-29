$(document).ready( function () {
    $('#balanzas-verlecturas .date_input').datetimepicker({
    //$('#datetimepicker1').datetimepicker({
    //$('#balanzas-verlecturas .date_input').datepicker({
      todayBtn:  1,
      autoclose: 1,
      todayHighlight: 1,
      startView: 2,
      forceParse: 0,
      //showMeridian: 1,
      minuteStep: 30,
      pickerPosition: 'bottom-left',
      icons: {
        previous: 'fa fa-chevron-circle-left',
        next: 'fa fa-chevron-circle-right',
      }
    });

    $('#agregarLectura #fecha-nueva').datetimepicker({
    //$('#datetimepicker1').datetimepicker({
      todayBtn:  1,
      autoclose: 1,
      todayHighlight: 1,
      startView: 2,
      forceParse: 0,
      //showMeridian: 1,
      minuteStep: 30,
      pickerPosition: 'bottom-left'
    });
    $("#agregarLectura #fecha-nueva").datetimepicker().on('show.bs.modal', function(event) {
      event.stopPropagation();
    });

    $('#agregarLectura').on('show.bs.modal', function (event) {
      //event.preventDefault();
      // limpio todos los campos
      $('#agregarLectura select').prop('disabled', false);
      $('#agregarLectura input[type=text]').val("");
      $('#agregarLectura input[type=text]').prop('disabled', false);

      // limpio mensajes de error
      $('#agregarLectura select').removeClass("is-invalid");
      $('#agregarLectura input[type=text]').removeClass("is-invalid");
      $('#agregarLectura .invalid-feedback').html("");

      var button = $(event.relatedTarget);
      var modo = button.data('modo');
      $('#agregarLectura #modo-nueva').val(modo);
      var id = button.data('id');
      $('#agregarLectura #id-nueva').val(id);
      var modal = $(this);
      modal.find('.modal-title').text('Agregar Lectura');

      if( modo == 1 ) {
        $('#agregarLectura select').val("");
      }
      if( modo == 2 ) {
        modal.find('.modal-title').text('Editar Lectura');
        $('#agregarLectura #balanza-nueva').prop('disabled', true);
        $('#agregarLectura #fecha-nueva').prop('disabled', true);
        $('#agregarLectura #fecha-nueva').val( button.data('fecha'));
        $('#agregarLectura #lectura-nueva').val( button.data('lectura'));
        $('#agregarLectura #lectura-nueva').prop('disabled', true);
        $('#agregarLectura #lectura-acumulada-nueva').val( button.data('lectura-acumulada'));
        $('#agregarLectura #lectura-acumulada-nueva').prop('disabled', true);
        $('#agregarLectura #comentarios-nueva').val( button.data('comentarios'));
      }

      //modal.find('.modal-body input').val(recipient);
    });
    $('#agregarLectura #guardar').click( function (e) {
      e.preventDefault();
      var l = Ladda.create(this);
	 	  l.start();

      $('#agregarLectura select').removeClass("is-invalid");
      $('#agregarLectura input[type=text]').removeClass("is-invalid");
      $('#agregarLectura .invalid-feedback').html("");

      var modo              = $('#agregarLectura #modo-nueva').val();
      var id                = $('#agregarLectura #id-nueva').val();
      var balanza           = $('#agregarLectura #balanza-nueva').val();
      var lectura           = $('#agregarLectura #lectura-nueva').val().replace(',', '.');
      var lectura_acumulada = $('#agregarLectura #lectura-acumulada-nueva').val().replace(',', '.');
      var fecha             = $('#agregarLectura #fecha-nueva').val();
      var comentarios       = $('#agregarLectura #comentarios-nueva').val();

      var error = 0;
      if( modo == 1 ) {
      	if( balanza == "" ) {
          $('#agregarLectura #balanza-nueva').addClass('is-invalid');
          $('#agregarLectura #invalid-balanza-nueva').html('La balanza es un campo requerido');
          error = 1;
      	}
        if( lectura == "" || !$.isNumeric(lectura) ) {
          $('#agregarLectura #lectura-nueva').addClass('is-invalid');
          $('#agregarLectura #invalid-lectura-nueva').html('La lectura es un campo requerido, debe ser num&eacute;rico');
          error = 1;
        }
        if( lectura_acumulada == "" || !$.isNumeric(lectura_acumulada) ) {
          $('#agregarLectura #lectura-acumulada-nueva').addClass('is-invalid');
          $('#agregarLectura #invalid-lectura-acumulada-nueva').html('La lectura es un campo requerido, debe ser num&eacute;rico');
          error = 1;
        }
        var fechaExpReg = /^\d{4}\-\d{2}\-\d{2} \d{2}:\d{2}$/;
        if( fecha == "" || !fechaExpReg.test(fecha) ) {
          $('#agregarLectura #fecha-nueva').addClass('is-invalid');
          $('#agregarLectura #invalid-fecha-nueva').html('La fecha es un campo requerido, formato yyyy-mm-dd hh:mm');
          $('#agregarLectura #invalid-fecha-nueva').show();//attr('display', 'inherit!important');
          error = 1;
        }
      }
      if( error == 0 ) {
        $.ajax({
                 type: 'POST',
                 url: 'editar_lectura',
                 data: {
                     '_token': $('input[name=_token]').val(),
                     'modo': modo,
                     'id': id,
                     'balanza': balanza,
                     'lectura': lectura,
                     'fecha': fecha,
                     'comentarios': comentarios
                 },
                 success: function(data) {
                   if(data.error == 1){
                     /*
                     var msjes = "";
                     $.each(data.mensaje, function( index, value ) {
                       msjes += value + "<br>";
                     })
                     */
                     //var params = new Object();
                     //params.mensajes = data.mensaje;
                     //params.tipo = "error";
                     params = '{ "name": "test", "description": "test", "startdate": "2016-02-21T13:00:00.000Z", "enddate": "2016-02-23T13:00:00.000Z" }';
                     $("#error_modal").load('load_error', JSON.stringify(params));

                   }

                    /*
                     $('.errorTitle').addClass('hidden');
                     $('.errorContent').addClass('hidden');

                     if ((data.errors)) {
                         setTimeout(function () {
                             $('#addModal').modal('show');
                             toastr.error('Validation error!', 'Error Alert', {timeOut: 5000});
                         }, 500);

                         if (data.errors.title) {
                             $('.errorTitle').removeClass('hidden');
                             $('.errorTitle').text(data.errors.title);
                         }
                         if (data.errors.content) {
                             $('.errorContent').removeClass('hidden');
                             $('.errorContent').text(data.errors.content);
                         }
                     } else {
                         toastr.success('Successfully added Post!', 'Success Alert', {timeOut: 5000});
                         $('#postTable').append("<tr class='item" + data.id + "'><td>" + data.id + "</td><td>" + data.title + "</td><td>" + data.content + "</td><td class='text-center'><input type='checkbox' class='new_published' data-id='" + data.id + " '></td><td>Right now</td><td><button class='show-modal btn btn-success' data-id='" + data.id + "' data-title='" + data.title + "' data-content='" + data.content + "'><span class='glyphicon glyphicon-eye-open'></span> Show</button> <button class='edit-modal btn btn-info' data-id='" + data.id + "' data-title='" + data.title + "' data-content='" + data.content + "'><span class='glyphicon glyphicon-edit'></span> Edit</button> <button class='delete-modal btn btn-danger' data-id='" + data.id + "' data-title='" + data.title + "' data-content='" + data.content + "'><span class='glyphicon glyphicon-trash'></span> Delete</button></td></tr>");
                         $('.new_published').iCheck({
                             checkboxClass: 'icheckbox_square-yellow',
                             radioClass: 'iradio_square-yellow',
                             increaseArea: '20%'
                         });
                         $('.new_published').on('ifToggled', function(event){
                             $(this).closest('tr').toggleClass('warning');
                         });
                         $('.new_published').on('ifChanged', function(event){
                             id = $(this).data('id');
                             $.ajax({
                                 type: 'POST',
                                 url: "{{ URL::route('changeStatus') }}",
                                 data: {
                                     '_token': $('input[name=_token]').val(),
                                     'id': id
                                 },
                                 success: function(data) {
                                     // empty
                                 },
                             });
                         });
                     }
                     */
                 },
                 error: function (data) {
                  console.log('Error:', data);
                 },
                 complete: function () {
                   l.stop();
                 }
             });
         }
         else{
           l.stop();
         }
    });
});
