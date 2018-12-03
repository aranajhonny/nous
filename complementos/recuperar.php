<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
require '../vendor/autoload.php';
include_once("auditoria.php");

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['inicio'])){ 
	if(empty($_POST['email'])){ 
		$_SESSION['mensaje2']="Debe indicar un correo electronico";
		header("location: ../inicio/recuperar.php");
		exit();
	}

}

$cnn_ini = pg_pconnect("host=localhost port=5432 dbname=nous user=postgres password=@BigNous$2014");

// convirtiendo user y clav
$email =  $_POST['email'];

$r = pg_query($cnn_ini,"SELECT id_usuario, personal.email FROM usuarios, personal WHERE id_usuario = id_personal AND email='$email' LIMIT 1"); 
// SI NOMBRE DE USUARIO ENCONTRADO
if(pg_num_rows($r)==1){ 

	$bytes =  openssl_random_pseudo_bytes(4);
	$codigo = bin2hex($bytes);

	$rs = pg_query($cnn_ini, "update personal set token_recuperar='$codigo' where email='$email'");

	$mail = new PHPMailer;
	$mail->isSMTP();
	$mail->SMTPDebug = 0;
	$mail->Host = 'smtp.gmail.com';
	$mail->Port = 587;
	$mail->SMTPSecure = 'tls';
	$mail->SMTPAuth = true;
	$mail->Username = "jhonnyjosearana@gmail.com";
	$mail->Password = "Verano.2014";
	$mail->setFrom('admin@noustrack.com');
	$mail->addReplyTo('admin@noustrack.com', 'First Last');
	$mail->addAddress($email);
	$mail->Subject = 'Noustrack verificación';
	$mail->Body = 'su codigo de verificación es: ' .$codigo. '';
	
	if (!$mail->send()) {
	    echo "Mailer Error: " . $mail->ErrorInfo;
	}

	if($rs){ 
		$_SESSION['mensaje3']="Codigo de verificación fue enviado exitosamente revise su bandeja de entrada y/o spam";
		pg_close($cnn_ini);
		header("location: ../inicio/codigo.php"); 
	}

// SI NOMBRE DE USUARIO NO ENCONTRADO 
} else {
	Auditoria("Intento de recuperar contraseña Usuario: ".$email." - VALOR INCORRECTO",0);
	$_SESSION['mensaje1']="Email de usuario NO esta registrado"; 
	pg_close($cnn_ini);
	header("location: ../inicio/recuperar.php"); 
	exit(); 
}
