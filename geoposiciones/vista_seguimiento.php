<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");
include("../complementos/util.php");

$html = "";

if(isset($_REQUEST['id'])){ 
$_SESSION['seg_unidad'] = filtrar_campo('int', 6,$_REQUEST['id']);
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
	$texto .= "<strong>ULTIMA ACTUALIZACIÓN: ".date3($rs[10])."</strong><br/>";
	$prox = date3($rs[12]);
	$dif = minutos($rs[11]);
}

Auditoria("En Geoposición se Abrio la Vista de Recorrido para la Unidad: $titulo",$id);

$ult_data = "UTIMA TRANSMISIÓN: <strong>$dif Min.</strong><br/> PRÓXIMA TRANSMISIÓN: <strong>$prox</strong><br/>";
//------------------------ Velocidad -----------------------------//
$rs = pg_query($link, filtrar_sql("select UPPER(descripcion), ult_valor from sensores where id_unidad = $id and nro_tsen = 40 "));  $r = pg_num_rows($rs);
if($r!=false && $r>0){  
	$r = pg_fetch_array($rs); 
	$ult_data .= $r[0].": <strong>".($r[1]*1)." Km/Hr</strong><br/>";
}
//------------------------ Distancia Diaria -----------------------------//
$rs = pg_query($link, filtrar_sql("select UPPER(descripcion), ult_valor from sensores where id_unidad = $id and nro_tsen = 61 "));
$r = pg_num_rows($rs);
if($r!=false && $r>0){  
	$r = pg_fetch_array($rs); 
	$ult_data .= "DISTANCIA ACUM. DIARIA: <strong>".$r[1]." Km</strong><br/>";
}
//------------------------ Puerta del Piloto -----------------------------//
$rs = pg_query($link, filtrar_sql("select UPPER(descripcion), ult_valor from sensores where id_unidad = $id and nro_tsen = 31 "));  $r = pg_num_rows($rs);
if($r!=false && $r>0){  
	$r = pg_fetch_array($rs); 
	if(($r[1]*1)==3) $est = "Cerrada"; else $est = "Abierta";
	$ult_data .= $r[0].": <strong>".$est."</strong><br/>";
}
//------------------------ Puerta del CoPiloto -----------------------------//
$rs = pg_query($link, filtrar_sql("select UPPER(descripcion), ult_valor from sensores where id_unidad = $id and nro_tsen = 30 "));  $r = pg_num_rows($rs);
if($r!=false && $r>0){  
	$r = pg_fetch_array($rs); 
	if(($r[1]*1)==3) $est = "Cerrada"; else $est = "Abierta";
	$ult_data .= $r[0].": <strong>".$est."</strong><br/>";
}
//------------------------ Puerta de la Cava -----------------------------//
$rs = pg_query($link, filtrar_sql("select UPPER(descripcion), ult_valor from sensores where id_unidad = $id and nro_tsen = 32 "));  $r = pg_num_rows($rs);
if($r!=false && $r>0){  
	$r = pg_fetch_array($rs); 
	if(($r[1]*1)==3) $est = "Cerrada"; else $est = "Abierta";
	$ult_data .= $r[0].": <strong>".$est."</strong><br/>";
}
//------------------------ Temperatura de la Cava -----------------------------//
$rs = pg_query($link, filtrar_sql("select UPPER(descripcion), ult_valor from sensores where id_unidad = $id and nro_tsen = 21 "));  $r = pg_num_rows($rs);
if($r!=false && $r>0){  
	$r = pg_fetch_array($rs); 
	$ult_data .= $r[0].": <strong>".($r[1]*1)." °C</strong><br/>";
}




$html="<div class='well'>
<div class='header'>Datos del Recorrido<a class='headerclose'><i class='fa fa-times pull-right'></i></a> <a class='headerrefresh'><i class='fa fa-refresh pull-right'></i></a> <a class='headershrink'><i class='fa fa-chevron-down pull-right'></i></a></div>

<div class='well searchres'>
	<div class='row'>
		<a href='#'>
        	<div class='col-xs-6 col-sm-3 col-md-3 col-lg-2' style='max-width:140px;'>
<img class='img-responsive' id='vista' name='vista' src='vista_unidad.php?id=$id' height='120' width='120'  alt='' />
		    </div>
		    <div class='col-xs-6 col-sm-9 col-md-9 col-lg-10 title' style='max-width:300px;'>
				<h3 id='titulo'>$titulo</h3>
				<p id='texto'>$texto</p>
		    </div>
			<div class='col-xs-6 col-sm-9 col-md-9 col-lg-10 title' style='max-width:430px;'>
				<p align='right'>$ult_data</p>
		    </div>
		 </a>
	</div>
</div>
	
	<div id='wizard3'>       
<h2>Ultima Semana</h2><section><iframe src='ultima_semana.php?id=".$_REQUEST['id']."' name='recorrido1' id='recorrido1' height='720' width='915' scrolling='no' style='border:none;background:none;'></iframe></section>

<h2>Fechas</h2>
<section>

<div class='form-group'>
<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>
<label>Fecha Desde</label>
<input id='fi' name='fi' type='text' placeholder='Fecha Desde' class='form-control' maxlength='12' value='' onchange='fechas();' />
</div>
<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>
<label>Fecha Hasta</label>
<input id='ff' name='ff' type='text' placeholder='Fecha Hasta' class='form-control' maxlength='12' value='' onchange='fechas();' />
</div>  
<p>&nbsp;</p>    
</div> 
<iframe src='fechas.php' name='fechas' id='fechas' height='610' width='915' scrolling='no' style='border:none;background:none;'></iframe>
</section> 

</div>
<p>&nbsp;</p>
<div class='row'>
<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>
<input type='button' name='volver' value='Limpiar' class='btn btn-info btn-block' onclick='limpiar()'/></div></div>

</div>";
}

include("../complementos/closdb.php"); 
echo $html;?>