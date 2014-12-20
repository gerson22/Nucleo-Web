<?php
$id_modulo = 16; // Ciclos - Estadísticas
include_once("../includes/validar_admin.php");
include_once("../includes/funciones_auxiliares.php");
include_once("../includes/clases/class_lib.php");
include_once("../includes/validar_acceso.php");
$ciclos_escolares = CicloEscolar::getLista();
$ciclo_actual = CicloEscolar::getActual();
$count_alumnos = $ciclo_actual->getCountAlumnosInscritos();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Sistema Integral Meze - Estadísticas</title>
    <link rel="stylesheet" href="../estilo/general.css" />
    <link rel="stylesheet" href="../estilo/jquery.dataTables.css" />
    <style>
        .charts_div{ overflow: auto; display: none; }
        .chart_inner_div{ width: 480px; float: left; }
        .chart_inner_div_title{ color: #4F4F4F; text-align: center; }
        #charts_options
        {
            margin: 15px 0;
            overflow: auto;
            padding: 0;
            width: 100%;
            list-style: none;
        }

        #charts_options li.selected, #charts_options li:hover
        {
            background-color: #E1E1FF;
            border: 1px solid #838683;
        }

        #charts_options li
        {
            background-color: #FFFFFF;
            border: 1px solid #AEB0AD;
            height: 20px;
            padding: 10px 0;
            text-align: center;
            width: 100px;
            float: left;
            margin: 0px 10px 0px 0px;
        }

        .data_row
        {
            width: 100%;
            margin: 0px 0px 10px 0px;
            overflow: auto;
            height: 20px;
        }

        .data_row_color_square
        {
            border: 1px solid #AAAAAA;
            float: left;
            height: 14px;
            margin: 2px 10px 2px 2px;
            width: 14px;
        }

        .data_row_label
        {
            width: 200px;
            float: left;
        }

        .data_row_label_mini
        {
            width: 100px;
            float: left;
        }
    </style>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
    <script src="../librerias/jquery.dataTables.min.js" ></script>
    <script src="/librerias/Chart.js/Chart.min.js" ></script>
    <script>
        /** Alunmnos */
        var ctx_alumnos_pie;
        var chart_alumnos_pie;
        var ctx_alumnos_ciclos;
        var chart_alumnos_ciclos;
        var ctx_alumnos_inscripciones_bajas;
        var chart_alumnos_inscripciones_bajas;

        /** Maestros */
        var ctx_maestros_bar;
        var chart_maestros_bar;

        /** Clubs */
        var ctx_clubs_pie;
        var chart_clubs_pie;

        /** Colonias */
        var ctx_colonias_pie;
        var chart_colonias_pie;

        $(document).ready(function ()
        {

        });

        function crearChartsAlumnos()
        {
            ctx_alumnos_pie = $("#canvas_alumnos_pie").get(0).getContext("2d");
            chart_alumnos_pie = new Chart(ctx_alumnos_pie);

            $.getJSON('/includes/acciones/stats/alumnos/distribucion_area.php', "tipo=2", function(jsonData)
            {
                var alumnos_pie_data = [];

                $.each(jsonData, function(i, area)
                {
                    switch(i)
                    {
                        case 0: $("#alumnos_kinder").html(area.value); break;
                        case 1: $("#alumnos_primaria").html(area.value); break;
                        case 2: $("#alumnos_secundaria").html(area.value); break;
                        case 3: $("#alumnos_bachillerato").html(area.value); break;
                        case 4: $("#alumnos_ingenieria").html(area.value); break;
                    }
                    alumnos_pie_data.push({value: area.value * 1.0, color: area.color});
                });

                var options_pie = {animationSteps  : 180};
                new Chart(ctx_alumnos_pie).Pie(alumnos_pie_data, options_pie);
            });

            ctx_alumnos_ciclos = $("#canvas_alumnos_ciclos").get(0).getContext("2d");
            chart_alumnos_ciclos = new Chart(ctx_alumnos_ciclos);

            $.getJSON('/includes/acciones/stats/alumnos/inscritos_ciclos.php', function(jsonData)
            {
                var alumnos_ciclos_labels = ['Inicio'];
                var alumnos_ciclos_data = [0];

                $.each(jsonData, function(i, ciclo)
                {
                    alumnos_ciclos_labels.push(ciclo.ciclo);
                    alumnos_ciclos_data.push(ciclo.alumnos);
                });

                var alumnos_ciclos_metadata = {
                    labels : alumnos_ciclos_labels,
                    datasets : [
                        {
                            fillColor : "rgba(151,187,205,0.5)",
                            strokeColor : "rgba(151,187,205,1)",
                            pointColor : "rgba(151,187,205,1)",
                            pointStrokeColor : "#fff",
                            data : alumnos_ciclos_data
                        }
                    ]
                };

                var options_line = {animationSteps  : 180};
                new Chart(ctx_alumnos_ciclos).Line(alumnos_ciclos_metadata, options_line);
            });

            ctx_alumnos_inscripciones_bajas = $("#canvas_alumnos_inscripciones_bajas").get(0).getContext("2d");
            chart_alumnos_inscripciones_bajas = new Chart(ctx_alumnos_inscripciones_bajas);

            $.getJSON('/includes/acciones/stats/alumnos/inscripciones_ciclos.php', function(jsonData)
            {
                var alumnos_inscripciones_bajas_labels = ['Inicio'];
                var alumnos_altas_data = [0];
                var alumnos_bajas_data = [0];

                $.each(jsonData, function(i, ciclo)
                {
                    alumnos_inscripciones_bajas_labels.push(ciclo.ciclo);
                    alumnos_altas_data.push(ciclo.altas);
                    alumnos_bajas_data.push(ciclo.bajas);
                });

                var alumnos_ciclos_metadata = {
                    labels : alumnos_inscripciones_bajas_labels,
                    datasets : [
                        {
                            fillColor : "rgba(151,187,205,0.5)",
                            strokeColor : "rgba(151,187,205,1)",
                            pointColor : "rgba(151,187,205,1)",
                            pointStrokeColor : "#fff",
                            data : alumnos_altas_data
                        },
                        {
                            fillColor : "rgba(220,220,220,0.5)",
                            strokeColor : "rgba(220,220,220,1)",
                            pointColor : "rgba(220,220,220,1)",
                            pointStrokeColor : "#fff",
                            data : alumnos_bajas_data
                        }
                    ]
                };

                var options_line = {animationSteps  : 180};
                new Chart(ctx_alumnos_inscripciones_bajas).Line(alumnos_ciclos_metadata, options_line);
            });
        }

        function crearChartsMaestros()
        {
            ctx_maestros_bar = $("#canvas_maestros_bar").get(0).getContext("2d");
            chart_maestros_bar = new Chart(ctx_maestros_bar);

            $.getJSON('/includes/acciones/stats/maestros/distribucion_area.php', "tipo=1", function(jsonData)
            {
                var datos = {
                    labels : ["Kinder", "Primaria", "Secundaria", "Bachillerato", "Ingenieria"],
                    datasets : [
                        {
                            fillColor : "rgba(151,187,205,0.5)",
                            strokeColor : "rgba(151,187,205,1)",
                            pointColor : "rgba(151,187,205,1)",
                            pointStrokeColor : "#fff",
                            data : jsonData
                        }
                    ]
                }
                var options = {animationSteps  : 180};
                new Chart(ctx_maestros_bar).Bar(datos, options);
            });
        }

        function crearChartsClubs()
        {
            ctx_clubs_pie = $("#canvas_clubs_pie").get(0).getContext("2d");
            chart_clubs_pie = new Chart(ctx_clubs_pie);

            $.getJSON('/includes/acciones/stats/clubs/pie.php', function(jsonData)
            {
                var clubs_pie_data = [];

                $("#datos_clubs").html("");
                $.each(jsonData, function(i, club)
                {
                    clubs_pie_data.push({value: club.value * 1.0, color: club.color, label: club.label});
                    $("#datos_clubs").append('<div class="data_row" style="color: '+club.color+'; font-weight: bold">' +
                        '<div class="data_row_label">'+club.label+'</div>' +
                        '<div class="data_row_value" id="total_alumnos" >'+club.value+'</div>' +
                    '</div>');
                });

                var options_pie = {animationSteps  : 50};
                new Chart(ctx_clubs_pie).Pie(clubs_pie_data, options_pie);
            });
        }

        function crearChartsColonias()
        {
            ctx_colonias_pie = $("#canvas_colonias_pie").get(0).getContext("2d");
            chart_colonias_pie = new Chart(ctx_colonias_pie);

            $.getJSON('/includes/acciones/stats/colonias/pie.php', function(jsonData)
            {
                var colonias_pie_data = [];

                $.each(jsonData, function(i, colonia)
                {
                    colonias_pie_data.push({value: colonia.value * 1.0, color: colonia.color, label: colonia.label});
                });

                var options_pie = {animationSteps  : 50};
                new Chart(ctx_colonias_pie).Pie(colonias_pie_data, options_pie);
            });
        }

        function selectedDiv(opt, caller)
        {
            $(".charts_div").hide();
            $("#charts_options li").removeClass('selected');
            $(caller).addClass('selected');
            switch(opt)
            {
                case 1: $("#charts_alumnos").show(0 , function(){ crearChartsAlumnos(); }); break;
                case 2: $("#charts_maestros").show(0, function(){ crearChartsMaestros(); }); break;
                case 3: $("#charts_clubs").show(0, function(){ crearChartsClubs(); }); break;
                case 4: $("#charts_colonias").show(0, function(){ crearChartsColonias(); }); break;
            }
        }
    </script>
</head>
<body>
<div id="wrapper">
    <?php include("../includes/header.php"); ?>
    <div id="content">

        <div id="inner_content">

            <h3>Estadísticas</h3>

            <ul id="charts_options" >
                <li onclick="selectedDiv(1, this);" >Alumnos</li>
                <li onclick="selectedDiv(2, this);" >Maestros</li>
                <li onclick="selectedDiv(3, this);" >Clubs</li>
                <li onclick="selectedDiv(4, this);" >Colonias</li>
            </ul>

            <div class="charts_div" id="charts_alumnos">
                <div class="chart_inner_div">
                    <div class="chart_inner_div_title">Distribución por area</div>
                    <canvas id="canvas_alumnos_pie" width="480" height="400"></canvas>
                </div>
                <div class="chart_inner_div" style="margin: 125px 0;">
                    <div class="data_row">
                        <div class="data_row_label">Alumnos inscritos:</div>
                        <div class="data_row_value" id="total_alumnos" ><?php echo $count_alumnos; ?></div>
                    </div>
                    <div class="data_row">
                        <div class="data_row_color_square" style="background-color: #A3A3FA;" ></div>
                        <div class="data_row_label_mini">Kinder:</div>
                        <div class="data_row_value" id="alumnos_kinder" ></div>
                    </div>
                    <div class="data_row">
                        <div class="data_row_color_square" style="background-color: #FF7373;" ></div>
                        <div class="data_row_label_mini">Primaria:</div>
                        <div class="data_row_value" id="alumnos_primaria" ></div>
                    </div>
                    <div class="data_row">
                        <div class="data_row_color_square" style="background-color: #8BFF8B;" ></div>
                        <div class="data_row_label_mini">Secundaria:</div>
                        <div class="data_row_value" id="alumnos_secundaria" ></div>
                    </div>
                    <div class="data_row">
                        <div class="data_row_color_square" style="background-color: #FFFB9B;" ></div>
                        <div class="data_row_label_mini">Bachillerato:</div>
                        <div class="data_row_value" id="alumnos_bachillerato" ></div>
                    </div>
                    <div class="data_row">
                        <div class="data_row_color_square" style="background-color: #D873FF;" ></div>
                        <div class="data_row_label_mini">Ingenieria:</div>
                        <div class="data_row_value" id="alumnos_ingenieria" ></div>
                    </div>
                </div>
                <div class="chart_inner_div">
                    <div class="chart_inner_div_title">Alumnos inscritos por ciclo</div>
                    <canvas id="canvas_alumnos_ciclos" width="480" height="400"></canvas>
                </div>
                <div class="chart_inner_div">
                    <div class="chart_inner_div_title">Inscripciones y bajas</div>
                    <canvas id="canvas_alumnos_inscripciones_bajas" width="480" height="400"></canvas>
                </div>
            </div>

            <div class="charts_div" id="charts_maestros">
                <div class="chart_inner_div">
                    <div class="chart_inner_div_title">Maestros</div>
                    <canvas id="canvas_maestros_bar" width="480" height="400"></canvas>
                </div>
            </div>

            <div class="charts_div" id="charts_clubs">
                <div class="chart_inner_div">
                    <div class="chart_inner_div_title">Clubs</div>
                    <canvas id="canvas_clubs_pie" width="480" height="400"></canvas>
                </div>
                <div class="chart_inner_div" style="margin: 125px 0;" id="datos_clubs">

                </div>

                <!-- DATATABLE -->
                <table id="tabla_clubs">

                </table>
                <!--------------->
            </div>

            <div class="charts_div" id="charts_colonias">
                <div class="chart_inner_div">
                    <div class="chart_inner_div_title">Colonias</div>
                    <canvas id="canvas_colonias_pie" width="480" height="400"></canvas>
                </div>
            </div>

        </div>

    </div>
</div>
</body>
</html>
