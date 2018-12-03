<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");
include("../complementos/util.php");

$_SESSION['acc']['mod'] = 67;
$_SESSION['acc']['form'] = 173;
include("../complementos/permisos.php");

$html = "";

if(isset($_REQUEST['id'])){ 
$id = filtrar_campo('int', 6,$_REQUEST['id']);

$texto = $titulo = " ";
$rs = pg_query($link, filtrar_sql("select confunid.codigo_principal, UPPER(n_configuracion_01), UPPER(n_configuracion_02), UPPER(n_configuracion_03), UPPER(n_configuracion_04), unidades.codigo_principal, n_configuracion1, n_configuracion2, n_configuracion3, n_configuracion4, ult_act, (ult_act - timestamp '".date('Y-m-d H:i:s')."'), (ult_act + interval '5 minute') from unidades, confunid where unidades.id_confunid = confunid.id_confunid and id_unidad = $id")); 
$r = pg_num_rows($rs);
if($r!=false || $r>0){ 
	$rs = pg_fetch_array($rs); 
	$titulo = $rs[0]." ".$rs[5]; 
	$texto = $rs[1].": ".$rs[6]."<br/>";
	$texto .= $rs[2].": ".$rs[7]."<br/>";
	$texto .= $rs[3].": ".$rs[8]."<br/>";
	$texto .= $rs[4].": ".$rs[9]."<br/>";
	$prox = date3($rs[12]);
	$dif = minutos($rs[11]);
}

$rs = pg_query($link, filtrar_sql("select dir, f_event from log_img where id_unidad = $id order by id desc limit 1 "));
$rs = pg_fetch_array($rs);

$texto .= "<strong>ULTIMA VISUAL: ".date3($rs[1])."</strong><br/>";
$dir = $rs[0];

Auditoria("En Visuales Accedio a Ultima Visual para la Unidad: $titulo",$id);

$html="<div class='well'>
<div class='header'>Ultima Visual</div>

<div class='well searchres'>
	<div class='row'>
		<a href='#'>
		    <div class='col-xs-6 col-sm-9 col-md-9 col-lg-10 title' style='max-width:330px;'>
				<h3 id='titulo'>$titulo</h3>
		    </div>
			<div class='col-xs-6 col-sm-9 col-md-9 col-lg-10 title' style='max-width:530px;'>
				<p align='right' id='texto'>$texto</p>
		    </div>
		 </a>
	</div>
</div>
	
<img src='$dir' width='880' height='500' />
	
<p>&nbsp;</p>
<div class='row'>
<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>
<input type='button' name='volver' value='Limpiar' class='btn btn-info btn-block' onclick='limpiar();'/></div></div>

</div>";
}

include("../complementos/closdb.php"); 
echo $html;?>