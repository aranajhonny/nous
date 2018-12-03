<?php 

	
function ComponerComboxCompUnid($id, $label, $select, $estilo){ 
$cnn_combox = pg_connect("host=localhost port=5432 dbname=nous user=combox password=C::%/|box15*",PGSQL_CONNECT_FORCE_NEW);
	$html="";
	$ts = pg_query($cnn_combox, filtrar_sql("select id_composicion, nombre from composiciones where id_dependencia = $id"));
	$t = pg_num_rows($ts);
	if($t!=false && $t>0){ 
		while($t = pg_fetch_array($ts)){ 
			if($select==$t[0]) $seleccionar=" selected "; else $seleccionar="";
			$qs=pg_query($cnn_combox, filtrar_sql("select count(id_composicion) from composiciones where id_dependencia = ".$t[0])); 
			$qs=pg_fetch_array($qs); 
			if($qs[0]==0){	
$html .= "<option value='".$t[0]."' $seleccionar > $estilo ".$t[1]."</option>\n";
			} else { 
$html .= "<option value='".$t[0]."' $seleccionar > $estilo ".$t[1]."</option>\n";
$html .= ComponerComboxCompUnid($t[0], $t[1], $select, $estilo."&emsp;");
			}
		}
	}	
	$html.="";
	pg_close($cnn_combox);
	return $html;
}

function ComponerComboxCompUnid2($id, $i, $estilo){ 
$cnn_combox = pg_connect("host=localhost port=5432 dbname=nous user=combox password=C::%/|box15*",PGSQL_CONNECT_FORCE_NEW);
	$html="";
$ts = pg_query($cnn_combox, filtrar_sql("select composiciones.id_composicion, composiciones.nombre, confunid.codigo_principal, descripcion from composiciones, confunid where composiciones.id_confunid = confunid.id_confunid and id_dependencia= $id "));
	$t = pg_num_rows($ts);
	if($t!=false && $t>0){ $j=1;
		while($t = pg_fetch_array($ts)){ 
			
$qs=pg_query($cnn_combox, filtrar_sql("select count(id_composicion) from composiciones where id_dependencia = ".$t[0]));
			$qs=pg_fetch_array($qs); 
			if($qs[0]==0){	
			
			
$html .= "<tr><td><div class=' info-tooltip'>
<img src='../img/cross.png' width='15' height='15' title='Eliminar Composición' rel='tooltip' data-placement='right' onclick='pregunta(".$t[0].");' style='margin-right:15px;'/>

<img src='../img/search.png' width='15' height='15' title='Ver datos de la composición' rel='tooltip' data-placement='right' onclick=".'"'."location.href='ver.php?comp=".$t[0]."'".'"'."/>

<img src='../img/pencil.png' width=15 height=15 title=Editar Datos de la composición style=margin-left:15px;  rel=tooltip data-placement=right onclick=".'"'."location.href='editar.php?comp=".$t[0]."'".'"'." />

<img src='../img/plus.png' width=15 height=15 title=Agregar Nueva composición style=margin-left:15px;  rel=tooltip data-placement=right onclick=".'"'."location.href='agregar.php'".'"'." />

</div></td>
<td>".$t[2]."</td>
<td>$estilo ".$i.".".$j.".- ".$t[1]."</td>
<td>".$t[3]."</td>
</tr>";
			} else { 
$html .= "<tr><td><div class=' info-tooltip'>
<img src='../img/cross.png' width='15' height='15' title='Eliminar Composición' rel='tooltip' data-placement='right' onclick='pregunta(".$t[0].");' style='margin-right:15px;'/>

<img src='../img/search.png' width='15' height='15' title='Ver datos de la composición' rel='tooltip' data-placement='right' onclick=".'"'."location.href='ver.php?comp=".$t[0]."'".'"'."/>

<img src='../img/pencil.png' width=15 height=15 title=Editar Datos de la composición style=margin-left:15px;  rel=tooltip data-placement=right onclick=".'"'."location.href='editar.php?comp=".$t[0]."'".'"'." />

<img src='../img/plus.png' width=15 height=15 title=Agregar Nueva composición style=margin-left:15px;  rel=tooltip data-placement=right onclick=".'"'."location.href='agregar.php'".'"'." />

</div></td>
<td>".$t[2]."</td>
<td>$estilo ".$i.".".$j.".- ".$t[1]."</td>
<td>".$t[3]."</td>
</tr>";
$html .= ComponerComboxCompUnid2($t[0], $i.".".$j, $estilo."&emsp;");

			}
			$j+=1;
		}
	}	
	$html.="";
	pg_close($cnn_combox);
	return $html;
}
?>