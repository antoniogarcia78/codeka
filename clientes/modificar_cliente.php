<?php include ("../conectar.php"); 

$codcliente=$_GET["codcliente"];

$query="SELECT * FROM clientes WHERE codcliente='$codcliente'";
$rs_query=mysql_query($query);

?>
<html>
	<head>
		<title>Principal</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
		<script type="text/javascript" src="../funciones/validar.js"></script>
		<script language="javascript">
		
		function cancelar() {
			location.href="index.php?cadena_busqueda=<? echo $cadena_busqueda?>";
		}
		
		var cursor;
		if (document.all) {
		// Está utilizando EXPLORER
		cursor='hand';
		} else {
		// Está utilizando MOZILLA/NETSCAPE
		cursor='pointer';
		}
		
		function limpiar() {
			document.getElementById("formulario").reset();
		}
	
		</script>
	</head>
	<body>
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header">MODIFICAR CLIENTE </div>
				<div id="frmBusqueda">
				<form id="formulario" name="formulario" method="post" action="guardar_cliente.php">
					<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0>
						<tr>
							<td>C&oacute;digo</td>
							<td><?php echo $codcliente?></td>
						    <td width="42%" rowspan="14" align="left" valign="top"><ul id="lista-errores"></ul></td>
						</tr>
						<tr>
							<td width="15%">Nombre</td>
						    <td width="43%"><input NAME="Anombre" type="text" class="cajaGrande" id="nombre" size="45" maxlength="45" value="<?php echo mysql_result($rs_query,0,"nombre")?>"></td>
				        </tr>
						<tr>
						  <td>NIF / CIF</td>
						  <td><input id="nif" type="text" class="cajaPequena" NAME="anif" maxlength="15" value="<?php echo mysql_result($rs_query,0,"nif")?>"></td>
				      </tr>
						<tr>
						  <td>Direcci&oacute;n</td>
						  <td><input NAME="adireccion" type="text" class="cajaGrande" id="direccion" size="45" maxlength="45" value="<?php echo mysql_result($rs_query,0,"direccion")?>"></td>
				      </tr>
						<tr>
						  <td>Localidad</td>
						  <td><input NAME="alocalidad" type="text" class="cajaGrande" id="localidad" size="35" maxlength="35" value="<?php echo mysql_result($rs_query,0,"localidad")?>"></td>
				      </tr>
					  <?php
					  	$codprovincia=mysql_result($rs_query,0,"codprovincia");
					  	$query_provincias="SELECT * FROM provincias ORDER BY nombreprovincia ASC";
						$res_provincias=mysql_query($query_provincias);
						$contador=0;
					  ?>
						<tr>
							<td width="15%">Provincia</td>
							<td width="43%"><select id="cboProvincias" name="cboProvincias" class="comboGrande">
							<option value="0">Seleccione una provincia</option>
								<?php
								while ($contador < mysql_num_rows($res_provincias)) { 
									if ($codprovincia == mysql_result($res_provincias,$contador,"codprovincia")) {?>
								<option value="<?php echo mysql_result($res_provincias,$contador,"codprovincia")?>" selected="selected"><?php echo mysql_result($res_provincias,$contador,"nombreprovincia")?></option>
								<? } else { ?>
									<option value="<?php echo mysql_result($res_provincias,$contador,"codprovincia")?>"><?php echo mysql_result($res_provincias,$contador,"nombreprovincia")?></option>
								<? } $contador++;
								} ?>				
								</select>							</td>
				        </tr>
						<?php
						$codformapago=mysql_result($rs_query,0,"codformapago");
					  	$query_formapago="SELECT * FROM formapago WHERE borrado=0 ORDER BY nombrefp ASC";
						$res_formapago=mysql_query($query_formapago);
						$contador=0;
					  ?>
						<tr>
							<td width="15%">Forma de pago</td>
							<td width="43%"><select id="cboFPago" name="cboFPago" class="comboGrande">
							<option value="0">Seleccione una forma de pago</option>
								<?php
								while ($contador < mysql_num_rows($res_formapago)) { 
									if ($codformapago == mysql_result($res_formapago,$contador,"codformapago")) { ?>
								<option value="<?php echo mysql_result($res_formapago,$contador,"codformapago")?>" selected="selected"><?php echo mysql_result($res_formapago,$contador,"nombrefp")?></option>
								<? } else { ?>
								<option value="<?php echo mysql_result($res_formapago,$contador,"codformapago")?>"><?php echo mysql_result($res_formapago,$contador,"nombrefp")?></option>
								<? } $contador++;
								} ?>	
											</select>							</td>
				        </tr>
						<?php
						$codentidad=mysql_result($rs_query,0,"codentidad");
					  	$query_entidades="SELECT * FROM entidades WHERE borrado=0 ORDER BY nombreentidad ASC";
						$res_entidades=mysql_query($query_entidades);
						$contador=0;
					  ?>
						<tr>
							<td width="15%">Entidad Bancaria</td>
							<td width="43%"><select id="cboBanco" name="cboBanco" class="comboGrande">
							<option value="0">Seleccione una Entidad Bancaria</option>
									<?php
								while ($contador < mysql_num_rows($res_entidades)) { 
									if ($codentidad == mysql_result($res_entidades,$contador,"codentidad")) { ?>
								<option value="<?php echo mysql_result($res_entidades,$contador,"codentidad")?>" selected="selected"><?php echo mysql_result($res_entidades,$contador,"nombreentidad")?></option>
								<? } else { ?>
								<option value="<?php echo mysql_result($res_entidades,$contador,"codentidad")?>"><?php echo mysql_result($res_entidades,$contador,"nombreentidad")?></option>
								<? } $contador++;
								} ?>
											</select>							</td>
				        </tr>
						<tr>
							<td>Cuenta bancaria</td>
							<td><input id="cuentabanco" type="text" class="cajaGrande" NAME="acuentabanco" maxlength="20" value="<?php echo mysql_result($rs_query,0,"cuentabancaria")?>"></td>
					    </tr>
						<tr>
							<td>C&oacute;digo postal </td>
							<td><input id="codpostal" type="text" class="cajaPequena" NAME="acodpostal" maxlength="5" value="<?php echo mysql_result($rs_query,0,"codpostal")?>"></td>
					    </tr>
						<tr>
							<td>Tel&eacute;fono </td>
							<td><input id="telefono" name="atelefono" type="text" class="cajaPequena" maxlength="14" value="<?php echo mysql_result($rs_query,0,"telefono")?>"></td>
					    </tr>
						<tr>
							<td>M&oacute;vil</td>
							<td><input id="movil" name="amovil" type="text" class="cajaPequena" maxlength="14" value="<?php echo mysql_result($rs_query,0,"movil")?>"></td>
					    </tr>
						<tr>
							<td>Correo electr&oacute;nico  </td>
							<td><input NAME="aemail" type="text" class="cajaGrande" id="email" size="35" maxlength="35" value="<?php echo mysql_result($rs_query,0,"email")?>"></td>
					    </tr>
												<tr>
							<td>Direcci&oacute;n web </td>
							<td><input NAME="aweb" type="text" class="cajaGrande" id="web" size="45" maxlength="45" value="<?php echo mysql_result($rs_query,0,"web")?>"></td>
					    </tr>
					</table>
			  </div>
				<div id="botonBusqueda">
					<img src="../img/botonaceptar.jpg" width="85" height="22" onClick="validar(formulario,true)" border="1" onMouseOver="style.cursor=cursor">
					<img src="../img/botonlimpiar.jpg" width="69" height="22" onClick="limpiar()" border="1" onMouseOver="style.cursor=cursor">
					<img src="../img/botoncancelar.jpg" width="85" height="22" onClick="cancelar()" border="1" onMouseOver="style.cursor=cursor">
					<input id="accion" name="accion" value="modificar" type="hidden">
					<input id="id" name="id" value="" type="hidden">
					<input id="codcliente" name="codcliente" value="<?php echo $codcliente?>" type="hidden">
			  </div>
			  </form>
		  </div>
		  </div>
		</div>
	</body>
</html>
