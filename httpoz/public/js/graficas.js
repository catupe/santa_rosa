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


      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      var id_balanza  = $("#balanza option:selected").val();
      var fecha_ini   = $("#fecha_ini").val();
      var fecha_fin   = $("#fecha_fin").val();

      function drawChart() {
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
        	success: function(data) {

            var nombre_balanza = $("#balanza option:selected").text();

            //function drawChart() {

              var datos_grafica = new Array();

              var dataInCabezal = new Array();
              dataInCabezal.push('Fecha');
              dataInCabezal.push('Lecturas');
              datos_grafica.push(dataInCabezal);

              for( i = 0; i < data.data.lenght; i++ ) {
                var dataIn = new Array();
                dataIn.push(data.data[i].created_at);
                dataIn.push(data.data[i].lectura_acumulada);
                datos_grafica.push(dataIn);
              }
              var data = google.visualization.arrayToDataTable([ JSON.stringify(datos_grafica) ]);
              /*
              var data = google.visualization.arrayToDataTable([
                ['Fecha', 'Lecturas'],
                ['2004',  1000  ],
                ['2005',  1170  ],
                ['2006',  660   ],
                ['2007',  1030  ]
              ]);
              */
              var options = {
                title: 'Lecturas Acumuladas - ' + nombre_balanza + ' -',
                curveType: 'function',
                legend: { position: 'bottom' },
                width: 900,
                height: 500,
              };

              var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

              chart.draw(data, options);
            //}

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
        		l.stop();
        	}
        });
      }

  });
});
