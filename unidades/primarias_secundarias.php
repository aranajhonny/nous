<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");

Auditoria("Accedio Al Modulo de Unidades Primarias y Anexas",0);

$_SESSION['acc']['mod'] = 16;
$_SESSION['acc']['form'] = 133;
include("../complementos/permisos.php");

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
<link href="../Legend/admin/assets/bootstrapdatatables/css/DT_bootstrap.css" rel="stylesheet" />
<?php echo "<link href='../Legend/admin/assets/bootstrap/css/bootstrap.css' rel='stylesheet'/>";?>
<link href="../Legend/admin/assets/select2/css/select2.css" rel="stylesheet"/>
<?php echo "<link href='../Legend/admin/assets/css/styles.css' rel='stylesheet'/>"; ?>
<link href="../Legend/admin/assets/css/leftmenu.css" rel="stylesheet"/>
<style>
div.dataTables_scrollBody, 
div.dataTables_scrollHead, 
div.dataTables_scrollFoot { max-width:920px; }

table.table thead tr th,
table.table tbody tr td,
table.table tfoot tr th { min-width:180px; }

</style>
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
<li><a href="#">Unidades Primarias y Secundarias</a></li>
<li class="pull-right"><a href="" class="text-muted"><i class="fa fa-refresh"></i></a></li>
<li class="pull-right"><?php echo date('d/m/Y');?></li>
<li class="pull-right"><a href="" class="text-muted"><?php echo $_SESSION['miss'][6];?></a></li>
</ol>
<!-- InstanceEndEditable -->
<div class="row">
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
<div class="well">
<!-- InstanceBeginEditable name="formulario" -->


<div class="header">Listado de Unidades Primarias y Secundarias
<a class="headerclose"><i class="fa fa-times pull-right"></i></a> 
<a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> 
<a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a>
</div>

<table class="table table-striped table-bordered" id="managed-table">
	<thead>
	<tr>
    	<th>Unidad Primaria</th>
        <th>Unidad Anexa</th>
		<th>Acciones</th>
	</tr>
	</thead>
	<tbody>
<?php 
$a=filtrar_campo('int', 6, $_SESSION['miss'][0]);
$z=filtrar_campo('int', 6, $_SESSION['miss'][1]);
$t=filtrar_campo('int', 6, $_SESSION['miss'][2]);
$c=filtrar_campo('int', 6, $_SESSION['miss'][3]);

