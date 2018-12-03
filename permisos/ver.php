<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");
include("../complementos/util.php");

$_SESSION['acc']['mod'] = 37;
$_SESSION['acc']['form'] = 92;
include("../complementos/permisos.php");

if(isset($_REQUEST['perm'])){ $_SESSION['permiso']=filtrar_campo('int', 6, $_REQUEST['perm']); }

if(isset($_SESSION['permiso'])){
unset($_SESSION['tmp_req']);

$rs = pg_query($link, filtrar_sql("select tipo_permisos.nombre, serial, fecha_expedicion, fecha_vencimiento, id_estatus, is_imagen, permisos.id_cliente, id_responsable_especifico, id_unidad, id_area, id_zona, permisos.id_clasperm from permisos, tipo_permisos where permisos.id_tipo_permiso = tipo_permisos.id_tipo_permiso and id_permiso = ".$_SESSION['permiso']));
$r = pg_num_rows($rs);
if($r==false || $r<1){ 
	$_SESSION['mensaje1']="No se identifico El Permiso";
	Auditoria("Permiso No Identificado ",$_SESSION['permiso']);
	unset($_SESSION['permiso']);
	header("location: listado.php");
	exit();
} else {
	$rs = pg_fetch_array($rs);
	$tipo = $rs[0];
	$ser = $rs[1];
	$fe = date1($rs[2]);
	if($rs[3]!=NULL) $fv = date1($rs[3]); else $fv="";
	$est = $rs[4];
	$img = selecciono($rs[5]);
	$cli = $rs[6];
	$res = $rs[7];
	$unid = $rs[8];
	$area = $rs[9];
	$zona = $rs[10];
	$clas = $rs[11];
	Auditoria("Accedio Al Modulo Ver permiso: $ser",0);
	
	if($cli==0){ $cli="- -"; } else { 
	$rs=pg_query($link, filtrar_sql("select rif, razon_social from clientes where id_cliente = ".$cli." limit 1")); 
	$rs = pg_fetch_array($rs); $cli = $rs[0]." ".$rs[1]; }
	
	if($res==0){ $res="- -"; } else { 
	$rs=pg_query($link, filtrar_sql("select ci, nombre from personal where id_personal = ".$res." limit 1")); 
	$rs = pg_fetch_array($rs); $res = $rs[0]." ".$rs[1]; }
	
	if($unid==0){ $unid="- -"; } else { 
	$rs=pg_query($link, filtrar_sql("select confunid.codigo_principal, unidades.codigo_principal from unidades, confunid where id_unidad = ".$unid." limit 1")); 
	$rs = pg_fetch_array($rs); $unid = $rs[0]." ".$rs[1]; }
	
	if($area==0){ $area="- -"; } else { 
	$rs=pg_query($link, filtrar_sql("select descripcion from areas where id_area = ".$area." limit 1")); 
	$rs = pg_fetch_array($rs); $area = $rs[0]; }
	
	if($zona==0){ $zona="- -"; } else { 
	$rs=pg_query($link, filtrar_sql("select nombre from zongeo where id_zongeo = ".$zona." limit 1")); 
	$rs = pg_fetch_array($rs); $zona = $rs[0]; }
	
	if($clas==0){ $clas="- -"; } else { 
	$rs=pg_query($link, filtrar_sql("select nom from clasperm where id = ".$clas." limit 1")); 
	$rs = pg_fetch_array($rs); $clas = $rs[0]; }
	
	if($est==0){ $est="- -"; } else { 
	$rs=pg_query($link, filtrar_sql("select nombre from estatus where id_estatu = ".$est." limit 1")); 
	$rs = pg_fetch_array($rs); $est = $rs[0]; }
}
 
 
} else { 
	$_SESSION['mensaje1']="No se identifico el permiso";
	Auditoria("Permiso No Identificado ",$_SESSION['permiso']);
	unset($_SESSION['permiso']);
	header("location: listado.php");
	exit();
}

