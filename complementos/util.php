<?php
//FUNCIONES PARA LAS FECHAS --------------------------------------------------------------------------------------
function date1($fecha){ # recibe en 0000-00-00 y devuelve 00/00/0000
	if(empty($fecha)){ 
		return "";
	} else  if(strlen($fecha)>10  && strpos($fecha,'-') && strpos($fecha,':')){ 
		list($y,$M,$d) = explode("-",substr($fecha,0,10));
		if(empty($fecha)) return ""; else return "$d/$M/$y ";
	} else if (strpos($fecha,'-')){ 
		list($year,$mes,$dia) = explode("-",$fecha);
		if(empty($fecha)){ return ""; } else { return $dia.'/'.$mes.'/'.$year; } 
	} else { 
		return "";
	}
} 

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

function date3($fecha){ # recibe en 0000-00-00 y devuelve 00/00/0000
	if(empty($fecha)){ 
		return "";
	} else  if(strlen($fecha)>10  && strpos($fecha,'-') && strpos($fecha,':')){ 
		list($h,$m,$s) = explode(":",substr($fecha,11,8));
		list($y,$M,$d) = explode("-",substr($fecha,0,10));
		if(($h*1)>12){ $H="PM"; $h=$h-12; } else if(($h*1)==12){ $H="PM"; } else { $H="AM"; }
		if(empty($fecha)) return ""; else return "$d/$M/$y $h:$m $H";
	} else if (strpos($fecha,'-')){ 
		list($year,$mes,$dia) = explode("-",$fecha);
		if(empty($fecha)){ return ""; } else { return $dia.'/'.$mes.'/'.$year; } 
	} else { 
		return "";
	}
} 

function horas($fecha){ # recibe en 23:59:59 y retorna 01:00 PM
if(strlen($fecha)>7){ 
  list($h,$m,$s) = explode(":",$fecha);
  if(($h*1)>12){ $H="PM"; $h=$h-12; } else if(($h*1)==12){ $H="PM"; } else { $H="AM"; }
  
  if(empty($fecha)) return ""; else return "$h:$m $H";
} else { 
  return "";
} 
}

function minutos($hora){ 
	if(empty($hora)){ 
		return 0;
	} else { 
		list($h, $m, $s) = explode(":",$hora);
		
		return $m;	
	}
}

#redondear valores numericos a dos decimales
function redondear2($valor) { 
   $float_redondeado=round($valor * 100) / 100; 
   return $float_redondeado; 
}

#redondear valores numericos a un decimales
function redondear1($valor) { 
   $float_redondeado=round($valor * 10) / 10; 
   return $float_redondeado; 
}?>