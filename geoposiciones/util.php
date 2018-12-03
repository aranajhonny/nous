<?php 
$colors = array('#006FDD', '#FF6317', '#000000');

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

function cordenadas4($cor){ 
	$cor = str_replace(")","",str_replace("(","",$cor));
	return $cor;
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

function date2($fecha){ # recibe en 00/00/0000 y devuelve 0000-00-00
	if(empty($fecha)){ 
		return ""; 
	} else if(strpos($fecha,'/')){ 
		list($dia,$mes,$year) = explode("/",$fecha);
		return $year.'-'.$mes.'-'.$dia; 
	} else { 
		return "";
	}
}

function date4($fecha){ 
	list($y,$M,$d) = explode("-",$fecha);
	$dias = array("Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado","Domingo");
	$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
	return $dias[(date('N', strtotime($fecha))-1)]." ".$d." de ".$meses[$M-1]." del ".$y;
}

$Panel_Estatus = "map.addControl({
        position: 'right_top',
        content: '<table align=".'"'."center".'"'."><tr><td width=".'"'."72".'"'."><label style=".'"'."color:#000;font-size:16px;".'"'.">Velocidad</label></td></tr><tr><td><label style=".'"'."color:#FC0;font-size:32px;line-height:0px;".'"'.">&bull;</label><label style=".'"'."color:#FC0;font-size:16px;".'"'.">Alto</label></td></tr><tr><td><label style=".'"'."color:#F00;font-size:32px;line-height:0px;".'"'.">&bull;</label><label style=".'"'."color:#F00;font-size:16px;".'"'.">Alarma</label></td></tr><tr><td><label style=".'"'."color:#390;font-size:32px;line-height:0px;".'"'.">&bull;</label><label style=".'"'."color:#390;font-size:16px;".'"'.">Normal</label></td></tr><tr><td><label style=".'"'."color:#00F;font-size:32px;line-height:0px;".'"'.">&bull;</label><label style=".'"'."color:#00F;font-size:16px;".'"'.">Detenido</label></td></tr></table>',
        style: {
          margin: '5px',
          padding: '1px 6px',
          border: 'solid 1px #717B87',
          background: '#fff'
        }
      });";
	  
$Panel_Estatus2 = "map.addControl({
        position: 'right_top',
        content: '<table align=".'"'."center".'"'."><tr><td width=".'"'."72".'"'."><label style=".'"'."color:#000;font-size:16px;".'"'.">Estatus</label></td></tr><tr><td><label style=".'"'."color:#00F;font-size:32px;line-height:0px;".'"'.">&bull;</label><label style=".'"'."color:#00F;font-size:16px;".'"'.">Detenido</label></td></tr><tr><td><label style=".'"'."color:#F00;font-size:32px;line-height:0px;".'"'.">&bull;</label><label style=".'"'."color:#F00;font-size:16px;".'"'.">Pánico</label></td></tr><tr><td><label style=".'"'."color:#390;font-size:32px;line-height:0px;".'"'.">&bull;</label><label style=".'"'."color:#390;font-size:16px;".'"'.">En Movimiento</label></td></tr></table>',
        style: {
          margin: '5px',
          padding: '1px 6px',
          border: 'solid 1px #717B87',
          background: '#fff'
        }
      });";

$Panel_Horario = "map.addControl({
        position: 'right_top',
        content: '<table align=".'"'."center".'"'."><tr><td width=".'"'."72".'"'."><label style=".'"'."color:#000;font-size:16px;".'"'.">Horario</label></td></tr><tr><td width=".'"'."72".'"'."><label style=".'"'."color:#006FDD;font-size:32px;line-height:0px;".'"'.">-</label><label style=".'"'."color:#006FDD;font-size:16px;".'"'."> Mañana</label></td></tr><tr><td><label style=".'"'."color:#FF6317;font-size:32px;line-height:0px;".'"'.">-</label><label style=".'"'."color:#FF6317;font-size:16px;".'"'."> Tarde</label></td></tr><tr><td><label style=".'"'."color:#000000;font-size:32px;line-height:0px;".'"'.">-</label><label style=".'"'."color:#000000;font-size:16px;".'"'."> Noche</label></td></tr></table>',
        style: {
          margin: '5px',
          padding: '1px 6px',
          border: 'solid 1px #717B87',
          background: '#fff'
        }
      });";