$rs = pg_query($link, filtrar_sql("select unidades.id_unidad, dconfunid, unidades.codigo_principal, id_unidpri from unidades where ((unidades.id_zona = $z or $z < 1) and (unidades.id_area = $a or $a < 1) and (unidades.id_confunid = $t or $t < 1))
order by dconfunid, unidades.codigo_principal asc")); 
$r = pg_num_rows($rs);
if($r!=false && $r>0){ $i=0;
while($r = pg_fetch_array($rs)){  

$qs = pg_query($link, filtrar_sql("select unidades.id_unidad, dconfunid, unidades.codigo_principal from unidades where id_unidpri = ".$r[0]." order by dconfunid, unidades.codigo_principal asc")); 
$q = pg_num_rows($qs);

if($q > 0){ 
	while( $q = pg_fetch_array($qs) ){  
		$sec = $q[0]; $secuencia = $q[1]." ".$q[2]; ?>	
<tr id="<?php echo "celda_$i";?>">
<td><?php echo $r[1]." ".$r[2];?></td>
<td><?php echo $secuencia;?></td>
<td><div class=" info-tooltip" style="width:100px;">

<img src="../img/cambios2.png" width="15" height="15"  title="Desasociar Unidades"  rel="tooltip" data-placement="right" onclick="desasociar(<?php echo $r[0].", ".$sec.", $i"; ?>);"/>

</div></td></tr>     
<?php $i++; } } else { ?>
<tr id="<?php echo "celda_$i";?>">
<td><?php echo $r[1]." ".$r[2];?></td>
<td><?php echo " - - ";?></td>
<td></td></tr>
<?php $i++; } } } ?>   
</tbody>
    
<tfoot><tr>
<th><input type="text" name="prin" id="prin" placeholder="Buscar Unidad Principal" class="search_init"  style="width:160px;" /></th>
<th><input type="text" name="sec" id="sec" placeholder="Buscar Unidad Secundaria" class="search_init"  style="width:160px;" /></th>
<th></th>
</tr></tfoot> 
</table>
</div>

<div class="well">
<div class="header">Asociar Unidades
<a class="headerclose"><i class="fa fa-times pull-right"></i></a> 
<a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> 
<a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a>
</div>

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<div class="form-group"><label>Unidad Primaria</label>
<div><select id="pri" name="pri" class="selectpicker">
<?php $rs = pg_query($link, filtrar_sql("select id_unidad, confunid.codigo_principal, unidades.codigo_principal from confunid, unidades where confunid.id_confunid = unidades.id_confunid and (confunid.id_cliente = $c and unidades.id_cliente = $c) and ((unidades.id_zona = $z or $z < 1) and (unidades.id_area = $a or $a < 1) and (unidades.id_confunid = $t or $t < 1)) order by confunid.codigo_principal, unidades.codigo_principal asc")); 
$r = pg_num_rows($rs); 
if($r==false || $r<1) { ?>
<option value="0" selected="selected">Lista Vacia</option>
<?php } else { ?>
<option value="0" selected="selected">Seleccione una Unidad</option>
<?php while ($r = pg_fetch_array($rs)){ ?>
<option value="<?php echo $r[0];?>"><?php echo $r[1]." ".$r[2];?></option>
<?php } } ?>
</select></div>
</div>
</div>

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<div class="form-group"><label>Unidad Anexa</label>
<div><select id="seg" name="seg" class="selectpicker">
<?php $rs = pg_query($link, filtrar_sql("select id_unidad, confunid.codigo_principal, unidades.codigo_principal from confunid, unidades where confunid.id_confunid = unidades.id_confunid and id_unidpri = 0 and (confunid.id_cliente = $c and unidades.id_cliente = $c) and ((unidades.id_zona = $z or $z < 1) and (unidades.id_area = $a or $a < 1) and (unidades.id_confunid = $t or $t < 1)) order by confunid.codigo_principal, unidades.codigo_principal asc")); 
$r = pg_num_rows($rs); 
if($r==false || $r<1) { ?>
<option value="0" selected="selected">Lista Vacia</option>
<?php } else { ?>
<option value="0" selected="selected">Seleccione una Unidad</option>
<?php while ($r = pg_fetch_array($rs)){ ?>
<option value="<?php echo $r[0];?>"><?php echo $r[1]." ".$r[2];?></option>
<?php } } ?>
</select></div>
</div>
<p>&nbsp;</p>
</div> 

<div class="row">
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<input type="button" name="asociar" value="Asociar" class="btn btn-info btn-block" onclick="asociar(document.getElementById('pri').value, document.getElementById('seg').value)"/></div>
</div>
</div>

<p>&nbsp;</p>
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
<script src="../Legend/admin/assets/bootstrapdatatables/js/jquery.dataTables.js"></script>
<script src="../Legend/admin/assets/bootstrapdatatables/js/DT_bootstrap.js"></script>      
<script>
$('[rel=tooltip]').tooltip();
var asInitVals = new Array();
var lastIdx = null;

$(document).ready(function() {
	
    var oTable = $('#managed-table').dataTable();
     
    $("tfoot input").keyup( function () {
        oTable.fnFilter( this.value, $("tfoot input").index(this) );
    } );

    $("tfoot input").each( function (i) {
        asInitVals[i] = this.value;
    } );
     
    $("tfoot input").focus( function () {
        if ( this.className == "search_init" )
        {
            this.className = "";
            this.value = "";
        }
    } );
     
    $("tfoot input").blur( function (i) {
        if ( this.value == "" )
        {
            this.className = "search_init";
            this.value = asInitVals[$("tfoot input").index(this)];
        }
    } );

} ); 


$('.selectpicker').selectBoxIt();
</script>

<script>
function desasociar(pri, seg, i){ 
	alert("pri: "+pri+" seg: "+seg+" i: "+i);
	$.get('desasociar.php?pri='+pri+"&seg="+seg, function(resultado){
		if(resultado=="true"){ 
			$('#celda_'+i).remove();
			mensaje('Unidades Desasociadas',3);
		} else { 
			mensaje('No se Logro Desasociar Las Unidades',1);
		}	
	});
}

function asociar(pri, seg){ 
	if(pri=="0"){ 
		mensaje("Debe Seleccionar La Unidad Primaria",1);
	} else if(seg=="0"){ 
		mensaje("Debe Seleccionar La Unidad Anexa",1);
	} else { 
		$.get('asociar.php?pri='+pri+"&seg="+seg, function(resultado){ 
			if(resultado=="true"){ 
				mensaje('Unidades Asociadas',3);
				pri = $('#pri').find(":selected").text();
				seg = $('#seg').find(":selected").text();
				
				$('tbody').append('<tr><td>'+pri+'</td><td>'+seg+'</td><td> - </td></tr>');
				
				
			} else if(resultado=="false"){
				mensaje('No se Logro Asociar Las Unidades',1);
			} else { 
				mensaje(resultado,1);
			}
		});
	}
}
</script>

<script src="../Legend/admin/assets/select2/js/select2.min.js"></script>
<script>
$("#pri").select2();
$("#seg").select2();</script>
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