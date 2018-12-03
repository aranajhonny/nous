<?php session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");
include("../geoposiciones/util.php"); 

?><!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Recorrido</title>
<script type="text/javascript" src="../jquery/development-bundle/jquery-1.10.2.js"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
<script type="text/javascript" src="../gmaps/gmaps.js"></script>
<script type="text/javascript">
    var map;
    $(document).ready(function(){ 

<?php 
$rs = pg_query($link, filtrar_sql("select confunid.codigo_principal, unidades.codigo_principal, fecha_hora_activacion, geo_posicion  from unidades, confunid, panico where unidades.id_confunid = confunid.id_confunid and unidades.id_unidad = panico.id_unidad and id_panico = ".$_SESSION['panico']));
$r = pg_fetch_array($rs);
 
$ultpos = cordenadas4($r[3]);
echo $Icono_Estatus2; ?>

	  map = new GMaps({
        el: '#map',
		zoom: 16,
		mapTypeControl: true,
		mapTypeControlOptions: {
      		style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
    	},
		streetViewControl: false,
		panControl: false,
		zoomControl: true,
		overviewMapControl: true,
        <?php echo cordenadas2($r[3]); ?>
        click: function(e){
          console.log(e);
        },
      });

<?php 
echo $Panel_Estatus2;
$punto="panico"; 
?>	  
	  
map.addMarker({
    <?php echo cordenadas2($r[3]); ?>
    title: '<?php echo $r[0]." ".$r[1]; ?>',
	icon: panico,
    infoWindow: {
		content: 'Fecha y Hora: <?php echo date1($r[2]); ?><br/><?php echo $r[0]." ".$r[1]; ?><br/>',
		maxWidth:600
    }
});
	   
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
});
</script>
</head>
<body>
  <div class="row">
    <div class="span11">
      <div id="map" style="width:870px; height:450px;"></div>
    </div>
  </div>
</body>
</html>