<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");
include("../complementos/util.php");

$html = "";

$_SESSION['acc']['mod'] = 47;
$_SESSION['acc']['form'] = 144;
include("../complementos/permisos.php");

if(isset($_POST['guardar'])){ 


$cli = $_SESSION['asignacion']['cli'];
$plan = $_SESSION['asignacion']['plan'];
$unid = $_SESSION['asignacion']['conf'];

$zona = $_SESSION['asignacion']['zona'];
$area = $_SESSION['asignacion']['area'];

$fi = $ff = "";
if(empty($plan)==false){ 
list($plan, $tipo) = explode("--",$plan);
	if($tipo==1){ 
		$maestro=$plan; 
		$plan=0;  
		$fi = 0; 
		$ff = 0;
	} else if($tipo==0){ 
		$maestro=0;
$fi = $_SESSION['asignacion']['fi'];
$ff = $_SESSION['asignacion']['ff'];
	}
}
 

if(empty($cli)){ $_SESSION['mensaje1']="Debe seleccionar un cliente";
} if(empty($plan) && empty($maestro)){ $_SESSION['mensaje1']="Debe seleccionar un Plan de Mantenimiento o Plan Maestro";
} else if(empty($unid)){ $_SESSION['mensaje1']="Debe seleccionar un Tipo de Unidad";
} else if(empty($fi) && $tipo==0){ $_SESSION['mensaje1']="Debe seleccionar la fecha de inicio de la programación";
} else if(empty($ff) && $tipo==0){ $_SESSION['mensaje1']="Debe seleccionar la fecha de finalización de la programación";
} else if(isset($_SESSION['asigplan'])==false){ $_SESSION['mensaje1']="No hay Unidades Seleccionadas"; 
} else if(in_array(423,$_SESSION['acl'])==false){
	$_SESSION['mensaje1']= "no posee permiso para guardar este registro";
	header("location: vacio.php");
	exit();
} else { // si validar 



// ============================ CARGANDO VALORES ===============================
$cont = count($_SESSION['asigplan']);
for($i=0; $i<$cont; $i++){
	$vi = 'vi_'.$_SESSION['asigplan'][$i][0];
	$vu = 'vu_'.$_SESSION['asigplan'][$i][0];
	
	$_SESSION['asigplan'][$i][2] = $_POST[$vi];
	$_SESSION['asigplan'][$i][3] = $_POST[$vu];
	
	if(empty($_SESSION['asigplan'][$i][2])) $_SESSION['asigplan'][$i][2]=0;
	if(empty($_SESSION['asigplan'][$i][3])) $_SESSION['asigplan'][$i][3]=0;	
} 

//======================== PLAN CICLICO ===============================================
if($plan!=0){ // programacion ciclica 

// REGISTRAR PLAN MANT UNIDAD
$unids = array();

if(empty($maestro)) { $detalle_maestro=""; } else { 
$rs = pg_query("select nombre from planmaes where id_planmaes = $maestro");
$rs = pg_fetch_array($rs); 
$detalle_maestro = " Segun Plan Maestro de Mantenimiento: ".$rs[0]; }
$rs = pg_query("select nombre from planmant where id_planamant = $plan");
$rs = pg_fetch_array($rs); $detalle_plan = $rs[0];


for($i=0; $i<count($_SESSION['asigplan']); $i++){ 

$rs = pg_query("insert into planmant_unidades ( id_planmant, id_unidad, fecha_ultimo_mant, id_planmaes, valor_inicial, valor_actual, valor_ult_mant ) values ($plan, ".$_SESSION['asigplan'][$i][0].", '".date("Y-m-d")."', $maestro, ".$_SESSION['asigplan'][$i][2].", ".$_SESSION['asigplan'][$i][2].", ".$_SESSION['asigplan'][$i][3].")");

$rs = pg_query("select max(id_planmant_unidad) from planmant_unidades ");
$rs = pg_fetch_array($rs);
$unids[$i] = $rs[0];

Auditoria("En Plan de Mantenimiento - Unidades se Asigno El Mantenimiento: $detalle_plan  a la Unidad: ".$_SESSION['asigplan'][$i][1]."  $detalle_maestro",$unids[$i]);

}// REGISTRAR PLAN MANT UNIDAD

$rs = pg_query("select tiempo from planmant where id_planmant = $plan");
$rs = pg_fetch_array($rs);
$dias = $rs[0];  $ini = date2($fi); $fin = date2($ff);
$sig = true; 
	while($sig==true){ // mientras fecha	
for($i=0; $i<count($unids); $i++){	// registrando programacion del dia	

pg_query("insert into progmant(id_planmantunid, fr, estatus) values (".$unids[$i].", '".$ini."', 'Programado')"); 

$rs = pg_query("select max(id_progmant) from progmant ");
$rs = pg_fetch_array($rs);
Auditoria("En Plan de Mantenimiento - Unidades se Genero La Programación del Mantenimiento    Plan: $detalle_plan    Fecha: ".date1($ini)."    Unidad: ".$_SESSION['asigplan'][$i][1]."  $detalle_maestro",$rs[0]);

}

$rs = pg_query($link,"select extract(days from ('$ini'::date + interval '$dias day' - timestamp '$fin')), ('$ini'::date + interval '$dias day')::timestamp::date"); 
$rs = pg_fetch_array($rs);
if($rs[0]<1){ $sig=true; $ini = $rs[1]; } else { $sig=false; }
 } // fin mientras
//======================================================================================




//=============================== PLAN INCREMENTAL =====================================
} else if($maestro!=0){ // programacion incremental

// REGISTRAR PLAN MANT UNIDAD
$unids = array(); 
$plans = array();
$rs = pg_query("select id_planmant, descripcion from planmant where id_planmaes = $maestro order by valor asc");
$r = pg_num_rows($rs);
if($r!=false && $r>0){ $i=0; while($r=pg_fetch_array($rs)){ 
	$plans[$i] = $r[0]; 
	$det_plan[$i] = $rs[1];	
	$i++; 
} }


if(empty($maestro)) { $detalle_maestro=""; } else { 
$rs = pg_query("select nombre from planmaes where id_planmaes = $maestro");
$rs = pg_fetch_array($rs); 
$detalle_maestro = " Segun Plan Maestro de Mantenimiento: ".$rs[0]; }

$t=0;
for($i=0; $i<count($_SESSION['asigplan']); $i++){
	for($j=0; $j<count($plans); $j++){ 
		$rs = pg_query("insert into planmant_unidades ( id_planmant, id_unidad, fecha_ultimo_mant, id_planmaes, valor_inicial, valor_actual , valor_ult_mant) values (".$plans[$j].", ".$_SESSION['asigplan'][$i][0].", '".date("Y-m-d")."', $maestro, ".$_SESSION['asigplan'][$i][2].", ".$_SESSION['asigplan'][$i][2].", ".$_SESSION['asigplan'][$i][3].")");
		
		$rs = pg_query("select max(id_planmant_unidad) from planmant_unidades ");
		$rs = pg_fetch_array($rs);
		$unids[$t] = $rs[0];
		
Auditoria("En Plan de Mantenimiento - Unidades se Asigno El Mantenimiento: ".$det_plan[$j]."  a la Unidad: ".$_SESSION['asigplan'][$i][1]."  $detalle_maestro",$unids[$t]);
		
		$t++;
	}
}// REGISTRAR PLAN MANT UNIDAD

for($i=0; $i<count($unids); $i++){ // mientras planes asignados
$qs = pg_query("select valor_actual, valor, ( valor_prom / 30), ( valor - valor_actual ) from planmant_unidades, planmant, unidades where id_planmant_unidad = ".$unids[$i]." and planmant_unidades.id_unidad = unidades.id_unidad and planmant_unidades.id_planmant = planmant.id_planmant");	$qs=pg_fetch_array($qs);
if($qs[0] < $qs[1]){ // si valor actual menor a valor del plan
	$dias = (1*$qs[3])/$qs[2];
	$qs = pg_query("select ('".date('Y-m-d')."'::date + interval '$dias day')::timestamp::date"); $qs=pg_fetch_array($qs);
	$sql = "insert into progmant(id_planmantunid, fr, estatus) values (".$unids[$i].", '".$qs[0]."', 'Programado')";
	pg_query($sql);
	
$rs = pg_query("select max(id_progmant) from progmant ");
$rs = pg_fetch_array($rs);
Auditoria("En Plan de Mantenimiento - Unidades se Genero La Programación del Mantenimiento Plan: $detalle_plan    Fecha: ".date1($qs[0])."    Unidad: ".$_SESSION['asigplan'][$i][1]."  $detalle_maestro",$rs[0]);
	
} // si valor actual menor a valor del plan
} // mientras planes asignados


	
}//=====================================================================================
		
		$_SESSION['mensaje3']="Planes de Mantenimiento Asignado.  Generando Mantenimientos Programados";
		$salir = true;
		
} // si validar
} 





