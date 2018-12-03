<?php

$cnn_acl = pg_connect("host=localhost port=5432 dbname=nous user=postgres password=@BigNous$2014",PGSQL_CONNECT_FORCE_NEW);

// CARGANDO MODULOS
$mod = array();
$i=0;
$Ys = pg_query($cnn_acl, "select id_dependencia from sys_acl, sys_acciones, sys_modulos where id_usu = ".$_SESSION['miss'][8]." and sys_acl.id_acc = sys_acciones.id_acc and sys_acciones.id_mod = sys_modulos.id_mod group by id_dependencia order by id_dependencia asc");
$Y = pg_num_rows($Ys);
if($Y<>false && $Y>0){ 
	while($Y=pg_fetch_array($Ys)){ 
		$mod[$i] = $Y[0];
		$i++;
	}
}

// Excepcion del Modulo 1 Configuraciones
if(in_array(8,$mod) || in_array(9,$mod) || in_array(10,$mod)){
	$mod[$i] = 1;
}

// CARGANDO LOS ACL
$acl = array();
$Ys = pg_query($cnn_acl, "select id_acc from sys_acl where id_usu = ".$_SESSION['miss'][8]." order by id_acc asc");
$Y = pg_num_rows($Ys);
if($Y<>false && $Y>0){ 
	while($Y=pg_fetch_array($Ys)){ 
		$acl[$i] = $Y[0];
		$i++;
	}
}


pg_close($cnn_acl);

$menu="<nav class='sidenav left' role='navigation'>
            <ul class='menu'>
                <li class='user'>
                    <div class='content'>
<img class='img-circle' height='54' width='54' src='../img/icono.png' alt=''  onclick=".'"'."location.href='../inicio/principal.php'".'"'." >
                        <p>NousTrack <br /> <small>Bienvenido</small></p>
                    </div>
                </li>  ";
				

if(in_array(1,$mod)){ 
$menu.="<li><a>Configuraciones<div><img src='../img/cog.png' alt=''></div></a>
	<ul class='dropdown-menu' role='menu'>
        <li><a class='back'><i class='fa fa-angle-left'></i> Volver</a></li>";

if(in_array(8,$mod)){ 
$menu.="<li>
			<a>Unidades <i class='fa fa-angle-right pull-right'></i></a>
			<ul class='dropdown-menu second' role='menu'>
				<li><a class='back'><i class='fa fa-angle-left'></i> Volver</a></li>";	
if(in_array(281,$acl)) $menu.="<li><a href='../configuracion_unidades/listado.php'>Configuración de Unidades</a></li>";
if(in_array(282,$acl)) $menu.="<li><a href='../marcas/listado.php'>Marcas</a></li>";
if(in_array(283,$acl)) $menu.="<li><a href='../modelos/listado.php'>Modelos</a></li>";
if(in_array(285,$acl)) $menu.="<li><a href='../unidades/listado.php'>Unidades</a></li>";
if(in_array(433,$acl)) $menu.="<li><a href='../unidades/primarias_secundarias.php'>Unidades Principal y Anexadas</a></li>";
$menu.="
			</ul>
		</li>";
}

if(in_array(9,$mod)){ 
$menu.="<li>
            <a>Dispositivos <i class='fa fa-angle-right pull-right'></i></a>
            <ul class='dropdown-menu second' role='menu'>
                <li><a class='back'><i class='fa fa-angle-left'></i> Volver</a></li>";
				
if(in_array(298,$acl)) $menu.="<li><a href='../lotes/listado.php'>Lotes</a></li>";
if(in_array(286,$acl)) $menu.="<li><a href='../dispositivos/listado.php'>Dispositivos</a></li>";
if(in_array(287,$acl)) $menu.="<li><a href='../dispositivos_modelos/listado.php'>Modelos de Dispositivo</a></li>";
if(in_array(288,$acl)) $menu.="<li><a href='../tipo_dispositivos/listado.php'>Tipo de Dispositivo</a></li>";
if(in_array(289,$acl)) $menu.="<li><a href='../controles/listado.php'>Controles</a></li>";
if(in_array(290,$acl)) $menu.="<li><a href='../sensores/listado.php'>Sensores</a></li>";
if(in_array(432,$acl)) $menu.="<li><a href='../sensores/sensor_control.php'>Sensor - Control</a></li>";
if(in_array(291,$acl)) $menu.="<li><a href='../tipo_sensores/listado.php'>Tipo de Sensores</a></li>";
if(in_array(292,$acl)) $menu.="<li><a href='../magnitudes/listado.php'>Magnitudes</a></li>";
if(in_array(293,$acl)) $menu.="<li><a href='../unidad_medida/listado.php'>Unidad de Medida</a></li>";

$menu.="
            </ul>
        </li>";
}
		
if(in_array(10,$mod)){ 
$menu.="<li>
            <a>Usuarios <i class='fa fa-angle-right pull-right'></i></a>
            <ul class='dropdown-menu second' role='menu'>
<li><a class='back'><i class='fa fa-angle-left'></i> Volver</a></li>";

if(in_array(294,$acl)) $menu.="<li><a href='../areas/listado.php'>Áreas</a></li>";
if(in_array(295,$acl)) $menu.="<li><a href='../cargos/listado.php'>Cargos</a></li>";
if(in_array(296,$acl)) $menu.="<li><a href='../clientes/listado.php'>Clientes</a></li>";
if(in_array(441,$acl)) $menu.="<li><a href='../dispositivos_clientes/listado.php'>Cliente Dispositivo</a></li>";
if(in_array(297,$acl)) $menu.="<li><a href='../personal/listado.php'>Personal</a></li>";
if(in_array(299,$acl)) $menu.="<li><a href='../roles/listado.php'>Roles de Usuario</a></li>";
if(in_array(300,$acl)) $menu.="<li><a href='../tipo_clientes/listado.php'>Tipo de Cliente</a></li>";
if(in_array(419,$acl)) $menu.="<li><a href='../unidad_medida_cliente/listado.php'>Unidades de Medida del Cliente</a></li>";
if(in_array(301,$acl)) $menu.="<li><a href='../zonas_geograficas/listado.php'>Zona Geográfica</a></li>";

$menu.="
             </ul>
        </li>
	</ul>
</li>";
}
}


