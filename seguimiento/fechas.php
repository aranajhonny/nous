<?php session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");
include("util.php");

$_SESSION['acc']['mod'] = 41;
$_SESSION['acc']['form'] = 148;
include("../complementos/permisos.php");

if(isset($_REQUEST['id'])==false || isset($_REQUEST['ff'])==false || isset($_REQUEST['fi'])==false){ 

Auditoria("En Seguimiento de Unidad Especifica Acceso Invalido a Recorrido por Fecha",0);
header("location: vacio.php");
exit(); 

} else { 

$ff = date1(filtrar_campo('date',10,$_REQUEST['ff']));    
$fi = date1(filtrar_campo('date',10,$_REQUEST['fi'])); 
$id = filtrar_campo('int',6,$_REQUEST['id']);


$rs = pg_query($link, filtrar_sql("select count(id_log_gps) from log_gps where id_unidad = $id and fecha_hora_gps between '$fi 00:00:00' and '$ff 23:59:59' ")); 
$rs = pg_fetch_array($rs);
if($rs[0]<1){ 

header("location: vacio.php");
exit();

} else { // SIPUNTOS

$rs = pg_query($link, filtrar_sql("select geo_posicion from log_gps where id_unidad = $id and fecha_hora_gps between '$fi 00:00:00' and '$ff 23:59:59' order by fecha_hora_gps desc limit 1")); $rs = pg_fetch_array($rs);
$coordenadas_iniciales = cordenadas2($rs[0]);
$ultpos = cordenadas4($rs[0]); 

// Inicializar Data de los Dias
$data = array( array('path'=>array('man'=>"",'tar'=>"",'noc'=>""),'punto'=>""));



$data[$k]['punto']="";
$data[$k]['path']['man']="";
$data[$k]['path']['tar']="";
$data[$k]['path']['noc']="";

$rs = pg_query($link, filtrar_sql("select unidades.codigo_principal, velocidad_gps, geo_posicion, fecha_hora_gps, valor_max, valor_min, valor_critico_max, log_gps.dconfunid, (fecha_hora_gps)::time, sentido, 
((fecha_hora_gps)::time >= '06:00:00'::time and (fecha_hora_gps)::time <= '11:59:59'::time), ((fecha_hora_gps)::time >= '12:00:00'::time and (fecha_hora_gps)::time <= '17:59:59'::time), ((fecha_hora_gps)::time >= '18:00:00'::time  or (fecha_hora_gps)::time <= '05:59:59'::time) 
from unidades, log_gps where unidades.id_unidad = $id and log_gps.id_unidad = $id and  fecha_hora_gps between '$fi 00:00:00' and '$ff 23:59:59' order by log_gps.fecha_hora_gps asc "));

$w=0;
while($r=pg_fetch_array($rs)){ // MIENTRAS PUNTOS
	// Determinar Sentido 
if($r[1]>$r[6]){ $tipo = grados($r[9],"r"); 
} else if($r[1]<$r[6] && $r[1]>=$r[4]){ $tipo = grados($r[9],"a");  
} else if(($r[1]*1)==0){ $tipo = grados(0,"az");
} else if($r[1]<$r[4]){ $tipo = grados($r[9],"v"); 
} else if($r[1]==$r[4] || $r[1]==$r[6]){ $tipo = grados($r[9],"v"); }

list($lat,$lng) = explode(",",str_replace(")","",str_replace("(","",$r[2])));

$data[0]['punto'] .= "[$lat, $lng, '".$r[7].' '.$r[0]."', $tipo, 'Fecha y Hora: ".date1($r[3])."<br/>".$r[7].' '.$r[0]."<br/>Velocidad: ".(1*$r[1])." Km/Hr.<br/>Ref. ".(1*$r[5])." Km/Hr - ".(1*$r[4])." Km/Hr<br/>'],";

if($r[10]=='t'){       /*Mañana*/  $data[0]['path']['man'] .= cordenadas3($r[2]);
} else if($r[11]=='t'){ /*Tarde*/  $data[0]['path']['tar'] .= cordenadas3($r[2]);
} else if($r[12]=='t'){ /*Noche*/  $data[0]['path']['noc'] .= cordenadas3($r[2]); }

$w++; } // MIENTRAS PUNTOS





if(strpos($data[0]['path']['man'],",")!=false){ 
	$data[0]['path']['man'] = 
	substr($data[0]['path']['man'], 0, (strlen($data[0]['path']['man'])-1));
}
if(strpos($data[0]['path']['tar'],",")!=false){ 
	$data[$k]['path']['tar'] = 
	substr($data[0]['path']['tar'], 0, (strlen($data[0]['path']['tar'])-1));
}
if(strpos($data[0]['path']['noc'],",")!=false){ 
	$data[$k]['path']['noc'] = 
	substr($data[0]['path']['noc'], 0, (strlen($data[0]['path']['noc'])-1));
}
if(strpos($data[0]['punto'],",")!=false){
	$data[0]['punto'] = substr($data[0]['punto'], 0, (strlen($data[0]['punto'])-1));
}


?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Demo del Mapa</title>
<style>.btn {
  display: inline-block;
  padding: 6px 12px;
  margin-bottom: 0;
  font-size: 14px;
  font-weight: normal;
  line-height: 1.42857143;
  text-align: center;
  white-space: nowrap;
  vertical-align: middle;
  cursor: pointer;
  -webkit-user-select: none;
     -moz-user-select: none;
      -ms-user-select: none;
          user-select: none;
  background-image: none;
  border: 1px solid transparent;
  border-radius: 4px;
}
.btn:focus,
.btn:active:focus,
.btn.active:focus {
  outline: thin dotted;
  outline: 5px auto -webkit-focus-ring-color;
  outline-offset: -2px;
}
.btn:hover,
.btn:focus {
  color: #333;
  text-decoration: none;
}
.btn:active,
.btn.active {
  background-image: none;
  outline: 0;
  -webkit-box-shadow: inset 0 3px 5px rgba(0, 0, 0, .125);
          box-shadow: inset 0 3px 5px rgba(0, 0, 0, .125);
}
.btn.disabled,
.btn[disabled],
fieldset[disabled] .btn {
  pointer-events: none;
  cursor: not-allowed;
  filter: alpha(opacity=65);
  -webkit-box-shadow: none;
          box-shadow: none;
  opacity: .65;
}


.btn-info {
  color: #fff;
  background-color: #5bc0de;
  border-color: #46b8da;
}
.btn-info:hover,
.btn-info:focus,
.btn-info:active,
.btn-info.active,
.open .dropdown-toggle.btn-info {
  color: #fff;
  background-color: #31b0d5;
  border-color: #269abc;
}
.btn-info:active,
.btn-info.active,
.open .dropdown-toggle.btn-info {
  background-image: none;
}
.btn-info.disabled,
.btn-info[disabled],
fieldset[disabled] .btn-info,
.btn-info.disabled:hover,
.btn-info[disabled]:hover,
fieldset[disabled] .btn-info:hover,
.btn-info.disabled:focus,
.btn-info[disabled]:focus,
fieldset[disabled] .btn-info:focus,
.btn-info.disabled:active,
.btn-info[disabled]:active,
fieldset[disabled] .btn-info:active,
.btn-info.disabled.active,
.btn-info[disabled].active,
fieldset[disabled] .btn-info.active {
  background-color: #5bc0de;
  border-color: #46b8da;
}
.btn-info .badge {
  color: #5bc0de;
  background-color: #fff;
}</style>
<script type="text/javascript" src="../jquery/development-bundle/jquery-1.10.2.js"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
<script type="text/javascript" src="../gmaps/gmaps.js"></script>
<script type="text/javascript">
var map;
var pts = [];
var lin = [];
var ii=0;
<?php echo $Icono_Estatus; ?>


$(document).ready(function(){ 
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
	}
});
<?php 
	echo $Panel_Estatus."\n"; 
	echo $Panel_Horario; 
?>  

setTimeout("fechas('<?php echo date4($dia[0]);?>');  cargar(1);",3000);
});

<?php 
echo "var pts0 = [".$data[0]['punto']."]; \n";

echo "var path0_man = [".$data[0]['path']['man']."]; \n"; 
echo "var path0_tar = [".$data[0]['path']['tar']."]; \n";
echo "var path0_noc = [".$data[0]['path']['noc']."]; \n"; ?>

function limpiar(){ 
	for(i=0; i<pts.length; i++){ 
		pts[i].setMap(null);
	}
	pts = [];
	for(i=0; i<lin.length; i++){ 
		lin[i].setMap(null);
	}
	lin = [];
	ii=0;
}

function pain_linea(color, path){ 
	lin[ii] = map.drawPolyline({ 
		path:          path, 
		strokeColor:   color, 
		strokeOpacity: 0.9, 
		strokeWeight:  4 
	}); 
	ii++;
}

function pain_punto(i, punto){ 
	pts[i] = map.addMarker({
		lat:   punto[0],
		lng:   punto[1],
		title: punto[2],
		icon:  punto[3],
		infoWindow: {
			content: punto[4],
			maxWidth:600
		}
	});
}

function cargar(op){ 
var i = 0; 

	switch(op){ 
		case 1:  
			limpiar();
			if(path0_man.length>0) { pain_linea('#006FDD', path0_man); }	
			if(path0_tar.length>0) { pain_linea('#FF6317', path0_tar); }
			if(path0_noc.length>0) { pain_linea('#000000', path0_noc); }
			for(i=0; i<pts0.length; i++){ pain_punto(i, pts0[i]); }
			i--;
            if(i>0) { 
				ubicar(pts0[i][0]+','+pts0[i][1]); 
				map.setCenter(pts0[i][0], pts0[i][1]); 
				map.setZoom(16);
			} else {
				map.setCenter(8.776510716052352, -66.697998046875); 
				map.setZoom(8);
			}
		break;
	}
}

function fechas(val){ 
	document.getElementById('fecha').innerHTML = val;
}

function ubicar(coor){ 
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
		document.getElementById('ubicacion').innerHTML = dir;
	}
});
}

</script>
</head>
<body>
<label id="fecha"><?php echo date4($dia[0]);?></label><br/>
<label id="ubicacion">Ubicado: - - </label>
<div class="row">
	<div class="span11">
		<div id="map" style="width:896px; height:600px; margin-left:10px;"></div>
	</div>
</div>
</body>
</html>
<?php  }  } ?>