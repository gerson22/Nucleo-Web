<?php
/**
 * Created by PhpStorm.
 * User: Yozki
 * Date: 1/6/15
 * Time: 6:41 PM
 */

?>
<div id="top" class="container">
    <!-- Panel izquierdo -->
    <div class="col-md-6 col-sm-12" id="panel_izquierdo" >

        <a href="admin/estadisticas/stats.php">
            <div class="index_opcion">
                <img class="img-responsive" src="/media/iconos/stats.png" alt="Estadísticas" />
                <footer>Estadísticas</footer>
            </div>
        </a>

        <a href="admin/maestros/index.php">
            <div class="index_opcion">
                <img class="img-responsive" src="/media/iconos/icon_teacher.png" alt="Maestros" />
                <footer>Maestros</footer>
            </div>
        </a>

        <a href="admin/alumnos/index.php">
            <div class="index_opcion_large">
                <img class="img-responsive" src="/media/iconos/icon_student.png" alt="Alumnos" />
                <footer>Alumnos</footer>
            </div>
        </a>

        <a href="admin/becas/lista.php">
            <div class="index_opcion">
                <img class="img-responsive" src="/media/iconos/icon_beca.png" alt="Becas" />
                <footer>Becas</footer>
            </div>
        </a>

        <a href="admin/cuentas/recibos.php">
            <div class="index_opcion">
                <img class="img-responsive" src="/media/iconos/icon_cuentas.png" alt="Cuentas" />
                <footer>Cuentas</footer>
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

<div id="bot" class="container">
    <a href="admin/configuracion/clubs/index.php">
        <div class="index_opcion_secundaria">
            <img class="img-responsive" src="/media/iconos/icon_clubs.png" alt="Clubs" />
            <footer>Clubs</footer>
        </div>
    </a>
    <a href="admin/configuracion/colonias/index.php">
        <div class="index_opcion_secundaria">
            <img class="img-responsive" src="/media/iconos/icon_colonia.png" alt="Colonias" />
            <footer>Colonias</footer>
        </div>
    </a>
    <a href="admin/configuracion/ocupaciones/index.php">
        <div class="index_opcion_secundaria">
            <img class="img-responsive" src="/media/iconos/icon_work.jpg" alt="Ocupaciones" />
            <footer>Ocupaciones</footer>
        </div>
    </a>
    <a href="admin/configuracion/papeleria/index.php">
        <div class="index_opcion_secundaria">
            <img class="img-responsive" src="/media/iconos/icon_papeleria.jpg" alt="Papeleria" />
            <footer>Papeleria</footer>
        </div>
    </a>
    <a href="admin/configuracion/niveles/index.php">
        <div class="index_opcion_secundaria">
            <img class="img-responsive" src="/media/iconos/icon_niveles.png" alt="Niveles" />
            <footer>Niveles</footer>
        </div>
    </a>
    <a href="/includes/logout.php">
        <div class="index_opcion_secundaria">
            <img class="img-responsive" src="/media/iconos/salir.png" alt="Salir" />
            <footer>Salir</footer>
        </div>
    </a>
</div>