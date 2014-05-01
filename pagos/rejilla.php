<?php
include ("../conectar.php");
include ("../funciones/fechas.php");

$codproveedor=$_POST["codproveedor"];
$estado=$_POST["cboEstados"];
$fechainicio=$_POST["fechainicio"];
if ($fechainicio<>"") { $fechainicio=explota($fechainicio); }
$fechafin=$_POST["fechafin"];
if ($fechafin<>"") { $fechafin=explota($fechafin); }

$cadena_busqueda=$_POST["cadena_busqueda"];

$where="1=1";
if ($codproveedor <> "") { $where.=" AND facturasp.codproveedor='$codproveedor'"; }
if ($estado <> 0) { $where.=" AND estado='$estado'"; }
if (($fechainicio<>"") and ($fechafin<>"")) {
	$where.=" AND fecha between '".$fechainicio."' AND '".$fechafin."'";
} else {
	if ($fechainicio<>"") {
		$where.=" and fecha>='".$fechainicio."'";
	} else {
		if ($fechafin<>"") {
			$where.=" and fecha<='".$fechafin."'";
		}
	}
}

$where.=" ORDER BY facturasp.codfactura DESC";
$query_busqueda="SELECT count(*) as filas FROM facturasp LEFT JOIN pagos ON facturasp.codfactura=pagos.codfactura AND facturasp.codproveedor=pagos.codproveedor INNER JOIN proveedores ON facturasp.codproveedor=proveedores.codproveedor WHERE facturasp.borrado=0 AND ".$where;

$rs_busqueda=mysql_query($query_busqueda);
$filas=mysql_result($rs_busqueda,0,"filas");

?>
<html>
	<head>
		<title>Proveedores</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
		<script language="javascript">

		function ver_cobros(codfactura,codproveedor) {
			parent.location.href="ver_cobros.php?codfactura=" + codfactura + "&codproveedor=" + codproveedor + "&cadena_busqueda=<? echo $cadena_busqueda?>";
		}
		
		function inicio() {
			var numfilas=document.getElementById("numfilas").value;
			var indi=parent.document.getElementById("iniciopagina").value;
			var contador=1;
			var indice=0;
			if (indi>numfilas) { 
				indi=1; 
			}
			parent.document.formulario.filas.value=numfilas;
			parent.document.formulario.paginas.innerHTML="";		
			while (contador<=numfilas) {
				texto=contador + "-" + parseInt(contador+9);
				if (indi==contador) {
					parent.document.formulario.paginas.options[indice]=new Option (texto,contador);
					parent.document.formulario.paginas.options[indice].selected=true;
				} else {
					parent.document.formulario.paginas.options[indice]=new Option (texto,contador);
				}
				indice++;
				contador=contador+10;
			}
		}
		</script>
	</head>

	<body onload=inicio()>	
		<div id="pagina">
			<div id="zonaContenido">
			<div align="center">
			<table class="fuente8" width="87%" cellspacing=0 cellpadding=3 border=0 ID="Table1">
			<form name="form1" id="form1">
			<input type="hidden" name="numfilas" id="numfilas" value="<? echo $filas?>">
				<?	if ($filas > 0) { ?>
						<? $sel_resultado="SELECT distinct facturasp.codfactura,facturasp.fecha as fecha,totalfactura,estado,facturasp.fechapago,proveedores.nombre as nombre,proveedores.codproveedor FROM facturasp LEFT JOIN pagos ON facturasp.codfactura=pagos.codfactura AND facturasp.codproveedor=pagos.codproveedor INNER JOIN proveedores ON facturasp.codproveedor=proveedores.codproveedor WHERE facturasp.borrado=0 AND ".$where;
						   $res_resultado=mysql_query($sel_resultado);
						   $contador=0;					   
						   while ($contador < mysql_num_rows($res_resultado)) { 
						   		if (mysql_result($res_resultado,$contador,"estado") == 1) { $estado="Sin pagar"; } else { $estado="Pagada"; } 
								if ($contador % 2) { $fondolinea="itemParTabla"; } else { $fondolinea="itemImparTabla"; }?>
						<tr class="<?php echo $fondolinea?>">
							<td class="aCentro" width="8%"><? echo $contador+1;?></td>
							<td width="12%"><div align="center"><? echo mysql_result($res_resultado,$contador,"codfactura")?></div></td>
							<td width="26%"><div align="left"><? echo mysql_result($res_resultado,$contador,"nombre")?></div></td>							
							<td width="9%"><div align="center"><? echo number_format(mysql_result($res_resultado,$contador,"totalfactura"),2,",",".")?></div></td>
							<? $sel_cobros="SELECT sum(importe) as aportaciones FROM pagos WHERE codfactura='".mysql_result($res_resultado,$contador,"codfactura")."' AND codproveedor='".mysql_result($res_resultado,$contador,"codproveedor")."'";
								$rs_cobros=mysql_query($sel_cobros);
								$aportaciones=mysql_result($rs_cobros,0,"aportaciones"); 
								$pendiente=mysql_result($res_resultado,$contador,"totalfactura") - $aportaciones; ?>
							<td class="aDerecha" width="10%"><div align="center"><? echo number_format($pendiente,2,",",".")?></div></td>
							<td class="aDerecha" width="10%"><div align="center"><? echo implota(mysql_result($res_resultado,$contador,"fecha"))?></div></td>
							<td class="aDerecha" width="10%"><div align="center"><? echo $estado?></div></td>							
							<td width="10%"><div align="center"><? echo implota(mysql_result($res_resultado,$contador,"fechapago"));?></div></td>
							<td width="5%"><div align="center"><a href="#"><img src="../img/dinero.jpg" width="16" height="16" border="0" onClick="ver_cobros('<?php echo mysql_result($res_resultado,$contador,"codfactura")?>',<?php echo mysql_result($res_resultado,$contador,"codproveedor")?>)" title="Ver Cobros"></a></div></td>
						</tr>
						<? $contador++;
							}
						?>			
					</table>
					<? } else { ?>
					<table class="fuente8" width="87%" cellspacing=0 cellpadding=3 border=0>
						<tr>
							<td width="100%" class="mensaje"><?php echo "No hay ninguna factura que cumpla con los criterios de b&uacute;squeda";?></td>
					    </tr>
					</table>					
					<? } ?>	
					</form>				
				</div>
			</div>
		  </div>			
		</div>
	</body>
</html>
