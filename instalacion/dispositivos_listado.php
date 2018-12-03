<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");

$_SESSION['acc']['mod'] = 66;
$_SESSION['acc']['form'] = 172;
include("../complementos/permisos.php");


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
<?php echo "<link href='../Legend/admin/assets/css/styles.css' rel='stylesheet'/>"; ?>

<link href="../Legend/admin/assets/bootstrapdatatables2/dataTables.bootstrap.css" rel="stylesheet" />
<style>
table.table thead tr th,
table.table tbody tr td,
table.table tfoot tr th { min-width:220px; }
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
<div class="header">Listado de Dispositivos</div>

<?php $rs = pg_query($link, filtrar_sql("select rif, razon_social from clientes where id_cliente = ".$_SESSION['instalacion']['cli'])); 
$rs = pg_fetch_array($rs); 
$cli = $rs[0]." ".$rs[1]; ?>
<div class="form-group">
<label>Cliente</label>
<input id="cli" name="cli" type="text" placeholder="Cliente" class="form-control" maxlength="20" value="<?php echo $cli;?>" readonly="readonly" /></div>

<table class="table table-striped table-bordered" id="managed-table">
<thead>
  <tr>
      <th>Serial</th>
      <th>Tipo de Dispositivo</th>
  </tr>
</thead>
<tbody>
<?php $rs = pg_query($link, filtrar_sql("select id_dispositivo, serial, tipo_disp.descripcion from dispositivos, tipo_disp where tipo_disp.id_tipo_disp = dispositivos.id_tipo_disp and id_cliente = ".$_SESSION['instalacion']['cli']." and not exists ( select id_dispositivo from unidades where id_dispositivo = dispositivos.id_dispositivo group by id_dispositivo ) order by serial asc")); 
$r = pg_num_rows($rs);
if($r!=false && $r>0){ while($r = pg_fetch_array($rs)){ ?>    
  <tr>
    <td><?php echo $r[1];?></td>
    <td><?php echo $r[2];?></td>
  </tr>
<?php } } ?>    
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

<?php if(isset($_SESSION['instalacion']['disp'])) {  
	echo "<script>window.open('controles_listado.php','ctr');</script>";
}?>



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