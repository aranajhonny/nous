<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");

$eventos="";

$rs = pg_query($link, filtrar_sql("select id_permiso, tipo_permisos.nombre, permisos.serial, fecha_expedicion, fecha_vencimiento, dias_gestion from tipo_permisos, permisos 
where (permisos.id_cliente=".$_SESSION['miss'][3]." and tipo_permisos.id_cliente=".$_SESSION['miss'][3].") and ((id_area=".$_SESSION['miss'][0]." or ".$_SESSION['miss'][0]." < 1) and (id_zona=".$_SESSION['miss'][1]." or ".$_SESSION['miss'][1]." < 1) ) and 
 permisos.id_tipo_permiso = tipo_permisos.id_tipo_permiso order by fecha_vencimiento asc"));
$r = pg_num_rows($rs);
if($r==false || $r<1){ 
	$eventos="";
} else { 
    while ($r = pg_fetch_array($rs)) {
		
$qs=pg_query($link, filtrar_sql("select date '".$r[4]."' - interval '".$r[5]." day' as fecha"));  
$qs = pg_fetch_array($qs);	
		
// FECHA DE EXPEDICION
$eventos .= "{id: ".$r[0].", title: 'Fecha de Expedición ".$r[1]." - ".$r[2]."', start: '".$r[3]."', backgroundColor: '#090', borderColor: '#090'},  ";
// INICIO DE TRAMITE		
$eventos .= "{id: ".$r[0].", title: 'Fecha de Inicio de Tramite ".$r[1]." - ".$r[2]."', start: '".$qs[0]."', backgroundColor: '#FC3', borderColor: '#FC3'},  ";
// FECHA DE VENCIMIENTO
$eventos .= "{id: ".$r[0].", title: 'Fecha de Vencimiento del Permiso ".$r[1]." - ".$r[2]."', start: '".$r[4]."', backgroundColor: '#C00', borderColor: '#C00'},  ";
    
	} 
	$eventos = substr($eventos, 0, (strlen($eventos)-1));
}

include("../complementos/closdb.php");
echo $eventos;
?>