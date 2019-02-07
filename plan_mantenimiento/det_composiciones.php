<?php 
session_start();
include("../complementos/condb.php");

$html = "";
if(isset($_REQUEST['conf'])){ 

include("../composiciones/composiciones_CompUnid.php");
$rs=pg_query($link, "select id_composicion, nombre from composiciones where id_dependencia=0 and id_confunid = ".$_REQUEST['conf']." order by nombre asc");


$html.="<option value='0' selected='selected'>Seleccione Una Composición</option>";

$r = pg_num_rows($rs);
if($r!=false && $r>0){ 

while($r = pg_fetch_array($rs)){
$qs = pg_query($link, "select count(id_composicion) from composiciones where id_dependencia = ".$r[0]); $qs = pg_fetch_array($qs); 

if($comp[0][$i]==$r[0]) $aux=' selected '; else $aux="";
if($qs[0]==0){ 
	$html .= "<option value='".$r[0]."'>".$r[1]."</option>";
} else { 
	$html .= "<option value='".$r[0]."'>".$r[1]."</option>";
	$html .= ComponerComboxCompUnid($r[0], $r[1], $comp[0][$i], "&emsp;"); 
} 
 
} 

} 
}

echo $html;
?>