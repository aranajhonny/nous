<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");

$_SESSION['acc']['mod'] = 36;
$_SESSION['acc']['form'] = 88;
include("../complementos/permisos.php");

if(isset($_REQUEST['tipoperm'])){ $_SESSION['tipo_perm']=filtrar_campo('int', 6, $_REQUEST['tipoperm']); }

if(isset($_SESSION['tipo_perm'])){
$rs = pg_query($link, filtrar_sql("select tipo_permisos.nombre, rif, razon_social, ci, personal.nombre, dias_gestion, tipo_permisos.id_tipo_permiso, cant_doc, tipo_permisos.tipo_aviso, tipo_msj, esc, id_clasperm from personal, tipo_permisos, clientes where id_responsable_general = personal.id_personal and tipo_permisos.id_cliente = clientes.id_cliente and id_tipo_permiso=".$_SESSION['tipo_perm'])); 
$r = pg_num_rows($rs);
if($r==false || $r<1){ 
	$_SESSION['mensaje1']="No se identifico El Tipo de Permiso";
	Auditoria("Tipo de Permiso No Identificado ",$_SESSION['tipo_perm']);
	unset($_SESSION['tipo_perm']);
	header("location: listado.php");
	exit();
} else {
	$rs = pg_fetch_array($rs);
	$cli=$rs[1]." ".$rs[2]; $resp = $rs[3]." ".$rs[4]; $nom = $rs[0];  $clas = $rs[11];
	$dias=$rs[5];  $cant_doc = $rs[7]; $aviso = $rs[8]; $msj = $rs[9]; $esc = $rs[10];
	
	if($clas==0) { $clas=""; } else { 
	$rs = pg_query($link, filtrar_sql("select nom from clasperm where id = ".$clas)); 
	$rs = pg_fetch_array($rs); $clas = $rs[0]; }
	
	$rs = pg_query($link, filtrar_sql("select descripcion, estatus from req_tipperm where id_tipo_permiso=".$rs[6]));
	$r = pg_num_rows($rs);
	if($r!=false && $r>0){ $i=0; 
		while($r = pg_fetch_array($rs)){ 
			$req[0][$i] = $r[0];
			$req[1][$i] = $r[1];
			$i++;
		}
	}
	Auditoria("Accedio Al Modulo Ver Tipo de Permiso: $nom ",$_SESSION['tipoperm']);
}
} else { 
	$_SESSION['mensaje1']="No se identifico el Tipo de Permiso";
	Auditoria("Tipo de Permiso No Identificado ",$_SESSION['tipo_perm']);
	unset($_SESSION['tipo_perm']);
	header("location: listado.php");
	exit();
}?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
<link href="../Legend/admin/assets/fuelux/css/fuelux.min.css" rel="stylesheet"/>
<?php echo "<link href='../Legend/admin/assets/bootstrap/css/bootstrap.css' rel='stylesheet'/>";?>

<link href="../Legend/admin/assets/select2/css/select2.css" rel="stylesheet"/>
<link href="../Legend/admin/assets/boxer/css/jquery.fs.boxer.css" rel="stylesheet"/>
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
<li><a href="#">Tipo de Permisos</a></li>
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

<div class="header">Ver Tipo de Permiso<a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
<form name="agregar" method="post" action="ver.php">
<fieldset>
<div class="fuelux">
<div id="MyWizard" class="wizard">
<ul class="steps">
<li data-target="#step1"  onclick="$('#MyWizard').wizard('selectedItem', { step: 1 });"  class="active">1.- Tipo de Permiso<span class="chevron"></span></li>
<li data-target="#step2"  onclick="$('#MyWizard').wizard('selectedItem', { step: 2 });" >2.- Requisitos<span class="chevron"></span></li>
<li data-target="#step3"  onclick="$('#MyWizard').wizard('selectedItem', { step: 3 });" >3.- Foto<span class="chevron"></span></li>
</ul>
</div>
<div class="step-content">
<div class="step-pane active" id="step1">


<div class="form-group"><label>Cliente</label>
<input id="cli" name="cli" type="text" placeholder="Cliente" class="form-control" value="<?php echo $cli;?>" readonly="readonly" /></div>