$Icono_Estatus = " 
var v0 = {url: '../img/verde/0-360.png',origin: new google.maps.Point(-4,4),anchor: new google.maps.Point(8,0),size: new google.maps.Size(22,22)};

var v22 = {url: '../img/verde/22.png',origin: new google.maps.Point(-4,4),anchor: new google.maps.Point(8,0),size: new google.maps.Size(22,22)};

var v45 = {url: '../img/verde/45.png',origin: new google.maps.Point(-4,4),anchor: new google.maps.Point(8,0),size: new google.maps.Size(22,22)};

var v67 = {url: '../img/verde/67.png',origin: new google.maps.Point(-4,4),anchor: new google.maps.Point(8,0),size: new google.maps.Size(22,22)};

var v90 = {url: '../img/verde/90.png',origin: new google.maps.Point(-4,4),anchor: new google.maps.Point(8,0),size: new google.maps.Size(22,22)};

var v112 = {url: '../img/verde/112.png',origin: new google.maps.Point(-4,4),anchor: new google.maps.Point(8,0),size: new google.maps.Size(22,22)};

var v135 = {url: '../img/verde/135.png',origin: new google.maps.Point(-4,4),anchor: new google.maps.Point(8,0),size: new google.maps.Size(22,22)};

var v157 = {url: '../img/verde/157.png',origin: new google.maps.Point(-4,4),anchor: new google.maps.Point(8,0),size: new google.maps.Size(22,22)};

var v180 = {url: '../img/verde/180.png',origin: new google.maps.Point(-4,4),anchor: new google.maps.Point(8,0),size: new google.maps.Size(22,22)};

var v202 = {url: '../img/verde/202.png',origin: new google.maps.Point(-4,4),anchor: new google.maps.Point(8,0),size: new google.maps.Size(22,22)};

var v225 = {url: '../img/verde/225.png',origin: new google.maps.Point(-4,4),anchor: new google.maps.Point(8,0),size: new google.maps.Size(22,22)};

var v247 = {url: '../img/verde/247.png',origin: new google.maps.Point(-4,4),anchor: new google.maps.Point(8,0),size: new google.maps.Size(22,22)};

var v270 = {url: '../img/verde/270.png',origin: new google.maps.Point(-4,4),anchor: new google.maps.Point(8,0),size: new google.maps.Size(22,22)};

var v292 = {url: '../img/verde/292.png',origin: new google.maps.Point(-4,4),anchor: new google.maps.Point(8,0),size: new google.maps.Size(22,22)};

var v315 = {url: '../img/verde/315.png',origin: new google.maps.Point(-4,4),anchor: new google.maps.Point(8,0),size: new google.maps.Size(22,22)};

var v337 = {url: '../img/verde/337.png',origin: new google.maps.Point(-4,4),anchor: new google.maps.Point(8,0),size: new google.maps.Size(22,22)};




var a0 = {url: '../img/amarillo/0-360.png',origin: new google.maps.Point(-4,4),anchor: new google.maps.Point(8,0),size: new google.maps.Size(22,22)};

var a22 = {url: '../img/amarillo/22.png',origin: new google.maps.Point(-4,4),anchor: new google.maps.Point(8,0),size: new google.maps.Size(22,22)};

var a45 = {url: '../img/amarillo/45.png',origin: new google.maps.Point(-4,4),anchor: new google.maps.Point(8,0),size: new google.maps.Size(22,22)};

