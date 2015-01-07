<?php
/**
 * Created by PhpStorm.
 * User: Yozki
 * Date: 1/6/15
 * Time: 7:02 PM
 */
?>
<div id="top" class="container">
    <!-- Panel izquierdo -->
    <div class="col-md-6 col-sm-12" id="panel_izquierdo" >

        <a href="maestros/grupos.php">
            <div class="index_opcion_large">
                <img class="img-responsive" src="/media/iconos/icon_teacher.png" alt="Grupos" />
                <footer>Grupos</footer>
            </div>
        </a>

        <a href="maestros/grupos.php">
            <div class="index_opcion">
                <img class="img-responsive" src="/media/iconos/salir.png" alt="Salir" />
                <footer>Salir</footer>
            </div>
        </a>

    </div>

    <!-- Panel derecho -->
    <div class="col-md-6 col-sm-12" id="panel_derecho">
        <div id="foto">
            <img src="/media/fotos/<?php echo $usuario->foto; ?>" />
            <footer>Bienvenido <?php echo $usuario->nombres; ?></footer>
        </div>

        <div id="carousel_noticias">

        </div>
    </div>
</div>