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

  $(document).on('click', '#aceptar', function(e) {


      var id_balanza  = $("#balanza option:selected").val();
      var fecha_ini   = $("#fecha_ini").val();
      var fecha_fin   = $("#fecha_fin").val();

      
        $.ajax({
        	type: 'POST',
        	url: 'getGraficaLecturas',
        	data: {
        		 '_token': $('input[name=_token]').val(),
        		 'balanza': id_balanza,
        		 'fecha_ini': fecha_ini,
        		 'fecha_fin': fecha_fin,
        		 //'lectura': lectura
        	},
          dataType: "json",
        	success: function(data) {

            var nombre_balanza = $("#balanza option:selected").text();

            function drawChart(dataI) {

              var datos_grafica = new Array();
              /*++++
              var dataInCabezal = new Array();
              dataInCabezal.push('Fecha');
              dataInCabezal.push('Lecturas');
              datos_grafica.push(dataInCabezal);
              ++++*/

              dataI = Object.values(dataI.data)
              for( i = 0; i < dataI.length; i++ ) {
                var dataIn = new Array();
                //+++dataIn.push(dataI[i].created_at);
                dataIn.push(new Date(dataI[i].created_at));
                dataIn.push(parseFloat(dataI[i].lectura_acumulada));
                datos_grafica.push(dataIn);
              }
              var data = new google.visualization.DataTable();
              data.addColumn('date', 'Fecha');
              data.addColumn('number', 'Lectura');
              //++++var data = google.visualization.arrayToDataTable( datos_grafica );
              data.addRows(datos_grafica);
              /*++
              var options = {
                title: 'Lecturas Acumuladas - ' + nombre_balanza + ' -',
                curveType: 'function',
                legend: { position: 'bottom' },
                width: 900,
                height: 500,
                hAxis: { format: 'decimal' }

              };
              +++*/
              var options = {
                              chart: {
                                title: 'Lectura Acumulada',
                                subtitle: ''
                              },
                              width: 900,
                              height: 500
                            };

              /*--
              var options = {
                              title: 'Company Performance',
                              hAxis: {title: 'Year',  titleTextStyle: {color: '#333'}},
                              vAxis: {minValue: 0}
                            };
              --*/
              //+++var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
              //--var chart = new google.visualization.AreaChart(document.getElementById('curve_chart'));
              var chart = new google.charts.Line(document.getElementById('curve_chart'));
              chart.draw(data, options);
            }

            //+++google.charts.load('current', {'packages':['corechart']});
            //--google.charts.load('current', {'packages':['corechart']});
            google.charts.load('current', {'packages':['line']});
            google.charts.setOnLoadCallback(function(){ drawChart(data) });



        	},
        	error: function (data) {
        		var params       = new Object();
        		params.mensajes  = new Array();
        		params.mensajes.push("En este momento no se puede procesar su solicitud");
        		params.tipo      = "error";
        		params._token    = $('input[name=_token]').val();
        		loadMensaje(params, params._token, "error_modal");
        	},
        	complete: function () {
        		//l.stop();
        	}
        });


  });
});
