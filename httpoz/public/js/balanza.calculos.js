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
  
  $(document).on('click', '.btn-add', function(e) {
      e.preventDefault();

      var controlForm = $('.controls #subproductos_form:first'),
          currentEntry = $(this).parents('.entry:first'),
          newEntry = $(currentEntry.clone()).appendTo(controlForm);

      newEntry.find('input').val('');
      controlForm.find('.entry:not(:last) .btn-add')
                  .removeClass('btn-add').addClass('btn-remove')
                  .html('<div class="input-group-text btn btn-outline-danger"><i class="fas fa-minus"></i></div>');
      controlForm.append('<br>');

      /*
      var borrar = new Array();
      $( ".id_sp" ).each(function( index ) {
        if(index == $( ".id_sp" ).length - 1 ){
          if(borrar.length>0){
            for(i=0; i<borrar.length; i++){
              $(this).find('[value="'+ borrar[i] +'"]').remove();
            };
          }
          return false;
        }
        borrar.push($( this ).val());
        console.log( index + ": " + $( this ).val() );

      });
      */
  }).on('click', '.btn-remove', function(e) {
    $('.entry:first + br').remove();
    $(this).parents('.entry:first').remove();
    e.preventDefault();
    return false;
  });
});
