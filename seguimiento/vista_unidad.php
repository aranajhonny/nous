<?php session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");

$_SESSION['acc']['mod'] = 41;
$_SESSION['acc']['form'] = 148;
include("../complementos/permisos.php");

if(isset($_REQUEST['id'])){ 
$id = filtrar_campo('int', 6, $_REQUEST['id']);

$result = pg_query($link, filtrar_sql("SELECT archivo, extension FROM unidimg WHERE id_unidad=".$_REQUEST['id']." order by id_unidimg asc limit 1"));

if(pg_num_rows($result)>0){//SI EXISTE EL PRODUCTO
$result_array = pg_fetch_array($result,NULL,PGSQL_NUM);

if(empty($result_array[0])){// SI NO TIENE IMAGEN
header("location: ../img/unidades.png");
exit(); 	
	
} else {
header("Content-Type: ".$result_array[1]);
echo pg_unescape_bytea($result_array[0]); 
}

} else { // SI NO EXISTE EL PRODUCTO
header("location: ../img/unidades.png");
exit();    }

} else { 
Auditoria("En Seguimiento de Unidad Especifico Acceso Invalido al Archivo de Unidades",0);
header("location: ../img/unidades.png");
exit();
}
?>