var a67 = {url: '../img/amarillo/67.png',origin: new google.maps.Point(-4,4),anchor: new google.maps.Point(8,0),size: new google.maps.Size(22,22)};

var a90 = {url: '../img/amarillo/90.png',origin: new google.maps.Point(-4,4),anchor: new google.maps.Point(8,0),size: new google.maps.Size(22,22)};

var a112 = {url: '../img/amarillo/112.png',origin: new google.maps.Point(-4,4),anchor: new google.maps.Point(8,0),size: new google.maps.Size(22,22)};

var a135 = {url: '../img/amarillo/135.png',origin: new google.maps.Point(-4,4),anchor: new google.maps.Point(8,0),size: new google.maps.Size(22,22)};

var a157 = {url: '../img/amarillo/157.png',origin: new google.maps.Point(-4,4),anchor: new google.maps.Point(8,0),size: new google.maps.Size(22,22)};

var a180 = {url: '../img/amarillo/180.png',origin: new google.maps.Point(-4,4),anchor: new google.maps.Point(8,0),size: new google.maps.Size(22,22)};

var a202 = {url: '../img/amarillo/202.png',origin: new google.maps.Point(-4,4),anchor: new google.maps.Point(8,0),size: new google.maps.Size(22,22)};

var a225 = {url: '../img/amarillo/225.png',origin: new google.maps.Point(-4,4),anchor: new google.maps.Point(8,0),size: new google.maps.Size(22,22)};

var a247 = {url: '../img/amarillo/247.png',origin: new google.maps.Point(-4,4),anchor: new google.maps.Point(8,0),size: new google.maps.Size(22,22)};

var a270 = {url: '../img/amarillo/270.png',origin: new google.maps.Point(-4,4),anchor: new google.maps.Point(8,0),size: new google.maps.Size(22,22)};

var a292 = {url: '../img/amarillo/292.png',origin: new google.maps.Point(-4,4),anchor: new google.maps.Point(8,0),size: new google.maps.Size(22,22)};

var a315 = {url: '../img/amarillo/315.png',origin: new google.maps.Point(-4,4),anchor: new google.maps.Point(8,0),size: new google.maps.Size(22,22)};

var a337 = {url: '../img/amarillo/337.png',origin: new google.maps.Point(-4,4),anchor: new google.maps.Point(8,0),size: new google.maps.Size(22,22)};



var az0 = {url: '../img/004.png',origin: new google.maps.Point(0,0),anchor: new google.maps.Point(0,0),size: new google.maps.Size(9,9)};



var r0 = {url: '../img/rojo/0-360.png',origin: new google.maps.Point(-4,4),anchor: new google.maps.Point(8,0),size: new google.maps.Size(22,22)};

var r22 = {url: '../img/rojo/22.png',origin: new google.maps.Point(-4,4),anchor: new google.maps.Point(8,0),size: new google.maps.Size(22,22)};

var r45 = {url: '../img/rojo/45.png',origin: new google.maps.Point(-4,4),anchor: new google.maps.Point(8,0),size: new google.maps.Size(22,22)};

var r67 = {url: '../img/rojo/67.png',origin: new google.maps.Point(-4,4),anchor: new google.maps.Point(8,0),size: new google.maps.Size(22,22)};

var r90 = {url: '../img/rojo/90.png',origin: new google.maps.Point(-4,4),anchor: new google.maps.Point(8,0),size: new google.maps.Size(22,22)};

var r112 = {url: '../img/rojo/112.png',origin: new google.maps.Point(-4,4),anchor: new google.maps.Point(8,0),size: new google.maps.Size(22,22)};

var r135 = {url: '../img/rojo/135.png',origin: new google.maps.Point(-4,4),anchor: new google.maps.Point(8,0),size: new google.maps.Size(22,22)};

var r157 = {url: '../img/rojo/157.png',origin: new google.maps.Point(-4,4),anchor: new google.maps.Point(8,0),size: new google.maps.Size(22,22)};

