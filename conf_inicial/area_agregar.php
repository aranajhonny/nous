<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");

$_SESSION['acc']['mod'] = 65;
$_SESSION['acc']['form'] = 171;
include("../complementos/permisos.php");


if(isset($_POST['guardar'])){ 
$des = filtrar_campo('todo',250,$_POST['des']); 
$cli = filtrar_campo('int',6,$_SESSION['confini']['cli']); 
$dep = filtrar_campo('int',6,$_POST['dep']); 
if(empty($dep)) $dep=0;

if(empty($des)){ $_SESSION['mensaje1']="Debe indicar la descripción del área";
} else if(empty($cli)){ $_SESSION['mensaje1']="Debe seleccionar un cliente";
} else if(in_array(446,$_SESSION['acl'])==false){$_SESSION['mensaje']= "no posee permiso para guardar este registro";
} else { // si validar 

$rs = pg_query($link, filtrar_sql("insert into areas(descripcion, id_cliente, id_dependencia, id_responsable) values('$des', $cli, $dep, 0)"));
	if($rs==true || $rs>0){ 

		$rs = pg_query($link, filtrar_sql("select max(id_area) from areas "));
		$rs = pg_fetch_array($rs);
		Auditoria("En Configuración Inicial Agrego Área: $des",$rs[0]); 

		$_SESSION['mensaje3']="Área Agregada";
		$_SESSION['confini']['area'] = true;
		header("location: area_listado.php");
		exit();
	} else { 
		$_SESSION['mensaje1']="No se logro agregar el área";
		Auditoria("Problema al registrar el Área Error: ".pg_last_error($link),0);
	}

} // si validar
} else { 
	$des = ""; $dep = 0;
	Auditoria("En Configuración Inicial Accedio a Agregar Areas",0);
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

<script src="../complementos/utilidades.js"></script>
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

<div class="header">Agregar Área</div>
<form name="agregar" method="post" action="area_agregar.php" onsubmit="return validar();">
<fieldset>

<div class="form-group"><label>Descripción</label>
<input id="des" name="des" type="text" placeholder="Breve Descripción o Denominación del Área" class="form-control" maxlength="250" value="<?php echo $des;?>" onKeyPress="return permite(event,'todo')" onKeyUp="mayu(this)" required/></div>

<div class="form-group"><label>Dependencia</label>
<div><select id="dep" name="dep" class="selectpicker" required>
<option value="0" selected="selected">Seleccione una Dependencia de poseer</option>
<!-- LLENADO POR JAVASCRIPT -->
</select></div>
<p class="help-block">Área de la que depende organizacionalmente esta Área</p>
</div>
                                
</fieldset>
<p>&nbsp;</p><p>&nbsp;</p>
<div class="row">
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<input type="button" name="volver" value="Volver" class="btn btn-info btn-block" onclick="location.href='area_listado.php'"/></div>
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<input type="submit" name="guardar" id="guardar" value="Guardar" class="btn btn-primary btn-block"/></div></div>

</form>
</div>

<script>
function validar(){ 
	val = false;
	if(document.getElementById('des').value.length<3){ 
		mensaje("Debe indicar la descripción",1);
	} else { 
		val = true;
		mensaje("Registrando...",3);
		$('#guardar').css('display','none');
	}
		
	return val; 
}
</script>


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

<script src="../Legend/admin/assets/bootstrapmaxlength/js/bootstrap-maxlength.min.js"></script>
<script src="../Legend/admin/assets/select2/js/select2.min.js"></script>
<script>
$("#des").maxlength({ alwaysShow: true });
$("#dep").select2();</script>
<script>

$(document).ready(function(){
	dependencia_areas(); 
	$("#dep").attr("disabled",true);
});

function dependencia_areas(){
	var code = <?php echo $_SESSION['confini']['cli'];?>;
	$.get("../combox/dependencia_areas.php", { code: code },
		function(resultado){
			if(resultado == false){ alert("Error"); }
			else {
				$("#dep").attr("disabled",false);
				document.getElementById("dep").options.length=0;
				$('#dep').append(resultado);
				document.getElementById('dep').value = '<?php echo $dep;?>';			
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