<div class="form-group"><label>Clasificación</label>
<input id="resp" name="resp" type="text" placeholder="Clasificación del Tipo de Permiso" class="form-control" value="<?php echo $clas;?>" readonly="readonly" /></div>

<div class="form-group"><label>Responsable General</label>
<input id="resp" name="resp" type="text" placeholder="Responsable General" class="form-control" value="<?php echo $resp;?>" readonly="readonly" /></div>
                                
<div class="form-group"><label>Nombre</label>
<input id="nom" name="nom" type="text" placeholder="Nombre del Tipo de Permiso" class="form-control" value="<?php echo $nom;?>" readonly="readonly" /></div>

<div class="form-group">
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<label>Días de Gestión</label>
<input id="dias" name="dias" type="text" placeholder="Días de Gestión" class="form-control" maxlength="12" value="<?php echo $dias;?>" readonly="readonly" />
</div>
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<label>Cantidad de Documentos Por Permiso</label>
<input id="cant_doc" name="cant_doc" type="text" placeholder="Cantidad de Documentos Por Permiso" class="form-control" maxlength="12" value="<?php echo $cant_doc;?>" readonly="readonly" /><p>&nbsp;</p>
</div>
</div>

<div class="form-group">
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<label>Tipo de Aviso</label>
<input id="aviso" name="aviso" type="text" placeholder="Tipo de Aviso" class="form-control" maxlength="12" value="<?php echo $aviso;?>" readonly="readonly" />
</div>
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<label>Tipo de Mensaje</label>
<input id="msj" name="msj" type="text" placeholder="Tipo de Mensaje" class="form-control" maxlength="12" value="<?php echo $msj;?>" readonly="readonly" /><p>&nbsp;</p>
</div>
</div>

<div class="form-group">
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<label>Tiempo de Escalabilidad</label>
<input id="esc" name="esc" type="text" placeholder="Tiempo de Escalabilidad" class="form-control" maxlength="12" value="<?php echo $esc;?>" readonly="readonly" />
</div>
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</div>
</div>


<p>&nbsp;</p>
</div>
                                    
                                    
                                    
                                    
                                    
                                    
<div class="step-pane" id="step2">  
<div class="row">       
<?php $t = $i;  
if($t==0){ ?>
<div class="form-group"><label>No Hay Requisitos para este Tipo de Permiso</label></div>

<?php } else { for($i=0; $i<$t; $i++){ ?>

<div class="form-group">
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><label>Requisito Nro <?php echo ($i+1);?></label><input type="text" placeholder="Breve Descripción del Requisito" class="form-control" value="<?php echo $req[0][$i];?>" readonly="readonly" />
</div>
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><label>Estatus</label><input type="text" placeholder="Estatus del Requisito" class="form-control" value="<?php echo $req[1][$i];?>"  readonly="readonly"/></div>
</div>

<?php } } ?><br><p>&nbsp;</p>
</div>
</div>
	
    
    
    
    
    
<div class="step-pane" id="step3">
<div class="row">
<?php $rs = pg_query($link, filtrar_sql("select id_reqimg, descripcion, extension from reqimg where id_tipo_permiso = ".$_SESSION['tipo_perm']." order by descripcion asc")); 
$r = pg_num_rows($rs);
if($r==false || $r==0){ ?>
<div class="form-group"><label>No Hay Foto</label></div>
<?php } else { $i=1; $r=pg_fetch_array($rs);
$dir = "vista.php?id=".$r[0]; 

if(strpos($r[2],"image/")>-1){ ?>
<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6"><label><?php echo $i.".- ".$r[1]; ?></label>
<a href="<?php echo $dir;?>" class="boxer thumbnail" title="<?php echo $r[1]; ?>" rel="gallery"><img src="<?php echo $dir;?>" alt="Thumbnail One" /></a></div>

<?php } else { ?>
<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6"><label><?php echo $i.".- ".$r[1]; ?></label>
<a href="" target="new" class="boxer thumbnail" title="<?php echo $r[1]; ?>" rel="gallery"><img src="../img/arch_descargar.png" width="150" height="150" alt="Thumbnail One"/></a></div>

<?php } $i++; }  ?>
</div>
<p>&nbsp;</p>
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