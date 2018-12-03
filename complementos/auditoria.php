<?php  

function getRealIP() {
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) return $_SERVER['HTTP_CLIENT_IP'];
	if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) return $_SERVER['HTTP_X_FORWARDED_FOR'];
	return $_SERVER['REMOTE_ADDR'];
}

function Auditoria($accion, $id){ 
$cnn_audi = pg_connect("host=localhost port=5432 dbname=nous user=postgres password=@BigNous$2014",PGSQL_CONNECT_FORCE_NEW);
$accion = str_replace("'","",$accion);
$accion = strtr(strtolower($accion), array("É" => "é", "Í" => "í", "Ó" => "ó", "Ú" => "ú", "Á" => "á", "Ç" => "ç", "Ñ" => "ñ", ));

if(isset($_SESSION['acc']['form'])) $form=$_SESSION['acc']['form']; else $form=0;
if(isset($_SESSION['miss'][6])) $nom = $_SESSION['miss'][6]; else $nom = "";
if(isset($_SESSION['miss'][7])) $tipo = $_SESSION['miss'][7]; else $tipo = 0;
if(isset($_SESSION['miss'][8])) $usu = $_SESSION['miss'][8]; else $usu = 0;

pg_query($cnn_audi, "insert into auditoria(id_usuario, id_form, fr, ip, accion, nom_u, id_tipou, id_operacion) values ($usu, $form, '".date("Y-m-d H:i:s")."', '".getRealIP()."', '$accion', '$nom', $tipo, $id)");
pg_close($cnn_audi);
} 


function filtrar_sql($sql){ 
	$sql = str_replace("<","+*+",$sql);
	$sql = str_replace(">","-*-",$sql);
	$sql = strip_tags($sql,"<> >= <="); // filtrando HTML y PHP
	$sql = pg_escape_string($sql); // filtrando Caracteres Especiales
	$sql = str_replace("''","'",$sql); // Corrigiendo Detalles
	$sql = str_replace("+*+","<",$sql);
	$sql = str_replace("-*-",">",$sql);
	
/*Filtrando Comandos Bloqueados ------------------------------------------------------- */
	$filtro = array('drop ','database ','alter ','create ','view ','functions','trigger','secuence','schema','gran ','replace','privilege','privileges',' to ',' on ','before','after',' all ','cascade','revoke','session','authorization','default',' if ','language','returns','return','declare','begin',' end',' new',' old ','then','elsif','  cast ',' row ',' for ','each','procedure','execute','loop','role ','while');
// extension, table, path, as, public
	$det="";
	$excepcion = false; 
	
	for($i=0; $i<count($filtro); $i++){ 
		$r = stripos($sql, $filtro[$i]);
		if($r!=false && $r>-1){ $det .= $filtro[$i].", ";  $excepcion = true; }
	}
	
	if($excepcion==true){ 
		Auditoria("Posible Ataque SQL Injescción Detectado: ".str_replace("'","",$sql)." Comandos Bloqueados ".$det,0);
		$det = $sql = "";
	}
/*Filtrando Comandos Bloqueados ------------------------------------------------------- */
	//echo "Filtro 4: ".$sql."<br/>";
	return $sql;
}

function filtrar_campo($tipo, $lenght, $cnt){ 
	$aux = "";
	if(strlen($cnt)>0){ 
		$cnt = strip_tags($cnt); // filtrando HTML y PHP
		if($lenght!=0) $cnt = substr($cnt, 0, $lenght); // ajustando tamaño de la cadena
		  // validando caracteres ----------------------------------------------
		$cnt =  str_split($cnt);
		switch($tipo){
			case 'string':  
				$filtro = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','ñ','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','Ñ','Á','É','Í','Ó','Ú','á','é','í','ó','ú',' ');
			break;
			case 'int':  
				$filtro = array('0','1','2','3','4','5','6','7','8','9','-'); 
			break;
			case 'todo':
				$filtro = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','ñ','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','Ñ','Á','É','Í','Ó','Ú','á','é','í','ó','ú',' ','(',')',',','.','-','_','/','#','°','@','0','1','2','3','4','5','6','7','8','9');
			break;
			case 'cadena':  
				$filtro = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','ñ','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','Ñ','Á','É','Í','Ó','Ú','á','é','í','ó','ú',' ','0','1','2','3','4','5','6','7','8','9',':','.',',','/','-','_','#','°','@','(',')');
			break;
			case 'esp':
				$filtro = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','ñ','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','Ñ','Á','É','Í','Ó','Ú','á','é','í','ó','ú',' ','(',')',',','.','-','_','/','#','°','@');
			break;
			case 'num': 
				$filtro = array('0','1','2','3','4','5','6','7','8','9','.','-'); 
			break;
			case 'date': 
				$filtro = array('0','1','2','3','4','5','6','7','8','9','/','-'); 
			break;
			case 'time': 
				$filtro = array('0','1','2','3','4','5','6','7','8','9',':','a','m','A','M','p','P'); 
			break;
			case 'datetime': 
				$filtro = array('0','1','2','3','4','5','6','7','8','9',':','a','m','A','M','p','P','-','/',' '); 
			break;
			case 'posicion': 
				$filtro = array('0','1','2','3','4','5','6','7','8','9','.',',','[',']','(',')'); 
			break;
			case 'tlf':  
				$filtro = array('0','1','2','3','4','5','6','7','8','9','-'); 
			break;
			case 'rif': 
				$filtro = array('0','1','2','3','4','5','6','7','8','9','-','j','J','g','G','p','P','n','N','v','V','e','E'); 
			break;
			case 'ci': 
				$filtro = array('0','1','2','3','4','5','6','7','8','9','-','v','V','e','E'); 	
			break;
			case 'boolean':  
				$filtro = array('t','r','u','e','f','a','l','s'); 
			break;
			case 'onoff':  
				$filtro = array('o','n','f'); 
			break;
			default: 
				$filtro = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','ñ','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','Ñ','Á','É','Í','Ó','Ú','á','é','í','ó','ú',' ');
		} 
		for($i=0; $i<count($cnt); $i++) if(in_array($cnt[$i],$filtro)==false)$cnt[$i]='';
		  // validando caracteres ----------------------------------------------
		for($i=0; $i<count($cnt); $i++) $aux .= $cnt[$i];
	}
	return $aux;
}

?>