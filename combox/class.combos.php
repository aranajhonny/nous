<?php
session_start();
include_once("../complementos/auditoria.php");


class selects {
var $code = "";
var $cnn_combox = NULL; 
	
function conectar(){
	$this->cnn_combox = pg_connect("host=localhost port=5432 dbname=nous user=postgres password=@BigNous$2014",PGSQL_CONNECT_FORCE_NEW);
}

function desconectar(){ 
	pg_close($this->cnn_combox);
	$this->cnn_combox = NULL;
}

function cargarAreas(){
include ("../composiciones/composiciones_areas.php");	
	$this->conectar();
$consulta = pg_query($this->cnn_combox, filtrar_sql("select id_area, descripcion from areas where id_cliente = ".$this->code." and (id_area = ".$_SESSION['miss'][0]." or ".$_SESSION['miss'][0]." < 1) order by descripcion asc "));
$num_total_registros = pg_num_rows($consulta);
	if($num_total_registros>0){
		$clientes = array();
		while($clien = pg_fetch_array($consulta)){
$qs=pg_query($this->cnn_combox, filtrar_sql("select count(id_area) from areas where id_dependencia = ".$clien[0])); 
$qs=pg_fetch_array($qs); 
			if($qs[0]==0){
				$code = $clien[0];					
				$clientes[$code]=$clien[1];
			} else { 
				$code = $clien[0];					
				$clientes[$code]=$clien[1];
$clientes = $clientes + ComponerComboxAreas($clien[0],  $clien[1], "&emsp;");
			}
		}
		return $clientes;
	} else { return false; }
	$this->desconectar();
}
function cargarClientes(){
	$this->conectar();
$consulta = pg_query($this->cnn_combox, filtrar_sql("select id_cliente, rif, razon_social from clientes order by rif asc"));
$num_total_registros = pg_num_rows($consulta);
	if($num_total_registros>0) {
		$clientes = array();
		while($clien = pg_fetch_array($consulta)) {
			$code = $clien[0];			
			$clientes[$code]= $clien[1]." ".$clien[2];
		}
		return $clientes;
	} else { return false; }
	$this->desconectar();
}
function cargarConfGuia(){
	$this->conectar();
$consulta = pg_query($this->cnn_combox, filtrar_sql("select id_confguia, nombre, n_configuracion_01, n_configuracion_02, n_configuracion_03, codigo_principal from confguia where id_cliente = ".$this->code." order by nombre asc "));
$num_total_registros = pg_num_rows($consulta);
	if($num_total_registros>0){
		$clientes = array();
		while($clien = pg_fetch_array($consulta)){
		$code = $clien[0].":::".$clien[2].":::".$clien[3].":::".$clien[4].":::".$clien[5];					
			$clientes[$code]=$clien[1];
		}
		return $clientes;
	} else { return false; }
	$this->desconectar();
}
function cargarConfUnid(){
	$this->conectar();
$consulta = pg_query($this->cnn_combox, filtrar_sql("select id_confunid, nombre, n_configuracion_01, n_configuracion_02, n_configuracion_03, n_configuracion_04, codigo_principal from confunid where id_cliente = ".$this->code." and (id_confunid=".$_SESSION['miss'][2]." or ".$_SESSION['miss'][2]." < 1) order by nombre asc "));
$num_total_registros = pg_num_rows($consulta);
	if($num_total_registros>0){
		$clientes = array();
		while($clien = pg_fetch_array($consulta)){
		$code = $clien[0].":::".$clien[2].":::".$clien[3].":::".$clien[4].":::".$clien[5].":::".$clien[1];					
			$clientes[$code]=$clien[6];
		}
		return $clientes;
	} else { return false; }
	$this->desconectar();
}
function cargarControles(){
	$this->conectar();
$consulta = pg_query($this->cnn_combox, filtrar_sql("select id_control, nombre from controles where id_cliente = ".$this->code." order by nombre asc "));
$num_total_registros = pg_num_rows($consulta);
	if($num_total_registros>0){
		$clientes = array();
		while($clien = pg_fetch_array($consulta)){
			$code = $clien[0];					
			$clientes[$code]=$clien[1];
		}
		return $clientes;
	} else { return false; }
	$this->desconectar();
}
function cargarDispositivos(){
	$this->conectar();
$consulta = pg_query($this->cnn_combox, filtrar_sql("select id_dispositivo, descripcion, serial from dispositivos, tipo_disp where dispositivos.id_tipo_disp = tipo_disp.id_tipo_disp and id_cliente = ".$this->code." order by descripcion, serial asc "));
$num_total_registros = pg_num_rows($consulta);
	if($num_total_registros>0){
		$clientes = array();
		while($clien = pg_fetch_array($consulta)){
			$code = $clien[0];					
			$clientes[$code]=$clien[1]." - ".$clien[2];
		}
		return $clientes;
	} else { return false; }
	$this->desconectar();
}
function cargarDispositivos2(){
	$this->conectar();
$consulta = pg_query($this->cnn_combox, filtrar_sql("select id_dispositivo, serial, tipo_disp.descripcion from dispositivos, tipo_disp where tipo_disp.id_tipo_disp = dispositivos.id_tipo_disp and id_cliente = ".$this->code." and not exists ( select id_dispositivo from unidades where id_dispositivo = dispositivos.id_dispositivo group by id_dispositivo ) order by serial asc"));
$num_total_registros = pg_num_rows($consulta);
	if($num_total_registros>0){
		$clientes = array();
		while($clien = pg_fetch_array($consulta)){
			$code = $clien[0];					
			$clientes[$code]=$clien[2]." - ".$clien[1];
		}
		return $clientes;
	} else { return false; }
	$this->desconectar();
}
function cargarGeocerca(){
	$this->conectar();
$consulta = pg_query($this->cnn_combox, filtrar_sql("select id_geocerca, nom from geocercas where id_cliente = ".$this->code." order by nom asc "));
$num_total_registros = pg_num_rows($consulta);
	if($num_total_registros>0){
		$clientes = array();
		while($clien = pg_fetch_array($consulta)){
			$code = $clien[0];					
			$clientes[$code]=$clien[1];
		}
		return $clientes;
	} else { return false; }
	$this->desconectar();
}	
function cargarLotes(){
	$this->conectar();
$consulta = pg_query($this->cnn_combox, filtrar_sql("select id_lote, nro from lotes order by nro asc"));
$num_total_registros = pg_num_rows($consulta);
	if($num_total_registros>0) {
		$clientes = array();
		while($clien = pg_fetch_array($consulta)) {
			$code = $clien[0];			
			$clientes[$code]= $clien[1];
		}
		return $clientes;
	} else { return false; }
	$this->desconectar();
}
function cargarMagnitudes(){
	$this->conectar();
$consulta = pg_query($this->cnn_combox, filtrar_sql("select id_magnitud, nombre from magnitudes order by nombre asc"));
$num_total_registros = pg_num_rows($consulta);
	if($num_total_registros>0) {
		$clientes = array();
		while($clien = pg_fetch_array($consulta)) {
			$code = $clien[0];			
			$clientes[$code]= $clien[1];
		}
		return $clientes;
	} else { return false; }
	$this->desconectar();
}
function cargarMagnitudes2(){
	$this->conectar();
$consulta = pg_query($this->cnn_combox, filtrar_sql("select magnitudes.id_magnitud, magnitudes.nombre from magnitudes, unimedcli where unimedcli.id_magnitud = magnitudes.id_magnitud and id_cliente = ".$this->code." group by magnitudes.id_magnitud order by magnitudes.nombre asc"));
$num_total_registros = pg_num_rows($consulta);
	if($num_total_registros>0) {
		$clientes = array();
		while($clien = pg_fetch_array($consulta)) {
			$code = $clien[0];			
			$clientes[$code]= $clien[1];
		}
		return $clientes;
	} else { return false; }
	$this->desconectar();
}
function cargarMarcas(){
	$this->conectar();
$consulta = pg_query($this->cnn_combox, filtrar_sql("select id_marca, descripcion from marcas where id_cliente = ".$this->code." order by descripcion asc "));
$num_total_registros = pg_num_rows($consulta);
	if($num_total_registros>0){
		$clientes = array();
		while($clien = pg_fetch_array($consulta)){
			$code = $clien[0];					
			$clientes[$code]=$clien[1];
		}
		return $clientes;
	} else { return false; }
	$this->desconectar();
}
function cargarModelos(){
	$this->conectar();
$consulta = pg_query($this->cnn_combox, filtrar_sql("select id_modelo, marcas.descripcion,  modelos.descripcion from marcas, modelos where modelos.id_marca = marcas.id_marca and id_cliente = ".$this->code." order by  marcas.descripcion,  modelos.descripcion asc "));
$num_total_registros = pg_num_rows($consulta);
	if($num_total_registros>0){
		$clientes = array();
		while($clien = pg_fetch_array($consulta)){
			$code = $clien[0];					
			$clientes[$code]=$clien[1]." - ".$clien[2];
		}
		return $clientes;
	} else { return false; }
	$this->desconectar();
}
function cargarPersonal(){
	$this->conectar();
$consulta = pg_query($this->cnn_combox, filtrar_sql("select id_personal, ci, nombre from personal where id_cliente = ".$this->code." and ((id_area=".$_SESSION['miss'][0]." or ".$_SESSION['miss'][0]." < 1) and (id_zona=".$_SESSION['miss'][1]." or ".$_SESSION['miss'][1]." < 1)) order by ci asc "));
$num_total_registros = pg_num_rows($consulta);
	if($num_total_registros>0){
		$clientes = array();
		while($clien = pg_fetch_array($consulta)){
			$code = $clien[0];					
			$clientes[$code]=$clien[1]." ".$clien[2];
		}
		return $clientes;
	} else { return false; }
	$this->desconectar();
}
function cargarPlanes(){
	$this->conectar();
$consulta = pg_query($this->cnn_combox, filtrar_sql("select id_planmant, descripcion from planmant where (id_confunid=".$_SESSION['miss'][2]." or ".$_SESSION['miss'][2]." < 1) and id_cliente = ".$this->code." order by descripcion asc "));
$num_total_registros = pg_num_rows($consulta);
	if($num_total_registros>0){
		$clientes = array();
		while($clien = pg_fetch_array($consulta)){
			$code = $clien[0];					
			$clientes[$code]=$clien[1];
		}
		return $clientes;
	} else { return false; }
	$this->desconectar();
}
function cargarPlanes_y_Maestros(){
	$this->conectar();
$consulta = pg_query($this->cnn_combox, filtrar_sql("select id_planmaes, nombre, 1 from planmaes where (id_confunid=".$_SESSION['miss'][2]." or ".$_SESSION['miss'][2]." < 1) and id_cliente = ".$this->code." union select id_planmant, descripcion, 0 from planmant where (id_confunid=".$_SESSION['miss'][2]." or ".$_SESSION['miss'][2]." < 1) and id_cliente = ".$this->code." and id_planmaes = 0 "));
$num_total_registros = pg_num_rows($consulta);
	if($num_total_registros>0){
		$clientes = array();
		while($clien = pg_fetch_array($consulta)){
			$code = $clien[0]."--".$clien[2];					
			$clientes[$code]=$clien[1];
		}
		return $clientes;
	} else { return false; }
	$this->desconectar();
}
function cargarPlanes_y_Maestros2(){
	$this->conectar(); 
list($confunid, $tmp) = explode(":::", $this->code);
$consulta = pg_query($this->cnn_combox, filtrar_sql("select id_planmaes, nombre, 1 from planmaes where id_confunid = ".$confunid." union select id_planmant, descripcion, 0 from planmant where id_confunid = ".$confunid." and id_planmaes = 0 "));
$num_total_registros = pg_num_rows($consulta);
	if($num_total_registros>0){
		$clientes = array();
		while($clien = pg_fetch_array($consulta)){
			$code = $clien[0]."--".$clien[2];					
			$clientes[$code]=$clien[1];
		}
		return $clientes;
	} else { return false; }
	$this->desconectar();
}
function cargarPlanMaestro(){
	$this->conectar();
$consulta = pg_query($this->cnn_combox, filtrar_sql("select id_planmaes, nombre from planmaes where (id_confunid=".$_SESSION['miss'][2]." or ".$_SESSION['miss'][2]." < 1) and estatus='Activo' and  id_cliente = ".$this->code." order by nombre asc "));
$num_total_registros = pg_num_rows($consulta);
	if($num_total_registros>0){
		$clientes = array();
		while($clien = pg_fetch_array($consulta)){
			$code = $clien[0];					
			$clientes[$code]=$clien[1];
		}
		return $clientes;
	} else { return false; }
	$this->desconectar();
}
function cargarProveedores(){
	$this->conectar();
$consulta = pg_query($this->cnn_combox, filtrar_sql("select id_provserv, rif, nombre_prov from provserv where id_cliente = ".$this->code." order by rif asc "));
$num_total_registros = pg_num_rows($consulta);
	if($num_total_registros>0){
		$clientes = array();
		while($clien = pg_fetch_array($consulta)){
			$code = $clien[0];					
			$clientes[$code]=$clien[1]." ".$clien[2];
		}
		return $clientes;
	} else { return false; }
	$this->desconectar();
}
function cargarRutas(){
	$this->conectar();
$consulta = pg_query($this->cnn_combox, filtrar_sql("select id_ruta, nombre from rutas where id_cliente = ".$this->code." order by nombre asc "));
$num_total_registros = pg_num_rows($consulta);
	if($num_total_registros>0){
		$clientes = array();
		while($clien = pg_fetch_array($consulta)){
			$code = $clien[0];					
			$clientes[$code]=$clien[1];
		}
		return $clientes;
	} else { return false; }
	$this->desconectar();
}
function cargarSensores(){
	$this->conectar();
$consulta = pg_query($this->cnn_combox, filtrar_sql("select id_sensor, descripcion, serial from sensores, tipo_sensores where sensores.id_tipo_sensor = tipo_sensores.id_tipo_sensor and id_dispositivo = ".$this->code." order by descripcion, serial asc "));
$num_total_registros = pg_num_rows($consulta);
	if($num_total_registros>0){
		$clientes = array();
		while($clien = pg_fetch_array($consulta)){
			$code = $clien[0];					
			$clientes[$code]=$clien[1]." - ".$clien[2];
		}
		return $clientes;
	} else { return false; }
	$this->desconectar();
}
function cargarSensores2(){
	$this->conectar();
$consulta = pg_query($this->cnn_combox, filtrar_sql("select id_sensor, descripcion, serial from sensores, tipo_sensores where sensores.id_tipo_sensor = tipo_sensores.id_tipo_sensor and id_unidad = ".$this->code." order by descripcion, serial asc "));
$num_total_registros = pg_num_rows($consulta);
	if($num_total_registros>0){
		$clientes = array();
		while($clien = pg_fetch_array($consulta)){
			$code = $clien[0];					
			$clientes[$code]=$clien[1]." - ".$clien[2];
		}
		return $clientes;
	} else { return false; }
	$this->desconectar();
}
function cargarUnidades(){
	$this->conectar();
$consulta = pg_query($this->cnn_combox, filtrar_sql("select id_unidad, unidades.codigo_principal, confunid.codigo_principal from unidades, confunid where (confunid.id_cliente=".$_SESSION['miss'][3]." and unidades.id_cliente=".$_SESSION['miss'][3].") and ((unidades.id_area=".$_SESSION['miss'][0]." or ".$_SESSION['miss'][0]." < 1) and (unidades.id_zona=".$_SESSION['miss'][1]." or ".$_SESSION['miss'][1]." < 1) and (unidades.id_confunid=".$_SESSION['miss'][2]." or ".$_SESSION['miss'][2]." < 1)) and unidades.id_confunid = confunid.id_confunid and unidades.id_cliente = ".$this->code." order by unidades.codigo_principal asc"));
$num_total_registros = pg_num_rows($consulta);
	if($num_total_registros>0){
		$clientes = array();
		while($clien = pg_fetch_array($consulta)){
			$code = $clien[0];					
			$clientes[$code]=$clien[2]." - ".$clien[1];
		}
		return $clientes;
	} else { return false; }
	$this->desconectar();
}
function cargarUnidades2(){
	$this->conectar();
$consulta = pg_query($this->cnn_combox, filtrar_sql("select id_unidad, confunid.codigo_principal, unidades.codigo_principal from unidades, confunid where (confunid.id_cliente=".$_SESSION['miss'][3]." and unidades.id_cliente=".$_SESSION['miss'][3].") and ((unidades.id_area=".$_SESSION['miss'][0]." or ".$_SESSION['miss'][0]." < 1) and (unidades.id_zona=".$_SESSION['miss'][1]." or ".$_SESSION['miss'][1]." < 1) and (unidades.id_confunid=".$_SESSION['miss'][2]." or ".$_SESSION['miss'][2]." < 1)) and unidades.id_confunid = confunid.id_confunid and id_dispositivo = ".$this->code." order by confunid.codigo_principal, unidades.codigo_principal asc "));
$num_total_registros = pg_num_rows($consulta);
	if($num_total_registros>0){
		$clientes = array();
		while($clien = pg_fetch_array($consulta)){
			$code = $clien[0];					
			$clientes[$code]=$clien[1]." ".$clien[2];
		}
		return $clientes;
	} else { return false; }
	$this->desconectar();
}
function cargarUnidades3(){
	$this->conectar();
$consulta = pg_query($this->cnn_combox, filtrar_sql("select id_unidad, areas.id_area, zongeo.id_zongeo, confunid.codigo_principal, unidades.codigo_principal, areas.descripcion, zongeo.nombre from unidades, areas, zongeo, confunid where (confunid.id_cliente=".$_SESSION['miss'][3]." and unidades.id_cliente=".$_SESSION['miss'][3]." and areas.id_cliente=".$_SESSION['miss'][3]." and zongeo.id_cliente=".$_SESSION['miss'][3].") and ((unidades.id_area=".$_SESSION['miss'][0]." or ".$_SESSION['miss'][0]." < 1) and (unidades.id_zona=".$_SESSION['miss'][1]." or ".$_SESSION['miss'][1]." < 1) and (unidades.id_confunid=".$_SESSION['miss'][2]." or ".$_SESSION['miss'][2]." < 1)) and unidades.id_area = areas.id_area and unidades.id_zona = zongeo.id_zongeo and unidades.id_confunid = confunid.id_confunid and unidades.id_cliente = ".$this->code." and areas.id_cliente = ".$this->code." and zongeo.id_cliente = ".$this->code." and confunid.id_cliente = ".$this->code." "));
$num_total_registros = pg_num_rows($consulta);
	if($num_total_registros>0){
		$clientes = array();
		while($clien = pg_fetch_array($consulta)){
			$code = $clien[0].":::".$clien[1].":::".$clien[2].":::".$clien[3].":::".$clien[4].":::".$clien[5].":::".$clien[6];					
			$clientes[$code]=$clien[3]." ".$clien[4];
		}
		return $clientes;
	} else { return false; }
	$this->desconectar();
}
function cargarUnidMed(){
	$this->conectar();
$consulta = pg_query($this->cnn_combox, filtrar_sql("select id_unidmed, nombre from unidmed where id_magnitud = ".$this->code." order by nombre asc "));
$num_total_registros = pg_num_rows($consulta);
	if($num_total_registros>0){
		$clientes = array();
		while($clien = pg_fetch_array($consulta)){
			$code = $clien[0];					
			$clientes[$code]=$clien[1];
		}
		return $clientes;
	} else { return false; }
	$this->desconectar();
}
function cargarUniMedCli(){
	$this->conectar();
	list($cli, $mag) = explode("::",$this->code);
$consulta = pg_query($this->cnn_combox, filtrar_sql("select id_unimedcli, unidmed.nombre, magnitudes.nombre from unimedcli, unidmed, magnitudes where magnitudes.id_magnitud = unidmed.id_magnitud and unimedcli.id_unidmed = unidmed.id_unidmed and magnitudes.id_magnitud = $mag and unimedcli.id_cliente = $cli order by unidmed.nombre asc "));
$num_total_registros = pg_num_rows($consulta);
	if($num_total_registros>0){
		$clientes = array();
		while($clien = pg_fetch_array($consulta)){
			$code = $clien[0];					
			$clientes[$code]=$clien[1];
		}
		return $clientes;
	} else { return false; }
	$this->desconectar();
}
function cargarUniMedCli2(){
	$this->conectar();
	list($cli, $mag) = explode("::",$this->code);
$consulta = pg_query($this->cnn_combox, filtrar_sql("select id_unimedcli, unidmed.nombre from unimedcli, unidmed where unimedcli.id_unidmed = unidmed.id_unidmed and id_cliente = $cli and unidmed.id_magnitud = $mag order by unidmed.nombre asc "));
$num_total_registros = pg_num_rows($consulta);
	if($num_total_registros>0){
		$clientes = array();
		while($clien = pg_fetch_array($consulta)){
			$code = $clien[0];					
			$clientes[$code]=$clien[1];
		}
		return $clientes;
	} else { return false; }
	$this->desconectar();
}
function cargarTipoDispositivos(){
	$this->conectar();
$consulta = pg_query($this->cnn_combox, filtrar_sql("select id_tipo_disp, descripcion from tipo_disp order by descripcion asc"));
$num_total_registros = pg_num_rows($consulta);
	if($num_total_registros>0) {
		$clientes = array();
		while($clien = pg_fetch_array($consulta)) {
			$code = $clien[0];			
			$clientes[$code]= $clien[1];
		}
		return $clientes;
	} else { return false; }
	$this->desconectar();
}
function cargarTipoReq(){
	$this->conectar();
$consulta = pg_query($this->cnn_combox, filtrar_sql("select id_tipo_permiso, nombre from tipo_permisos where id_cliente = ".$this->code." order by nombre asc "));
$num_total_registros = pg_num_rows($consulta);
	if($num_total_registros>0){
		$clientes = array();
		while($clien = pg_fetch_array($consulta)){
			$code = $clien[0];					
			$clientes[$code]=$clien[1];
		}
		return $clientes;
	} else { return false; }
	$this->desconectar();
}
function cargarZonas(){
	$this->conectar();
$consulta = pg_query($this->cnn_combox, filtrar_sql("select id_zongeo, nombre from zongeo where id_cliente = ".$this->code." and (id_zongeo=".$_SESSION['miss'][1]." or ".$_SESSION['miss'][1]." < 1) order by nombre asc "));
$num_total_registros = pg_num_rows($consulta);
	if($num_total_registros>0){
		$clientes = array();
		while($clien = pg_fetch_array($consulta)){
			$code = $clien[0];					
			$clientes[$code]=$clien[1];
		}
		return $clientes;
	} else { return false; }
	$this->desconectar();
}

function cargarClasPerm(){
	$this->conectar();
$consulta = pg_query($this->cnn_combox, filtrar_sql("select id, nom from clasperm where ( id_cliente = ".$this->code." or ".$this->code." = -1 ) order by nom asc "));
$num_total_registros = pg_num_rows($consulta);
	if($num_total_registros>0){
		$clientes = array();
		while($clien = pg_fetch_array($consulta)){
			$code = $clien[0];					
			$clientes[$code]=$clien[1];
		}
		return $clientes;
	} else { return false; }
	$this->desconectar();
}


}?>