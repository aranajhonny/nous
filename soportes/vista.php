<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");

$id = filtrar_campo('int', 6, $_REQUEST['id']);

$result = pg_query($link, filtrar_sql("SELECT foto, ext FROM soporte_img WHERE id_soporteimg=".$id));

if(pg_num_rows($result)>0){//SI EXISTE EL PRODUCTO
$result_array = pg_fetch_array($result,NULL,PGSQL_NUM);

if(empty($result_array[0])){// SI NO TIENE IMAGEN
header("Content-Type: image/png");
$image = imagecreatefrompng('../img/delete.png'); 
ob_start(); 
imagepng($image); 
$png = ob_get_contents();
ob_end_clean();
echo $png; 	
	
} else {
header("Content-Type: ".$result_array[1]);
echo pg_unescape_bytea($result_array[0]); 
}

} else { // SI NO EXISTE EL PRODUCTO
header("Content-Type: image/png");
$image = imagecreatefrompng('../img/delete.png'); 
ob_start(); 
imagepng($image); 
$png = ob_get_contents();
ob_end_clean();
echo $png;    }
?>