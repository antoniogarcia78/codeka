<?php
include ("../conectar.php");
include ("../funciones/fechas.php");

$fechainicio=$_POST["fechainicio"];
if ($fechainicio<>"") { $fechainicio=explota($fechainicio); }
$fechafin=$_POST["fechafin"];
if ($fechafin<>"") { $fechafin=explota($fechafin); }

$cadena_busqueda=$_POST["cadena_busqueda"];

$where="1=1";
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

$where.=" ORDER BY fecha DESC";
$query_busqueda="SELECT count(*) as filas FROM librodiario WHERE ".$where;

$rs_busqueda=mysql_query($query_busqueda);
$filas=mysql_result($rs_busqueda,0,"filas");

?>
<html>
	<head>
		<title>Clientes</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
		<script language="javascript">

		function ver_cobros(codfactura) {
			parent.location.href="ver_cobros.php?codfactura=" + codfactura + "&cadena_busqueda=<? echo $cadena_busqueda?>";
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
			<div id="zonaContenido">
			<div align="center">
			<table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0 ID="Table1">
			<form name="form1" id="form1">
			<input type="hidden" name="numfilas" id="numfilas" value="<? echo $filas?>">
				<?	if ($filas > 0) { ?>
						<? $sel_resultado="SELECT librodiario.*,formapago.nombrefp FROM librodiario LEFT JOIN formapago ON librodiario.codformapago=formapago.codformapago WHERE ".$where;
						   $res_resultado=mysql_query($sel_resultado);
						   $contador=0;					   
						   while ($contador < mysql_num_rows($res_resultado)) { 
								if ($contador % 2) { $fondolinea="itemParTabla"; } else { $fondolinea="itemImparTabla"; }?>
						<tr class="<?php echo $fondolinea?>">
						  <? if (mysql_result($res_resultado,$contador,"tipodocumento")==1) { 
						  		$codproveedor=mysql_result($res_resultado,$contador,"codcomercial");
								$sel_proveedor="SELECT nombre FROM proveedores WHERE codproveedor='$codproveedor'";
								$rs_proveedor=mysql_query($sel_proveedor);
								$nombrecomercial=mysql_result($rs_proveedor,0,"nombre");
								$movimiento="Compra"; 
							} else { 
								$codcliente=mysql_result($res_resultado,$contador,"codcomercial");
								$sel_proveedor="SELECT nombre FROM clientes WHERE codcliente='$codcliente'";
								$rs_proveedor=mysql_query($sel_proveedor);
								$nombrecomercial=mysql_result($rs_proveedor,0,"nombre"); 
								$movimiento="Venta";
							}  ?>
							<td class="aCentro" width="5%"><? echo $contador+1;?></td>
							<td width="10%"><div align="center"><? echo implota(mysql_result($res_resultado,$contador,"fecha"))?></div></td>
							<td width="10%"><div align="center"><? echo $movimiento?></div></td>							
							<td width="10%"><div align="center"><? echo mysql_result($res_resultado,$contador,"coddocumento")?></div></td>
							<td class="aDerecha" width="20%"><div align="center"><? echo $nombrecomercial?></div></td>
							<td class="aDerecha" width="20%"><div align="center"><? echo mysql_result($res_resultado,$contador,"nombrefp")?></div></td>
							<td class="aDerecha" width="15%"><div align="center"><? echo mysql_result($res_resultado,$contador,"numpago")?></div></td>							
							<td width="10%"><div align="center"><? echo number_format(mysql_result($res_resultado,$contador,"total"),2,",",".");?></div></td>
						</tr>
						<? $contador++;
							}
						?>			
					</table>
					<? } else { ?>
					<table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0>
						<tr>
							<td width="100%" class="mensaje"><?php echo "No hay ning&uacute;n movimiento que cumpla con los criterios de b&uacute;squeda";?></td>
					    </tr>
					</table>					
					<? } ?>	
					</form>				
			</div>
		  </div>			
		</div>
	</body>
</html>
