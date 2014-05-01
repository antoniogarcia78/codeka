<?php 
include ("../conectar.php");
include ("../funciones/fechas.php");
 
$codfactura=$_GET["codfactura"];
$codproveedor=$_GET["codproveedor"];


$select_facturas="SELECT proveedores.codproveedor,proveedores.nombre,facturasp.codfactura,estado,facturasp.fechapago,totalfactura FROM facturasp LEFT JOIN pagos ON facturasp.codfactura=pagos.codfactura AND facturasp.codproveedor=pagos.codproveedor INNER JOIN proveedores ON facturasp.codproveedor=proveedores.codproveedor WHERE facturasp.codfactura='$codfactura' AND facturasp.codproveedor='$codproveedor'";

$rs_facturas=mysql_query($select_facturas);

$hoy=date("d/m/Y");

$sel_cobros="SELECT sum(importe) as aportaciones FROM pagos WHERE codfactura='$codfactura' AND codproveedor='$codproveedor'";
$rs_cobros=mysql_query($sel_cobros);
$aportaciones=mysql_result($rs_cobros,0,"aportaciones");

?>
<html>
	<head>
		<title>Principal</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
		<link href="../calendario/calendar-blue.css" rel="stylesheet" type="text/css">
		<script type="text/javascript" src="../funciones/validar.js"></script>		
		<script type="text/JavaScript" language="javascript" src="../calendario/calendar.js"></script>
		<script type="text/JavaScript" language="javascript" src="../calendario/lang/calendar-sp.js"></script>
		<script type="text/JavaScript" language="javascript" src="../calendario/calendar-setup.js"></script>
		<script language="javascript">
		var cursor;
		if (document.all) {
		// Está utilizando EXPLORER
		cursor='hand';
		} else {
		// Está utilizando MOZILLA/NETSCAPE
		cursor='pointer';
		}
		
		
		function cancelar() {
			location.href="index.php";
		}
		
		function cambiar_estado() {
			var estado=document.getElementById("cboEstados").value;
			var codfactura=document.getElementById("codfactura").value;
			var codproveedor=document.getElementById("codproveedor").value;
			miPopup = window.open("actualizarestado.php?estado="+estado+"&codfactura="+codfactura+"&codproveedor="+codproveedor,"frame_datos","width=700,height=80,scrollbars=yes");
		}
		
		function cambiar_vencimiento() {
			var fechapago=document.getElementById("fechapago").value;
			var codfactura=document.getElementById("codfactura").value;
			var codproveedor=document.getElementById("codproveedor").value;
			miPopup = window.open("actualizarvencimiento.php?fechapago="+fechapago+"&codfactura="+codfactura+"&codproveedor="+codproveedor,"frame_datos","width=700,height=80,scrollbars=yes");
		}
			
		</script>
	</head>
	<body>
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header">PAGOS </div>
				<div id="frmBusqueda">
				<form id="formdatos" name="formdatos" method="post" action="guardar_cobro.php">
					<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0>
					<? 
					 	$codproveedor=mysql_result($rs_facturas,0,"codproveedor");
						$nombre=mysql_result($rs_facturas,0,"nombre");
						$codfactura=mysql_result($rs_facturas,0,"codfactura");
						$totalfactura=mysql_result($rs_facturas,0,"totalfactura");
						$estado=mysql_result($rs_facturas,0,"estado"); 
						$fechapago=mysql_result($rs_facturas,0,"fechapago");
						if ($fechapago=="0000-00-00") { $fechapago=""; } else { $fechapago=implota($fechapago); } 						
						?>
						<tr>
							<td width="15%">C&oacute;digo de proveedor</td>
						    <td width="43%"><? echo $codproveedor?></td>
					        <td width="42%" rowspan="14" align="left" valign="top"></td>
						</tr>
						<tr>
							<td width="15%">Nombre</td>
						    <td width="43%"><? echo $nombre?></td>
					        <td width="42%" rowspan="14" align="left" valign="top"></td>
						</tr>	
						<tr>
							<td width="15%">C&oacute;digo de factura</td>
						    <td width="43%"><? echo $codfactura?></td>
					        <td width="42%" rowspan="14" align="left" valign="top"></td>
						</tr>
						<tr>
							<td width="15%">Importe de la factura</td>
						    <td width="43%"><? echo number_format($totalfactura,2)?></td>
					        <td width="42%" rowspan="14" align="left" valign="top"></td>
						</tr>
						<? $pendiente=$totalfactura-$aportaciones; ?>
						<tr>
							<td width="15%">Pendiente por pagar</td>
						    <td width="43%"><input type="text" name="pendiente" id="pendiente" value="<? echo number_format($pendiente,2,".","")?>" readonly="yes" class="cajaTotales"> &#8364;</td>
					        <td width="42%" rowspan="14" align="left" valign="top"></td>
						</tr>
						<tr>
							<td width="15%">Estado de la factura</td>
						    <td width="43%"><select id="cboEstados" name="cboEstados" class="comboMedio" onChange="cambiar_estado()">
								<? if ($estado==1) { ?><option value="1" selected="selected">Sin Pagar</option>
								<option value="2">Pagada</option><? } else { ?>
								<option value="1">Sin Pagar</option>
								<option value="2" selected="selected">Pagada</option>
								<? } ?> 			
								</select></td>
					        <td width="42%" rowspan="14" align="left" valign="top"></td>
						</tr>	
						<tr>
							<td width="15%">Fecha de pago</td>
						    <td width="43%"><input id="fechapago" type="text" class="cajaPequena" NAME="fechapago" maxlength="10" value="<? echo $fechapago?>" readonly><img src="../img/calendario.png" name="Image1" id="Image1" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'" title="Calendario">
        <script type="text/javascript">
					Calendar.setup(
					  {
					inputField : "fechapago",
					ifFormat   : "%d/%m/%Y",
					button     : "Image1"
					  }
					);
		</script><img src="../img/disco.png" name="Image2" id="Image2" width="16" height="16" border="0" id="Image2" onMouseOver="this.style.cursor='pointer'" title="Guardar fecha" onClick="cambiar_vencimiento()"></td>
					        <td width="42%" rowspan="14" align="left" valign="top"></td>
						</tr>										
					</table>
					</form>
			  </div>
			  <br>
			  <div id="frmBusqueda">
				<form id="formulario" name="formulario" method="post" action="frame_cobros.php" target="frame_cobros">
					<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0>
						<tr>
							<td width="15%">Fecha del pago</td>
						    <td width="35%"><input id="fechapago2" type="text" class="cajaPequena" NAME="fechapago2" maxlength="10" value="<? echo $hoy?>" readonly><img src="../img/calendario.png" name="Image1" id="Image1" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'" title="Calendario">
        <script type="text/javascript">
					Calendar.setup(
					  {
					inputField : "fechapago2",
					ifFormat   : "%d/%m/%Y",
					button     : "Image1"
					  }
					);
		</script></td>
					        <td width="50%" rowspan="14" align="left" valign="top"><ul id="lista-errores"></ul></td>
						</tr>
						<tr>
							<td width="15%">Importe</td>
						    <td width="35%"><input id="Rimporte" type="text" class="cajaPequena" NAME="Rimporte" maxlength="12"> &#8364;</td>
					        <td width="50%" rowspan="14" align="left" valign="top"></td>
						</tr>	
						<?php
					  	$query_fp="SELECT * FROM formapago WHERE borrado=0 ORDER BY nombrefp ASC";
						$res_fp=mysql_query($query_fp);
						$contador=0;
					  ?>
						<tr>
							<td width="15%">Forma de pago</td>
							<td width="35%"><select id="AcboFP" name="AcboFP" class="comboGrande">
							
								<option value="0">Seleccione una forma de pago</option>
								<?php
								while ($contador < mysql_num_rows($res_fp)) { ?>
								<option value="<?php echo mysql_result($res_fp,$contador,"codformapago")?>"><?php echo mysql_result($res_fp,$contador,"nombrefp")?></option>
								<? $contador++;
								} ?>				
								</select>							</td>
								<td width="50%" rowspan="14" align="left" valign="top"></td>
				        </tr>
						<tr>
							<td width="15%">Num. Documento</td>
						    <td width="35%"><input id="anumdocumento" type="text" class="cajaMedia" NAME="anumdocumento" maxlength="30"></td>
					        <td width="50%" rowspan="14" align="left" valign="top"></td>
						</tr>	
						<tr>
							<td width="15%">Observaciones</td>
						    <td width="35%"><textarea rows="5" cols="30" class="areaTexto" name="observaciones" id="observaciones"></textarea></td>
					        <td width="40%" rowspan="14" align="left" valign="top"></td>
						</tr>																	
					</table>
			  </div>
				<div id="botonBusqueda">
					<input type="hidden" name="id" id="id">
					<input type="hidden" name="accion" id="accion" value="insertar">
					<input type="hidden" name="codproveedor" id="codproveedor" value="<? echo $codproveedor?>">
					<input type="hidden" name="codfactura" id="codfactura" value="<? echo $codfactura?>">
					<img src="../img/botonaceptar.jpg" width="85" height="22" onClick="validar(formulario,true)" border="1" onMouseOver="style.cursor=cursor">
					<img src="../img/botoncancelar.jpg" width="85" height="22" onClick="cancelar()" border="1" onMouseOver="style.cursor=cursor">
			  </div>
			  </form>
			  <br>
			  <div id="frmBusqueda">
			  <div id="cabeceraResultado2" class="header">
					relacion de PAGOS </div>
				<div id="frmResultado2">
				<table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0 ID="Table1">
						<tr class="cabeceraTabla">
							<td width="10%">ITEM</td>
							<td width="12%">FECHA</td>
							<td width="12%">IMPORTE </td>							
							<td width="20%">FORMA PAGO</td>
							<td width="20%">N. DOCUMENTO</td>
							<td width="15%">FECHA PAGO</td>
							<td width="5%">OBV.</td>
							<td width="6%">&nbsp;</td>
						</tr>
				</table>
				</div>
					<div id="lineaResultado">
					<iframe width="100%" height="250" id="frame_cobros" name="frame_cobros" frameborder="0" src="frame_cobros.php?accion=ver&codfactura=<? echo $codfactura?>&codproveedor=<? echo $codproveedor?>">
						<ilayer width="100%" height="250" id="frame_cobros" name="frame_cobros"></ilayer>
					</iframe>
					<iframe id="frame_datos" name="frame_datos" width="0" height="0" frameborder="0">
					<ilayer width="0" height="0" id="frame_datos" name="frame_datos"></ilayer>
					</iframe>
				</div>
			</div>
			  </div>
		  </div>
		</div>
	</body>
</html>
