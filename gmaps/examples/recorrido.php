<?php session_start();
include("../../complementos/condb.php");

$fi = $_REQUEST['fi']; 
$ff = $_REQUEST['ff'];
$id = $_REQUEST['id'];


$sql="select count(id_log_gps) from log_gps where id_unidad = $id and fecha_hora_gps between '$fi 00:00:00' and '$ff 23:59:59'";


function cordenadas($cor){ 
	$cor = str_replace(")","",str_replace("(","",$cor));
	list($lat,$lng) = explode(",",$cor);
	$lat = substr($lat,0,9);
	$lng = substr($lng,0,9);
	return "lat: ".$lat.", lng: ".$lng.",";
}

function cordenadas2($cor){ 
	$cor = str_replace(")","",str_replace("(","",$cor));
	list($lat,$lng) = explode(",",$cor);
	return "lat: ".$lat.", lng: ".$lng.",";
}

function cordenadas3($cor){ 
	$cor = str_replace(")","]",str_replace("(","[",$cor));
	return $cor.",";
}

function date1($fecha){ # recibe en 0000-00-00 y devuelve 00/00/0000
if(strlen($fecha)>10){ 
list($h,$m,$s) = explode(":",substr($fecha,11,8));
list($y,$M,$d) = explode("-",substr($fecha,0,10));
if(($h*1)>12){ $H="PM"; $h=$h-12; } else if(($h*1)==12){ $H="PM"; } else { $H="AM"; }
if(empty($fecha)) return ""; else return "$d/$M/$y $h:$m $H";
} else { 
list($year,$mes,$dia) = explode("-",$fecha);
if(empty($fecha)){ return ""; } else { return $dia.'/'.$mes.'/'.$year; } 
} } ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Recorrido</title>
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
  <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
  <script type="text/javascript" src="../gmaps.js"></script>
  <script type="text/javascript">
    var map;
    $(document).ready(function(){ 
var cor = {
url: '002.png',
origin: new google.maps.Point(0,0),
anchor: new google.maps.Point(9,13),
size: new google.maps.Size(17,17)
};

var ale = {
url: '003.png',
origin: new google.maps.Point(0,0),
anchor: new google.maps.Point(9,13),
size: new google.maps.Size(17,17)
};

var err = {
url: '001.png',
origin: new google.maps.Point(0,0),
anchor: new google.maps.Point(9,13),
size: new google.maps.Size(17,17)
};
<?php 

$sql="select unidades.codigo_principal, velocidad_gps, geo_posicion, '', fecha_hora_gps, unidmed.nombre, valor_max, valor_min, valor_critico_max, confunid.codigo_principal from unidades, log_gps, confunid, sensores, tipo_sensores, unidmed where unidades.id_unidad = $id and log_gps.id_unidad = $id and unidades.id_confunid = confunid.id_confunid and log_gps.id_sensor = sensores.id_sensor and sensores.id_tipo_sensor = tipo_sensores.id_tipo_sensor and tipo_sensores.id_unidmed = unidmed.id_unidmed and fecha_hora_gps between '$fi 00:00:00' and '$ff 23:59:59' order by fecha_hora_gps asc  limit 50";

$rs=pg_query($sql); 
$r = pg_num_rows($rs);
if($r==false || $r<1){ $cor = "lat: 0, Lng: 0,";; 
} else { 
	$r=pg_fetch_array($rs); 
	$cor = cordenadas($r[2]);
}?>
      map = new GMaps({
        el: '#map',
		zoom: 11,
        <?php echo $cor;?>
        click: function(e){
          console.log(e);
        }
      });
<?php $r = pg_num_rows($rs); $tmp="";
if($r!=false && $r>0){ pg_result_seek($rs,0); 
while($r=pg_fetch_array($rs)){
	 
if($r[1]>$r[8]){ $tipo="err"; 
} else if($r[1]<$r[8] && $r[1]>=$r[6]){ $tipo="ale"; 
} else if($r[1]<$r[6]){ $tipo="cor"; }

$tmp .= cordenadas3($r[2]);

?>	  
	  map.addMarker({
        <?php echo cordenadas2($r[2]); ?>
        title: '<?php echo $r[0]; ?>',
		icon: <?php echo $tipo; ?>,
        infoWindow: {
          content: '<?php echo $r[9]." ".$r[0]; ?><br/>Velocidad: <?php echo (1*$r[1])." ".$r[5]; ?><br/>Ref. <?php echo (1*$r[7])." ".$r[5]; ?> - <?php echo (1*$r[6])." ".$r[5]; ?> <br/>Fecha y Hora: <?php echo date1($r[4]); ?><br/>',
		  maxWidth:600
        }
      });
<?php } 
$tmp = substr($tmp,0,(strlen($tmp)-1));?>
      path = [ <?php echo $tmp; ?> ];
      map.drawPolyline({
        path: path,
        strokeColor: '#F00',
        strokeOpacity: 0.9,
        strokeWeight: 2
      });
<?php } ?>	  
    });
  </script>
</head>
<body>
  <div class="row">
    <div class="span11">
      <div id="map" style="width:910px; height:600px;"></div>
    </div>
  </div>
</body>
</html>