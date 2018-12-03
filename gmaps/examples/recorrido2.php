<?php session_start();

$link = pg_pconnect("host=localhost port=5432 dbname=nous user=postgres password=@BigNous$2014");
$fi = $_REQUEST['fi']; 
$ff = $_REQUEST['ff'];
$id = $_REQUEST['id'];


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
} }
?>
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
url: 'correcto.png',
origin: new google.maps.Point(0,0),
anchor: new google.maps.Point(18,26),
size: new google.maps.Size(34,29)
};

var ale = {
url: 'alerta.png',
origin: new google.maps.Point(0,0),
anchor: new google.maps.Point(18,26),
size: new google.maps.Size(34,29)
};

var err = {
url: 'incorrecto.png',
origin: new google.maps.Point(0,0),
anchor: new google.maps.Point(18,26),
size: new google.maps.Size(34,29)
};
<?php 

$sql="select geo_posicion, confunid.codigo_principal, unidades.codigo_principal, 
valor_min, valor_max, valor_critico_min, valor_critico_max, 
fecha_hora_gps, velocidad_gps, unidmed.nombre  
from unidades, log_gps, confunid, sensores, tipo_sensores, unidmed 
where 
unidades.id_unidad = $id and log_gps.id_unidad = $id and unidades.id_confunid = confunid.id_confunid and log_gps.id_sensor = sensores.id_sensor and sensores.id_tipo_sensor = tipo_sensores.id_tipo_sensor and tipo_sensores.id_unidmed = unidmed.id_unidmed and fecha_hora_gps between '$fi 00:00:00' and '$ff 23:59:59' 
order by fecha_hora_gps asc limit 10";



$rs=pg_query($sql); 
$r = pg_num_rows($rs);
if($r==false || $r<1){ $cor = "lat: 0, Lng: 0,";; 
} else { 
	$r=pg_fetch_array($rs); 
	$cor = cordenadas($r[0]);
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
	 
//if($r[8]>$r[3]){ $tipo="err"; 
//} else if($r[1]<$r[8] && $r[1]>=$r[6]){ $tipo="ale"; 
//} else if($r[1]<$r[6]){ $tipo="cor"; }

$tipo="err"; 

$tmp .= cordenadas3($r[0]);

?>	  
	  map.addMarker({
        <?php echo cordenadas2($r[0]); ?>
        title: '<?php echo $r[1]." ".$r[2]; ?>',
		icon: <?php echo $tipo; ?>,
        infoWindow: {
          content: '<?php echo $r[1]." ".$r[2]; ?><br/>
		  <?php echo $r[8].$r[9]; ?>  Ref. <?php echo $r[3].$r[9]; ?> - <?php echo $r[4].$r[9]; ?> <br/>
		  Fecha y Hora: <?php echo date1($r[7]); ?><br/>',
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
        strokeWeight: 4
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