$(document).ready( function () {
    $('#balanzas-verlecturas .date').datetimepicker({
      todayBtn:  1,
      autoclose: 1,
      todayHighlight: 1,
      startView: 2,
      forceParse: 0,
      //showMeridian: 1,
      minuteStep: 30,
      pickerPosition: 'bottom-left'
    });
});
