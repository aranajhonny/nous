<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");
$cuerpo1 = "";
$cuerpo2 = "";

if(isset($_REQUEST['limpiar'])){ unset($_SESSION['tmp_req']); }

function selecciono($op){ 
	if(strcmp($op,"on")==0) return 'checked'; 
	else return '';
}

if(isset($_REQUEST['id']))  {
	$id = filtrar_campo('int', 6, $_REQUEST['id']);
	
if(isset($_SESSION['tmp_req'])==false){
$rs = pg_query($link, filtrar_sql("select id_reqtipperm, descripcion from req_tipperm where id_tipo_permiso = ".$id." and estatus='Activo' order by descripcion asc "));
$r = pg_num_rows($rs);
if($r!=false && $r>0){ $i=0; 
while($r = pg_fetch_array($rs)){
$_SESSION['tmp_req'][$i][0]=$r[0];
$_SESSION['tmp_req'][$i][1]=$r[1];
$_SESSION['tmp_req'][$i][2]="off";
$i++; } } }

$n = count($_SESSION['tmp_req']); $i=0;
while($i<$n){ 
	$cuerpo1 .= "<div class='skin skin-square skin-section checkbox icheck form-group'><label for='square-checkbox-2' class='icheck'><input tabindex='6' type='checkbox' name='ids_".$_SESSION['tmp_req'][$i][0]."' id='ids_".$_SESSION['tmp_req'][$i][0]."' ".selecciono($_SESSION['tmp_req'][$i][2])." />".$_SESSION['tmp_req'][$i][1]."</label></div><br/>";
	$i++;

if($i<$n){
	$cuerpo2 .= "<div class='skin skin-square skin-section checkbox icheck form-group'><label for='square-checkbox-2' class='icheck'><input tabindex='6' type='checkbox' name='ids_".$_SESSION['tmp_req'][$i][0]."' id='ids_".$_SESSION['tmp_req'][$i][0]."' ".selecciono($_SESSION['tmp_req'][$i][2])." />".$_SESSION['tmp_req'][$i][1]."</label></div><br/>";
	$i++; 
}	
}

if(empty($cuerpo1)){ 
	$cuerpo1="<div class='form-group'><label>No Hay Requisitos</label></div>"; 
}

$html = "<div class='row'>
<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>$cuerpo1</div>
<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>$cuerpo2</div>
</div>";



$rs = pg_query($link, filtrar_sql("select id_reqimg, descripcion, extension from reqimg where id_tipo_permiso = ".$id." order by descripcion asc")); 
$r = pg_num_rows($rs);
if($r==false || $r==0){ 

$html.="<div class='form-group'><label>No Hay Foto</label></div>";

} else { $i=1; $r=pg_fetch_array($rs);
$dir = "../tipo_permisos/vista.php?id=".$r[0]; 

if(strpos($r[2],"image/")>-1){
	
$html.="<div class='col-xs-6 col-sm-6 col-md-6 col-lg-6'><label>$i.- ".$r[1]."</label>
<a href='$dir' class='boxer thumbnail' title='".$r[1]."' rel='gallery'><img src='$dir' alt='Thumbnail One' /></a></div>
<div class='col-xs-6 col-sm-6 col-md-6 col-lg-6'><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p></div>";

} else { 

$html.="<div class='col-xs-6 col-sm-6 col-md-6 col-lg-6'><label>$i.- ".$r[1]."</label>
<a href='' target='new' class='boxer thumbnail' title='".$r[1]."' rel='gallery'><img src='../img/arch_descargar.png' width='150' height='150' alt='Thumbnail One'/></a></div>
<div class='col-xs-6 col-sm-6 col-md-6 col-lg-6'><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p></div>";

} $i++; }



echo $html;
}

include("../complementos/closdb.php");
?>