if(isset($_REQUEST['data'])){ 
	list($cli, $plan, $conf, $zona, $area, $fi, $ff) = explode(":::",$_REQUEST['data']);
	$_SESSION['asignacion']['cli'] = $cli;
	$_SESSION['asignacion']['plan'] = $plan;
	$_SESSION['asignacion']['conf'] = $conf;
	$_SESSION['asignacion']['zona'] = $zona;
	$_SESSION['asignacion']['area'] = $area;
	$_SESSION['asignacion']['fi'] = $fi;
	$_SESSION['asignacion']['ff'] = $ff;
	
if(isset($_REQUEST['limpiar'])) unset($_SESSION['asigplan']);

if(isset($_SESSION['asigplan'])==false){ 
$sql="select id_unidad, confunid.codigo_principal, unidades.codigo_principal from unidades, confunid where unidades.id_confunid = confunid.id_confunid and 
( unidades.id_confunid = $conf or $conf = 0 ) and  
( id_zona = $zona or $zona = 0 ) and 
( id_area = $area or $area = 0 ) and 
unidades.id_cliente = $cli 
order by confunid.codigo_principal, unidades.codigo_principal asc ";
$rs = pg_query($link, $sql); 
$r = pg_num_rows($rs); 
if($r!=false && $r>0){ $i=0; 
while($r = pg_fetch_array($rs)){
	$_SESSION['asigplan'][$i][0]=$r[0];//ids
	$_SESSION['asigplan'][$i][1]=$r[1]." - ".$r[2];//des
	$_SESSION['asigplan'][$i][2]="";//valor inicial
	$_SESSION['asigplan'][$i][3]="";//valor ultimo mantenimiento
$i++; }
} else { 
	unset($_SESSION['asigplan']);
} } }


