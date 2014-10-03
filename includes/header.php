<div id="header">
    <a href="/index.php" ><img id="logo_meze" src="/media/logos/mezeblanco.png" alt="MEZE" /></a>
    <div id="nombre_sesion">
        <a id="link_perfil" href="/perfil.php">Bievenid@ <?php if(isset($_SESSION['nombres'])) echo $_SESSION['nombres'] ; ?></a>
        <br>
        <a id="link_logout" href="/includes/logout.php">Salir</a>
    </div>
    <div id="header_titulo" >SISTEMA INTEGRAL EN LINEA MEZE</div>
</div>
<?php
switch($_SESSION['tipo_persona'])
{
    case 1: include("menu_alumno.php"); break;
    case 2: include("menu_maestro.php"); break;
    case 3: include("menu_administrador.php"); break;
    default: break;
}
?>