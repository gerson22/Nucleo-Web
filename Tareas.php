 <!DOCTYPE html>
<html lang="es_mx">
	<head>
		<title>Tareas</title>
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
<script type="text/javascript" src="/js/jquery-2.1.4.min.js"></script>
<script src="/js/bootstrap.js" type="text/javascript"></script>
<script src="/plugins/assets/js/appear.min.js" type="text/javascript"></script>
<script src="/plugins/assets/js/animations.js" type="text/javascript"></script>
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>
<script type="text/javascript">
	$(document).ready(function () {
		$.datepicker.setDefaults($.datepicker.regional["es"]);
		$("#fecha_entregaVal").datepicker({
			firstDay: 1
		});
	});
	
	var Tarea = {
        section: function(estatus){
            var data;
            data = $("<section/>");
             if(estatus)
                data.addClass("btn-success sectTarea col-md-4 col-lg-4");
            else
                data.addClass("btn-warning col-md-4 col-lg-4");

            return data;
        },
        fecha_encargo : function(fecha_encargo){
            return $("<h6/>").text(fecha_encargo)
        },
        clase : function(claseName){
            return $("<h3/>").text(claseName)
        },
        description: function(descContent){
            return $("<article/>").text(descContent)
        },
		fecha_entrega: function(fecha_entrega){
			return $("<h6/>").text(fecha_entrega)
		}
		
    }
	
	

    cargarClases();
    getTareas();

    function getTareas()
	{
		$.ajax({
			url: "/API/tareas",
			type:"GET",
			dataType: "JSON",
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Authorization", "Basic " + btoa('<?php echo $_SESSION['matricula']; ?>' +":"+ '<?php echo $_SESSION['password'] ?>'));
            }
		}).done(function(data){
			    $("#tareasContent").empty();
				var $content;
				$.each(data,function(i,o){
					$content =  Tarea.section(true);		
-					$("#tareasContent").append($content);		
-					$($content).append(Tarea.fecha_encargo(this.fecha_encargo),Tarea.clase(this.id_clase),Tarea.description(this.descripcion),Tarea.fecha_entrega(this.fecha_entrega));
				});
				console.log(data);
			}).fail(function(error,status){
				console.log(error);
				console.log(status);
			});
	}

    function cargarClases()
    {
        $.ajax({
            type: "GET",
            url: "/API/clases",
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Authorization", "Basic " + btoa('<?php echo $_SESSION['matricula']; ?>' +":"+ '<?php echo $_SESSION['password'] ?>'));
            },
            success: function (data) {
                jQuery.each(data, function () {
                    $("#id_claseVal").append("<option value='" + this.id_clase + "'>" + this.descripcion + "</option>");
                });
            }
        });
    }

    
</script>
