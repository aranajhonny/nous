<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");
include("../complementos/util.php");

$_SESSION['acc']['mod'] = 15;
$_SESSION['acc']['form'] = 20;
include("../complementos/permisos.php");

if(isset($_REQUEST['unidad'])){ $_SESSION['unidad']=filtrar_campo('int', 6, $_REQUEST['unidad']); }

if(isset($_SESSION['unidad'])){
$rs = pg_query($link, filtrar_sql("select * from unidades where id_unidad = ".$_SESSION['unidad']));
$r = pg_num_rows($rs);
if($r==false || $r<1){ 
	$_SESSION['mensaje1']="No se identifico La Unidad";
	Auditoria("Unidad No Identificado ",$_SESSION['unidad']);
	unset($_SESSION['unidad']);
	header("location: listado.php");
	exit();
} else { 
	$rs = pg_fetch_array($rs);
	$disp = $rs[1]; 
	$unid = $rs[2]; 
	$cli = $rs[3];
	$conf = $rs[4]; 
	$zona = $rs[5]; 
	$area = $rs[6]; 
	$cod = $rs[7]; 
	$conf1 = $rs[8];
	$conf2 = $rs[9]; 
	$conf3 = $rs[10]; 
	$conf4 = $rs[13]; 
	$prop = $rs[11]; 
	$prin = $rs[12];
	$est_control = $rs[15];
	$obs = $rs[16];
	$resp = $rs[18];
	$kmi = $rs[20];
	$kma = $rs[21];
	$hra = $rs[22];
	$fi = date1($rs[23]);
	
	if($prin==true){ $prin="on"; } else { $prin="off"; }
	
	$rs = pg_query($link, filtrar_sql("select rif, razon_social from clientes where id_cliente = $cli")); 
	$rs = pg_fetch_array($rs); $cli = $rs[0]." ".$rs[1]; 
	
	$rs = pg_query($link, filtrar_sql("select nombre from zongeo where id_zongeo = $zona"));
	$rs = pg_fetch_array($rs); $zona = $rs[0];
	
	$rs = pg_query($link, filtrar_sql("select descripcion from areas where id_area = $area"));
	$rs = pg_fetch_array($rs); $area = $rs[0];
	
	$rs = pg_query($link, filtrar_sql("select marcas.descripcion,  modelos.descripcion from marcas, modelos where modelos.id_marca = marcas.id_marca and id_modelo = $mod"));
	$rs = pg_fetch_array($rs); $mod = $rs[0]." - ".$rs[1];
	
	if($disp==0){ $disp="No Posee"; } else { 
	$rs = pg_query($link, filtrar_sql("select descripcion, serial from dispositivos, tipo_disp where dispositivos.id_tipo_disp = tipo_disp.id_tipo_disp and id_dispositivo = $disp"));
	$rs = pg_fetch_array($rs); $disp = $rs[0]." - ".$rs[1]; }
	
	$rs = pg_query($link, filtrar_sql("select nombre, codigo_principal, n_configuracion_01, n_configuracion_02, n_configuracion_03, n_configuracion_04 from confunid where id_confunid = $conf")); 
	$rs = pg_fetch_array($rs); $conf = $rs[1]; $eti5 = $rs[0];  $eti1 = $rs[2]; 
	$eti2 = $rs[3]; $eti3 = $rs[4]; $eti4 = $rs[5];
	
	$rs = pg_query($link, filtrar_sql("select nombre from tipo_unidades where id_tipo_unidad = $unid"));
	$rs = pg_fetch_array($rs); $unid = $rs[0];
	
	if(empty($resp)){ $resp="- -"; } else { 
	$rs = pg_query($link, filtrar_sql("select ci, nombre from personal where id_personal=$resp")); 
	$rs = pg_fetch_array($rs); $resp = $rs[0]." ".$rs[1]; } 
	
	Auditoria("Accedio Al Modulo Ver Unidades: $conf $cod",$_SESSION['unidad']);
}

} else { 
	$_SESSION['mensaje1']="No se identifico la Unidad";
	Auditoria("Unidad No Identificado ",$_SESSION['unidad']);
	unset($_SESSION['unidad']);
	header("location: listado.php");
	exit();
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
<link href="../Legend/admin/assets/boxer/css/jquery.fs.boxer.css" rel="stylesheet"/>
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
<li><a href="#">Unidades</a></li>
<li><a href="#">Unidades</a></li>
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

<div class="header">Ver Unidad<a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
<form name="agregar" method="post" action="ver.php" >
<fieldset>
<div class="fuelux">
<div id="MyWizard" class="wizard">
<ul class="steps">
<li data-target="#step1"  onclick="$('#MyWizard').wizard('selectedItem', { step: 1 });"  class="active">1.- Unidad<span class="chevron"></span></li>
<li data-target="#step2"  onclick="$('#MyWizard').wizard('selectedItem', { step: 2 });" >2.- Fotos<span class="chevron"></span></li>
</ul>
</div>
<div class="step-content">
<div class="step-pane active" id="step1">

<div class="form-group"><label>Cliente</label>
<input id="cli" name="cli" type="text" placeholder="Cliente" class="form-control" value="<?php echo $cli;?>" readonly="readonly" /></div>

<div class="form-group"><label>Zona Geográfica</label>
<input id="zona" name="zona" type="text" placeholder="Zona Geográfica" class="form-control" value="<?php echo $zona;?>" readonly="readonly" /></div>

<div class="form-group"><label>Área</label>
<input id="area" name="area" type="text" placeholder="Área" class="form-control" value="<?php echo $area;?>" readonly="readonly" /></div>

<div class="form-group"><label>Dispositivo</label>
<input id="disp" name="disp" type="text" placeholder="Dispositivo" class="form-control" value="<?php echo $disp;?>" readonly="readonly" /></div>

<div class="form-group"><label>Tipo de Unidad</label>
<input id="conf" name="conf" type="text" placeholder="Tipo de Unidad" class="form-control" value="<?php echo $conf;?>" readonly="readonly" /></div>

<div class="form-group"><label id="eti5"><?php echo $eti5;?></label>
<input id="cod" name="cod" type="text" placeholder="Código Principal" class="form-control"  value="<?php echo $cod;?>" readonly="readonly" /></div>

<div class="form-group"><label id="eti1"><?php echo $eti1;?></label>
<input id="conf1" name="conf1" type="text" placeholder="Primera Caracteristica" class="form-control" value="<?php echo $conf1;?>" readonly="readonly" /></div> 

<div class="form-group"><label id="eti2"><?php echo $eti2;?></label>
<input id="conf2" name="conf2" type="text" placeholder="Segunda  Caracteristica" class="form-control" value="<?php echo $conf2;?>" readonly="readonly" /></div> 

<div class="form-group"><label id="eti3"><?php echo $eti3;?></label>
<input id="conf3" name="conf3" type="text" placeholder="Tercera  Caracteristica" class="form-control" value="<?php echo $conf3;?>" readonly="readonly" /></div> 

<div class="form-group"><label id="eti4"><?php echo $eti4;?></label>
<input id="conf4" name="conf4" type="text" placeholder="Cuarta  Caracteristica" class="form-control" value="<?php echo $conf4;?>" readonly="readonly" /></div> 

<div class='form-group'>
<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>
<label>Km Inicial</label>
<input id="kmi" name="kmi" type="text" placeholder="Kilometraje Inicial" class="form-control" value="<?php echo $kmi;?>" readonly="readonly"/>
</div>
<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>
<label>Km Acumulado</label>
<input id='kma' name='kma' type='text' placeholder='Kilometraje Acumulado' class='form-control' value='<?php echo $kma;?>' readonly="readonly"/>
</div>  
<p>&nbsp;</p>    
</div>

<div class='form-group'>
<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>
<label>Fecha de Instalaciòn</label>
<input id='fi' name='fi' type='text' placeholder='Fecha de Instalaciòn' class='form-control' value='<?php echo $fi;?>' readonly="readonly"/>
</div> 
<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>
<label>Hrs Acumuladas</label>
<input id="hra" name="hra" type="text" placeholder="Horas Acumuladas" class="form-control" value="<?php echo $hra;?>" readonly="readonly"/>
</div>
<p>&nbsp;</p>    
</div>

<div class="form-group"><label>Responsable</label>
<input id="resp" name="resp" type="text" placeholder="Responsable de la Unidad" class="form-control" value="<?php echo $resp;?>" readonly="readonly" /></div>

<div class="form-group"><label>Estatus de Control</label>
<input id="est_control" name="est_control" type="text" placeholder="Estatus de Control" class="form-control" value="<?php echo $est_control;?>" readonly="readonly" /></div>

<div class="form-group"><label>Observaciones</label>
<textarea rows="8" name="obs" id="obs" readonly="readonly" class="form-control"><?php echo $obs; ?></textarea>
</div>

</div>
       
                                    

<div class="step-pane" id="step2">
<div class="row">
<?php $rs = pg_query($link, filtrar_sql("select id_unidimg, descripcion from unidimg where id_unidad = ".$_SESSION['unidad']." order by descripcion asc") ); 
$r = pg_num_rows($rs);
if($r==false || $r<1){ ?>
<div class="form-group"><label>No Hay Fotos</label></div>
<?php } else { $i=1; while($r=pg_fetch_array($rs)){ $dir = "vista.php?id=".$r[0]; ?>
<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6"><label><?php echo $i.".- ".$r[1]; ?></label>
<a href="<?php echo $dir;?>" class="boxer thumbnail" title="<?php echo $r[1]; ?>" rel="gallery"><img src="<?php echo $dir;?>" alt="Thumbnail One" /></a></div>
<?php $i++; } } ?>
</div>
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

<script src="../Legend/admin/assets/boxer/js/jquery.fs.boxer.min.js"></script>
<script>function lightbox() { 
	$(".boxer").boxer(); 
} lightbox();</script>

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
fueluxwizard();
</script>
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