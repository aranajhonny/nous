<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");

$_SESSION['acc']['mod'] = 66;
$_SESSION['acc']['form'] = 172;
include("../complementos/permisos.php");



if(isset($_POST['guardar'])){ 
$tsen  = filtrar_campo('int', 6, $_POST['tsen']);
$cli   = filtrar_campo('int', 6,$_SESSION['instalacion']['cli']);
$tunid = filtrar_campo('int', 6,count($_SESSION['instalacion']['unids']));

if(empty($cli)){ $_SESSION['mensaje1']="Cliente No Definido";
} else if(empty($tsen)){ $_SESSION['mensaje1']="Sensores No Definidos"; 
} else if(empty($tunid)){ $_SESSION['mensaje1']="Unidades No Definidas";
} else { // si validar

// SQL base de los Sensores
$sql = "insert into sensores(id_tipo_sensor, id_cliente, id_control, id_dispositivo, id_unidad, serial, id_estatus_operacion, id_ult_alarma, id_estatus_alarma, ult_alarma, ult_valor, ult_act, descripcion, act_alarma, if_digital, nro_tsen) values";
for($i=0; $i<$tsen; $i++){ 
	if(isset($_POST["molsensor_".$i])){ 
		$tipo = $_POST["molsensor_".$i];
		$ctr = $_POST["ctr_".$i];
		$des = $_POST["des_".$i];
		$nro = $_POST["ser_".$i];
		$dig = $_POST["dig_".$i];
		$sql.="( $tipo, $cli, $ctr, IdDisp, IdUnid, '$cli-IdUnid-IdDisp-$nro', 16, 0, 0, '".date('Y-m-d')."', 0, '".date('Y-m-d')."', '$des', TRUE, $dig, $nro),";
	}
}
$sql = substr($sql,0, (strlen($sql)-1)).";";

// Agregar y Registrar Sensores
$reg=false;
for($i=0; $i<$tunid; $i++){ 
	$sql2 = str_replace("IdDisp",$_SESSION['instalacion']['disps'][$i],$sql);
	$sql2 = str_replace("IdUnid",$_SESSION['instalacion']['unids'][$i],$sql2);
	$rs = pg_query($link, filtrar_sql($sql2));
	if($rs!=false){
		$reg=true;	
		Auditoria("En Instalacion se Registraron Sensores Id de Unidad: ".$_SESSION['instalacion']['unids'][$i]." y Id de Dispositivo: ".$_SESSION['instalacion']['disps'][$i],0);
	} 
}
unset($sql);
unset($sql2);

if($reg==true){ 
	$_SESSION['mensaje3']="Instalación Generada Correctamente";
	Auditoria("Instalación Generada Correctamente", 0);
	$terminar = true;
	
} else { 
	$_SESSION['mensaje1']="No Se Logro Generar La Instalación";
	Auditoria("Problema al generar la Instalación", 0);
}

} // si validar
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
<link href="../Legend/admin/assets/icheck/skins/square/_all.css" rel="stylesheet"/>

<style>
	.wrap { margin:0px;padding:0px; }
	.wrap .container { padding:0px; }
	body { background-color:#FFF; }
</style>
</head>
<body>  

<?php if(isset($terminar)){ 
$_SESSION['report'] = $_SESSION['instalacion']; ?>
<script>
window.open('reporte.php','_parent');
</script>
<?php } ?>
      
<section class="wrap">
<div class="container">
<div class="row">
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
<div class="well">
<div class="header">Listado de Sensores</div>
<form name="agregar" method="post" action="sensores_listado.php" onsubmit="return validar();">
<fieldset>
<table class="table">
<thead>
  <tr>
    <th style="max-width:30px;"><div class="skin skin-square skin-section checkbox icheck">#.- <label for="square-checkbox-2" class="icheck" style="margin-left:10px;"><input tabindex="6" type="checkbox" name="todos" id="todos"/></label></div></th>
    <th>Nro - Sensor </th>
    <th style="max-width:300px; width:300px;">Control</th>
  </tr>
</thead>
<tbody>

<?php
$ops = "<option value='0'>Seleccione un control</option>";
$rs = pg_query($link, filtrar_sql("select id_control, nombre from controles where id_cliente = ".$_SESSION['instalacion']['cli']." order by nombre asc "));
while($r = pg_fetch_array($rs)){ $ops.="<option value='".$r[0]."'>".$r[1]."</option>"; }

$rs = pg_query($link, filtrar_sql("select id_molsensor, des, nro, id_tipo_sensor, if_digital  from modelo_sensor order by des asc")); 
$r = pg_num_rows($rs);
if($r!=false && $r>0){ $i=0;
while($r = pg_fetch_array($rs)){ ?>    
<tr>
<td>
<div class="skin skin-square skin-section checkbox icheck">
<?php echo ($i+1)." ";?>.-
<label for="square-checkbox-2" class="icheck" style="margin-left:10px;">
<input tabindex="6" type="checkbox" name="molsensor_<?php echo $i;?>" id="molsensor_<?php echo $i;?>" class="modsensor" value="<?php echo $r[3];?>" />
</label></div></td>
<td style="padding-top:20px;"><?php echo $r[2]." - ".$r[1];?>
<input type="hidden" name="des_<?php echo $i;?>" id="des_<?php echo $i;?>" value="<?php echo $r[1];?>" />
<input type="hidden" name="ser_<?php echo $i;?>" id="ser_<?php echo $i;?>" value="<?php echo $r[2];?>" />
<input type="hidden" name="dig_<?php echo $i;?>" id="dig_<?php echo $i;?>" 
value="<?php echo  str_replace('t','true',str_replace('f','false',$r[4]));?>" />
</td>
<td>
<div class="form-group"><div>
<select name="ctr_<?php echo $i;?>" id="ctr_<?php echo $i;?>" style="max-width:300px;"><?php echo $ops;?></select>
</div></div>
</td>
</tr>
<?php $i++; } } ?>
</tbody>
</table>
<input type="hidden" name="tsen" id="tsen" value="<?php echo $i;?>" />
<p>&nbsp;</p><p>&nbsp;</p>
<div class="row">
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
<input type="submit" name="guardar" id="guardar" value="GUARDAR INSTALACIÓN" class="btn btn-primary btn-block"/></div></div>
</form>

<script>
function validar(){
	var val = false;
	for(i=0; i<<?php echo $i;?>; i++){
			if(document.getElementById('molsensor_'+i).checked==true){ 
				val = true;
			}
	} 	
	
	if(val==false){ 
		mensaje("Debe Seleccionar Al Menos un Sensor",1);
	} else { 
		mensaje("Registrando...",3);
		$('#guardar').css('display','none');
	}
	
	
	return val;
}
</script>

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

<script src="../Legend/admin/assets/icheck/js/jquery.icheck.min.js"></script>
<script> 
function icheck() {
     $('.colors li').click(function () {
         var self = $(this);

         if (!self.hasClass('active')) {
             self.siblings().removeClass('active');
			 
             var skin = self.closest('.skin'),
                 color = self.attr('class') ? '-' + self.attr('class') : '',
                 checkbox = skin.data('icheckbox'),
                 checkbox_default = 'icheckbox_minimal';

             if (skin.hasClass('skin-square')) {
                 checkbox_default = 'icheckbox_square';
                 checkbox == undefined && (checkbox = 'icheckbox_square');
             };

             checkbox == undefined && (checkbox = checkbox_default);

             skin.find('input, .skin-states .state').each(function () {
                 var element = $(this).hasClass('state') ? $(this) : $(this).parent(),
                     element_class = element.attr('class').replace(checkbox, checkbox_default + color);

                 element.attr('class', element_class);
             });

             skin.data('icheckbox', checkbox_default + color);
         
             self.addClass('active');
         };
     });
     $('.skin-square input').iCheck({
         checkboxClass: 'icheckbox_square-blue',
        
         increaseArea: '20%'
     });
 }
icheck();</script>

<script>
$(document).ready(function(){
	$("#todos").on('ifChecked', function(){ marca_todo(); });
	$("#todos").on('ifUnchecked', function(){ marca_todo(); });
});

function marca_todo(){ 
	var op = document.getElementById('todos').checked;
	
	if ( op==true ){        $('.modsensor').iCheck('check');
	} else if( op==false ){ $('.modsensor').iCheck('uncheck'); } 
}
</script>

<script src="../Legend/admin/assets/select2/js/select2.min.js"></script>
<script>
<?php for($j=0; $j<$i; $j++) echo "$('#ctr_$j').select2();\n"; ?>
</script>
<script>

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