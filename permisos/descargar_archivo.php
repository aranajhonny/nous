<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");

if(isset($_REQUEST['id']))  {
$id = filtrar_campo('int', 6, $_REQUEST['id']);

$rs = pg_query($link, filtrar_sql("select archivo, nombre from permimg where id_permimg = ".$id));
$r = pg_num_rows($rs);
if($r==false || $r<1){ 
	$_SESSION['mensaje1']="No Logro Identificar el Archivo del Permiso";
	Auditoria("Archivo del Permiso No Identificado ",$id);
	include("../complementos/closdb.php");
} else { 
	$rs = pg_fetch_array($rs);
	Auditoria("En Permisos se Descargo el Archivo: ".$rs[1],$id);
	header ("Content-Disposition: attachment; filename=".$rs[1].";" ); 
	header ("Content-Type: application/force-download");
	include("../complementos/closdb.php");
	echo pg_unescape_bytea($rs[0]);
}

}

?>