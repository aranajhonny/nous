<?php 

include_once("auditoria.php");

$cnn_acl = pg_connect("host=localhost port=5432 dbname=nous user=postgres password=@BigNous$2014",PGSQL_CONNECT_FORCE_NEW);

unset($_SESSION['acl']); // REINICIANDO PERMISOS
$permiso = "Denegado";
$rs = pg_query($cnn_acl, filtrar_sql("select id_acc from sys_acciones where id_mod=".$_SESSION['acc']['mod']." and id_form=".$_SESSION['acc']['form']));
$r = pg_num_rows($rs);
if($r!=false && $r>0){ // SI EXISTE EL [ MODULO - FORMULARIO - BOTON ]
	$rs = pg_fetch_array($rs);
	$actual = $rs[0];
	
	$rs = pg_query($cnn_acl, filtrar_sql("select sys_acciones.id_acc, id_bot, permiso from sys_acciones, sys_acl where id_mod=".$_SESSION['acc']['mod']." and id_usu=".$_SESSION['miss'][8]." and sys_acl.id_acc = sys_acciones.id_acc order by sys_acciones.id_acc asc"));
	$r = pg_num_rows($rs);
	if($r!=false && $r>0){ // SI POSEE PERMISOS 
		$i=0;
		$enc = false;
		$_SESSION['acl'] = array();
		while( $r = pg_fetch_array($rs) ){ // CARGANDO ACL
			if($r[2]=='t'){ 
				$_SESSION['acl'][$i] = $r[0];
				if($r[1]==9) { 
					$enc = true;
				}
				$i++;
			}
		} // CARGANDO ACL
		
		if($enc==true){ // SI PERMISO DE ACCESO AL MODULO  
			if( in_array($actual,$_SESSION['acl'])==TRUE ){ 
				$permiso = "Concedido";
			} else {
				$_SESSION['mensaje1'] = "NO POSEE PERMISOS DE ACCESO AL MODULO.";
				
				$rs = pg_query($cnn_acl, filtrar_sql("select sys_modulos.nom, sys_formularios.nom from sys_modulos, sys_formularios where sys_formularios.id_mod = sys_modulos.id_mod and id_form = ".$_SESSION['acc']['form'])); $rs = pg_fetch_array($rs);
				Auditoria("NO POSEE PERMISOS DE ACCESO AL MODULO.: ".$rs[0]." ".$r[1],$_SESSION['acc']['form']);
				
				header("location: ../inicio/principal.php");
				exit();
			}
		} else { // SI NO POSEE PERMISO DE ACCESO AL MODULO
			$_SESSION['mensaje1'] = "NO POSEE PERMISOS DE ACCESO AL MODULO..";
			
			$rs = pg_query($cnn_acl, filtrar_sql("select sys_modulos.nom, sys_formularios.nom from sys_modulos, sys_formularios where sys_formularios.id_mod = sys_modulos.id_mod and id_form = ".$_SESSION['acc']['form'])); $rs = pg_fetch_array($rs);
			Auditoria("NO POSEE PERMISOS DE ACCESO AL MODULO..: ".$rs[0]." ".$r[1],$_SESSION['acc']['form']);
			
			header("location: ../inicio/principal.php");
			exit();
		}
	} else { // SI NO POSEE PERMISOS
		$_SESSION['mensaje1'] = "NO POSEE PERMISOS DE ACCESO AL MODULO...";
		
		$rs = pg_query($cnn_acl, filtrar_sql("select sys_modulos.nom, sys_formularios.nom from sys_modulos, sys_formularios where sys_formularios.id_mod = sys_modulos.id_mod and id_form = ".$_SESSION['acc']['form'])); $rs = pg_fetch_array($rs);
		Auditoria("NO POSEE PERMISOS DE ACCESO AL MODULO...: ".$rs[0]." ".$r[1],$_SESSION['acc']['form']);
		
		header("location: ../inicio/principal.php");
		exit();
	}
} else { // NO EXISTE EL [ MODULO - FORMULARIO - BOTON ]
	$_SESSION['mensaje1'] = "Permiso de Acceso NO ESTABLECIDO";
	
	$rs = pg_query($cnn_acl, filtrar_sql("select sys_modulos.nom, sys_formularios.nom from sys_modulos, sys_formularios where sys_formularios.id_mod = sys_modulos.id_mod and id_form = ".$_SESSION['acc']['form'])); $rs = pg_fetch_array($rs);
	Auditoria("PERMISOS DE ACCESO NO ESTABLECIDOS PARA EL MODULO: ".$rs[0]." ".$r[1],$_SESSION['acc']['form']);
	
	header("location: ../inicio/principal.php");
	exit();
} 

pg_close($cnn_acl);
unset($_SESSION['acc']['mod']); ?>