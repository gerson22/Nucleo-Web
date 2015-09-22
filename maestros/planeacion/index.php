<?php
include("../../includes/validar_maestro.php");
include_once("../../includes/clases/class_lib.php");
$maestro = new Maestro($_SESSION['id_persona']);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Sistema Integral Meze - Planeación</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<script src="../../js/jquery.js" type="text/javascript"></script>
		<script src="../../js/bootstrap.js" type="text/javascript"></script>
		<script src="../../plugins/assets/js/appear.min.js" type="text/javascript"></script>
		<script src="../../plugins/assets/js/animations.js" type="text/javascript"></script>
        <link rel="stylesheet" href="../../estilo/general.css" />
        <link rel="stylesheet" href="../../estilo/planeacion.css" />
        <link rel="stylesheet" href="../../estilo/fixed_form.css" />
        <link rel="stylesheet" href="../../estilo/jquery.dataTables.css" />
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
        <script src="/librerias/jquery.dataTables.min.js" ></script>
        <script>
            var id_grado;
            var id_materia;
            var id_tema;
            var JSON_tema; /** Objeto del tema seleccionado */
            var planeacion = new Array([],[],[],[],[]);

            $(document).ready(function ()
            {
                cargarMaterias();
                declareDraggable();
                declareSortable();
            });

            function declareDraggable()
            {
                $("#div_nuevo_tema").draggable({ handle: ".fixed_form_handle", containment: "document" });
                $("#div_nueva_estrategia").draggable({ handle: ".fixed_form_handle", containment: "document" });
                $("#div_nuevo_metodo_ev").draggable({ handle: ".fixed_form_handle", containment: "document" });
            }

            function declareSortable()
            {
                $("#temas_disponibles, #temas_bloque1, #temas_bloque2, #temas_bloque3, #temas_bloque4, #temas_bloque5").sortable({
                    connectWith: ".lista_temas",
                    cancel: ".stuck",
                    distance: 30,
                    placeholder: "placeholder",
                    helper: 'clone',
                    receive: function( event, ui ) { temaAsignado(event, ui); }
                }).disableSelection();
            }

            function temaAsignado(event, ui)
            {
                var id_tema = ui.item.attr('id');
                var tema = ui.item.html();
                var bloque_origen;
                switch(ui.sender.attr('id'))
                {
                    case 'temas_bloque1': bloque_origen = 1; break;
                    case 'temas_bloque2': bloque_origen = 2; break;
                    case 'temas_bloque3': bloque_origen = 3; break;
                    case 'temas_bloque4': bloque_origen = 4; break;
                    case 'temas_bloque5': bloque_origen = 5; break;
                    default: bloque_origen = 0; break;
                }

                var bloque_destino;
                switch(ui.item.parent().attr('id'))
                {
                    case 'temas_bloque1': bloque_destino = 1; break;
                    case 'temas_bloque2': bloque_destino = 2; break;
                    case 'temas_bloque3': bloque_destino = 3; break;
                    case 'temas_bloque4': bloque_destino = 4; break;
                    case 'temas_bloque5': bloque_destino = 5; break;
                    default: bloque_destino = 0; break;
                }

                console.log("Pasando tema " + tema + " del bloque " + bloque_origen + " al " + bloque_destino);
                var temaJSON;
                if(bloque_origen > 0)
                {
                    temaJSON = removerTema(bloque_origen, id_tema);
                }
                else
                {
                    temaJSON = getJSONTema(id_tema);
                }
                if(bloque_destino > 0) asignarTema(bloque_destino, temaJSON );
            }

            function removerTema(bloque_origen, id_tema)
            {
                var index_tema;
                var bloque = planeacion[bloque_origen - 1];
                for( var j in bloque )
                {
                    var tema = bloque[j];
                    if(tema.id_tema == id_tema)
                    {
                        index_tema = j;
                        bloque.splice(index_tema, 1);
                        console.log("Tema eliminado del bloque " + bloque_origen);
                        return tema;
                    }
                }
            }

            function asignarTema(bloque_destino, tema)
            {
                var bloque = planeacion[bloque_destino - 1];
                bloque.push(tema);

                console.log("Tema asignado al bloque " + bloque_destino);
            }

            function cargarMaterias()
            {
                var id_grado = $("#gradoVal").val();
                $.post("../../includes/acciones/materias/get_materias_grado.php", { id_grado: id_grado }, function (data)
                {
                    $("#materiaVal").html(data);
                });
            }

            function cargarTemas()
            {
                id_grado = $("#gradoVal").val();
                id_materia = $("#materiaVal").val();

                $.ajax({
                    type: "POST",
                    url: "../../includes/acciones/planeacion/temas/print_lista.php",
                    data: "id_grado=" + id_grado + "&id_materia=" + id_materia,
                    success: function (data)
                    {
                        $("#temas_disponibles").html(data);
                    }
                });
            }

            function agregarTema()
            {
                $(".fixed_form_button").attr('disabled', 'disabled');

                var tema = $("#nuevoTemaVal").val();

                if(tema.length == 0)
                {
                    alert("Debe de asignarle un nombre al tema.");
                }
                else
                {
                    if(confirm("¿Seguro que desea agregar el tema " + tema + "?"))
                    {
                        var id_grado    = $("#gradoVal").val();
                        var id_materia  = $("#materiaVal").val();

                        $.ajax({
                            type: "POST",
                            url: "/includes/acciones/planeacion/temas/insert.php",
                            data: "tema=" + tema + "&id_grado=" + id_grado + "&id_materia=" + id_materia,
                            success: function (data)
                            {
                                $("#div_nuevo_tema").fadeOut();
                                $("#temas_disponibles").append(data);
                            }
                        });
                    }
                }

                $(".fixed_form_button").removeAttr('disabled');
                $("#nuevoTemaVal").val("");
            }

            function agregarEstrategia()
            {
                if(id_tema == 0){ alert("Debe de seleccionar un tema antes de agregar estrategias."); return false; }

                $(".fixed_form_button").attr('disabled', 'disabled');

                var estrategia = $("#nuevaEstrategiaVal").val();

                if(estrategia.length == 0)
                {
                    alert("Debe de asignarle un nombre a la estrategia.");
                }
                else
                {
                    if(confirm("¿Seguro que desea agregar la estrategia " + estrategia + "?"))
                    {

                        $.ajax({
                            type: "POST",
                            url: "/includes/acciones/planeacion/estrategias/insert.php",
                            data: "estrategia=" + estrategia + "&id_tema=" + id_tema,
                            success: function (data)
                            {
                                $("#div_nueva_estrategia").fadeOut();
                                if(data == 1) cargarEstrategias(id_tema);
                            }
                        });
                    }
                }

                $(".fixed_form_button").removeAttr('disabled');
                $("#nuevoTemaVal").val("");
            }

            function agregarMetodo()
            {
                if(id_tema == 0){ alert("Debe de seleccionar un tema antes de agregar métodos de evaluación."); return false; }

                $(".fixed_form_button").attr('disabled', 'disabled');

                var metodo = $("#nuevoMetodoVal").val();

                if(metodo.length == 0)
                {
                    alert("Debe de asignarle un nombre al método.");
                }
                else
                {
                    if(confirm("¿Seguro que desea agregar el método " + metodo + "?"))
                    {

                        $.ajax({
                            type: "POST",
                            url: "/includes/acciones/planeacion/metodos/insert.php",
                            data: "metodo=" + metodo + "&id_tema=" + id_tema,
                            success: function (data)
                            {
                                $("#div_nuevo_metodo_ev").fadeOut();
                                if(data == 1) cargarMetodosEvaluacion(id_tema);
                            }
                        });
                    }
                }

                $(".fixed_form_button").removeAttr('disabled');
                $("#nuevoTemaVal").val("");
            }

            function temaSeleccionado(caller)
            {
                if($(caller).parent("ul").attr('id') != "temas_disponibles")
                {
                    id_tema = caller.id;
                    JSON_tema = getJSONTema(id_tema);
                    cargarEstrategias(id_tema);
                    cargarMetodosEvaluacion(id_tema);
                }
            }

            function getJSONTema(id_tema)
            {
                console.log("Buscar el tema " + id_tema + " en el JSON");
                for( var i in planeacion )
                {
                    var bloque = planeacion[i];
                    for( var j in bloque )
                    {
                        var tema = bloque[j];
                        if(tema.id_tema == id_tema){ console.log("Tema encontrado y regresado."); return tema; }
                    }
                }
                console.log("No se encontró el tema en el JSON de planeación. Construyendo objeto...");
                var temaJSON = { "id_tema": id_tema, "tema": $('#'+id_tema).html(), "estrategias":[], "metodos":[]}
                return temaJSON;
            }

            function estrategiaClicked(caller, id_estrategia)
            {
                if(caller.checked) JSON_tema.estrategias.push(id_estrategia);
                else JSON_tema.estrategias.splice(JSON_tema.estrategias.indexOf(id_estrategia), 1);
            }

            function metodoClicked(caller, id_metodo)
            {
                if(caller.checked) JSON_tema.metodos.push(id_metodo);
                else JSON_tema.metodos.splice(JSON_tema.metodos.indexOf(id_metodo), 1);
            }

            function cargarEstrategias(id_tema)
            {
                $.ajax({
                    type: "POST",
                    url: "/includes/acciones/planeacion/temas/print_tabla_estrategias.php",
                    data: "id_tema=" + id_tema + "&estrategiasSeleccionadas= " + JSON.stringify(JSON_tema.estrategias),
                    success: function (data)
                    {
                        $("#tabla_estrategias tbody").html(data);
                    }
                });
            }

            function cargarMetodosEvaluacion(id_tema)
            {
                $.ajax({
                    type: "POST",
                    url: "/includes/acciones/planeacion/temas/print_tabla_metodos_ev.php",
                    data: "id_tema=" + id_tema + "&metodosSeleccionados=" + JSON.stringify(JSON_tema.metodos),
                    success: function (data)
                    {
                        $("#tabla_metodos_ev tbody").html(data);
                    }
                });
            }

            function guardarCambios()
            {
                $("#boton_guardar_final").attr('disabled','disabled');
                if(confirm('¿Seguro que desea guardar la planeación?'))
                {
                    $.ajax({
                        type: "POST",
                        url: "/includes/acciones/planeacion/insert.php",
                        data: "id_grado=" + id_grado + "&id_materia=" + id_materia + "&planeacion=" + JSON.stringify(planeacion),
                        processData: false,
                        success: function (data)
                        {
                            document.location = "/index.php";
                        }
                    });
                }
                else $("#boton_guardar_final").removeAttr('disabled');
            }

            function materiaSeleccionada()
            {
                $("#boton_seleccionar").attr('disabled', 'disabled');

                $("#opciones_top").fadeOut(500, function() {
                    $("#main_content").fadeIn(500);
                });

                id_grado = document.getElementById("gradoVal").id;
                id_materia = document.getElementById("materiaVal").id;

                var grado = document.getElementById("gradoVal").options[document.getElementById("gradoVal").selectedIndex].text;
                var materia = document.getElementById("materiaVal").options[document.getElementById("materiaVal").selectedIndex].text;

                $("#titulo_planeacion").html("Planeación de <b>" + materia + "</b> de <b>" + grado + "</b>");
                cargarTemas();
            }
        </script>
    </head>
    <body>
            <?php include("../../includes/header.php"); ?>
            <div id="principal" class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2" style="margin-top:20px;">
                <div id="area_trabajo">
                    <div style="margin: 10px 0; font-size: 18px;" id="titulo_planeacion" >Planeación</div>
                    
                    <div id="opciones_top">

                        <div class="div_select">
                            <label>Grado</label>
                            <select id="gradoVal" onchange="cargarMaterias();">
                            <?php
                            $grados = $maestro->getGradosActuales();
                            if(is_array($grados))
                            {
                                foreach($grados as $grado)
                                {
                                    echo "<option value='".$grado['id_grado']."'>".$grado['grado']."</option>";
                                }
                            }
                            ?>
                            </select>
                        </div>

                        <div class="div_select">
                            <label>Materia</label>
                            <select id="materiaVal" class="form-control" >
                                <!-- AJAX -->
                            </select>
                        </div>

                        <button class="boton" id="boton_seleccionar" onclick="materiaSeleccionada();">Aceptar</button>

                    </div>

                    <div id="main_content">

                        <div id="div_temas">

                            <div id="div_temas_disponibles">
                                <div class="stuck" >Temas disponibles</div>
                                <ul id="temas_disponibles" class="lista_temas" >
                                </ul>
                                <button class="boton" onclick="$('#div_nuevo_tema').fadeIn();" id="bot_nuevo_tema" >Nuevo tema</button>
                            </div>

                            <div id="div_temas_seleccionados" >
                                <div id="div_temas_bloque1" class="div_bloque" >
                                    <div class="stuck">Bloque 1</div>
                                    <ul id="temas_bloque1" class="lista_temas" >
                                    </ul>
                                </div>
                                <div id="div_temas_bloque2" class="div_bloque" >
                                    <div class="stuck" >Bloque 2</div>
                                    <ul id="temas_bloque2" class="lista_temas" >
                                    </ul>
                                </div>
                                <div id="div_temas_bloque3" class="div_bloque" >
                                    <div class="stuck" >Bloque 3</div>
                                    <ul id="temas_bloque3" class="lista_temas" >
                                    </ul>
                                </div>
                                <div id="div_temas_bloque4" class="div_bloque" >
                                    <div class="stuck" >Bloque 4</div>
                                    <ul id="temas_bloque4" class="lista_temas" >
                                    </ul>
                                </div>
                                <div id="div_temas_bloque5" class="div_bloque" >
                                    <div class="stuck" >Bloque 5</div>
                                    <ul id="temas_bloque5" class="lista_temas" >
                                    </ul>
                                </div>
                            </div>

                        </div>

                        <div id="div_estrategias">
                            <div class="stuck" >Estrategias</div>
                            <table id="tabla_estrategias" class="table">
                                <thead>
                                <tr>
                                    <th style="width: 10%;"></th>
                                    <th style="width: 90%"></th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr style="text-align: center" >
                                        <td colspan="2">Seleccione un tema para ver las estrategias disponibles</td>
                                    </tr>
                                    <!-- AJAX -->
                                </tbody>
                            </table>
                            <button class="boton" onclick="$('#div_nueva_estrategia').fadeIn();" id="bot_nueva_strat">Nueva Estrategia</button>
                        </div>

                        <div id="div_metodos_evaluacion">
                            <div class="stuck" >Métodos de evaluación</div>
                            <table id="tabla_metodos_ev" class="table">
                                <thead>
                                <tr>
                                    <th style="width: 10%;"></th>
                                    <th style="width: 90%;"></th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr style="text-align: center" >
                                        <td colspan="2">Seleccione un tema para ver los métodos de evaluación disponibles</td>
                                    </tr>
                                    <!-- AJAX -->
                                </tbody>
                            </table>
                            <button class="boton" onclick="$('#div_nuevo_metodo_ev').fadeIn();" id="bot_nuevo_met"><span class="glyphicon glyphicon-plus"></span> Nuevo método</button>
                        </div>

                        <button class="boton btn btn-success" onclick="guardarCambios();" id="boton_guardar_final" >Guardar</button>

                    </div>

                    <div class="fixed_form" id="div_nuevo_tema" >
                        <div class="fixed_form_handle" >
                            <img src="/media/iconos/icon_close.gif" alt="X" onclick="$(this).parent().parent().fadeOut();" />
                        </div>
                        <div class="fixed_form_content">
                            <div class="fixed_form_row">
                                <label>Tema</label>
                                <input type="text" class="fixed_form_value" id="nuevoTemaVal" />
                            </div>
                            <div class="fixed_form_row">
                                <input type="button" value="Aceptar" class="fixed_form_button btn btn-primary" onclick="agregarTema();" />
                            </div>
                        </div>
                    </div>

                    <div class="fixed_form" id="div_nueva_estrategia" >
                        <div class="fixed_form_handle" >
                            <img src="/media/iconos/icon_close.gif" alt="X" onclick="$(this).parent().parent().fadeOut();" />
                        </div>
                        <div class="fixed_form_content">
                            <div class="fixed_form_row">
                                <label>Estrategia</label>
                                <input type="text" class="fixed_form_value" id="nuevaEstrategiaVal" />
                            </div>
                            <div class="fixed_form_row">
                                <input type="button" value="Aceptar" class="fixed_form_button btn-btn-primary" onclick="agregarEstrategia();" />
                            </div>
                        </div>
                    </div>

                    <div class="fixed_form" id="div_nuevo_metodo_ev" >
                        <div class="fixed_form_handle" >
                            <img src="/media/iconos/icon_close.gif" alt="X" onclick="$(this).parent().parent().fadeOut();" />
                        </div>
                        <div class="fixed_form_content">
                            <div class="fixed_form_row">
                                <label>Método</label>
                                <input type="text" class="fixed_form_value" id="nuevoMetodoVal" />
                            </div>
                            <div class="fixed_form_row">
                                <input type="button" value="Aceptar" class="fixed_form_button btn btn-primary" onclick="agregarMetodo();" />
                            </div>
                        </div>
                    </div>

                </div>

            </div>
    </body>
</html>
