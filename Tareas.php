 <!DOCTYPE html>
<html lang="es_mx">
	<head>
		<title>Asignar Tareas</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="/css/bootstrap.css">
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.11.4/themes/flick/jquery-ui.css">
	</head>
	<body>
		<?php include("includes/headerAlumno.php"); ?>
				<div id="principal" class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2" style="margin-top:50px;">
					 <div id="area_trabajo" class="col-md-12">
						<section class="col-md-6">
							 <label>Fecha de entrega</label>
							<input type="text" id="fecha_entregaVal" class="form-control"  /><br><br>
							<label class="col-md-offset-6">Clase</label>
							<select id="id_claseVal" class="form-control col-md-offset-6">
								<!-- [GET] API/clases -->
							</select><br><br>
							<label class="col-md-offset-6">Descripción</label>
							<textarea id="descripcionVal" class="form-control col-md-offset-6" readonly ></textarea><br><br>
						 </section>
					</div>
					<section class="row" id="tareasContent">
						<!--- getTareas();---->
					</section>
				</div>
				
	</body>
</html>
<script src="/js/jquery.js" type="text/javascript"></script>
<script src="/js/bootstrap.js" type="text/javascript"></script>
<script src="/plugins/assets/js/appear.min.js" type="text/javascript"></script>
<script src="/plugins/assets/js/animations.js" type="text/javascript"></script>
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>
<script type="text/javascript">
	var Tarea = {
		section: function(estatus){
			var	data;
			 if(estatus)
				data = $("<section/>").addClass("btn-success col-md-4 col-lg-4");
			else
				data = $("<section/>").addClass("btn-warning col-md-4 col-lg-4");
				
			return data;
		},
		title : function(titleTarea){
			return $("<h3/>").text(titleTarea)
		},
		clase : function(claseName){
			return $("<h5/>").text(claseName)
		},
		description: function(descContent){
			return $("<article/>").text(descContent)
		}
	}
	$(document).ready(function () {
		$.datepicker.setDefaults($.datepicker.regional["es"]);
		$("#fecha_entregaVal").datepicker({
			firstDay: 1
		});
	});
	
	getTareas();
    cargarClases();
	
	function getTareas()
	{
		$.ajax({
			url: "API/tareas",
			type:"GET",
			dataType: "JSON",
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Authorization", "Basic " + btoa('<?php echo $_SESSION['matricula']; ?>' +":"+ '<?php echo $_SESSION['password'] ?>'));
            },
			success: function(data){
                jQuery.each(data, function () {
                    $("#tareasContent").append("Fecha: " + this.fecha_encargo + ", descripcion: " + this.descripcion);
                });
			}
		});
	}
	
    function cargarClases()
    {
		var matricula = '<?php echo $usuario->matricula; ?>';
		var password = '<?php echo $usuario->password; ?>';
        $.ajax({
            type: "GET",
            url: "/API/clases",
            beforeSend: function (xhr) {
                //xhr.setRequestHeader ("Authorization", "Basic " + btoa("ADM13001:shadow"));
                xhr.setRequestHeader("Authorization", "Basic " + btoa(matricula+":"+password)); 
            },
            success: function (data) {
                jQuery.each(data, function () {
                    $("#id_claseVal").append("<option value='" + this.id_clase + "'>" + this.descripcion + "</option>");
                });
            }
        });
    }

    function asignarTarea()
    {
        var id_clase = $("#id_claseVal").val();
        var descripcion = $("#descripcionVal").val();
        var fecha_entrega = $("#fecha_entregaVal").val();

        $.ajax({
            type: "POST",
            url: "/API/tareas",
            beforeSend: function (xhr) {
                //xhr.setRequestHeader ("Authorization", "Basic " + btoa("ADM13001:shadow"));
                xhr.setRequestHeader("Authorization", "Basic " + btoa(matricula+":"+password));
            },
            data: "id_clase=" + id_clase + "&descripcion=" + descripcion + "&fecha_entrega=" + fecha_entrega,
            success: function (data) {
                jQuery.each(data, function () {
                    $("#id_claseVal").append("<option value='" + this.id_clase + "'>" + this.descripcion + "</option>");
                });
            }
        });
    }
</script>