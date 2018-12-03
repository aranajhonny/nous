<?php
session_start();
include_once("auditoria.php");

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['inicio'])){ 
if(empty($_POST['nom'])){ 
$_SESSION['mensaje2']="Debe indicar el nombre de usuario";
header("location: ../index.php");
exit();

} else if(empty($_POST['clav'])){ 
$_SESSION['mensaje2']="Debe indicar la clave de usuario";
header("location: ../index.php");
exit();

} else { 
$_POST['nom'] = strtr(strtoupper($_POST['nom']), array("é" => "É", "í" => "Í", "ó" => "Ó", "u" => "Ú", "á" => "Á", "ç" => "Ç", "ñ" => "Ñ", ));
$_SESSION['miss'][8]=filtrar_campo('todo', 120, $_POST['nom']); 

$_POST['clav'] = strtr(strtoupper($_POST['clav']), array("é" => "É", "í" => "Í", "ó" => "Ó", "u" => "Ú", "á" => "Á", "ç" => "Ç", "ñ" => "Ñ", ));
$_SESSION['clav']=filtrar_campo('todo', 20, $_POST['clav']); 
}

} else { header("location: ../index.php"); }

if(isset($_SESSION['miss'][8]) && isset($_SESSION['clav']) ){ //*********************************************************
$cnn_ini = pg_pconnect("host=localhost port=5432 dbname=nous user=postgres password=@BigNous$2014");

// convirtiendo user y clav
$r = pg_query($cnn_ini, "select md5('".$_SESSION['clav']."')");
$r = pg_fetch_array($r, 0, PGSQL_NUM);
$nom_user = $_SESSION['miss'][8];
$clav_user = $r[0];

$r = pg_query($cnn_ini,"SELECT id_usuario, nom, clav, id_tipou, est, usuarios.id_area, usuarios.id_zona, usuarios.id_confunid, personal.id_cliente FROM usuarios, personal WHERE id_usuario = id_personal AND nom='$nom_user' LIMIT 1"); 
if(pg_num_rows($r)==1){ // SI NOMBRE DE USUARIO ENCONTRADO
$r = pg_fetch_array($r, 0, PGSQL_NUM);



if(strcmp($clav_user,$r[2])==0 ){ // SI CLAVE CORRECTA
unset($_SESSION['clav']);
unset($clav_user);


if(strcmp($r[4],"t")==0){// SI USUARIO ACTIVO
$extra="";
$qs = pg_query($cnn_ini, "select id_sess from sesiones where est='Abierta' and id_usuario = ".$r[0]);
$q = pg_num_rows($qs);
if($q!=false && $q>0){ 
	$qs = pg_fetch_array($qs);
	if( pg_query($cnn_ini, "update sesiones set est='Cerrada' where id_usuario = ".$r[0]) ){ 
		$extra = "<br/> Se ha Cerrado La Session en Otros Lugares";
	}
}


// si habre la session 
if( pg_query($cnn_ini, "insert into sesiones(id_usuario, nom, ini, ult, ip, est) values (".$r[0].", '".session_id()."','".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."','".getRealIP()."','Abierta')") ){
	//cargando datos de la session
	$_SESSION['@accedio']=TRUE;  
	$_SESSION['miss'][0] = $r[5]; //area
	$_SESSION['miss'][1] = $r[6]; //zona
	$_SESSION['miss'][2] = $r[7]; //confunid
	$_SESSION['miss'][3] = $r[8]; //cli
	$_SESSION['miss'][5] = session_id();
	$_SESSION['miss'][6] = $r[1]; //nom
	$_SESSION['miss'][7] = $r[3]; //tipo
	$_SESSION['miss'][8] = $r[0]; //ID
	
	$_SESSION['mensaje_ini']="BIENVENIDO A NousTechnologies.com<br/>[ ".$_SESSION['miss'][6]." ]".$extra; 
	Auditoria("Usuario $nom_user Ha Iniciado Sesión",$_SESSION['miss'][6]);
	
	
	include("menu.php");
	$_SESSION['miss'][4] = $menu;

	unset($menu);
	pg_close($cnn_ini);
	header("location: ../inicio/principal.php");
	exit();

} else { // si no habre la session
	Auditoria("No se logro Abrir la Session Error: ".pg_last_error($cnn_ini),0);
	unset($_SESSION['miss'][8]); 
	unset($_SESSION['clav']);
	$_SESSION['mensaje1']="Problema al Iniciar La Sesion"; 
	pg_close($cnn_ini);
	header("location: ../index.php");
	exit();
	
}






} else { //SI USUARIO INACTIVO 
Auditoria("Intento de Inicio de Sesión Usuario: ".$_SESSION['miss'][8]." - USUARIO DESACTIVADO",0);
unset($_SESSION['miss'][8]); unset($_SESSION['clav']);
$_SESSION['mensaje1']="Usuario Desactivado. NO POSSE ACCESO A LA APLICACIÓN"; 
pg_close($cnn_ini);
header("location: ../index.php");
exit();
}

} else { // SI CLAVE INCORRECTA 
Auditoria("Intento de Inicio de Sesión Usuario: ".$_SESSION['miss'][8]." - CLAVE DE USUARIO INCORRECTA",0);
unset($_SESSION['miss'][8]); unset($_SESSION['clav']);
$_SESSION['mensaje1']="Clave de Usuario Incorrecta...  ";
pg_close($cnn_ini); 
header("location: ../index.php");
exit();
}

} else { // SI NOMBRE DE USUARIO NO ENCONTRADO 
Auditoria("Intento de Inicio de Sesión Usuario: ".$_SESSION['miss'][8]." - NOMBRE DE USUARIO INCORRECTO",0);
unset($_SESSION['miss'][8]); unset($_SESSION['clav']);
$_SESSION['mensaje1']="Nombre de Usuario Incorrecto"; 
pg_close($cnn_ini);
header("location: ../index.php");
exit(); 
}

} else { //**************************************************************************************
// SI ACCESO INCORRECTO DESDE URL 
$_SESSION['mensaje1']="ACCESO INCORRECTO A LA APLICACION...   ACCESO DENEGADO";
header("Location: ../index.php");
exit(); }
?>