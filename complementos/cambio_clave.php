<?php
session_start();
include_once("auditoria.php");

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['inicio'])){ 
	if(empty($_POST['clave1'])){ 
		$_SESSION['mensaje2']="Debe indicar una nueva contraseña";
		header("location: ../inicio/recuperar.php");
		exit();
	}

} 
$clave1 = $_POST['clave1'];
$clave2 = $_POST['clave2'];

if ($clave1 != $clave2) {
	$_SESSION['mensaje2']="Las contraseñas no coinciden";
	header("location: ../inicio/cambiar_clave.php");
	exit();
}

$cnn_ini = pg_pconnect("host=localhost port=5432 dbname=nous user=postgres password=@BigNous$2014");

// establece md5
$rs = pg_query($link, filtrar_sql("select md5('$clave')"));
$rs = pg_fetch_array($rs);
$tmp = $rs[0]; 

$id = $_SESSION['id'];

$rs = pg_query($cnn_ini, "update usuarios set clav='$tmp' where id_usuario=$id");

if ($rs) {
	$_SESSION['mensaje3']="contraseña cambiada exitosamente"; 
	header("location: ../index.php"); 
	unset($_SESSION); 
	$rs = pg_query($cnn_ini, "update personal set token_recuperar='null' where id_personal=$id ");
	// SI NOMBRE DE USUARIO NO ENCONTRADO 
	pg_close($cnn_ini);
	exit();
}else{
$_SESSION['mensaje2']="Error al cambiar la contraseña";
	header("location: ../inicio/cambiar_clave.php");
	exit();
}


