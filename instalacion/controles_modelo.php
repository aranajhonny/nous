<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");

$_SESSION['acc']['mod'] = 66;
$_SESSION['acc']['form'] = 172;
include("../complementos/permisos.php");






if(isset($_POST['guardar'])){ 
$tcon  = filtrar_campo('int', 6,$_POST['tcon']);
$cli   = filtrar_campo('int', 6,$_SESSION['instalacion']['cli']);
$reg=false;

if(empty($tcon)){ $_SESSION['mensaje1']="Controles No Definidos";
} else if(empty($cli)){ $_SESSION['mensaje1']="Cliente No Definidos"; 
} else { // si validar

// SQL base de los Sensores

for($i=0; $i<$tcon; $i++){ 
	if(isset($_POST["modcontrol_".$i])){ 

list($mag, $med, $vista, $nom, $tact_min, $tact_max, $t_aviso, $tesc, $valmin, $valmax, $crimin, $crimax, $hi, $hf, $dom, $lun, $mar, $mie, $jue, $vie, $sab) = explode("::", filtrar_campo('cadena', 0, $_POST["data_".$i]));
$dom = str_replace("f","false",str_replace("t","true",$dom));
$lun = str_replace("f","false",str_replace("t","true",$lun));
$mar = str_replace("f","false",str_replace("t","true",$mar));
$mie = str_replace("f","false",str_replace("t","true",$mie));
$jue = str_replace("f","false",str_replace("t","true",$jue));
$vie = str_replace("f","false",str_replace("t","true",$vie));
$sab = str_replace("f","false",str_replace("t","true",$sab));

$mag = filtrar_campo('int', 6, $mag);
$med = filtrar_campo('int', 6, $med);
$vista = filtrar_campo('int', 6, $vista);
$nom = filtrar_campo('todo', 100, $nom);
$tact_min = filtrar_campo('int', 6,$tact_min);  
$tact_max = filtrar_campo('int', 6,$tact_max);
$t_aviso = filtrar_campo('string', 30,$t_aviso);
$tesc = filtrar_campo('int', 6, $tesc); 
$valmin = filtrar_campo('num', 12,$valmin);  
$valmax = filtrar_campo('num', 12,$valmax);
$crimin = filtrar_campo('num', 12,$crimin);  
$crimax = filtrar_campo('num', 12,$crimax);
$hi = filtrar_campo('time', 12,$hi); 
$hf = filtrar_campo('time', 12,$hf);
$lun = filtrar_campo('boolean',5,$lun);
$mar = filtrar_campo('boolean',5,$mar);
$mie = filtrar_campo('boolean',5,$mie);
$jue = filtrar_campo('boolean',5,$jue);
$vie = filtrar_campo('boolean',5,$vie);
$sab = filtrar_campo('boolean',5,$sab);
$dom = filtrar_campo('boolean',5,$dom);

// verificando y registrando la UnidMedCli
$rs = pg_query($link, filtrar_sql("select id_unimedcli from unimedcli where id_cliente = $cli and id_magnitud = $mag and id_unidmed = $med")); 
$r = pg_num_rows($rs);
if($r!=false && $r>0){ 
	$rs = pg_fetch_array($rs); 
	$unidmedcli = $rs[0];
} else { 
	pg_query($link, filtrar_sql("insert into unimedcli(id_cliente,id_magnitud,id_unidmed) values ($cli,$mag,$med)"));
	$rs = pg_query($link, filtrar_sql("select max(id_unimedcli) from unimedcli"));
	$rs = pg_fetch_array($rs); 
	$unidmedcli = $rs[0]; 
}




$rs = pg_query($link, filtrar_sql("INSERT INTO controles(nombre, tiempo_activacion_min, tiempo_activacion_max, val_maximo, val_minimo, tiempo_activacion_on_off, on_off, id_unimedcli, valor_critico_max, valor_critico_min, id_cliente, tipo_aviso, tiempo_esc, reenvios, id_vista) values ('$nom', $tact_min, $tact_max, $valmax, $valmin, 0, true, $unidmedcli, $crimax, $crimin, $cli, '$t_aviso', $tesc, 0, $vista)"));
if($rs){ 
	$reg=true;
	$rs = pg_query($link, filtrar_sql("select max(id_control) from controles"));
	$rs = pg_fetch_array($rs);
pg_query($link, filtrar_sql("INSERT INTO horalarm(hi, hf, estatus, id_cliente, id_control, dom, lun, mar, mie, jue, vie, sab) values ('$hi','$hf','Activo', $cli, ".$rs[0].", $dom, $lun, $mar, $mie, $jue, $vie, $sab)"));
	Auditoria("En Instalación se agrego Control: $nom",$rs[0]);
} else { 
	$_SESSION['mensaje1']="No se logro Generar Los Controles";
	Auditoria("Problema al registrar Modelo del Control Error: ".pg_last_error($link), 0);
}
		
	}
}

if($reg==true){ 
	$_SESSION['instalacion']['ctr'] = true;
	$_SESSION['mensaje3']="Controles Generados Correctamente";
	header("location: controles_listado.php");
	exit();
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
<section class="wrap">
<div class="container">
<div class="row">
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
<div class="well">
<div class="header">Modelos de Control</div>
<form name="agregar" method="post" action="controles_modelo.php" onsubmit="return validar();">
<fieldset>
<table class="table">
<thead>
  <tr>
    <th style="max-width:30px;"><div class="skin skin-square skin-section checkbox icheck" title="Seleccionar Todos"># .- 
    <label for="square-checkbox-2" class="icheck" style="margin-left:10px;"><input tabindex="6" type="checkbox" name="todos" id="todos" /></label></div></th>
    <th>Control</th>
  </tr>
</thead>
<tbody>
<?php
$rs = pg_query($link, filtrar_sql("select * from modelo_control order by nombre asc")); 
$r = pg_num_rows($rs);
if($r!=false && $r>0){ $i=0;
while($r = pg_fetch_array($rs)){ ?>    
<tr>
<td>
<div class="skin skin-square skin-section checkbox icheck">
<?php echo ($i+1)." ";?>.-
<label for="square-checkbox-2" class="icheck" style="margin-left:10px;">
<input tabindex="6" type="checkbox" name="modcontrol_<?php echo $i;?>" id="modcontrol_<?php echo $i;?>" class="modcontrol" value="<?php echo $r[0];?>" />
</label></div></td>
<td style="padding-top:20px;"><?php echo $r[4];?>
<input type="hidden" name="data_<?php echo $i;?>" id="data_<?php echo $i;?>" value="<?php echo $r[1]."::".$r[2]."::".$r[3]."::".$r[4]."::".$r[5]."::".$r[6]."::".$r[7]."::".$r[8]."::".$r[9]."::".$r[10]."::".$r[11]."::".$r[12]."::".$r[13]."::".$r[14]."::".$r[15]."::".$r[16]."::".$r[17]."::".$r[18]."::".$r[19]."::".$r[20]."::".$r[21];?>" readonly="readonly"/></td>
</tr>
<?php $i++; } } ?>
</tbody>
</table>
<input type="hidden" name="tcon" id="tcon" value="<?php echo $i;?>" />
<p>&nbsp;</p><p>&nbsp;</p>
<div class="row">
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<input type="button" name="volver" value="Volver" class="btn btn-info btn-block" onclick="location.href='controles_listado.php'"/></div>
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<input type="submit" name="guardar" id="guardar" value="Generar Controles" class="btn btn-primary btn-block"/></div>
</div>
</form>

<script>
function validar(){
	var val = false;
	for(i=0; i<<?php echo $i;?>; i++){
			if(document.getElementById('modcontrol_'+i).checked==true){ 
				val = true;
			}
	} 	
	
	if(val==false){ 
		mensaje("Debe Seleccionar Al Menos un Control para Generar",1);
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
icheck();
</script>

<script>
$(document).ready(function(){
	$("#todos").on('ifChecked', function(){ marca_todo(); });
	$("#todos").on('ifUnchecked', function(){ marca_todo(); });
});

function marca_todo(){ 
	var op = document.getElementById('todos').checked;
	
	if ( op==true ){        $('.modcontrol').iCheck('check');
	} else if( op==false ){ $('.modcontrol').iCheck('uncheck'); } 
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