function selecciono($op){ 
	if($op=='t') return 'checked'; 
	else return '';
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
<link href="../Legend/admin/assets/boxer/css/jquery.fs.boxer.css" rel="stylesheet"/>
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
<li><a href="#">Configuración</a></li>
<li><a href="#">Usuarios</a></li>
<li><a href="#">Permisos</a></li>
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

<div class="header">Ver Permiso<a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
<form name="agregar" method="post" action="ver.php">
<fieldset>

<div class="fuelux">
<div id="MyWizard" class="wizard">
<ul class="steps">
<li data-target="#step1"  onclick="$('#MyWizard').wizard('selectedItem', { step: 1 });"  class="active">1.- Permiso<span class="chevron"></span></li>
<li data-target="#step2"  onclick="$('#MyWizard').wizard('selectedItem', { step: 2 });" >2.- Requisitos<span class="chevron"></span></li>
<li data-target="#step3"  onclick="$('#MyWizard').wizard('selectedItem', { step: 3 });" >3.- Archivos<span class="chevron"></span></li>
</ul>
</div>
<div class="step-content">
<div class="step-pane active" id="step1">

<div class="form-group"><label>Clasificación de Permiso</label>
<input id="clas" name="clas" type="text" placeholder="Clasificación de Permiso" class="form-control" value="<?php echo $clas;?>" readonly="readonly" /></div>

<div class="form-group"><label>Tipo de Permiso</label>
<input id="tipo" name="tipo" type="text" placeholder="Tipo de Permiso" class="form-control" value="<?php echo $tipo;?>" readonly="readonly" /></div>

<div class="form-group"><label>Cliente</label>
<input id="cli" name="cli" type="text" placeholder="Cliente" class="form-control" value="<?php echo $cli;?>" readonly="readonly" /></div>

<div class="form-group"><label>Unidad</label>
<input id="unid" name="unid" type="text" placeholder="Unidad" class="form-control" value="<?php echo $unid;?>" readonly="readonly" /></div>

<div class="form-group"><label>Área</label>
<input id="area" name="area" type="text" placeholder="Área a la que Pertenece la Unidad" class="form-control" value="<?php echo $area;?>" readonly="readonly" /></div>

<div class="form-group"><label>Zona</label>
<input id="zona" name="zona" type="text" placeholder="Zona a la que Pertenece la Zona" class="form-control" value="<?php echo $zona;?>" readonly="readonly" /></div>

<div class="form-group"><label>Responsable</label>
<input id="res" name="res" type="text" placeholder="Responsable" class="form-control" value="<?php echo $res;?>" readonly="readonly" /></div>

<div class="form-group"><label>Serial</label>
<input id="ser" name="ser" type="text" placeholder="Serial, Nro ó Código del Permiso" class="form-control" value="<?php echo $ser;?>" readonly="readonly" /></div>

<div class="form-group"><label>Fecha de Expedición</label>
<input id="fe" name="fe" type="text" placeholder="Fecha de Expedición" class="form-control"  value="<?php echo $fe;?>" readonly="readonly" /></div>

<div class="form-group"><label>Fecha de Vencimiento</label>
<input id="fv" name="fv" type="text" placeholder="Fecha de Vencimiento" class="form-control" value="<?php echo $fv;?>" readonly="readonly" /></div>

<div class="form-group"><label>Estatus</label>
<input id="est" name="est" type="text" placeholder="Estatus" class="form-control" value="<?php echo $est;?>" readonly="readonly" /></div>

<div class="skin skin-square skin-section checkbox icheck form-group">
<label for="square-checkbox-2" class="icheck">
<input tabindex="6" type="checkbox" name="imagen" id="imagen" <?php echo $img;?> disabled /> ¿ Usa Imagenes ?
</label>
</div>
</div>
       
                                    
                                              
<div class="step-pane" id="step2">
<?php 
$cuerpo1 = "";
$cuerpo2 = "";
if(isset($_SESSION['tmp_req'])==false){
$rs = pg_query($link, filtrar_sql("select id_reqperm, descripcion, is_doc from req_tipperm, reqperm where id_permiso = ".$_SESSION['permiso']." and reqperm.id_reqtipperm = req_tipperm.id_reqtipperm order by descripcion asc ") );
$r = pg_num_rows($rs);
if($r!=false && $r>0){ $i=0; 
while($r = pg_fetch_array($rs)){
$_SESSION['tmp_req'][$i][0]=$r[0];
$_SESSION['tmp_req'][$i][1]=$r[1];
$_SESSION['tmp_req'][$i][2]=selecciono($r[2]);
$i++; } } }

$n = count($_SESSION['tmp_req']); $i=0;
while($i<$n){ 
$cuerpo1 .= "<div class='skin skin-square skin-section checkbox icheck form-group'><label for='square-checkbox-2' class='icheck'><input tabindex='6' type='checkbox' name='ids_".$_SESSION['tmp_req'][$i][0]."' id='ids_".$_SESSION['tmp_req'][$i][0]."' ".$_SESSION['tmp_req'][$i][2]." disabled />".$_SESSION['tmp_req'][$i][1]."</label></div><br/>"; $i++;

if($i<$n){
$cuerpo2 .= "<div class='skin skin-square skin-section checkbox icheck form-group'><label for='square-checkbox-2' class='icheck'><input tabindex='6' type='checkbox' name='ids_".$_SESSION['tmp_req'][$i][0]."' id='ids_".$_SESSION['tmp_req'][$i][0]."' ".$_SESSION['tmp_req'][$i][2]." disabled />".$_SESSION['tmp_req'][$i][1]."</label></div><br/>"; $i++;  }	
}

if(empty($cuerpo1)){ $cuerpo1="<div class='form-group'><label>No Hay Requisitos</label></div>"; }

echo "<div class='row'>
<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>$cuerpo1</div>
<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>$cuerpo2</div>
</div>";

?></div>



<div class="step-pane" id="step3">
<div class="row">
<?php $rs = pg_query($link, filtrar_sql("select id_permimg, descripcion, extension from permimg where id_permiso = ".$_SESSION['permiso']." order by descripcion asc")); 
$r = pg_num_rows($rs);
if($r==false || $r<1){ ?>
<div class="form-group"><label>No Hay Fotos</label></div>
<?php } else { $i=1; while($r=pg_fetch_array($rs)){ 
$dir = "vista.php?id=".$r[0]; 

if(strpos($r[2],"image/")>-1){ ?>
<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6"><label><?php echo $i.".- ".$r[1]; ?></label>
<a href="<?php echo $dir;?>" class="boxer thumbnail" title="<?php echo $r[1]; ?>" rel="gallery"><img src="<?php echo $dir;?>" onclick="window.open('descargar_archivo.php?id=<?php echo $r[0];?>','_blank')" alt="Thumbnail One" /></a></div>

<?php } else { ?>
<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6"><label><?php echo $i.".- ".$r[1]; ?></label>
<a href="" target="new" class="boxer thumbnail" title="<?php echo $r[1]; ?>" rel="gallery"><img src="../img/arch_descargar.png" width="150" height="150" alt="Thumbnail One" onclick="window.open('descargar_archivo.php?id=<?php echo $r[0];?>','_blank')" /></a></div>

<?php } $i++; } } ?>
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