var r180 = {url: '../img/rojo/180.png',origin: new google.maps.Point(-4,4),anchor: new google.maps.Point(8,0),size: new google.maps.Size(22,22)};

var r202 = {url: '../img/rojo/202.png',origin: new google.maps.Point(-4,4),anchor: new google.maps.Point(8,0),size: new google.maps.Size(22,22)};

var r225 = {url: '../img/rojo/225.png',origin: new google.maps.Point(-4,4),anchor: new google.maps.Point(8,0),size: new google.maps.Size(22,22)};

var r247 = {url: '../img/rojo/247.png',origin: new google.maps.Point(-4,4),anchor: new google.maps.Point(8,0),size: new google.maps.Size(22,22)};

var r270 = {url: '../img/rojo/270.png',origin: new google.maps.Point(-4,4),anchor: new google.maps.Point(8,0),size: new google.maps.Size(22,22)};

var r292 = {url: '../img/rojo/292.png',origin: new google.maps.Point(-4,4),anchor: new google.maps.Point(8,0),size: new google.maps.Size(22,22)};

var r315 = {url: '../img/rojo/315.png',origin: new google.maps.Point(-4,4),anchor: new google.maps.Point(8,0),size: new google.maps.Size(22,22)};

var r337 = {url: '../img/rojo/337.png',origin: new google.maps.Point(-4,4),anchor: new google.maps.Point(8,0),size: new google.maps.Size(22,22)};


var ult = {
url: '../img/005.png',
origin: new google.maps.Point(0,0),
anchor: new google.maps.Point(9,13),
size: new google.maps.Size(23,23)
};";



$Icono_Estatus2 = "var detenido = {
url: '../img/004.png',
origin: new google.maps.Point(0,0),
anchor: new google.maps.Point(0,0),
size: new google.maps.Size(9,9)
};

var en_movimiento = {
url: '../img/002.png',
origin: new google.maps.Point(0,0),
anchor: new google.maps.Point(0,0),
size: new google.maps.Size(9,9)
};

var panico = {
url: '../img/001.png',
origin: new google.maps.Point(0,0),
anchor: new google.maps.Point(0,0),
size: new google.maps.Size(9,9)
};";


function grados($sentido, $estatus){ 
$dir = $estatus."0.png";
	if($sentido < 11.25 || $sentido > 348.75){ $dir = $estatus."0";
	} else if($sentido > 11.25 && $sentido <= 33.75){ $dir = $estatus."22";
	} else if($sentido > 33.75 && $sentido <= 56.25){ $dir = $estatus."45";
	} else if($sentido > 56.25 && $sentido <= 78.75){ $dir = $estatus."67";
	} else if($sentido > 78.75 && $sentido <= 101.25){ $dir = $estatus."90";
	} else if($sentido > 101.25 && $sentido <= 123.75){ $dir = $estatus."112";
	} else if($sentido > 123.75 && $sentido <= 146.25){ $dir = $estatus."135";
	} else if($sentido > 146.25 && $sentido <= 168.75){ $dir = $estatus."157";
	} else if($sentido > 168.75 && $sentido <= 191.25){ $dir = $estatus."180";
	} else if($sentido > 191.25 && $sentido <= 213.75){ $dir = $estatus."202";
	} else if($sentido > 213.75 && $sentido <= 236.25){ $dir = $estatus."225";
	} else if($sentido > 236.25 && $sentido <= 258.75){ $dir = $estatus."247";
	} else if($sentido > 258.75 && $sentido <= 281.25){ $dir = $estatus."270";
	} else if($sentido > 281.25 && $sentido <= 303.75){ $dir = $estatus."292";
	} else if($sentido > 303.75 && $sentido <= 326.25){ $dir = $estatus."315";
	} else if($sentido > 326.25 && $sentido <= 348.75){ $dir = $estatus."337"; } 
	return $dir;
}

?>