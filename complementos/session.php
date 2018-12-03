<?php
include_once("auditoria.php");

if(isset($_SESSION['@accedio'])==FALSE){
	if (ini_get("session.use_cookies")) {
	    $params = session_get_cookie_params();
		setcookie(
			session_name(),// nombre de la sesion 
			'',// cookie a enviar (esta en blanco por que no me interesa enviar nada)
			time() - 1200,// tiempo en que expira la session 1200 seg = 20 min
			$params["path"], 
			$params["domain"],
			$params["secure"], 
			$params["httponly"]
		);
	}
	Auditoria("Acceco Indebido a la Aplicación Cierre Forzado",0);
	unset($_SESSION); 
	session_destroy();
	header("location: ../index.php?messaje_den=ACCESO DENEGADO..."); 
	exit();
	
	
} else { 
	
	$cnn_sess = pg_pconnect("host=localhost port=5432 dbname=nous user=postgres password=@BigNous$2014");

	$rs = pg_query($cnn_sess, "select id_sess, '".date('Y-m-d H:i:s')."'::timestamp > (ult + interval '30 minute') from sesiones where est='Abierta' and  nom='".$_SESSION['miss'][5]."'");
	$r = pg_num_rows($rs);
	
	if($r==false || $r<1){ // si sesion cerrada
		Auditoria("Usuario: ".$_SESSION['miss'][6]."  SESIÓN CERRADA DESDE OTRA UBICACIÓN",$_SESSION['miss'][8]);
		unset($_SESSION); 
		session_destroy();
		pg_close($cnn_sess);
		header("location: ../index.php?messaje_den=SESIÓN CERRADA DESDE OTRA UBICACIÓN"); 
		exit();
	} else { // si session abierta
		$rs = pg_fetch_array($rs);
		if($rs[1]=='t'){ // Ya paso mas de media hora
			pg_query($cnn_sess, "update sesiones set est='Cerrada' where id_sess = ".$rs[0]);
			Auditoria("Usuario: ".$_SESSION['miss'][6]."  SESIÓN CERRADA POR INACTIVIDAD ",$_SESSION['miss'][8]);
			unset($_SESSION); 
			session_destroy();
			pg_close($cnn_sess);
			header("location: ../index.php?messaje_den=SESIÓN CERRADA POR INACTIVIDAD"); 
			exit();
		} else { // actualiza la hora de la session
			pg_query($cnn_sess, "update sesiones set ult='".date('Y-m-d H:i:s')."' where id_sess = ".$rs[0]);
			pg_close($cnn_sess);
		}
	
	}
}


?>