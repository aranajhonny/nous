<?php session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");
include_once("util.php");

$_SESSION['acc']['mod'] = 42;
$_SESSION['acc']['form'] = 149;
include("../complementos/permisos.php");

if(isset($_REQUEST['id'])==false || isset($_REQUEST['ff'])==false || isset($_REQUEST['fi'])==false){ 

Auditoria("En Seguimiento de Unidad General Acceso Invalido a Recorrido por Fecha",0);
header("location: vacio.php");
exit(); 

} else { 

$ff = date1(filtrar_campo('date',10,$_REQUEST['ff']));    
$fi = date1(filtrar_campo('date',10,$_REQUEST['fi'])); 
$id = filtrar_campo('int',6,$_REQUEST['id']);

$rs = pg_query($link, filtrar_sql("select count(id_log_gps) from log_gps where id_unidad = $id and fecha_hora_gps between '$fi 00:00:00' and '$ff 23:59:59'")); 
$rs = pg_fetch_array($rs);
if($rs[0]<1){ 

header("location: vacio.php");
exit();

} else {

$rs = pg_query($link, filtrar_sql("select geo_posicion from log_gps where id_unidad = $id and fecha_hora_gps between '$fi 00:00:00' and '$ff 23:59:59' order by fecha_hora_gps desc limit 1")); 
$rs = pg_fetch_array($rs);
$coordenadas_iniciales = cordenadas2($rs[0]);
$ultpos = cordenadas4($rs[0]); ?>
<!DOCTYPE html>
<html><head>
  <meta charset="utf-8">
  <title>Recorrido</title>
<script type="text/javascript" src="../jquery/development-bundle/jquery-1.10.2.js"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
<script type="text/javascript" src="../gmaps/gmaps.js"></script>
<script type="text/javascript">
var map;
$(document).ready(function(){ 
<?php echo $Icono_Estatus; ?>

map = new GMaps({
        el: '#map',
		zoom: 18,
		mapTypeControl: true,
		mapTypeControlOptions: {
      		style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
    	},
		streetViewControl: false,
		panControl: false,
		zoomControl: true,
		overviewMapControl: true,
        <?php echo $coordenadas_iniciales; ?>
        click: function(e){
          console.log(e);
        },
      });
	  
	  <?php echo $Panel_Estatus; ?>
	  <?php echo $Panel_Horario; ?>
	  
<?php 
$rs=pg_query($link, filtrar_sql("select unidades.codigo_principal, velocidad_gps, geo_posicion, '', fecha_hora_gps, unidmed.nombre, valor_max, valor_min, valor_critico_max, confunid.codigo_principal, (fecha_hora_gps)::time from unidades, log_gps, confunid, sensores, tipo_sensores, unidmed where unidades.id_unidad = $id and log_gps.id_unidad = $id and unidades.id_confunid = confunid.id_confunid and log_gps.id_sensor = sensores.id_sensor and sensores.id_tipo_sensor = tipo_sensores.id_tipo_sensor and tipo_sensores.id_unidmed = unidmed.id_unidmed and fecha_hora_gps between '$fi 00:00:00' and '$ff 23:59:59' order by log_gps.fecha_hora_gps asc ")); 
$r = pg_num_rows($rs);
if($r==false || $r<1){ $cor = "lat: 0, Lng: 0,";; 

} else { $tmp = ""; $i = $aux = 0; 
while($r=pg_fetch_array($rs)){
	 
if($j==($t-1)){ $tipo="ult"; 
} else if($r[1]>$r[8]){ $tipo = grados($r[11],"r");
} else if($r[1]<$r[8] && $r[1]>=$r[6]){ $tipo = grados($r[11],"a"); 
} else if(($r[1]*1)==0){ $tipo = grados(0,"az");
} else if($r[1]<$r[6]){ $tipo = grados($r[11],"v"); 
} else if($r[1]==$r[6] || $r[1]==$r[8]){ $tipo = grados($r[11],"v"); }

$tmp .= cordenadas3($r[2]);?>	  
map.addMarker({
	<?php echo cordenadas2($r[2]); ?>
	title: '<?php echo $r[9]." ".$r[0]; ?>',
	icon: <?php echo $tipo; ?>,
	infoWindow: {
		content: 'Fecha y Hora: <?php echo date1($r[4]); ?> <br/> <?php echo $r[9]." ".$r[0]; ?><br/>Velocidad: <?php echo (1*$r[1])." Km/Hr"; ?><br/>Ref. <?php echo (1*$r[7])." Km/Hr"; ?> - <?php echo (1*$r[6])." Km/Hr"; ?> <br/>',
		maxWidth:600
	}
});
	  
<?php $q = pg_query($link, filtrar_sql("select  ('".$r[10]."'::time >= '06:00:00'::time and '".$r[10]."'::time <= '11:59:59'::time), ('".$r[10]."'::time >= '12:00:00'::time and '".$r[10]."'::time <= '17:59:59'::time), ('".$r[10]."'::time >= '18:00:00'::time  or '".$r[10]."'::time <= '05:59:59'::time)")); $q = pg_fetch_array($q); 	
if($q[0]=='t'){ $aux=1; } else if($q[1]=='t'){ $aux=2; } else if($q[2]=='t'){ $aux=3; }
	
if($i==0){ $i = $aux; 
} else if($i != $aux ){ 
	$tmp = substr($tmp,0,(strlen($tmp)-2)); ?>
path = [ <?php echo $tmp; ?> ];
map.drawPolyline({
	path: path,
	strokeColor: '<?php echo $colors[($i-1)]; ?>',
	strokeOpacity: 0.9,
	strokeWeight: 4
});	
<?php $i=$aux; $tmp = cordenadas3($r[2]);
} // si cambio de horario
} // mientras puntos 
$tmp = substr($tmp,0,(strlen($tmp)-2)); ?>

path = [ <?php echo $tmp; ?> ];
map.drawPolyline({
	path: path,
	strokeColor: '<?php echo $colors[($aux-1)]; ?>',
	strokeOpacity: 0.9,
	strokeWeight: 4
});	
<?php } // si puntos ?>	 
});


$.getJSON( "http://maps.googleapis.com/maps/api/geocode/json?latlng=<?php echo $ultpos;?>&sensor=true_or_false", function( json ) {
	var dir = "";
	if(json.status=="OK"){
		for(i=0; i<json.results[0].address_components.length; i++){ 
			if(json.results[0].address_components[i].types[0]=="route"){ 
				dir += "Ruta: "+json.results[0].address_components[i].long_name+", ";
			} else { 
				dir += json.results[0].address_components[i].long_name+", ";
			}
		}
map.addControl({
        position: 'left_bottom',
        content: '<table align="center"><tr><td width="400"><label style=" color:#00F; font-size:16px; ">'+dir+'</label></td></tr></table>',
        style: {
          margin: '5px',
          padding: '1px 6px',
          border: 'solid 1px #717B87',
          background: '#fff'
        }
});
	}
});</script>
</head><body><div class="row"><div class="span11">
<div id="map" style="width:710px; height:500px;"></div>
</div></div></body></html>
<?php } } ?>