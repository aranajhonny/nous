<?php 
session_start();
include("../complementos/condb.php");
include("util.php");


$_SESSION['acc']['mod'] = 41;
$_SESSION['acc']['form'] = 148;
include("../complementos/permisos.php");



if(isset($_REQUEST['unid'])==false){ 
Auditoria("En Seguimiento de Unidad Especifico Acceso Invalido Archivo Mapa de Puertas",0);
	header("location: vacio.php");
	exit();
} else { 
	
//$dia[0] = filtrar_campo('date', 10, date("Y-m-d"));
$dia[0] = filtrar_campo('date', 10, '2014-10-29');
$unid = filtrar_campo('int', 6, $_REQUEST['unid']);
$sensor = filtrar_campo('int', 6, $_REQUEST['sen']);
$rs = pg_query($link, filtrar_sql("select serial from sensores where id_sensor = $sensor"));
$rs = pg_fetch_array($rs); 
$serial = $rs[0];

$rs = pg_query($link, filtrar_sql("select ('".$dia[0]."'::date - interval '1 day')::date, ('".$dia[0]."'::date - interval '2 day')::date, ('".$dia[0]."'::date - interval '3 day')::date, ('".$dia[0]."'::date - interval '4 day')::date, ('".$dia[0]."'::date - interval '5 day')::date, ('".$dia[0]."'::date - interval '6 day')::date, ('".$dia[0]."'::date - interval '7 day')::date"));
$rs = pg_fetch_array($rs);
$dia[1] = $rs[0];
$dia[2] = $rs[1];
$dia[3] = $rs[2];
$dia[4] = $rs[3];
$dia[5] = $rs[4];
$dia[6] = $rs[5];
$dia[7] = $rs[6];

$rs = pg_query($link, filtrar_sql("select count(id_log_gps) from log_gps, log_sensor where log_gps.id_unidad = $unid and log_sensor.id_unidad = $unid and log_sensor.serial = '$serial' and fecha_hora_gps between '".$dia[7]." 00:00:00' and '".$dia[0]." 23:59:59' and fecha_evento between '".$dia[7]." 00:00:00' and '".$dia[0]." 23:59:59' and log_gps.id_transmision = log_sensor.id_transmision")); 
$rs = pg_fetch_array($rs);
if($rs[0]<1){ 

header("location: vacio.php");
exit();

} else { // SIPUNTOS




$rs = pg_query($link, filtrar_sql("select geo_posicion from log_gps, log_sensor where log_gps.id_unidad = $unid and log_sensor.id_unidad = $unid and log_sensor.serial = '$serial' and fecha_hora_gps between '".$dia[7]." 00:00:00' and '".$dia[0]." 23:59:59' and fecha_evento between '".$dia[7]." 00:00:00' and '".$dia[0]." 23:59:59' and   log_gps.id_transmision = log_sensor.id_transmision order by fecha_hora_gps desc limit 1")); $rs = pg_fetch_array($rs);
$coordenadas_iniciales = cordenadas2($rs[0]);
$ultpos = cordenadas4($rs[0]); 

// Inicializar Data de los Dias
$data = array( 
array('path'=>array('man'=>"",'tar'=>"",'noc'=>""),'punto'=>""), 
array('path'=>array('man'=>"",'tar'=>"",'noc'=>""),'punto'=>""), 
array('path'=>array('man'=>"",'tar'=>"",'noc'=>""),'punto'=>""), 
array('path'=>array('man'=>"",'tar'=>"",'noc'=>""),'punto'=>""), 
array('path'=>array('man'=>"",'tar'=>"",'noc'=>""),'punto'=>""), 
array('path'=>array('man'=>"",'tar'=>"",'noc'=>""),'punto'=>""), 
array('path'=>array('man'=>"",'tar'=>"",'noc'=>""),'punto'=>""));


for($k=0; $k<8; $k++){  // FOR PARA LOS DIAS
$data[$k]['punto']="";
$data[$k]['path']['man']="";
$data[$k]['path']['tar']="";
$data[$k]['path']['noc']="";


$rs = pg_query($link, filtrar_sql("select confunid.codigo_principal, unidades.codigo_principal, dato, geo_posicion, fecha_hora_gps, (fecha_hora_gps)::time, 
((fecha_hora_gps)::time >= '06:00:00'::time and (fecha_hora_gps)::time <= '11:59:59'::time), 
((fecha_hora_gps)::time >= '12:00:00'::time and (fecha_hora_gps)::time <= '17:59:59'::time), 
((fecha_hora_gps)::time >= '18:00:00'::time  or (fecha_hora_gps)::time <= '05:59:59'::time) from unidades, log_gps, log_sensor, confunid, sensores, tipo_sensores where unidades.id_unidad=$unid and log_gps.id_unidad=$unid and log_sensor.id_unidad=$unid and  log_sensor.serial = '$serial' and fecha_hora_gps between '".$dia[$k]." 00:00:00' and '".$dia[$k]." 23:59:59' and fecha_evento between '".$dia[$k]." 00:00:00' and '".$dia[$k]." 23:59:59' and log_gps.id_transmision = log_sensor.id_transmision and unidades.id_confunid = confunid.id_confunid and log_gps.id_sensor = sensores.id_sensor and sensores.id_tipo_sensor = tipo_sensores.id_tipo_sensor order by log_gps.fecha_hora_gps asc "));

$w=0;
while($r=pg_fetch_array($rs)){ // MIENTRAS PUNTOS
// Determinar estado de la puerta
if($r[2]==2){
	$tipo = "abierta"; $tipo2="Abierta";
} else { 
	$tipo = "cerrada"; $tipo2="Cerrada";
}

list($lat,$lng) = explode(",",str_replace(")","",str_replace("(","",$r[3])));

$data[$k]['punto'] .= "[$lat, $lng, '".$r[0].' '.$r[1]."', $tipo, 'Fecha y Hora: ".date1($r[4])."<br/>Unidad: ".$r[0].' '.$r[1]."<br/>Puerta: $tipo2<br/>'],";

if($r[6]=='t'){       /*Mañana*/  $data[$k]['path']['man'] .= cordenadas3($r[3]);
} else if($r[7]=='t'){ /*Tarde*/  $data[$k]['path']['tar'] .= cordenadas3($r[3]);
} else if($r[8]=='t'){ /*Noche*/  $data[$k]['path']['noc'] .= cordenadas3($r[3]); }

$w++; } // MIENTRAS PUNTOS
} // FOR PARA LOS DIAS



for($k=0; $k<8; $k++){  // FOR PARA LOS DIAS
	if(strpos($data[$k]['path']['man'],",")!=false){ 
		$data[$k]['path']['man'] = 
		substr($data[$k]['path']['man'], 0, (strlen($data[$k]['path']['man'])-1));
	}
	if(strpos($data[$k]['path']['tar'],",")!=false){ 
		$data[$k]['path']['tar'] = 
		substr($data[$k]['path']['tar'], 0, (strlen($data[$k]['path']['tar'])-1));
	}
	if(strpos($data[$k]['path']['noc'],",")!=false){ 
		$data[$k]['path']['noc'] = 
		substr($data[$k]['path']['noc'], 0, (strlen($data[$k]['path']['noc'])-1));
	}
	if(strpos($data[$k]['punto'],",")!=false){
		$data[$k]['punto'] = substr($data[$k]['punto'], 0, (strlen($data[$k]['punto'])-1));
	}
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
<?php echo $Icono_Puertas; ?>

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
	echo $Panel_Horario; 
?>  
});

<?php 
echo "var pts0 = [".$data[0]['punto']."]; \n";
echo "var pts1 = [".$data[1]['punto']."]; \n";
echo "var pts2 = [".$data[2]['punto']."]; \n";
echo "var pts3 = [".$data[3]['punto']."]; \n";
echo "var pts4 = [".$data[4]['punto']."]; \n";
echo "var pts5 = [".$data[5]['punto']."]; \n";
echo "var pts6 = [".$data[6]['punto']."]; \n";
echo "var pts7 = [".$data[7]['punto']."]; \n";

echo "var path0_man = [".$data[0]['path']['man']."]; \n"; 
echo "var path0_tar = [".$data[0]['path']['tar']."]; \n";
echo "var path0_noc = [".$data[0]['path']['noc']."]; \n";
echo "var path1_man = [".$data[1]['path']['man']."]; \n"; 
echo "var path1_tar = [".$data[1]['path']['tar']."]; \n";
echo "var path1_noc = [".$data[1]['path']['noc']."]; \n";
echo "var path2_man = [".$data[2]['path']['man']."]; \n"; 
echo "var path2_tar = [".$data[2]['path']['tar']."]; \n";
echo "var path2_noc = [".$data[2]['path']['noc']."]; \n";
echo "var path3_man = [".$data[3]['path']['man']."]; \n"; 
echo "var path3_tar = [".$data[3]['path']['tar']."]; \n";
echo "var path3_noc = [".$data[3]['path']['noc']."]; \n";
echo "var path4_man = [".$data[4]['path']['man']."]; \n"; 
echo "var path4_tar = [".$data[4]['path']['tar']."]; \n";
echo "var path4_noc = [".$data[4]['path']['noc']."]; \n";
echo "var path5_man = [".$data[5]['path']['man']."]; \n"; 
echo "var path5_tar = [".$data[5]['path']['tar']."]; \n";
echo "var path5_noc = [".$data[5]['path']['noc']."]; \n";
echo "var path6_man = [".$data[6]['path']['man']."]; \n"; 
echo "var path6_tar = [".$data[6]['path']['tar']."]; \n";
echo "var path6_noc = [".$data[6]['path']['noc']."]; \n";
echo "var path7_man = [".$data[7]['path']['man']."]; \n"; 
echo "var path7_tar = [".$data[7]['path']['tar']."]; \n";
echo "var path7_noc = [".$data[7]['path']['noc']."]; \n"; ?>

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
		case 2: 
			limpiar();
			if(path1_man.length>0) { pain_linea('#006FDD', path1_man); }	
			if(path1_tar.length>0) { pain_linea('#FF6317', path1_tar); }
			if(path1_noc.length>0) { pain_linea('#000000', path1_noc); }
			for(i=0; i<pts1.length; i++){ pain_punto(i, pts1[i]); }
			i--;
            if(i>0) { 
				ubicar(pts1[i][0]+','+pts1[i][1]); 
				map.setCenter(pts1[i][0], pts1[i][1]); 
				map.setZoom(16);
			} else {
				map.setCenter(8.776510716052352, -66.697998046875); 
				map.setZoom(8);
			} 		
		break;
		case 3: 
			limpiar();
			if(path2_man.length>0) { pain_linea('#006FDD', path2_man); }	
			if(path2_tar.length>0) { pain_linea('#FF6317', path2_tar); }
			if(path2_noc.length>0) { pain_linea('#000000', path2_noc); }
			for(i=0; i<pts2.length; i++){ pain_punto(i, pts2[i]); }	
			i--;
            if(i>0) { 
				ubicar(pts2[i][0]+','+pts2[i][1]); 
				map.setCenter(pts2[i][0], pts2[i][1]); 
				map.setZoom(16);
			} else {
				map.setCenter(8.776510716052352, -66.697998046875); 
				map.setZoom(8);
			}		
		break;
		case 4: 
			limpiar();
			if(path3_man.length>0) { pain_linea('#006FDD', path3_man); }	
			if(path3_tar.length>0) { pain_linea('#FF6317', path3_tar); }
			if(path3_noc.length>0) { pain_linea('#000000', path3_noc); }
			for(i=0; i<pts3.length; i++){ pain_punto(i, pts3[i]); }
			i--;
            if(i>0) { 
				ubicar(pts3[i][0]+','+pts3[i][1]); 
				map.setCenter(pts3[i][0], pts3[i][1]); 
				map.setZoom(16);
			} else {
				map.setCenter(8.776510716052352, -66.697998046875); 
				map.setZoom(8);
			} 			
		break;
		case 5: 
			limpiar();
			if(path4_man.length>0) { pain_linea('#006FDD', path4_man); }	
			if(path4_tar.length>0) { pain_linea('#FF6317', path4_tar); }
			if(path4_noc.length>0) { pain_linea('#000000', path4_noc); }
			for(i=0; i<pts4.length; i++){ pain_punto(i, pts4[i]); }
			i--;
            if(i>0) { 
				ubicar(pts4[i][0]+','+pts4[i][1]); 
				map.setCenter(pts4[i][0], pts4[i][1]); 
				map.setZoom(16);
			} else {
				map.setCenter(8.776510716052352, -66.697998046875); 
				map.setZoom(8);
			}			
		break;
		case 6: 
			limpiar();
			if(path5_man.length>0) { pain_linea('#006FDD', path5_man); }	
			if(path5_tar.length>0) { pain_linea('#FF6317', path5_tar); }
			if(path5_noc.length>0) { pain_linea('#000000', path5_noc); }
			for(i=0; i<pts5.length; i++){ pain_punto(i, pts5[i]); }	
			i--;
            if(i>0) { 
				ubicar(pts5[i][0]+','+pts5[i][1]); 
				map.setCenter(pts5[i][0], pts5[i][1]); 
				map.setZoom(16);
			} else {
				map.setCenter(8.776510716052352, -66.697998046875); 
				map.setZoom(8);
			} 		
		break;
		case 7: 
			limpiar();
			if(path6_man.length>0) { pain_linea('#006FDD', path6_man); }	
			if(path6_tar.length>0) { pain_linea('#FF6317', path6_tar); }
			if(path6_noc.length>0) { pain_linea('#000000', path6_noc); }
			for(i=0; i<pts6.length; i++){ pain_punto(i, pts6[i]); }	
			i--;
            if(i>0) { 
				ubicar(pts6[i][0]+','+pts6[i][1]); 
				map.setCenter(pts6[i][0], pts6[i][1]); 
				map.setZoom(16);
			} else {
				map.setCenter(8.776510716052352, -66.697998046875); 
				map.setZoom(8);
			}	
		break;
		case 8: 
			limpiar();
			if(path7_man.length>0) { pain_linea('#006FDD', path7_man); }	
			if(path7_tar.length>0) { pain_linea('#FF6317', path7_tar); }
			if(path7_noc.length>0) { pain_linea('#000000', path7_noc); }
			for(i=0; i<pts7.length; i++){ pain_punto(i, pts7[i]); }
			i--;
            if(i>0) { 
				ubicar(pts7[i][0]+','+pts7[i][1]); 
				map.setCenter(pts7[i][0], pts7[i][1]); 
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
<input type="button" name="dia1" id="dia1" value="Actual" 
title="<?php echo date4($dia[0]);?>" onclick="fechas('<?php echo date4($dia[0]);?>');  cargar(1);" class="btn btn-info"/>
<input type="button" name="dia2" id="dia2" value="Hace 1 Dia" title="<?php echo date4($dia[1]);?>" onclick="fechas('<?php echo date4($dia[1]);?>'); cargar(2);" class="btn btn-info"/>
<input type="button" name="dia3" id="dia3" value="Hace 2 Dia" title="<?php echo date4($dia[2]);?>" onclick="fechas('<?php echo date4($dia[2]);?>'); cargar(3);" class="btn btn-info"/>
<input type="button" name="dia4" id="dia4" value="Hace 3 Dia" title="<?php echo date4($dia[3]);?>" onclick="fechas('<?php echo date4($dia[3]);?>'); cargar(4);" class="btn btn-info"/>
<input type="button" name="dia5" id="dia5" value="Hace 4 Dia" title="<?php echo date4($dia[4]);?>" onclick="fechas('<?php echo date4($dia[4]);?>'); cargar(5);" class="btn btn-info"/>
<input type="button" name="dia6" id="dia6" value="Hace 5 Dia" title="<?php echo date4($dia[5]);?>" onclick="fechas('<?php echo date4($dia[5]);?>'); cargar(6);" class="btn btn-info"/>
<input type="button" name="dia7" id="dia7" value="Hace 6 Dia" title="<?php echo date4($dia[6]);?>" onclick="fechas('<?php echo date4($dia[6]);?>'); cargar(7);" class="btn btn-info"/>
<input type="button" name="dia8" id="dia8" value="Hace 7 Dia" title="<?php echo date4($dia[7]);?>" onclick="fechas('<?php echo date4($dia[7]);?>'); cargar(8);" class="btn btn-info"/>
<br/><br/>
<label id="fecha"><?php echo date4($dia[0]);?></label><br/>
<label id="ubicacion">Ubicado: - - </label>
<div class="row">
	<div class="span11">
		<div id="map" style="width:800px; height:570px; margin-left:10px;"></div>
	</div>
</div>
</body>
</html>
<?php } } ?>