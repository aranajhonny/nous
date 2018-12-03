<?php session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");
include("../reportes/partes.php");

$_SESSION['acc']['mod'] = 66;
$_SESSION['acc']['form'] = 172;
include("../complementos/permisos.php");

Auditoria("Reporte: Listado de Unidades del Cliente",0);

unset($_SESSION['report']['unids']);
$cli = 9;
$rs = pg_query($link, filtrar_sql("select id_unidad, id_dispositivo from unidades where id_cliente = $cli and id_dispositivo <> 0 and (id_unidad = 1260 or id_unidad = 1261) order by id_unidad asc "));
$i=0;
while($r = pg_fetch_array($rs)){ 
	$_SESSION['report']['unids'][$i] = $r[0];
	$_SESSION['report']['disps'][$i] = $r[1];
	$i++;
}

$rs = pg_query($link, filtrar_sql("select UPPER(rif), UPPER(razon_social) from clientes where id_cliente=$cli"));
$rs = pg_fetch_array($rs);
$dcli = $rs[0]." ".$rs[1];

$page=array();

for($i=0; $i<count($_SESSION['report']['unids']); $i++){ 

$html ="<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /> 
<meta name='author' content='NousTrack' />
<link rel='shortcut icon' href='../img/icono.png' />
<title>.:: NousTrack ::.</title>
<style>
.principal { border-bottom:4px solid #CCCCCC; border-top:4px solid #CCCCCC; margin-bottom:80px;}
.principal tr td { border:1px solid #CCCCCC; }
.titulo { background:#97C6FF; font-size:18px; padding:4px 0px 4px 0px; }
.titulo2 { font-size:18px; padding:4px 0px 4px 0px; }
.sensores { border:4px solid #84DDFF; border-left:none; border-right:none; }
.sensores tr td { border:1px solid #E8E8E8; }
.f1 td { background:#FFF; padding:2px 0px 2px 0px; }
.f2 td { background:#E8E8E8; padding:2px 0px 2px 0px; }
</style>
</head>
<body>";

$rs = pg_query($link, filtrar_sql("select confunid.codigo_principal, confunid.nombre, unidades.codigo_principal, tipo_disp.descripcion, dispositivos.serial from unidades, confunid, dispositivos, tipo_disp where confunid.id_confunid = unidades.id_confunid and dispositivos.id_dispositivo = unidades.id_dispositivo and tipo_disp.id_tipo_disp = dispositivos.id_tipo_disp and id_unidad = ".$_SESSION['report']['unids'][$i]." and dispositivos.id_dispositivo = ".$_SESSION['report']['disps'][$i]."")); 
$rs = pg_fetch_array($rs);

$html.="<table align='center' width='850' class='principal'><tr>
<td colspan='3' align='center'><h3 align='center'>HOJA DE INSTALACION</h3></td>
</tr><tr>
<td width='160' class='titulo'>CLIENTE:</td>
<td width='490' style='padding-left:10px;' >".$dcli."</td>
<td width='200' class='titulo'>id_cliente = ".$cli."</td>
</tr><tr>
<td class='titulo'>DISPOSITIVO:</td>
<td style='padding-left:10px;' >".$rs[3]." ".$rs[4]."</td>
<td class='titulo'>id_dispositivo = ".$_SESSION['report']['disps'][$i]."</td>
</tr><tr>
<td class='titulo'>UNIDAD:</td>
<td style='padding-left:10px;' >".$rs[0]." ".$rs[1].": ".$rs[2]."</td>
<td class='titulo'>id_unidad = ".$_SESSION['report']['unids'][$i]."</td>
</tr><tr>
<td colspan='3' align='center' style='padding:30px 0px 10px 0px;'>
<table align='center' width='840' class='sensores'><tr>
<td colspan='5' align='center' class='titulo2' height='30'>SENSORES</td>
</tr><tr>
<td class='titulo' align='center' width='40'>#</td>
<td class='titulo' align='center' width='130'>SERIAL</td>
<td style='padding-left:10px;' width='350' class='titulo'>DESCRIPCION</td>
<td style='padding-left:10px;' width='320' class='titulo'>CONTROL</td>
<td class='titulo' align='center' width='40'>HECHO</td>
</tr>";

$j=1;
$rs = pg_query($link, filtrar_sql("select id_sensor, serial, UPPER(descripcion), id_control from sensores where id_dispositivo = ".$_SESSION['report']['disps'][$i])); 
while($r = pg_fetch_array($rs)){ 
if($r[3]!=0){ 
$qs = pg_query($link, filtrar_sql("select UPPER(nombre) from controles where id_control = ".$r[3]));
$qs = pg_fetch_array($qs); $control = $qs[0];
} else { $control=" - - "; }
if($j%2==0){ $clase="f1"; } else { $clase="f2"; }

$html.="<tr class='$clase'>
<td align='center'>$j</td>
<td align='center'>".$r[1]."</td>
<td style='padding-left:10px;'>".$r[2]."</td>
<td style='padding-left:10px;'>".$control."</td>
<td align='center'><img src='../img/select.png' height='20' width='20' /></td></tr>";

$j++; }

$html.="</table></td></tr></table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p><p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>

</body></html>";

$page[$i] .= $html;
}

include("../mpdf/mpdf.php");
$mpdf=new mPDF('c','A4','','',15,15,25,25,5,5);
$mpdf->allow_charset_conversion = true;
$mpdf->charset_in = 'UTF-8';
$mpdf->SetDisplayMode('fullpage');
$mpdf->SetHTMLHeader($header);
$mpdf->SetHTMLFooter($footer);
for($i=0; $i<count($_SESSION['report']['unids']); $i++){  
	$mpdf->WriteHTML($page[$i]);
}

unset($_SESSION['report']);

$mpdf->Output();
exit; ?>