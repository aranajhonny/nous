<?php 

function ComponerComboxAreas($id, $label, $estilo){ 
$cnn_combox = pg_connect("host=localhost port=5432 dbname=nous user=postgres password=@BigNous$2014",PGSQL_CONNECT_FORCE_NEW);
	$html = array();
	$ts = pg_query($cnn_combox, filtrar_sql("select id_area, descripcion from areas where 
	id_dependencia = $id order by descripcion asc "));
	$t = pg_num_rows($ts);
	if($t!=false && $t>0){ 
		while($t = pg_fetch_array($ts)){ 
			$qs=pg_query($cnn_combox, filtrar_sql("select count(id_area) from areas 
			where id_dependencia = ".$t[0])); $qs=pg_fetch_array($qs); 
			if($qs[0]==0){	
				$html [$t[0]] = "$estilo ".$t[1];
			} else { 
				$html [$t[0]] = "$estilo ".$t[1];
$html = $html + ComponerComboxAreas($t[0], $t[1], $estilo."&emsp;");
			}
		}
	}
pg_close($cnn_combox);
	return $html;
} 


function ComponerComboxAreas2($id, $label, $select, $estilo){ 
$cnn_combox = pg_connect("host=localhost port=5432 dbname=nous user=postgres password=@BigNous$2014",PGSQL_CONNECT_FORCE_NEW);
	$html="";
	$ts = pg_query($cnn_combox, filtrar_sql("select id_area, descripcion from areas where 
	id_dependencia = $id "));
	$t = pg_num_rows($ts);
	if($t!=false && $t>0){ 
		while($t = pg_fetch_array($ts)){ 
			if($select==$t[0]) $seleccionar=" selected "; else $seleccionar="";
			$qs=pg_query($cnn_combox, filtrar_sql("select count(id_area) from areas 
			where id_dependencia = ".$t[0])); $qs=pg_fetch_array($qs); 
			if($qs[0]==0){	
$html .= "<option value='".$t[0]."' $seleccionar > $estilo ".$t[1]."</option>\n";
			} else { 
$html .= "<option value='".$t[0]."' $seleccionar > $estilo ".$t[1]."</option>\n";
$html .= ComponerComboxAreas2($t[0], $t[1], $select, $estilo."&emsp;");
			}
		}
	}	
	$html.="";
pg_close($cnn_combox);
	return $html;
}


function ComponerComboxAreas3($id, $i, $estilo){ 
$cnn_combox = pg_connect("host=localhost port=5432 dbname=nous user=postgres password=@BigNous$2014",PGSQL_CONNECT_FORCE_NEW);
	$html="";
	$ts = pg_query($cnn_combox, filtrar_sql("select areas.id_area, areas.descripcion, id_dependencia, rif, razon_social, areas.id_responsable from  clientes, areas where areas.id_cliente = clientes.id_cliente and  
	id_dependencia = $id "));
	$t = pg_num_rows($ts);
	if($t!=false && $t>0){ $j=1;
		while($t = pg_fetch_array($ts)){ 
			if($select==$t[0]) $seleccionar=" selected "; else $seleccionar="";

if($r[5]==0){ $resp = " - - "; } else { 
$qs = pg_query($cnn_combox, filtrar_sql("select ci, nombre from personal where id_personal = ".$r[5]));
$qs = pg_fetch_array($qs); $resp = $qs[0]." ".$qs[1]; }	
$qs=pg_query($cnn_combox, filtrar_sql("select count(id_area) from areas where id_dependencia = ".$t[0])); 
$qs=pg_fetch_array($qs); 
			if($qs[0]==0){	
$html .= "<tr>
<td class='extra'><div class=' info-tooltip'>";

$html .= "<img src='../img/cross.png' width='15' height='15'  title='Eliminar El Área' rel='tooltip' data-placement='right' onclick='pregunta(".$t[0].")'/>"; 

if ( in_array(331,$_SESSION['acl']) ){
$html .= "<img src='../img/search.png' width='15' height='15' title='Ver datos del Área' rel='tooltip' data-placement='right' onclick=".'"'."location.href='ver.php?area=".$t[0]."'".'"'."   style='margin-left:15px;'/>"; }

if ( in_array(225,$_SESSION['acl']) ){
$html .= "<img src='../img/pencil.png' width='15' height='15' title='Editar Datos del Área' style='margin-left:15px;'  rel='tooltip' data-placement='right' onclick=".'"'."location.href='editar.php?area=".$t[0]."'".'"'." />"; }

if ( in_array(85,$_SESSION['acl']) ){
$html .= "<img src='../img/plus.png' width='15' height='15' title='Agregar Nueva Área' style='margin-left:15px;'  rel='tooltip' data-placement='right' onclick=".'"'."location.href='agregar.php'".'"'." />"; }



$html .= "</div></td>
<td>".$t[3]." ".$t[4]."</td>
<td>$estilo ".$i.".".$j.".- ".$t[1]."</td>
<td>".$resp."</td></tr>";
			} else { 
$html .= "<tr>
<td class='extra'><div class=' info-tooltip'>";

$html .= "<img src='../img/cross.png' width='15' height='15'  title='Eliminar El Área' rel='tooltip' data-placement='right' onclick='pregunta(".$t[0].")'/>"; 

if ( in_array(331,$_SESSION['acl']) ){
$html .= "<img src='../img/search.png' width='15' height='15' title='Ver datos del Área' rel='tooltip' data-placement='right' onclick=".'"'."location.href='ver.php?area=".$t[0]."'".'"'." style='margin-left:15px;'/>"; }

if ( in_array(225,$_SESSION['acl']) ){
$html .= "<img src='../img/pencil.png' width='15' height='15' title='Editar Datos del Área' style='margin-left:15px;'  rel='tooltip' data-placement='right' onclick=".'"'."location.href='editar.php?area=".$t[0]."'".'"'." />"; }

if ( in_array(85,$_SESSION['acl']) ){
$html .= "<img src='../img/plus.png' width='15' height='15' title='Agregar Nueva Área' style='margin-left:15px;'  rel='tooltip' data-placement='right' onclick=".'"'."location.href='agregar.php'".'"'." />"; }

$html .= "</div></td>
<td>".$t[3]." ".$t[4]."</td>
<td>$estilo ".$i.".".$j.".- ".$t[1]."</td>
<td>".$resp."</td>
</tr>";
pg_close($cnn_combox);
$html .= ComponerComboxAreas3($t[0],  $i.".".$j, $estilo."&emsp;");
			}
			$j+=1;
		}
	}	
	$html.="";

	return $html;
}





function ComponerComboxAreas4($id, $i, $estilo){ 
$cnn_combox = pg_connect("host=localhost port=5432 dbname=nous user=postgres password=@BigNous$2014",PGSQL_CONNECT_FORCE_NEW);
	$html="";
	$ts = pg_query($cnn_combox, filtrar_sql("select areas.id_area, areas.descripcion, id_dependencia, rif, razon_social, areas.id_responsable from  clientes, areas where areas.id_cliente = clientes.id_cliente and  
	id_dependencia = $id "));
	$t = pg_num_rows($ts);
	if($t!=false && $t>0){ $j=1;
		while($t = pg_fetch_array($ts)){ 
			if($select==$t[0]) $seleccionar=" selected "; else $seleccionar="";
			if($r[5]==0){ 
				$resp = " - - "; 
			} else { 
				$qs = pg_query($cnn_combox, filtrar_sql("select ci, nombre from personal where id_personal = ".$r[5]));
				$qs = pg_fetch_array($qs); $resp = $qs[0]." ".$qs[1]; 
			}	
			$qs=pg_query($cnn_combox, filtrar_sql("select count(id_area) from areas where id_dependencia = ".$t[0])); 
			$qs=pg_fetch_array($qs); 
			if($qs[0]==0){	
				$html .= "<tr><td class='extra'><div class=' info-tooltip'> 
<img src='../img/cross.png' width='15' height='15'  title='Eliminar El Área' rel='tooltip' data-placement='right' onclick='pregunta(".$t[0].")'/>
<img src='../img/pencil.png' width='15' height='15' title='Editar Datos del Área' style='margin-left:15px;'  rel='tooltip' data-placement='right' onclick=".'"'."location.href='editar.php?area=".$t[0]."'".'"'." />
</div></td><td>$estilo ".$i.".".$j.".- ".$t[1]."</td><td>".$resp."</td></tr>";
			} else { 
				$html .= "<tr><td class='extra'><div class=' info-tooltip'> 
<img src='../img/cross.png' width='15' height='15'  title='Eliminar El Área' rel='tooltip' data-placement='right' onclick='pregunta(".$t[0].")'/>
<img src='../img/pencil.png' width='15' height='15' title='Editar Datos del Área' style='margin-left:15px;'  rel='tooltip' data-placement='right' onclick=".'"'."location.href='editar.php?area=".$t[0]."'".'"'." />
</div></td><td>$estilo ".$i.".".$j.".- ".$t[1]."</td><td>".$resp."</td></tr>";
				pg_close($cnn_combox);
				$html .= ComponerComboxAreas4($t[0],  $i.".".$j, $estilo."&emsp;");
			}
			$j+=1;
		}
	}	
	$html.="";

	return $html;
}?>