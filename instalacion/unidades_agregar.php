<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");
include("../complementos/util.php");

$_SESSION['acc']['mod'] = 66;
$_SESSION['acc']['form'] = 172;
include("../complementos/permisos.php");



if(isset($_POST['guardar'])){ 
$cli   = filtrar_campo('int', 6,$_SESSION['instalacion']['cli']);
$conf  = filtrar_campo('int', 6,$_SESSION['instalacion']['cfunid']);
$zona  = filtrar_campo('int', 6,$_POST['zona']); 
$area  = filtrar_campo('int', 6,$_POST['area']);
$tunid = filtrar_campo('int', 6,$_POST['tunid']);
$fi = filtrar_campo('date', 10,$_POST['fi']);
$tmp  = "false"; 
$prop = "";
$unid = 0;  

if(empty($cli)){ $_SESSION['mensaje1']="Debe seleccionar un cliente";
} else if(empty($zona)){ $_SESSION['mensaje1']="Debe seleccionar la zona";
} else if(empty($area)){ $_SESSION['mensaje1']="Debe seleccionar el área";
} else if(empty($conf)){$_SESSION['mensaje1']="Debe seleccionar la configración de la unidad";
} else if(empty($fi)){ $_SESSION['mensaje1']="Debe seleccionar La Fecha de Instalaciòn";
} else if(empty($tunid)){ $_SESSION['mensaje1']="Debe Agregar Unidades";
} else if(in_array(447,$_SESSION['acl'])==false){$_SESSION['mensaje1']= "no posee permiso para guardar este registro";
} else { // si validar

$reg = false;
$j=0;

for($i=0; $i<$tunid; $i++){ 
$disp = filtrar_campo('int', 6,$_POST["disp_".$i]);   
$cod = filtrar_campo('todo', 20,$_POST["cod_".$i]);
$conf1 = filtrar_campo('todo', 60,$_POST["conf1_".$i]); 
$conf2 = filtrar_campo('todo', 60,$_POST["conf2_".$i]);
$conf3 = filtrar_campo('todo', 60,$_POST["conf3_".$i]); 
$conf4 = filtrar_campo('todo', 60,$_POST["conf4_".$i]);
$resp = filtrar_campo('int', 6,$_POST["resp_".$i]);   
$kmi = filtrar_campo('num', 12,$_POST["kmi_".$i]); if(empty($kmi)) $kmi=0;


if(empty($disp)){ $_SESSION['mensaje1']="Debe seleccionar El Dispositivo";
} else if(empty($resp)){ $_SESSION['mensaje1']="Debe seleccionar un responsable";
} else if(empty($cod)){ $_SESSION['mensaje1']="Debe indicar el Código Principal";
} else if(empty($conf1)){ $_SESSION['mensaje1']="Debe indicar la Primera Caracteristica";
} else if(empty($conf2)){ $_SESSION['mensaje1']="Debe indicar la Segunda Caracteristica";
} else if(strlen($kmi) < 1){ $_SESSION['mensaje1']="Debe Indicar El Kilometraje Inicial";
} else if(in_array(447,$_SESSION['acl'])==false){$_SESSION['mensaje1']= "no posee permiso para guardar este registro";
} else { // si validar 2

	$rs = pg_query($link, filtrar_sql("insert into unidades(id_cliente, id_zona, id_area,  id_dispositivo, id_confunid, id_tipo_unidad, codigo_principal, n_configuracion1, n_configuracion2, n_configuracion3, n_configuracion4, propietario, is_principal, ult_posicion, estatus_control, obs, ult_act, id_responsable, id_unidpri, km_ini, km_acum, hr_acum, fecha_instalacion) values ($cli, $zona, $area, $disp, $conf, $unid, '$cod', '$conf1', '$conf2', '$conf3', '$conf4', '', $tmp, null, 'Estable', '', '".date('Y-m-d')."', $resp, 0, $kmi, $kmi, 0, '".date2($fi)."')"));
	if($rs){ 
		$rs = pg_query($link, filtrar_sql("select max(id_unidad) from unidades where id_unidad <> 1991"));
		$rs = pg_fetch_array($rs);
		$id = $rs[0];
		$_SESSION['instalacion']['unids'][$j] = $id;
		$_SESSION['instalacion']['disps'][$j] = $disp;
		Auditoria("En Instalación Agrego Unidad: $cod ",$id);
		$reg=true; 
		$j++;
	} 
} // si validar 2 
}

if($reg==true){ 
	$_SESSION['instalacion']['area'] = $area;
	$_SESSION['instalacion']['zona'] = $zona;
	$_SESSION['mensaje3']="Unidades Agregada";
	Auditoria("En Instalación Unidades Agregas ", 0);
	header("location: unidades_listado.php");
	exit();
} else { 
	Auditoria("Problema al registrar Unidades ",0);
	$_SESSION['mensaje1']="No se logro agregar las unidades";
}
	
	
} // si validar
} else { 
	$cod = $prop = $prin = $conf1 = $conf2 = $conf3 = $conf4 = $obs = "";
	$resp = $dis1 = $des2 = $des3 = $des4 = $des5 = $des6 = ""; 
	Auditoria("En Instalación accedio a Agregar Unidades",0);
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
<link href="../Legend/admin/assets/bootstrapui/css/jquery-ui-1.9.2.custom.css" rel="stylesheet"/>
<script src="../complementos/utilidades.js"></script>
<style>
	.wrap { margin:0px;padding:0px; }
	.wrap .container { padding:0px; }
	body { background-color:#FFF; min-width:1000px; }
	.mymarco, #mymarco2 { 
		max-width:845px; 
		width:845px; 
		max-height:700px; 
		height:700px; 
		overflow:scroll;
	}
</style>
</head>
<body>

<div class="mymarco" id="mymarco2">

<section class="wrap">
<div class="container">
<div class="row">
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
<div class="well">


<div class="header">Agregar Unidad</div>
<form name="agregar" method="post" action="unidades_agregar.php" onsubmit="return validar();" enctype="multipart/form-data">
<fieldset>


<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'><label>Zona Geográfica</label>
<div><select id="zona" name="zona" class="selectpicker">
<option value="0" selected="selected">Seleccione una Zona</option>
<!-- LLENADO POR JAVASCRIPT -->
</select></div><br/>
</div>

<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'><label>Área</label>
<div><select id="area" name="area" class="selectpicker">
<option value="0" selected="selected">Seleccione un Área</option>
<!-- LLENADO POR JAVASCRIPT -->
</select></div><br/>
</div>

<?php $rs = pg_query($link, filtrar_sql("select * from confunid where id_confunid = ".$_SESSION['instalacion']['cfunid']));
$rs = pg_fetch_array($rs);
$nom=$rs[2]; $cod=$rs[3]; 
$conf1=$rs[4]; $conf2=$rs[5]; $conf3=$rs[6]; $conf4=$rs[7]; ?>

<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'><label>Fecha de Instalación</label>
<input id='fi' name='fi' type='text' placeholder='Fecha de Instalaciòn' class='form-control' maxlength='12' value='<?php echo $fi;?>'/></div>


<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'><a href="javascript:agregar_unidades();"><br/>
<img src="../img/plus.png" width="15" height="15" style="margin-left:220px; margin-right:7px;" />Agregar Unidad</a></div>

<p>&nbsp;</p>
<p>&nbsp;</p>
<table align="center" width="850" class="table">
<thead>
<th width="30" align="center">#</th>
<th>Dispositivo</th>
<th>Responsable</th>
<th><?php echo $nom;?></th>
<th><?php echo $conf1;?></th>
<th><?php echo $conf2;?></th>
<th><?php echo $conf3;?></th>
<th><?php echo $conf4;?></th>
<th>Km Inicial</th>
</thead>

<tbody id="unid"></tbody>
</table>

<input type="hidden" name="tunid" id="tunid" value="0" readonly="readonly"/>             
</fieldset>
<p>&nbsp;</p>
<div class="row">
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<input type="submit" name="guardar" id="guardar" value="Guardar" class="btn btn-primary btn-block"/></div></div>

</form>
</div>

<script>function validar(){ 
val = false;
	if(document.getElementById('zona').value=="0"){ 
		mensaje("Debe seleccionar la zona geográfica ",1);
		
	} else if(document.getElementById('area').value=="0"){ 
		mensaje("Debe seleccionar el área",1);
		
	} else if(document.getElementById('fi').value=="0"){ 
		mensaje("Debe seleccionar la fecha de instalaciòn ",1);
		
	} else { 
		val = true;
	}
	
	if(val==true){ 
		var nom = 0,   disp = 0,  per = 0;
		var conf1 = 0, conf2 = 0;
		for(i=0; i<tunid && val==true; i++){ 
			nom = document.getElementById('cod_'+i).value.length;
			if(nom>0){ 
				conf1 = document.getElementById('conf1_'+i).value.length;
				conf2 = document.getElementById('conf2_'+i).value.length;
				disp  = document.getElementById('disp_'+i).value;
				per   = document.getElementById('resp_'+i).value;
				if(disp=='0'){ 
					mensaje("Debe Seleccionar El Dispositivo ",1);
					val=false;
				} else if(per=='0'){ 
					mensaje("Debe Seleccionar El Responsable ",1);
					val=false;
				} else if(conf1 < 1){ 
					mensaje("Debe Indicar <?php echo $conf1;?> ",1);
					val=false;
				} else if(conf2 < 1){ 
					mensaje("Debe Indicar <?php echo $conf2;?> ",1);
					val=false;
				}
			}
		}
	}
	
	if(val==true){ 
		mensaje("Registrando...",3);
		$('#guardar').css('display','none');
	}
	
return val; }</script>

</div>
</div>
</div>
</section> 
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
}
</script>

