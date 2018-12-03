<?php session_start();

include_once("auditoria.php");


$cnn_ini = pg_pconnect("host=localhost port=5432 dbname=nous user=postgres password=@BigNous$2014");

$rs = pg_query($cnn_ini, filtrar_sql("update sesiones set est='Cerrada' where id_usuario = ".$_SESSION['miss'][8]));
if($rs!=false){ 
Auditoria("Usuario: ".$_SESSION['miss'][6]." CERRO SESIÓN",$_SESSION['miss'][8]);
} else { 
Auditoria("Problema al Cerrar Sesión Error: ".pg_last_error($cnn_ini),$_SESSION['miss'][8]);
}

pg_close($cnn_ini);


if (ini_get("session.use_cookies")) {
  $params = session_get_cookie_params();
  setcookie(session_name(), '', time() - 42000,
      $params["path"], $params["domain"],
      $params["secure"], $params["httponly"]); 
}

unset($_SESSION); 
session_destroy();
header("location: ../index.php"); 
?>