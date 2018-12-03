<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");

$_SESSION['acc']['mod'] = 69;
$_SESSION['acc']['form'] = 179;
include("../complementos/permisos.php");

if(isset($_REQUEST['eli'])){ 
	$id = filtrar_campo('int', 6, $_REQUEST['eli']);
	$rs = pg_query($link, filtrar_sql("delete from clientes where id_cliente = $id"));
	if($rs){ 
		pg_query($link, filtrar_sql("delete from areas where id_cliente = $id"));
		pg_query($link, filtrar_sql("delete from zongeo where id_cliente = $id"));
		pg_query($link, filtrar_sql("delete from personal where id_cliente = $id"));
		pg_query($link, filtrar_sql("delete from confunid where id_cliente = $id"));
		pg_query($link, filtrar_sql("delete from controles where id_cliente = $id"));
		pg_query($link, filtrar_sql("delete from geocercas where id_cliente = $id"));
		pg_query($link, filtrar_sql("delete from horalarm where id_cliente = $id"));
		pg_query($link, filtrar_sql("delete from panico where id_cliente = $id"));
		pg_query($link, filtrar_sql("delete from permisos where id_cliente = $id"));
		pg_query($link, filtrar_sql("delete from planmant where id_cliente = $id"));
		pg_query($link, filtrar_sql("delete from planmaes where id_cliente = $id"));
		pg_query($link, filtrar_sql("delete from rutas where id_cliente = $id"));
		pg_query($link, filtrar_sql("delete from tipo_permisos where id_cliente = $id"));
		pg_query($link, filtrar_sql("delete from unimedcli where id_cliente = $id"));
		pg_query($link, filtrar_sql("delete from unidades where id_cliente = $id"));
		pg_query($link, filtrar_sql("update dispositivos set id_cliente = 0 where id_cliente = $id"));
		
		Auditoria("El Cliente fue Eliminado ",$id); 
		$_SESSION['mensaje3']="Cliente Eliminado";
	} else { 
		$_SESSION['mensaje1']="No se logro eliminar El Cliente";
		Auditoria("Problema al eliminar El Cliente Error: ".pg_last_error($link),$id);
	}

} else if(isset($_REQUEST['limpiar'])){
	unset($_SESSION['confperm']);
	$_SESSION['mensaje2']="Recuerde Agregar o Seleccionar Un Cliente para Continuar";
	
} else {
	Auditoria("Accedio a Listado de Clientes", 0);
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
<link href="../Legend/admin/assets/vex/css/vex.css" rel="stylesheet" />
<link href="../Legend/admin/assets/vex/css/vex-theme-top.css" rel="stylesheet" />
<?php echo "<link href='../Legend/admin/assets/css/styles.css' rel='stylesheet'/>"; ?>

<link href="../Legend/admin/assets/bootstrapdatatables2/dataTables.bootstrap.css" rel="stylesheet" />
<style>
table.table thead tr th,
table.table tbody tr td,
table.table tfoot tr th { min-width:220px; }
table.table thead tr .extra,
table.table tbody tr .extra,
table.table tfoot tr .extra { min-width:120px; }
</style>
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
<div class="header">Listado de Clientes</div>
<table class="table table-striped table-bordered" id="managed-table">
	<thead>
	<tr>
    	<th class="extra">Acciones</th>
        <th>RIF</th>
        <th>Nombre</th>
        <th>Contacto</th>
        <th>Télefono</th>
        <th>Correo</th>
	</tr>
	</thead>
	<tbody>
<?php $rs = pg_query($link, filtrar_sql("select id_cliente, rif, razon_social, contacto, telefono, correo_contacto from clientes order by rif, razon_social asc")); 
$r = pg_num_rows($rs);
if($r!=false && $r>0){ 
$i=1; while($r = pg_fetch_array($rs)){ ?>    
<tr>
<td class="extra"><div class=" info-tooltip">
<img src='../img/cross.png' width='15' height='15'  title='Eliminar Cliente' rel='tooltip' data-placement='right' onclick="pregunta(<?php echo $r[0];?>);"/>
<img src="../img/pencil.png" width="15" height="15" title="Editar Datos del Cliente" style="margin-left:15px;"  rel="tooltip" data-placement="right" onclick="location.href='cliente_editar.php?cli=<?php echo $r[0];?>'" />
<img src="../img/cambios.png" width="15" height="15" title="Seleccionar Este Cliente" style="margin-left:15px;"  rel="tooltip" data-placement="right" onclick="location.href='cliente_vista.php?cli=<?php echo $r[0];?>'" />
</div></td>
<td><?php echo $r[1];?></td>
<td><?php echo $r[2];?></td>
<td><?php echo $r[3];?></td>
<td><?php echo $r[4];?></td>
<td><?php echo $r[5];?></td>
</tr>
<?php $i++; } } else { ?> 
<tr><td></td><td>Sin Clientes</td><td></td><td></td><td></td><td></td></tr>
<?php } ?>    
	</tbody>
</table>
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
<script src="../Legend/admin/assets/bootstrapdatatables2/jquery.dataTables.min.js"></script>
<script src="../Legend/admin/assets/bootstrapdatatables2/ZeroClipboard.js"></script>
<script src="../Legend/admin/assets/bootstrapdatatables2/dataTables.bootstrap.min.js"></script>
<script>
var InitiateSearchableDataTable = function () {
    return {
        init: function () {
            var oTable = $('#managed-table').dataTable({
                "sDom": "Tflt<'row DTTTFooter'<'col-sm-6'i><'col-sm-6'p>>",
				"scrollX": true,
                "aaSorting": [[1, 'asc']],
                "aLengthMenu": [
                   [5, 10, 20, 50, 100, -1],
                   [5, 10, 20, 50, 100, "All"]
                ],
                "iDisplayLength": 10,
                "language": {
                    "search": "",
                    "sLengthMenu": "_MENU_",
                    "oPaginate": {
                        "sPrevious": "Sig.", "sNext": "Ant."
                    }
                }
            });


        }
    }
}();
InitiateSearchableDataTable.init();
</script>
<script>$('[rel=tooltip]').tooltip();</script>

<script src="../Legend/admin/assets/vex/js/vex.js"></script>
<script src="../Legend/admin/assets/vex/js/vex.dialog.js"></script>
<script>
function pregunta(id) {
         vex.defaultOptions.className = 'vex-theme-top';
         vex.dialog.open({
             message: '¿ Desea Eliminar El Cliente ? Recuerde que este procedimiento borrara todo realcionado con El Cliente',
             buttons: [
                 $.extend({}, vex.dialog.buttons.YES, {
                     text: 'SI',
					 click: function(){ location.href='cliente_listado.php?eli='+id; }
                 }),
                 $.extend({}, vex.dialog.buttons.NO, {
                     text: 'NO'
                 })
             ]
         });
     }
</script>

<?php echo "<script>window.open('desabilitado.php?id=2','confs');</script>"; ?>

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