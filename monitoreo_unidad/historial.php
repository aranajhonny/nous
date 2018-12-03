<?php 

$_SESSION['acc']['mod'] = 42;
$_SESSION['acc']['form'] = 149;
include_once("../complementos/permisos.php");

if(isset($_REQUEST['id'])==false){ 
	header("location: vacio.php");
	exit();
} else { 
	$id = filtrar_campo('int', 6, $_REQUEST['id']);
}

$tabla="<table class='table table-striped table-bordered class-managed-table'><thead><tr><th class='extra'>Estatus</th><th>Fecha y Hora</th><th>Descripción</th><th class='extra'>Valor</th></tr></thead><tbody>";


$rs = pg_query($link, filtrar_sql("select id_control, unidmed.nombre from unimedcli, unidmed, controles where unimedcli.id_unidmed = unidmed.id_unidmed and unimedcli.id_unimedcli = controles.id_unimedcli "));
$r = pg_num_rows($rs);
if($r!=false && $r>0) { 
	while($r = pg_fetch_array($rs)){ 
		$unidmed[$r[0]] = $r[1];
	}
}


$rs = pg_query($link, filtrar_sql("select id_estatus, fecha_evento, sensores.descripcion, sensores.serial, dato, estatus.nombre, sensores.id_control from log_sensor, sensores, estatus where log_sensor.id_unidad = ".$id." and sensores.id_unidad = ".$id." and log_sensor.id_sensor = sensores.id_sensor and log_sensor.id_estatus = estatus.id_estatu and (sensores.nro_tsen <> 20 and sensores.nro_tsen <> 53 and sensores.nro_tsen <> 54 and sensores.nro_tsen <> 55 ) order by fecha_evento desc limit 50")); 
$r = pg_num_rows($rs);
if($r!=false && $r>0){ while($r = pg_fetch_array($rs)){  

if($r[0]==22){$est="Por Debajo del Valor Crítico Mínimo"; 
} else if($r[0]==23){$est="Por Encima del Valor Crítico Máximo";
} else if($r[0]==20){$est="Por Debajo del Valor Mínimo";
} else if($r[0]==21){$est="Por Encima del Valor Máximo";
} else if($r[0]==24){$est="Estable"; } 
    
$tabla.="<tr><td class='extra'>".$r[5]."</td><td>".date3($r[1])."</td><td>".$r[2]." ".$r[3]."</td><td class='extra'>".$r[4]." ".$unidmed[$r[6]]."</td></tr>";

} } else { 

$tabla.="<tr><td>NO HAY LECTURAS EN EL HISTORIAL PARA ESTA UNIDAD</td><td></td><td></td><td></td></tr>";

}    

$rs = pg_query($link, filtrar_sql("select fecha_hora_gps, tipo_sensores.descripcion, sensores.serial, velocidad_gps, geo_posicion, valor_min, valor_max, valor_critico_min, valor_critico_max from log_gps, tipo_sensores, sensores where log_gps.id_unidad = ".$id." and sensores.id_unidad = ".$id." and log_gps.id_sensor = sensores.id_sensor and sensores.id_tipo_sensor = tipo_sensores.id_tipo_sensor order by fecha_hora_gps desc limit 50")); 
$r = pg_num_rows($rs);
if($r!=false && $r>0){ while($r = pg_fetch_array($rs)){  

if($r[3]<=$r[7]){$est="Por Debajo del Valor Crítico Mínimo"; 
} else if($r[3]>=$r[8]){$est="Por Encima del Valor Crítico Máximo";
} else if($r[3]<=$r[5]){$est="Por Debajo del Valor Mínimo";
} else if($r[3]>=$r[6]){$est="Por Encima del Valor Máximo";
} else {$est="Estable"; } 

  
$tabla.="<tr><td class='extra'>Estable</td><td>".date3($r[0])."</td><td>".$r[1]." Velocidad </td><td class='extra'>".$r[3]." Km/Hr</td></tr><tr><td class='extra'>".$est."</td><td>".date3($r[0])."</td><td>".$r[1]." Geoposición ".$r[2]."</td><td class='extra'>".$r[4]." Lon/Lat</td></tr>";

} } else {

$tabla.="<tr><td>NO HAY LECTURAS EN EL HISTORIAL PARA ESTA UNIDAD</td><td></td><td></td><td></td></tr>";

}     

$tabla.="</tbody><tfoot><tr><th class='extra'><input type='text' name='est' id='est' placeholder='Buscar Estatus' class='search_init'  style='width:80px;' /></th><th><input type='text' name='fecha' id='fecha' placeholder='Buscar Fecha' class='search_init'  style='width:160px;' /></th><th><input type='text' name='sens' id='sens' placeholder='Buscar Sensor' class='search_init'  style='width:160px;' /></th><th class='extra'><input type='text' name='valor' id='valor' placeholder='Buscar Valor' class='search_init'  style='width:80px;' /></th></tr></tfoot></table>";


?>