<?php  
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");

$val = "false";
if(isset($_REQUEST['id'])){ 
	$id = filtrar_campo('int', 6, $_REQUEST['id']);
	$rs = pg_query("select descripcion from permimg where id_permimg = $id");
	$rs = pg_fetch_array($rs);
	$archivo = $rs[0];
	
	if(pg_query("delete from permimg where id_permimg = $id")){ 
		$val="true";
		Auditoria("En Permisos Se Elimino El Archivo: $archivo",$id);
	}
}
include("../complementos/closdb.php");
echo $val;
?>