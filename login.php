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
		<link rel="stylesheet" href="css/bootstrap.css">
		<link rel="stylesheet" href="css/login2.css">
		<link rel="stylesheet" type="text/css" href="plugins/assets/css/animations.min.css">
    </head>
    <body>
        <form  class="col-lg-4 col-md-5 col-md-offset-5 col-lg-offset-4 col-sm-10 col-sm-offset-1 col-xs-12  comboxlog animate-in" data-anim-type="fade-in-left" action="includes/login.php" method="post" id="forma_Log" style="height:400px;">
            <img src="img/logo.png" alt="Colegio Meze"   class="col-md-offset-4 col-md-4 col-sm-offset-4 col-sm-4 col-xs-offset-4 col-xs-4  animate-in" data-anim-type="fade-in-down">
           <?php if(isset($error)) echo "<div id='error_msg' >Datos erroneos. Porfavor intente de nuevo.</div>"; ?><br>
                <label class="col-md-12 col-sm-12 col-xs-12">Matrícula</label><br>
                <input class="form-control animate-in" data-anim-type="fade-in-left" type="text" name="matriculaVal" placeholder="Matricula"><br>
           
                <label class="col-md-12 col-sm-12 col-xs-12">Contraseña</label><br>
                <input class="form-control animate-in" data-anim-type="fade-in-up" type="password" name="passwordVal" placeholder="Contraseña"><br>
         
                <input  type="submit" value="Aceptar" class="btn btn-info btn-lg button col-md-offset-4 col-md-4 col-sm-offset-4 col-sm-4 col-xs-offset-2 col-xs-8 animate-in" data-anim-type="fade-in-down" ><br><br><br>
         
        </form>

   </body>
</html>
<script src="js/jquery.js" type="text/javascript"></script>
<script src="js/bootstrap.js" type="text/javascript"></script>
<script src="js/myanimation.js" type="text/javascript"></script>
<script src="plugins/assets/js/smoothscroll.min.js" type="text/javascript"></script>
<script src="plugins/assets/js/backbone.js" type="text/javascript"></script>
<script src="plugins/assets/js/appear.min.js" type="text/javascript"></script>
<script src="plugins/assets/js/animations.min.js" type="text/javascript"></script>
<script src="js/jquery.backstretch.min.js"></script>
	<script>
		
        $.backstretch([
			"/img/meze1.jpg",
			"/img/meze2.jpg",
			"/img/meze3.jpg"

        ], {
            fade: 750,
            duration: 4000
        });
    </script>