if(in_array(2,$mod)){
$menu.="<li><a>Control de Permisos<div><img src='../img/book.png' alt=''></div></a>
	<ul class='dropdown-menu' role='menu'>
		<li><a class='back'><i class='fa fa-angle-left'></i> Volver</a></li>";
if(in_array(450,$acl)) $menu.="<li><a href='../clasificacion_permisos/listado.php'>Clasificación de Permisos</a></li>";
if(in_array(302,$acl))$menu.="<li><a href='../tipo_permisos/listado.php'>Tipo de Permisos</a></li>";
if(in_array(303,$acl))$menu.="<li><a href='../permisos/listado.php'>Permisos</a></li>";

$menu.="
	</ul>
</li>";
}

if(in_array(62,$mod)){
$menu.="<li><a>Controles<div><img src='../img/control.png'  alt=''></div></a>
  <ul class='dropdown-menu' role='menu'>
	<li><a class='back'><i class='fa fa-angle-left'></i> Volver</a></li>";
	
if(in_array(289,$acl)) $menu.="<li><a href='../controles/listado.php'>Controles</a></li>";
if(in_array(432,$acl)) $menu.="<li><a href='../sensores/sensor_control.php'>Sensor - Control</a></li>";
if(in_array(285,$acl)) $menu.="<li><a href='../unidades/listado.php'>Unidades</a></li>";
if(in_array(434,$acl)) $menu.="<li><a href='../geocercas/listado.php'>Geocercas</a></li>";
if(in_array(438,$acl)) $menu.="<li><a href='../geocerca_unidades/listado.php'>Geocerca - Unidades</a></li>";

$menu.="
  </ul>
</li>";
}



if(in_array(3,$mod)){
$menu.="<li><a>Monitoreo de Unidades<div><img src='../img/map.png' alt=''></div></a>
  <ul class='dropdown-menu' role='menu'>
	<li><a class='back'><i class='fa fa-angle-left'></i> Volver</a></li>";
	
if(in_array(414,$acl)) $menu.="<li><a href='../geoposiciones/vista.php?limpiar=true'>Geoposición</a></li>";
if(in_array(413,$acl)) $menu.="<li><a href='../estatus_general/vista.php'>Estatus General</a></li>";
if(in_array(417,$acl)) $menu.="<li><a href='../seguimiento/vista.php?limpiar=true'>Seguimiento de Unidad Específico</a></li>";
if(in_array(418,$acl)) $menu.="<li><a href='../monitoreo_unidad/vista.php?limpiar=true'>Seguimiento de Unidad General</a></li>";
if(in_array(448,$acl)) $menu.="<li><a href='../visuales/listado.php?limpiar=true'>Visual</a></li>";

$menu.="
  </ul>
</li>";
}


