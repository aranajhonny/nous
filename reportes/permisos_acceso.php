<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");
include("../complementos/util.php");

$_SESSION['acc']['mod'] = 70;
$_SESSION['acc']['form'] = 180;
include("../complementos/permisos.php");

$usu = filtrar_campo('int', 6, $_REQUEST['id']);

if(empty($usu)) $usu=0;
if($usu > 0){ 
	$rs = pg_query($link, filtrar_sql("select nom, ci, nombre from usuarios, personal where id_personal = id_usuario and id_usuario = $usu"));
	$rs = pg_fetch_array($rs);
	$titulo="PERMISOS DE ACCESO USUARIO <br/>".$rs[0]." [".$rs[1]." ".$rs[2]."]";
} else {  
	$titulo="PLANILLA PERMISOS DE ACCESO";
}

Auditoria("Genero Reporte Permisos de Acceso",0);

$html = "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'><head>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
<meta name='author' content='Nous Technologies' />
<link rel='shortcut icon' href='../img/icono.png' />
<title>.:: NousTrack ::.</title>
<style>
.f1 td { background:#FFF; padding:2px 0px 2px 0px; padding-left:7px; }
.f2 td { background:#E8E8E8; padding:2px 0px 2px 0px; padding-left:7px; }
.titulo th { height:30px; background:#428bca; color:#fff; padding:3px 0px; padding:0px 7px; }
</style>
</head><body>

<table width='900' cellpadding='0' cellspacing='3'>
<thead> <tr class='titulo'>
<td colspan='6' align='center'><h4 align='center'>$titulo</h4></td>
</tr>        
<tr class='titulo'>
<th style='min-width:210px;'>Formularios / Botones</th>";

$bot = array(); $i=0;
$rs = pg_query($link, filtrar_sql("select id_bot, nom from sys_botones where id_bot<10 and id_bot<>7 and id_bot<>8 and id_bot<>6 and id_bot>2 order by nom asc"));
while( $r = pg_fetch_array($rs)){ 
	$bot[$i]=$r[0];
	$html .= "<th align='center'>".$r[1]."</th>";
	$i++; 
} 
$bot[$i]=-1;

$html .= "<th align='center'>Opciones Extras</th>
</tr>
</thead>
<tbody>";

$rs = pg_query($link, filtrar_sql("select sys_modulos.id_mod, sys_modulos.nom from sys_modulos where sys_modulos.est='Activo' and id_dependencia <> 1 and id_dependencia <> 0 order by sys_modulos.nom asc ")); 
$t=0; $clase ="";
while( $r = pg_fetch_array($rs) ){ 
	if($t%2==0){ $clase="f1"; } else { $clase="f2"; }
	$html .= "<tr class='$clase'>
	<td>".$r[1]."</td>";
    
	$tmp = array(0,0,0,0,-1); 
	for($i=0; $i<count($bot); $i++){  
		$qs = pg_query($link, filtrar_sql("select id_acc from sys_acciones where id_mod = ".$r[0]." and id_bot = 9 order by id_form asc"));
		$q = pg_num_rows($qs);
		if($q!=false && $q>0){ 
			$j = 0;
			while( $q = pg_fetch_array($qs)) { 
				$tmp[$j] = $q[0];
				$j++;
			}
		} else { $tmp[$i] = 0; }
	}

	for($i=0; $i<count($bot); $i++){ 
		if($tmp[$i]!=0 && $tmp[$i]!=-1){
			$chk = "<img src='../img/cambios3.png' width='22' height='22'/>";
			if($usu > 0){
				$ws = pg_query($link, filtrar_sql("select count(id_acl) from sys_acl where id_usu = $usu and id_acc = ".$tmp[$i])); 
				$ws = pg_fetch_array($ws);  
				if($ws[0] > 0) $chk="<img src='../img/cambios.png' width='22' height='22'/>";
			}
			$html .= "<td align='center'>$chk</td>";
		} else { 
			$html .= "<td align='center'> - - </td>";  
		} 
	} 
$html .= "</tr>";
$t++; 
} 
$html .= "</tbody></table></body></html>";
include("../complementos/closdb.php"); 

include("partes.php");
include("../mpdf/mpdf.php");
$mpdf=new mPDF('c','A4','','',15,15,25,25,5,5);
$mpdf->allow_charset_conversion = true;
$mpdf->charset_in = 'UTF-8';
$mpdf->SetDisplayMode('fullpage');
$mpdf->SetHTMLHeader($header);
$mpdf->SetHTMLFooter($footer); 
$mpdf->WriteHTML($html);
$mpdf->Output();
exit;
?>