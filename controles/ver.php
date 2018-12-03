<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");

$_SESSION['acc']['mod'] = 21;
$_SESSION['acc']['form'] = 40;
include("../complementos/permisos.php");

if(isset($_REQUEST['control'])){ $_SESSION['control']=$_REQUEST['control']; }

if(isset($_SESSION['control'])){
$rs = pg_query("select * from controles where id_control = ".$_SESSION['control']);
$r = pg_num_rows($rs);
if($r==false || $r<1){ 
	$_SESSION['mensaje1']="No se identifico el Control del Sensor";
	Auditoria("Control del Sensor No Identificado ",$_SESSION['control']);
	unset($_SESSION['control']);
	header("location: listado.php");
	exit();
} else {
	$rs = pg_fetch_array($rs);
	$nom = $rs[1]; $unid = $rs[8]; $cli = $rs[11]; 
	$tamin = $rs[2]; $tamax = $rs[3];
	$vemin = $rs[5]; $vemax = $rs[4];
	$vcmin = $rs[10]; $vcmax = $rs[9];
	$tipo_aviso = $rs[12]; $tiempo_esc = $rs[13];
	$tipo_aviso=str_replace("SMS","Mensajeria de Texto SMS",$tipo_aviso);
	$tipo_aviso=str_replace("Correo","Correo Electrónico",$tipo_aviso);
$tipo_aviso=str_replace("Ambos","Correo Electrónico + Mensajeria de Texto SMS",$tipo_aviso);
	$rs = pg_query($link, filtrar_sql("select unidmed.nombre, magnitudes.nombre from unimedcli, unidmed, magnitudes where unimedcli.id_magnitud = magnitudes.id_magnitud and unidmed.id_magnitud = magnitudes.id_magnitud and unimedcli.id_unidmed = unidmed.id_unidmed and id_unimedcli=$unid"));
	$rs = pg_fetch_array($rs); $unid = $rs[0];  $mag = $rs[1]; $unid2 = $rs[0];
	$rs = pg_query($link, filtrar_sql("select * from horalarm where id_control = ".$_SESSION['control']." and id_cliente = ".$cli));
	$rs = pg_fetch_array($rs);
	$_SESSION['control_horalarm'] = $rs[0];
	$hi = ExtraerHora($rs[1]); $hf = ExtraerHora($rs[2]); 
	if($rs[6]=='t'){ $dom="on"; } else { $dom="off"; }
	if($rs[7]=='t'){ $lun="on"; } else { $lun="off"; }
	if($rs[8]=='t'){ $mar="on"; } else { $mar="off"; }
	if($rs[9]=='t'){ $mie="on"; } else { $mie="off"; }
	if($rs[10]=='t'){ $jue="on"; } else { $jue="off"; }
	if($rs[11]=='t'){ $vie="on"; } else { $vie="off"; }
	if($rs[12]=='t'){ $sab="on"; } else { $sab="off"; } 
	$rs = pg_query($link, filtrar_sql("select rif, razon_social from clientes where id_cliente = $cli")); 
	$rs = pg_fetch_array($rs); $cli = $rs[0]." ".$rs[1];
	Auditoria("Accedio Al Modulo Ver Control: $nom",$_SESSION['control']);
}

} else { 
	$_SESSION['mensaje1']="No se identifico el control del sensor";
	Auditoria("Control del Sensor No Identificado ",$_SESSION['control']);
	unset($_SESSION['control']);
	header("location: listado.php");
	exit();
}

