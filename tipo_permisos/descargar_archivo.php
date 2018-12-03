<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");

if(isset($_REQUEST['id'])) { 
$id = filtrar_campo('int', 6, $_REQUEST['id']);
	$rs = pg_query($link,"select archivo, nombre from reqimg where id_tipo_permiso=$id");
	$rs = pg_fetch_array($rs);
	Auditoria("En Tipo de Permiso Se Descargo El Archivo ".$rs[1] ,$_REQUEST['id']);
	header ("Content-Disposition: attachment; filename=".$rs[1].";" ); 
	header ("Content-Type: application/force-download");
	
	include("../complementos/closdb.php");
	echo pg_unescape_bytea($rs[0]);
} else { 
	include("../complementos/closdb.php");
}


?>