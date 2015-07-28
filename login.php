<?php
    extract($_GET);
?>

<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="utf-8" />
        <link rel="Shortcut Icon" href="/media/iconos/meze.ico">
        <title>Plataforma Meze</title>
        <link rel="stylesheet" href="estilo/login.css" />
    </head>
    <body>
        
        <form id='forma_login' action="includes/login.php" method="post">
            <img src="media/logos/meze.png" alt="Colegio Meze" style="width: 100px; margin: 30px 200px 0;" />
            <?php if(isset($error)) echo "<div id='error_msg' >Datos erroneos. Porfavor intente de nuevo.</div>"; ?>
            <p>
                <label for="matriculaVal">Matrícula</label>
                <input class="input" type="text" name="matriculaVal" id="matriculaVal"/>
            </p>
            <p>
                <label for="passwordVal">Contraseña</label>
                <input class="input" type="password" name="passwordVal" id="passwordVal"/>
            </p>
            <p>
                <input id="boton_aceptar" type="submit" value="Aceptar" />
            </p>
        </form>

    </body>
</html>