function ExtraerHora($h){ 
	list($h, $m, $s) = explode(":",$h);
	if( $h > 12 ) { $h -=12; $s="PM"; } else { $h += 0; $s="AM"; }
	return $h.":".$m." ".$s;
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="../Templates/marco.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="../Legend/admin/assets/ico/apple-touch-icon-144-precomposed.png" />
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="../Legend/admin/assets/ico/apple-touch-icon-114-precomposed.png" />
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="../Legend/admin/assets/ico/apple-touch-icon-72-precomposed.png" />
<link rel="apple-touch-icon-precomposed" href="../Legend/admin/assets/ico/apple-touch-icon-57-precomposed.png" />  
<meta name="author" content="Nous Technologies" />
<link rel="shortcut icon" href="../img/icono.png" />
<link href="../Legend/admin/assets/humane/css/jackedup.css" rel="stylesheet" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>.:: NousTrack ::.</title>
<!-- InstanceEndEditable -->

<?php 
include("../complementos/panico.php");
if(isset($_SESSION['ptc'])){ ?>
<link href="../Legend/admin/assets/vex/css/vex.css" rel="stylesheet" />
<link href="../Legend/admin/assets/vex/css/vex-theme-top.css" rel="stylesheet" />
<?php } ?>

<!-- InstanceBeginEditable name="head" -->

<?php echo "<link href='../Legend/admin/assets/bootstrap/css/bootstrap.css' rel='stylesheet'/>";?>
<link href="../Legend/admin/assets/fuelux/css/fuelux.min.css" rel="stylesheet"/>
<link href="../Legend/admin/assets/icheck/skins/square/_all.css" rel="stylesheet"/>
<?php echo "<link href='../Legend/admin/assets/css/styles.css' rel='stylesheet'/>"; ?>
<link href="../Legend/admin/assets/css/leftmenu.css" rel="stylesheet"/>
<script src="../complementos/utilidades.js"></script>
<!-- InstanceEndEditable -->
</head>
<body>
<?php echo $_SESSION['miss'][4]; ?>          
<div class="overlay"></div>
<div class="controlshint" ><img src="../img/swipe.png" alt="Menu Help" /></div>
<section class="wrap">
<div class="container">
<img src="../img/logo.png" height="67" width="454" onclick="location.href='../inicio/principal.php'" /><br/>
<!-- InstanceBeginEditable name="panelsession" -->
<ol class="breadcrumb">
<li><a href="#">Controles</a></li>
<li><a href="#">Controles de Sensor</a></li>
<li><a href="#">Ver</a></li>
<li class="pull-right"><a href="" class="text-muted"><i class="fa fa-refresh"></i></a></li>
<li class="pull-right"><?php echo date('d/m/Y');?></li>
<li class="pull-right"><a href="" class="text-muted"><?php echo $_SESSION['miss'][6];?></a></li>
</ol>
<!-- InstanceEndEditable -->
<div class="row">
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
<div class="well">
<!-- InstanceBeginEditable name="formulario" -->

<div class="well">

<div class="header">Ver Control del Sensor<a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
<form name="agregar" method="post" action="ver.php" >
<fieldset>
		                    <div class="fuelux">
		                        <div id="MyWizard" class="wizard">
		                            <ul class="steps">
<li data-target="#step1"  onclick="$('#MyWizard').wizard('selectedItem', { step: 1 });"  class="active">1.- Control<span class="chevron"></span></li>
<li data-target="#step2"  onclick="$('#MyWizard').wizard('selectedItem', { step: 2 });" >2.- Horario de Alarmas<span class="chevron"></span></li>
		                            </ul>
		                        </div>
		                        <div class="step-content">
		                            <div class="step-pane active" id="step1">
                                    
                                    
                                    
                                                                    
<div class="form-group"><label>Nombre</label>
<input id="nom" name="nom" type="text" placeholder="Nombre del Control" class="form-control" value="<?php echo $nom;?>" readonly="readonly" /></div>

<div class="form-group"><label>Cliente</label>
<input id="cli" name="cli" type="text" placeholder="Cliente" class="form-control" value="<?php echo $cli;?>" readonly="readonly" /></div>

<div class="form-group"><label>Magnitud</label>
<input id="mag" name="mag" type="text" placeholder="Magnitud" class="form-control" value="<?php echo $mag;?>" readonly="readonly" /></div>

<div class="form-group"><label>Unidad de Medida</label>
<input id="unid" name="unid" type="text" placeholder="Unidad de Medida" class="form-control" value="<?php echo $unid2;?>" readonly="readonly" /></div>



<div class="form-group">
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><label>Valor Estable Máximo</label>
<input id="vemax" name="vemax" type="text" placeholder="Valor Estable Máximo para el Sensor" class="form-control" value="<?php echo $vemax." ".$unid;?>" readonly="readonly" /></div>

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><label>Valor Estable Mínimo</label>
<input id="vemin" name="vemin" type="text" placeholder="Valor Estable Mínimo para el Sensor" class="form-control" value="<?php echo $vemin." ".$unid;?>" readonly="readonly" /></div>
</div>

<div class="form-group">
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><label>Tiempo de Activación Máximo</label>
<input id="tamax" name="tamax" type="text" placeholder="Tiempo de Activación Máximo para el Sensor" class="form-control" value="<?php echo $tamax;?> Min"  readonly="readonly" /></div>

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><label>Tiempo de Activación Mínimo</label>
<input id="tamin" name="tamin" type="text" placeholder="Tiempo de Activación Mínimo para el Sensor" class="form-control" value="<?php echo $tamin;?> Min"  readonly="readonly" /></div>
</div>

<div class="form-group">
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><label>Valor Crítico Máximo</label>
<input id="vcmax" name="vcmax" type="text" placeholder="Valor Crítico Máximo para el Sensor" class="form-control" value="<?php echo $vcmax." ".$unid;?>" readonly="readonly" /></div>

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><label>Valor Crítico Mínimo</label>
<input id="vcmin" name="vcmin" type="text" placeholder="Valor Crítico Mínimo para el Sensor" class="form-control" value="<?php echo $vcmin." ".$unid;?>" readonly="readonly" /></div>
</div>
<p>&nbsp;</p>
<br/>
</div>
                                    
                                    
                                    
                                    
                                    
                                    
<div class="step-pane" id="step2">

<div class="form-group"><label>Seleccione Los Días de Activación de las Alarmas</label><br/></div>

<div class="skin skin-square skin-section checkbox icheck form-group">
<label for="square-checkbox-2" class="icheck" style="margin-right:70px;">
<input tabindex="6" type="checkbox" name="lun" id="lun" <?php if(strcmp($lun,"on")==0) echo "checked";?> disabled="disabled" /> Lunes</label>

<label for="square-checkbox-2" class="icheck" style="margin-right:65px;">
<input tabindex="6" type="checkbox" name="mar" id="mar" <?php if(strcmp($mar,"on")==0) echo "checked";?> disabled="disabled" /> Martes</label>

<label for="square-checkbox-2" class="icheck" style="margin-right:60px;">
<input tabindex="6" type="checkbox" name="mie" id="mie" <?php if(strcmp($mie,"on")==0) echo "checked";?> disabled="disabled" /> Miercoles
</label>

<label for="square-checkbox-2" class="icheck">
<input tabindex="6" type="checkbox" name="jue" id="jue" <?php if(strcmp($jue,"on")==0) echo "checked";?> disabled="disabled" /> Jueves
</label>
</div>

<div class="skin skin-square skin-section checkbox icheck form-group">
<label for="square-checkbox-2" class="icheck" style="margin-right:60px;">
<input tabindex="6" type="checkbox" name="vie" id="vie" <?php if(strcmp($vie,"on")==0) echo "checked";?> disabled="disabled" /> Viernes
</label>

<label for="square-checkbox-2" class="icheck" style="margin-right:60px;">
<input tabindex="6" type="checkbox" name="sab" id="sab" <?php if(strcmp($sab,"on")==0) echo "checked";?> disabled="disabled" /> Sabado
</label>

<label for="square-checkbox-2" class="icheck">
<input tabindex="6" type="checkbox" name="dom" id="dom" <?php if(strcmp($dom,"on")==0) echo "checked";?> disabled="disabled" /> Domingo
</label>
</div>


<div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-6"><label>Hora de Inicio</label>
<input type="text" id="hi" name="hi" value="<?php echo $hi;?>" readonly="" class="form-control" disabled="disabled" >
</div>
	        
<div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-6">
<label>Hora de Finalización</label>
<input type="text" id="hf" name="hf" value="<?php echo $hf;?>" readonly="" class="form-control"disabled="disabled" >
</div>

<div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-6"><label>Tiempo de Escalabilidad</label>
<input type="text" id="tiempo_esc" name="tiempo_esc" value="<?php echo $tiempo_esc;?> Min." readonly="" class="form-control" disabled="disabled" >
</div>
	        
<div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-6">
<label>Tipo de Aviso</label>
<input type="text" id="tipo_aviso" name="tipo_aviso" value="<?php echo $tipo_aviso;?>" readonly="" class="form-control"disabled="disabled">
</div>

<p>&nbsp;</p><br/>
</div>
		                            
		                        </div>
		                        <br>
<button type="button" class="btn btn-default" id="btnWizardPrev">Ant.</button>
<button type="button" class="btn btn-primary" id="btnWizardNext">Sig.</button>
		                    </div>                                    
</fieldset>
<p>&nbsp;</p><p>&nbsp;</p>
<div class="row">
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<input type="button" name="volver" value="Volver" class="btn btn-info btn-block" onclick="location.href='listado.php'"/></div>
</div>

</form>
</div>

<!-- InstanceEndEditable -->
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
<!-- InstanceBeginEditable name="lib" -->
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

<script src="../Legend/admin/assets/fuelux/js/all.min.js"></script>
<script src="../Legend/admin/assets/fuelux/js/loader.min.js"></script>
<script>

 function fueluxwizard() {
     $('#MyWizard').on('change', function (e, data) {
         console.log('change');
         if (data.step === 3 && data.direction === 'next') {
              //return e.preventDefault();
         }
     });
     $('#MyWizard').on('changed', function (e, data) {
         console.log('changed');
     });
     $('#MyWizard').on('finished', function (e, data) {
         console.log('finished');
     });
     $('#btnWizardPrev').on('click', function () {
         $('#MyWizard').wizard('previous');
     });
     $('#btnWizardNext').on('click', function () {
         $('#MyWizard').wizard('next', 'foo');
     });
     $('#btnWizardStep').on('click', function () {
         var item = $('#MyWizard').wizard('selectedItem');
         console.log(item.step);
     });
     $('#MyWizard').on('stepclick', function (e, data) {
         console.log('step' + data.step + ' clicked');
         if (data.step === 1) {
              //return e.preventDefault();
         }
     });

     // optionally navigate back to 2nd step
     $('#btnStep2').on('click', function (e, data) {
         $('[data-target=#step2]').trigger("click");
     });
 }
fueluxwizard();</script>
<!-- InstanceEndEditable -->

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

<?php if(isset($_SESSION['ptc'])){ ?>
<script src="../Legend/admin/assets/vex/js/vex.js"></script>
<script src="../Legend/admin/assets/vex/js/vex.dialog.js"></script>
<script>
function mostrar_ptc() {
         vex.defaultOptions.className = 'vex-theme-top';
         vex.dialog.open({
message: 'Panico Activado para La Unidad <?php echo $_SESSION['ptc']['unid'];?>',
             buttons: [
                 $.extend({}, vex.dialog.buttons.YES, {
                     text: 'Atender',
click: function(){ location.href='../panico/atender.php?pan=<?php echo $_SESSION['ptc']['id'];?>'; }
                 }),
                 $.extend({}, vex.dialog.buttons.NO, { text: 'Ignorar' })
             ]
         });
}
setTimeout('mostrar_ptc();',5);
</script>
<?php unset($_SESSION['ptc']); } ?>

<?php include("../complementos/closdb.php"); ?>
</body>
<!-- InstanceEnd --></html>