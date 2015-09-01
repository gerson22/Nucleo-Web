<?php
/**
 * Created by PhpStorm.
 * User: Yozki
 * Date: 11/01/2015
 * Time: 12:27 PM
 */

include_once("../../includes/validar_admin.php");
include_once("../../includes/funciones_auxiliares.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Sistema Integral Meze - Estadística de cuentas</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="../../js/jquery.js" type="text/javascript"></script>
	<script src="../../js/bootstrap.js" type="text/javascript"></script>
	<script src="../../plugins/assets/js/appear.min.js" type="text/javascript"></script>
	<script src="../../plugins/assets/js/animations.js" type="text/javascript"></script>
    <link rel="stylesheet" href="../../estilo/general.css" />
    <link rel="stylesheet" href="../../estilo/jquery.dataTables.css" />
    <link rel="stylesheet" href="../../estilo/estadisticas.css" />
    <link rel="stylesheet" href="../../estilo/jquery.dataTables.css" />
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
</head>
<body>
    <?php include("../../includes/header.php"); ?>
    <div id="principal" class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2" style="margin-top:20px;">
        <div id="area_trabajo">
        <h3>Estadísticas de cuentas</h3>

        <div id="div_parametros">
            <div class="div_parametro">
                <label class="parametroLabel">Nivel</label>
                <select class="parametroValue form-control" id="nivelVal">
                    <!-- AJAX -->
                </select>
            </div>
            <div class="div_parametro">
                <label class="parametroLabel">Concepto</label>
                <select class="parametroValue form-control" id="conceptoVal">
                    <!-- AJAX -->
                </select>
            </div>
            <div class="div_parametro">
                <label class="parametroLabel">De</label>
                <input type="hidden" id="fecha_inicioVal" />
                <input type="text" class="parametroValue form-control" id="fechaInicioVal" />
            </div>
            <div class="div_parametro">
                <label class="parametroLabel">A</label>
                <input type="hidden" id="fecha_finVal"/>
                <input type="text" class="parametroValue form-control" id="fechaFinVal" />
            </div>
            <br />
            <button type="button" style="margin-top: 20px" class="btn btn-primary" onclick="generar();">Generar <i class="fa fa-arrow-circle-right"></i></button>
        </div>

        <div id="resumen">
            <div>TOTAL </div>
            <input type="text" id="totalVal" value="200" class="form-control" readonly />
        </div>

        <div id="tabla" style="display: none;">
            <table id="tabla_pagos" class="table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Concepto</th>
                        <th>Monto</th>
                        <th>Alumno</th>
                        <th>Usuario</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- AJAX -->
                </tbody>
            </table>
        </div>

    </div>
</div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script src='//code.jquery.com/ui/1.10.4/jquery-ui.js' ></script>
<script src="/librerias/jquery.ui.datepicker-es.js"></script>
<script src="/librerias/jquery.dataTables.min.js" ></script>
<script src="/librerias/fnAjaxReload.js" ></script>
<script src="cuentas.js" ></script>
</body>
</html>