?><!DOCTYPE html>
<html lang="en"><head>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="../Legend/admin/assets/ico/apple-touch-icon-144-precomposed.png" />
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="../Legend/admin/assets/ico/apple-touch-icon-114-precomposed.png" />
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="../Legend/admin/assets/ico/apple-touch-icon-72-precomposed.png" />
<link rel="apple-touch-icon-precomposed" href="../Legend/admin/assets/ico/apple-touch-icon-57-precomposed.png" />  
<meta name="author" content="Nous Technologies" />
<link rel="shortcut icon" href="../img/icono.png" />
<title>.:: NousTrack ::.</title>
<link href="../Legend/admin/assets/humane/css/jackedup.css" rel="stylesheet" />
<link href="../Legend/admin/assets/bootstrapdatatables/css/DT_bootstrap.css" rel="stylesheet">
<?php echo "<link href='../Legend/admin/assets/bootstrap/css/bootstrap.css' rel='stylesheet'>"; ?>        
<?php echo "<link href='../Legend/admin/assets/css/styles.css' rel='stylesheet'> "; ?>        
        
        <!--[if lt IE 9]>
        <script src="../Legend/admin/assets/js/html5shiv.js"></script>
        <script src="../Legend/admin/assets/js/respond.min.js"></script>
        <![endif]-->
    </head>
    <body style="max-width:915px; background:#FFF;">