<script src="../Legend/admin/assets/bootstrapmaxlength/js/bootstrap-maxlength.min.js"></script>
<script src="../Legend/admin/assets/select2/js/select2.min.js"></script>
<script>
$("#zona").select2();
$("#area").select2();
</script>

<script>
var disps = ""; 
var pers = "";
var tunid = 0; 

$(document).ready(function(){ 
	dependencia_dispositivos();
	dependencia_personal();
	dependencia_zonas();
	dependencia_areas(); 

	$("#zona").attr("disabled",true);
	$("#area").attr("disabled",true);
});

function dependencia_zonas(){
	var code = <?php echo $_SESSION['instalacion']['cli'];?>;
	$.get("../combox/dependencia_zonas.php", { code: code },
		function(resultado){
			if(resultado == false){ alert("Error"); }
			else {
				$("#zona").attr("disabled",false);
				document.getElementById("zona").options.length=0;
				$('#zona').append(resultado);			
			}
		}
	);
}

function dependencia_areas(){
	var code = <?php echo $_SESSION['instalacion']['cli'];?>;
	$.get("../combox/dependencia_areas.php", { code: code },
		function(resultado){
			if(resultado == false){ alert("Error"); }
			else {
				$("#area").attr("disabled",false);
				document.getElementById("area").options.length=0;
				$('#area').append(resultado);			
			}
		}
	);
}