if(in_array(4,$mod)){
$menu.="<li><a>Mantenimiento<div><img src='../img/configurar.png' alt=''></div></a>
  <ul class='dropdown-menu' role='menu'>
	  <li><a class='back'><i class='fa fa-angle-left'></i> Volver</a></li>";
	  
if(in_array(304,$acl)) $menu.="<li><a href='../composicion_unidades/listado.php'>Composición de Unidades</a></li>";
if(in_array(305,$acl)) $menu.="<li><a href='../proveedor_servicios/listado.php'>Proveedor de Servicios</a></li>";
if(in_array(306,$acl)) $menu.="<li><a href='../plan_maestro_mantenimiento/listado.php'>Plan Maestro de Mantenimiento</a></li>";
if(in_array(307,$acl)) $menu.="<li><a href='../plan_mantenimiento/listado.php'>Plan de Mantenimiento</a></li>";
if(in_array(422,$acl)) $menu.="<li><a href='../plan_mantenimiento_unidades/listado.php'>Asignar Plan de Mantenimiento a Unidad</a></li>";
if(in_array(416,$acl)) $menu.="<li><a href='../programacion_mantenimiento/listado.php?limpiar=true'>Proximos Mantenimientos Programados</a></li>";
if(in_array(412,$acl)) $menu.="<li><a href='../programacion_mantenimiento/ver_programacion.php?limpiar=true'>Calendario de Mantenimientos Programados</a></li>";
if(in_array(428,$acl)) $menu.="<li><a href='../ejecucion_mantenimientos/listado.php'>Ejecución de Mantenimiento</a></li>";
if(in_array(430,$acl)) $menu.="<li><a href='../evaluaciones/listado.php'>Evaluación de Mantenimientos</a></li>";

$menu.="
  </ul>
</li>";
}


if(in_array(5,$mod)){
$menu.="<li><a>Servicio al Cliente<div><img src='../img/crop.png' alt=''></div></a>
  <ul class='dropdown-menu' role='menu'>
	  <li><a class='back'><i class='fa fa-angle-left'></i> Volver</a></li>";
	  
if(in_array(308,$acl)) $menu.="<li><a href='../configuracion_guia_despachos/listado.php'>Configuración Guía de Despacho</a></li>";
if(in_array(309,$acl)) $menu.="<li><a href='../guia_despachos/listado.php'>Guía de Despacho</a></li>";
if(in_array(0,$acl)) $menu.="<li><a href=''>Despacho</a></li>";
if(in_array(310,$acl)) $menu.="<li><a href='../rutas/listado.php'>Rutas</a></li>";

$menu.="
  </ul>
</li>";
} 

if(in_array(6,$mod)){
$menu.="<li><a>Actividades<div><img src='../img/bell.png'  alt=''></div></a>
  <ul class='dropdown-menu' role='menu'>
	  <li><a class='back'><i class='fa fa-angle-left'></i> Volver</a></li>";
	  
if(in_array(411,$acl)) $menu.="<li><a href='../alarmas/vista.php' target='_blank'>Alarmas</a></li>";
if(in_array(415,$acl)) $menu.="<li><a href='../notificaciones/vista.php' target='_blank'>Noticias</a></li>";
if(in_array(443,$acl)) $menu.="<li><a href='../panico/listado.php?limpiar=true'>Alertas de Pánico</a></li>";

$menu.="
	</ul>
</li>";
}

if(in_array(7,$mod)){
$menu.="<li><a>Administración de Sistema<div><img src='../img/users.png' alt=''></div></a>
  <ul class='dropdown-menu' role='menu'>
	  <li><a class='back'><i class='fa fa-angle-left'></i> Volver</a></li>";
	  
if(in_array(313,$acl)) $menu.="<li><a href='../auditoria/vista.php'>Auditoria</a></li>";
if(in_array(446,$acl)) $menu.="<li><a href='../conf_inicial/agregar.php?limpiar=true'>Configuración Inicial</a></li>";
if(in_array(454,$acl)) $menu.="<li><a href='../conf_permisos/agregar.php?limpiar=true'>Configuración de Permisos</a></li>";
if(in_array(447,$acl)) $menu.="<li><a href='../instalacion/agregar.php?limpiar=true'>Instalación</a></li>";
if(in_array(311,$acl)) $menu.="<li><a href='../usuarios/listado.php?limpiar=true'>Usuarios</a></li>";

if(in_array(455,$acl)) { 
	$menu .="<li><a>Reportes de Sistema</a>
  				<ul class='dropdown-menu' role='menu'>";
  					$menu.="<li><a href='../reportes/vpermacc.php'>Permisos de Acceso</a></li>";
  	$menu.="	</ul>
			</li>";
}

$menu.="
  </ul>
</li>";
}




$menu.="<li><a>Mi Cuenta<div><img src='../img/man.png' alt=''></div></a>
	<ul class='dropdown-menu' role='menu'>
		<li><a class='back'><i class='fa fa-angle-left'></i> Volver</a></li>
		<li><a href=''>Ayuda</a></li>
		<li><a href='../usuarios/cambio.php'>Cambio de Clave</a></li>
		<li><a href='../soportes/listado.php'>Centro de Soporte</a></li>
	</ul>
</li>";

$menu.="<li><a href='../../nousview/view.php'>Ir a NousView<div><img src='../img/modulo.png' alt=''></div></a></li>";


$menu.="
<li class='logout'>
<a href='../complementos/salir.php'>Cerrar Sesión<div><img src='../Legend/admin/assets/img/icons/off.png' alt=''></div></a>
</li> 
</ul>
</nav> ";  

unset($acl);
unset($mod);?>