<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin - Tareas</title>
</head>
<body>
    <ul id="tareas">
        <!-- AJAX -->
    </ul>
    <button onclick="probar()">Probar</button>
</body>

<script src="/librerias/jquery.min.js"></script>
<script>
    function probar()
    {
        // Aqui paso la matricula y password de la sesión de PHP a variables locales:
        var matricula = '<?php echo $_SESSION['matricula']; ?>';
        var password = '<?php echo $_SESSION['password']; ?>';

        $.ajax({
            type: "GET",
            url: "/API/tareas",
            beforeSend: function (xhr)
            {
                // Aquí mando las credenciales: las variables matricula y password
                xhr.setRequestHeader("Authorization", "Basic " + btoa(matricula + ":" + password));
            },
            success: function (data)
            {
                // Si las credenciales son de un administrador, la llamada regresará TODAS las tareas existentes
                // Aqui iteramos sobre el resultado e imprimimos la descripcione de cada tarea en la lista "tareas"
                jQuery.each(data, function () {
                    $("#tareas").append('<li>' + this.descripcion + '</li>');
                });
            }
        });
    }
</script>

</html>