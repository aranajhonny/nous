<?php session_start();
include("../complementos/condb.php"); 
include_once("../complementos/auditoria.php");
include("util.php");


$rs = pg_query($link, filtrar_sql("select count(id_unidad) from unidades where id_cliente = ".$_SESSION['miss'][3])); 
$rs = pg_fetch_array($rs);

if($rs[0]<1){ 
header("location: vacio.php");
exit();

} else {  ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Recorrido</title>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
<script type="text/javascript" src="../gmaps/gmaps.js"></script>
<script type="text/javascript">
    var map;
    $(document).ready(function(){ 

<?php echo $Icono_Estatus2; ?>

	  map = new GMaps({
        el: '#map',
		zoom: 6,
		mapTypeControl: true,
		mapTypeControlOptions: {
      		style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
    	},
		streetViewControl: false,
		panControl: false,
		zoomControl: true,
		overviewMapControl: true,
        lat: 8.776510716052352,    
        lng: -66.697998046875,
        click: function(e){
          console.log(e);
        },
      });

<?php echo $Panel_Estatus2;

$rs=pg_query($link, filtrar_sql("select dconfunid, unidades.codigo_principal, ult_act, ult_posicion, unidades.id_unidad from unidades, clientes where 
unidades.id_cliente = clientes.id_cliente and clientes.id_cliente = ".$_SESSION['miss'][3])); 
$r = pg_num_rows($rs);
if($r==false || $r<1){ $cor = "lat: 0, Lng: 0,";; 
} else { $r=pg_fetch_array($rs);  $cor = cordenadas($r[2]); }
$r = pg_num_rows($rs);
if($r!=false && $r>0){ 
pg_result_seek($rs,0); 
while ( $r=pg_fetch_array($rs) ) {  if(strlen($r[3])>5){ 

$punto="detenido";
$qs = pg_query($link, filtrar_sql("select count(id_panico) from panico where id_unidad = ".$r[4]." and id_estatus = 31")); $qs = pg_fetch_array($qs); 
if($qs[0]>0){ 
	$punto="panico"; 
} else { 
	$qs = pg_query($link, filtrar_sql("select id_log_gps, velocidad_gps from log_gps where id_unidad = ".$r[4]." order by id_log_gps desc limit 1 ")); 
	$q = pg_num_rows($qs);
	if($q!=false && $q>0){ 
		$qs = pg_fetch_array($qs);
		if($qs[1]>0){ $punto="en_movimiento"; } 
	}
}?>	  
	  
map.addMarker({
    <?php echo cordenadas2($r[3]); ?>
    title: '<?php echo $r[0]." ".$r[1]; ?>',
	icon: <?php echo $punto;?>,
    infoWindow: {
		content: 'Fecha y Hora: <?php echo date1($r[2]); ?><br/><?php echo $r[0]." ".$r[1]; ?><br/>',
		maxWidth:600
    }
});
	  
<?php } } } ?>	 

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
<?php } ?>