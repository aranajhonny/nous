<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");

$_SESSION['acc']['mod'] = 65;
$_SESSION['acc']['form'] = 171;
include("../complementos/permisos.php");

if(isset($_POST['guardar'])){ 
	$c = filtrar_campo('int', 6, $_SESSION['confini']['cli']);
	
	// AREAS
	$rs = pg_query($link, filtrar_sql("select id_area from areas where ( id_cliente = $c or $c = -1 ) order by descripcion asc")); 
	$r = pg_num_rows($rs);
	if($r!=false && $r>0){ while($r = pg_fetch_array($rs)){ pg_query($link, filtrar_sql("update areas set id_responsable = ".$_POST['ae_'.$r[0]]." where id_area = ".$r[0])); } } 

 	// ZONAS 
	$rs = pg_query($link, filtrar_sql("select id_zongeo from zongeo where ( id_cliente = $c or $c = -1 ) order by nombre asc"));
	$r = pg_num_rows($rs);
	if($r!=false && $r>0){ while($r = pg_fetch_array($rs)){ pg_query($link, filtrar_sql("update zongeo set id_responsable = ".$_POST['zn_'.$r[0]]." where id_zongeo = ".$r[0])); } } 
	
	Auditoria("En Configuración Inicial Se Asignaron Los Responsables",0);
	$_SESSION['mensaje3']="Responsables Asignados...";
	$_SESSION['confini']['paso'] = 6;
	
} else { 
	Auditoria("En Configuración Inicial Accedio a Asignación de Responsables",0);
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="../Legend/admin/assets/ico/apple-touch-icon-144-precomposed.png" />
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="../Legend/admin/assets/ico/apple-touch-icon-114-precomposed.png" />
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="../Legend/admin/assets/ico/apple-touch-icon-72-precomposed.png" />
<link rel="apple-touch-icon-precomposed" href="../Legend/admin/assets/ico/apple-touch-icon-57-precomposed.png" />  
<meta name="author" content="Nous Technologies" />
<link rel="shortcut icon" href="../img/icono.png" />
<link href="../Legend/admin/assets/humane/css/jackedup.css" rel="stylesheet" />
<title>.:: NousTrack ::.</title>
<?php echo "<link href='../Legend/admin/assets/bootstrap/css/bootstrap.css' rel='stylesheet'/>";?>
<link href="../Legend/admin/assets/select2/css/select2.css" rel="stylesheet"/>
<?php echo "<link href='../Legend/admin/assets/css/styles.css' rel='stylesheet'/>"; ?>
<style>
	.wrap { margin:0px;padding:0px; }
	.wrap .container { padding:0px; }
	body { background-color:#FFF; }
</style>
</head>
<body>        
<section class="wrap">
<div class="container">
<div class="row">
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
<div class="well">
<div class="header">Asignar Responsables</div>
<form name="asign" method="post" action="asignar.php" onsubmit="return validar();">
<fieldset>
<table class="table">
	<thead>
	<tr>
    	<th class="extra">Tipo</th>
    	<th>Decripción</th>
        <th>Responsable</th>
	</tr>
	</thead>
	<tbody>
<?php // AREAS 
$c = filtrar_campo('int', 6, $_SESSION['confini']['cli']);
$script="";

$rs = pg_query($link, filtrar_sql("select id_area, descripcion, id_responsable from areas where ( id_cliente = $c or $c = -1 ) order by descripcion asc")); 
$r = pg_num_rows($rs);
if($r!=false && $r>0){ while($r = pg_fetch_array($rs)){  $script.="document.getElementById('ae_".$r[0]."').value='".$r[2]."';"; ?>
	<tr>
        <td>Área</td>
        <td><?php echo $r[1];?></td>
        <td><div class="form-group"><div><select name="ae_<?php echo $r[0];?>" id="ae_<?php echo $r[0];?>" class="resps"></select></div></div></td>
    </tr>
<?php } } ?> 


<?php // ZONAS 
$rs = pg_query($link, filtrar_sql("select id_zongeo, nombre, id_responsable from zongeo where ( id_cliente = $c or $c = -1 ) order by nombre asc")); 
$r = pg_num_rows($rs);
if($r!=false && $r>0){ while($r = pg_fetch_array($rs)){  $script.="document.getElementById('zn_".$r[0]."').value='".$r[2]."';"; ?>
	<tr>
        <td>Zona Geográfica</td>
        <td><?php echo $r[1];?></td>
        <td><div class="form-group"><div><select name="zn_<?php echo $r[0];?>" id="zn_<?php echo $r[0];?>" class="resps"></select></div></div></td>
    </tr>
<?php } } ?>

	</tbody>  
</table>
<p>&nbsp;</p><p>&nbsp;</p>
<div class="row">
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
<input type="submit" name="guardar" id="guardar" value="Guardar Asignación" class="btn btn-primary btn-block"/></div></div>
</fieldset>
</form>
</div>
</div>
</div>
</div>
</section>
<p>&nbsp;</p> <p>&nbsp;</p>  
<script src="../jquery/development-bundle/jquery-1.10.2.js"></script>
<script type="text/javascript">
$(document).bind("mobileinit", function(){
		$.extend($.mobile, {autoInitializePage:false} );
	}
);</script>
<script src="../jquerymobile/jquery.mobile.custom.js"></script>
<script src="../Legend/admin/assets/bootstrap/js/bootstrap.min.js"></script>
<script src="../Legend/admin/assets/js/leftmenu.js"></script>
<script src="../Legend/admin/assets/js/theme.js"></script>
<script src="../Legend/admin/assets/humane/js/humane.min.js"></script> 
<script> 
function mensaje(texto, tipo) { 
		 var notify = 0;
		 if(tipo==1){ 
		 notify = humane.create({
             timeout: 6000,
             baseCls: 'humane-jackedup',
             addnCls: 'humane-jackedup-error'
         });
		 } else if(tipo==2){ 
		 notify = humane.create({
             timeout: 6000,
             baseCls: 'humane-jackedup',
			 addnCls: 'humane-jackedup-info'
         });
		 } else if(tipo==3){ 
		 notify = humane.create({
             timeout: 6000,
             baseCls: 'humane-jackedup',
			 addnCls: 'humane-jackedup-success'
         }); 
		 } 
         notify.log(''+texto);
}</script>


<script src="../Legend/admin/assets/select2/js/select2.min.js"></script>

<script>
function validar(){ 
	
		mensaje("Registrando...",3);
		$('#guardar').css('display','none');
	return true;
}

$(document).ready(function(){
	$(".resps").attr("disabled",true);
	dependencia_personal();
});

function dependencia_personal(){
	var code = <?php echo $_SESSION['confini']['cli'];?>;
	$.get("../combox/dependencia_personal.php", { code: code },
		function(resultado){
			if(resultado == false){ alert("Error"); }
			else {
				$(".resps").attr("disabled",false);
				$('.resps').append(resultado);	
				$(".resps").attr("value",0);
				
				<?php echo $script;?>
				$(".resps").select2();
			}
		}
	);
}
</script>

<?php 
if(isset($_SESSION['mensaje1'])){ 
	echo "<script>mensaje('".$_SESSION['mensaje1']."',1);</script>"; 
	unset($_SESSION['mensaje1']);
}

if(isset($_SESSION['mensaje2'])){ 
	echo "<script>mensaje('".$_SESSION['mensaje2']."',2);</script>"; 
	unset($_SESSION['mensaje2']);
}

if(isset($_SESSION['mensaje3'])){ 
	echo "<script>mensaje('".$_SESSION['mensaje3']."',3);</script>"; 
	unset($_SESSION['mensaje3']);
} ?>
<?php include("../complementos/closdb.php"); ?>
</body>
</html>