function dependencia_dispositivos(){
	var code = <?php echo $_SESSION['instalacion']['cli'];?>;
	$.get("../combox/dependencia_dispositivos2.php", { code: code },
		function(resultado){
			if(resultado == false){ alert("Error"); }
			else {
				disps = resultado;			
			}
		}
	);
}

function dependencia_personal(){
	var code = <?php echo $_SESSION['instalacion']['cli'];?>;
	$.get("../combox/dependencia_personal.php", { code: code },
		function(resultado){
			if(resultado == false){ alert("Error"); }
			else {
				pers = resultado;			
			}
		}
	);
}

function rellenar_personal(id){ 
	document.getElementById(id).options.length=0;
	$('#'+id).append(pers);
}

function rellenar_dispositivos(id){ 
	document.getElementById(id).options.length=0;
	$('#'+id).append(disps);
}

function agregar_unidades(){ 
	tunid = Number(document.getElementById('tunid').value);
	$('#unid').append('<tr><td align="center">'+(tunid+1)+'</td><td><select id="disp_'+tunid+'" name="disp_'+tunid+'" style="width:100px;"></select></td><td><select id="resp_'+tunid+'" name="resp_'+tunid+'" style="width:100px;"></select></td><td><input id="cod_'+tunid+'" name="cod_'+tunid+'" type="text" placeholder="<?php echo $nom;?>"  maxlength="60" value="" onkeyup="mayu(this)" onkeypress="return permite(event, \'todo\')" size="11"/></td><td><input id="conf1_'+tunid+'" name="conf1_'+tunid+'" type="text" placeholder="<?php echo $conf1;?>"  maxlength="60" value="" onkeyup="mayu(this)" onkeypress="return permite(event, \'todo\')"  size="9"/></td><td><input id="conf2_'+tunid+'" name="conf2_'+tunid+'" type="text" placeholder="<?php echo $conf2;?>"  maxlength="60" value="" onkeyup="mayu(this)" onkeypress="return permite(event, \'todo\')"  size="9"/> </td><td><input id="conf3_'+tunid+'" name="conf3_'+tunid+'" type="text" placeholder="<?php echo $conf3;?>"  maxlength="60" value="" onkeyup="mayu(this)" onkeypress="return permite(event, \'todo\')"  size="9"/></td><td><input id="conf4_'+tunid+'" name="conf4_'+tunid+'" type="text" placeholder="<?php echo $conf4;?>"  maxlength="60" value="" onkeyup="mayu(this)" onkeypress="return permite(event, \'todo\')"  size="9"/></td><td><input id="kmi_'+tunid+'" name="kmi_'+tunid+'" type="text" placeholder="Kilometraje Inicial"  maxlength="12" value="0" onkeypress="return permite(event, \'float\')" size="7"/></td></tr>');
	rellenar_personal("resp_"+tunid);
	rellenar_dispositivos("disp_"+tunid);
	tunid++;
	document.getElementById('tunid').value = tunid;
}

</script>

<script src="../jquery/development-bundle/ui/jquery.ui.core.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.widget.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.datepicker.js"></script>
<link rel="stylesheet" href="../jquery/development-bundle/themes/custom-theme/jquery.ui.datepicker.css"/>
<script> 
	$( "#fi" ).datepicker({ 
		defaultDate: "0",
		minDate: "0",
		maxDate: "+36M +1D"
	});
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
</div>
</body>
</html>