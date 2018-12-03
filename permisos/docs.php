<?php session_start(); 
include("../complementos/condb.php");
include_once("../complementos/auditoria.php"); 

if(isset($_REQUEST['id'])){ 
$id = filtrar_campo('int', 6, $_REQUEST['id']);
$cant = 0; 
$rs = pg_query($link, filtrar_sql("select cant_doc from tipo_permisos where id_tipo_permiso = ".$id)); 
$r = pg_num_rows($rs); 
if($r!=false && $r>0){  
	$rs = pg_fetch_array($rs);
	$cant = $rs[0];
} 
} 

include("../complementos/closdb.php");
echo $cant;?>