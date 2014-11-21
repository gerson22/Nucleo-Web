<?php
/** Created by Gustavo Carrillo
 * gus@yozki.net
 * @yozki
 */

include_once("../../includes/validar_admin.php");
?>
<div style="overflow: auto; width: 90%; margin: 0 16px" >
    <input type="checkbox" onchange="toggle_seleccion(this)" />Seleccionar todo
</div>
<div class="grupo_permisos">
    <div class="grupo_permisos_titulo">Administradores</div>
    <div class="permiso"><input type="checkbox" value="1" />Nuevo</div>
    <div class="permiso"><input type="checkbox" value="2" />Ver lista</div>
    <div class="permiso"><input type="checkbox" value="3" />Ver perfil</div>
    <div class="permiso"><input type="checkbox" value="4" />Eliminar</div>
</div>
<div class="grupo_permisos">
    <div class="grupo_permisos_titulo">Alumnos</div>
    <div class="permiso"><input type="checkbox" value="5" />Ver inscritos</div>
    <div class="permiso"><input type="checkbox" value="6" />Ver lista</div>
    <div class="permiso"><input type="checkbox" value="7" />Inscribir</div>
    <div class="permiso"><input type="checkbox" value="8" />Ver perfil</div>
    <div class="permiso"><input type="checkbox" value="37" />Baja</div>
</div>
<div class="grupo_permisos">
    <div class="grupo_permisos_titulo">Becas</div>
    <div class="permiso"><input type="checkbox" value="9" />Ver lista</div>
    <div class="permiso"><input type="checkbox" value="10" />Nueva</div>
    <div class="permiso"><input type="checkbox" value="11" />Nuevo tipo</div>
    <div class="permiso"><input type="checkbox" value="12" />Ver tipos</div>
    <div class="permiso"><input type="checkbox" value="36" />Eliminar</div>
</div>
<div class="grupo_permisos">
    <div class="grupo_permisos_titulo">Ciclos escolares</div>
    <div class="permiso"><input type="checkbox" value="14" />Ver lista</div>
    <div class="permiso"><input type="checkbox" value="15" />Nuevo</div>
    <div class="permiso"><input type="checkbox" value="16" />Estadísticas</div>
</div>
<div class="grupo_permisos">
    <div class="grupo_permisos_titulo">Cuentas</div>
    <div class="permiso"><input type="checkbox" value="17" />Pagos</div>
    <div class="permiso"><input type="checkbox" value="18" />Descuentos</div>
    <div class="permiso"><input type="checkbox" value="39" />Perfiles</div>
    <div class="permiso"><input type="checkbox" value="43" />Re-imprimir recibos</div>
</div>
<div class="grupo_permisos">
    <div class="grupo_permisos_titulo">Uniformes</div>
    <div class="permiso"><input type="checkbox" value="40" />Pago</div>
    <div class="permiso"><input type="checkbox" value="41" />Lista</div>
    <div class="permiso"><input type="checkbox" value="42" />Compra</div>
</div>
<div class="grupo_permisos">
    <div class="grupo_permisos_titulo">Grados</div>
    <div class="permiso"><input type="checkbox" value="22" />Ver lista</div>
    <div class="permiso"><input type="checkbox" value="23" />Modificar</div>
    <div class="permiso"><input type="checkbox" value="24" />Nuevo</div>
</div>
<div class="grupo_permisos">
    <div class="grupo_permisos_titulo">Grupos</div>
    <div class="permiso"><input type="checkbox" value="25" />Ver grupo</div>
    <div class="permiso"><input type="checkbox" value="26" />Grupos actuales</div>
    <div class="permiso"><input type="checkbox" value="27" />Ver lista</div>
    <div class="permiso"><input type="checkbox" value="28" />Nuevo</div>
</div>
<div class="grupo_permisos">
    <div class="grupo_permisos_titulo">Maestros</div>
    <div class="permiso"><input type="checkbox" value="29" />Ver lista</div>
    <div class="permiso"><input type="checkbox" value="30" />Ver actuales</div>
    <div class="permiso"><input type="checkbox" value="31" />Nuevo</div>
    <div class="permiso"><input type="checkbox" value="32" />Perfil</div>
    <div class="permiso"><input type="checkbox" value="35" />Modificar</div>
</div>
<div class="grupo_permisos">
    <div class="grupo_permisos_titulo">Materias</div>
    <div class="permiso"><input type="checkbox" value="33" />Ver lista</div>
    <div class="permiso"><input type="checkbox" value="34" />Nueva</div>
</div>
<div class="grupo_permisos">
    <div class="grupo_permisos_titulo">Configuración</div>
    <div class="permiso"><input type="checkbox" value="44" />Clubs</div>
    <div class="permiso"><input type="checkbox" value="45" />Colonias</div>
    <div class="permiso"><input type="checkbox" value="46" />Papeleria</div>
    <div class="permiso"><input type="checkbox" value="47" />Ocupaciones</div>
    <div class="permiso"><input type="checkbox" value="48" />Niveles</div>
</div>