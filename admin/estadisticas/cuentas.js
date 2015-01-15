/**
 * Created by Yozki on 11/01/2015.
 */

var tabla_pagos;
var tabla_iniciada = 0;

// Document.ready
llenarSelectNiveles();
llenarSelectConceptos();
setDatepickers();


function llenarSelectNiveles()
{
    $.getJSON('/includes/acciones/areas/JSONGetLista.php', function(jsonData)
    {
        var string_todos = "<option value='0' >Todos</option>";
        $("#nivelVal").append(string_todos);
        $.each(jsonData, function(i, nivel)
        {
            $("#nivelVal").append("<option value='"+nivel.id_area+"' >"+nivel.area+"</option>");
        });
    });
}

function llenarSelectConceptos()
{
    $.getJSON('/includes/acciones/cuentas/conceptos/JSONGetLista.php', function(jsonData)
    {
        var string_todos = "<option value='0' >Todos</option>";
        $("#conceptoVal").append(string_todos);
        $.each(jsonData, function(i, concepto)
        {
            $("#conceptoVal").append("<option value='"+concepto.id_concepto+"' >"+concepto.concepto+"</option>");
        });
    });
}

function generar()
{
    if(tabla_iniciada == 0)
    {
        $("#tabla").fadeIn();
        declararDataTable();
        tabla_iniciada = 1;
    }
    else
    {
        tabla_pagos.fnReloadAjax();
    }
}

function setDatepickers()
{
    $("#fechaInicioVal").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "dd 'de' MM 'del' yy",
        altField: "#fecha_inicioVal",
        altFormat: "yy-mm-dd",
        minDate: -20, maxDate: "+5Y"
    });

    $("#fechaFinVal").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "dd 'de' MM 'del' yy",
        altField: "#fecha_finVal",
        altFormat: "yy-mm-dd",
        minDate: -20, maxDate: "+5Y"
    });
}

function declararDataTable()
{
    tabla_pagos = $('#tabla_pagos').dataTable({
        "oLanguage": {
            "sLengthMenu": "Mostrar _MENU_ pagos por página",
            "sZeroRecords": "No existen pagos",
            "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ pagos",
            "sInfoEmpty": "Mostrando 0 a 0 de 0 pagos",
            "sInfoFiltered": "(Encontrados de _MAX_ pagos)"
        },
        "aoColumns": [
            {"sWidth":"21%"},{"sWidth":"21%"},{"sWidth":"10%"},{"sWidth":"23%"},{"sWidth":"23%"}
        ],
        "bProcessing": true,
        "sAjaxSource": '/includes/acciones/cuentas/pagos/JSONGetPagos.php',
        "fnServerParams": function (aoData)
        {
            var id_nivel        = $("#nivelVal").val();
            var id_concepto     = $("#conceptoVal").val();
            var fecha_inicio    = $("#fecha_inicioVal").val();
            var fecha_fin       = $("#fecha_finVal").val();

            aoData.push({ "name": "id_nivel", "value": id_nivel });
            aoData.push({ "name": "id_concepto", "value": id_concepto });
            aoData.push({ "name": "fecha_inicio", "value": fecha_inicio });
            aoData.push({ "name": "fecha_fin", "value": fecha_fin });
        },
        "iDisplayLength": 25,
        "fnDrawCallback": function( oSettings ) {
            calcularTotal();
        }
    });
}

function calcularTotal()
{
    var total = 0;
    $(".montoVal").each(function()
    {
        total += $(this).val() * 1.0;
    });
    $("#totalVal").val("$" + total);
}