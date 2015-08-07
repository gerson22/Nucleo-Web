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

        <form id='forma_login' action="includes/login.php" method="post">
            <img src="media/logos/meze.png" alt="Colegio Meze" style="width: 100px; margin: 30px 200px 0;" />
            <?php if(isset($error)) echo "<div id='error_msg' >Datos erroneos. Porfavor intente de nuevo.</div>"; ?>
        <form  class="col-md-4 col-md-offset-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-12  comboxlog animate-in" data-anim-type="fade-in-left" action="includes/login.php" method="post" id="forma_Log">
            <img src="img/logo.png" alt="Colegio Meze"   class="col-md-offset-4 col-md-4 col-sm-offset-4 col-sm-4 col-xs-offset-4 col-xs-4  animate-in" data-anim-type="fade-in-down">
			<?php if(isset($error)) echo "<div id='error_msg' >Datos erroneos. Porfavor intente de nuevo.</div>"; ?><br>
            <p>
                <label class="col-md-12 col-sm-12 col-xs-12">Matrícula</label>
                <input class="form-control animate-in" data-anim-type="fade-in-left" type="text" name="matriculaVal" placeholder="Matricula">
            </p>
            <p>
                <label class="col-md-12 col-sm-12 col-xs-12">Contraseña</label>
                <input class="form-control animate-in" data-anim-type="fade-in-up" type="password" name="passwordVal" placeholder="Contraseña">
            </p>
            <p>
                <input  type="submit" value="Aceptar" class="btn btn-info btn-lg button col-md-offset-4 col-md-4 col-sm-offset-4 col-sm-4 col-xs-offset-2 col-xs-8 animate-in" data-anim-type="fade-in-down" ><br><br><br>
            </p>
        </form>

   </body>
</html>
<script src="js/jquery-2.1.3.js" type="text/javascript"></script>
<script src="js/bootstrap.js" type="text/javascript"></script>
<script src="js/myanimation.js" type="text/javascript"></script>
<script src="plugins/assets/js/smoothscroll.min.js" type="text/javascript"></script>
<script src="plugins/assets/js/backbone.js" type="text/javascript"></script>
<script src="plugins/assets/js/appear.min.js" type="text/javascript"></script>
<script src="plugins/assets/js/animations.min.js" type="text/javascript"></script>
<script src="js/jquery.backstretch.min.js" type="text/javascript"></script>
<script type="text/javascript">
	 $.backstretch([
      "img/Meze1.jpg"
    , "img/Meze2.jpg"
    , "img/Meze3.jpg"
  ], {duration: 3000, fade: 750});
</script>