<form name="unidades" action="cargar_unidades.php" method="post" onSubmit="return validar();">
            	<div class="row">
            		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		                <div class="well">
		                	
			                <table class="table table-striped table-bordered" id="managed-table">
			                    <thead>
			                        <tr>
			                            <th>Unidad</th>
			                            <th align="center">Valor Ultimo Mant.</th>
			                            <th align="center">Valor Inicial</th>
			                        </tr>
			                    </thead>
			                    <tbody>
<?php $cont = count($_SESSION['asigplan']); $script="";
for($i=0; $i<$cont; $i++){ 
$script .= "
} else if( document.getElementById('vi_".$_SESSION['asigplan'][$i][0]."').value.length<1 ){ mensaje('Debe Indicar Todos los Valores Iniciales y de Ultimo Mantenimiento',1); 
} else if( document.getElementById('vu_".$_SESSION['asigplan'][$i][0]."').value.length<1 ){ mensaje('Debe Indicar Todos los Valores Iniciales y de Ultimo Mantenimiento',1); "; ?>
<tr><td><?php echo $_SESSION['asigplan'][$i][1];?></td>
<td align="center"><input type="text" name="vu_<?php echo $_SESSION['asigplan'][$i][0];?>" id="vu_<?php echo $_SESSION['asigplan'][$i][0];?>" value="<?php echo $_SESSION['asigplan'][$i][3];?>" maxlength="8" size="16" onKeyPress="return permite(event, 'num');" placeholder="Valor del Ultimo Mantenimiento" /></td>
<td align="center"><input type="text" name="vi_<?php echo $_SESSION['asigplan'][$i][0];?>" id="vi_<?php echo $_SESSION['asigplan'][$i][0];?>" value="<?php echo $_SESSION['asigplan'][$i][2];?>" maxlength="8" size="16" onKeyPress="return permite(event, 'num');" placeholder="Valor Inicial" /></td></tr>
<?php } ?>
			                    </tbody>
			                </table>
		                </div>
		                
            		</div>
<div class="row">
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<input type="button" name="volver" value="Cancelar" class="btn btn-info btn-block" onclick="location.href='vacio.php'"/></div>
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<input type="submit" name="guardar" value="Guardar" class="btn btn-primary btn-block" /></div></div>                    
                    
</div>
</form>

<script>function validar(){ 
	var val=false;
	if( 1 == 0 ){ 
	
	<?php echo $script; ?>
		
	} else { 
		val = true;
	}
	return val;
}</script>

<script src="../jquery/development-bundle/jquery-1.10.2.js"></script>
<script type="text/javascript">$(document).bind("mobileinit", function(){$.extend(  $.mobile , {autoInitializePage: false})});</script>
<script src="../jquerymobile/jquery.mobile.custom.js"></script>

<script src="../Legend/admin/assets/bootstrap/js/bootstrap.min.js"></script>
<script src="../Legend/admin/assets/bootstrapdatatables/js/jquery.dataTables.js"></script>
<script src="../Legend/admin/assets/bootstrapdatatables/js/DT_bootstrap.js"></script>
<script>$('#managed-table').dataTable(); $('.selectpicker').selectBoxIt();</script>
<script>$('[rel=tooltip]').tooltip();</script>
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


<?php 
if(isset($_SESSION['mensaje1'])){ 
	echo "<script>mensaje('".$_SESSION['mensaje1']."',1);</script>"; 
	unset($_SESSION['mensaje1']);
}

if(isset($_SESSION['mensaje2'])){ 
	echo "<script>mensaje('".$_SESSION['mensaje2']."',2);</script>"; 
	unset($_SESSION['mensaje2']);
}

 ?>
<?php include("../complementos/closdb.php"); ?>
    </body>
</html>

<?php if(isset($salir)){ echo "<html><head></head><body><script>window.open('listado.php','_parent')</script></body></html>"; }?>