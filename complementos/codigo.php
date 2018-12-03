<?php
session_start();
include_once("auditoria.php");

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['inicio'])){ 
	unset($_SESSION); 
	if(empty($_POST['codigo'])){ 
		$_SESSION['mensaje2']="Debe indicar un correo electronico";
		header("location: ../inicio/recuperar.php");
		exit();
	}

} 

$cnn_ini = pg_pconnect("host=localhost port=5432 dbname=nous user=postgres password=@BigNous$2014");

$codigo =  $_POST['codigo'];

$r = pg_query($cnn_ini,"SELECT usuarios.id_usuario FROM usuarios, personal WHERE id_usuario = id_personal AND token_recuperar='$codigo' LIMIT 1"); 
// SI NOMBRE DE USUARIO ENCONTRADO
if(pg_num_rows($r)==1){ 

	$rs = pg_fetch_array($r);

	$_SESSION['mensaje3']="Codigo verificado correctamente"; 

	$_SESSION['id'] = $rs[0];
	$_SESSION['codigo'] = $codigo;

	header("location: ../inicio/cambiar_clave.php"); 

	// $rs = pg_query($cnn_ini, "update personal set token_recuperar='null' where token_recuperar='$codigo'");

// SI NOMBRE DE USUARIO NO ENCONTRADO 
pg_close($cnn_ini);
} else {
	Auditoria("Intento de recuperar contraseña Usuario: ".$email." - VALOR INCORRECTO",0);
	$_SESSION['mensaje1']="Este codigo es incorrecto, verifiquelo o genere uno nuevo"; 
	pg_close($cnn_ini);
	header("location: ../inicio/codigo.php"); 
	exit(); 
}
