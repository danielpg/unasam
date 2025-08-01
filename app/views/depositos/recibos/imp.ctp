<html>
<head>
<style>
body{margin:0;padding:0}
*{font-family:'Arial';font-size:14px}
.title{text-align:center;font-size:16px;margin-bottom:20px;margin-top:20px}
.concepto{text-align:center;font-size:16px;margin-top:20px;margin-bottom:20px}
.box{padding:10px}
</style>
<script type="text/javascript">
function printMe() {
  window.print();
}

  window.print();
</script>
</head>
<body>

<div class="box" style="width:353px;height:537px;border:1px solid black;border-radius:10px">


<div class="title">RECIBO DE INGRESO <?php echo substr($recibo['Recibo']['created'],0,4)  ?></div>




<div>
	<table border= 0 cellpadding=0 cellspacing=0>
	<tr>
		<td valign="top"> Recibí de: </td>
		<td>
			<?php if($recibo['Persona']['tipo']==TEMPRESA): ?>
				<?php echo $recibo['Persona']['nombre']; ?><br>
				RUC:<?php echo $recibo['Persona']['dni_ruc']; ?><br >
			<?php elseif($recibo['Persona']['tipo']==TESTUDIANTE): ?>
				<?php echo $recibo['Persona']['nombre']; ?><br>
				<?php echo $recibo['Persona']['codigo_alumno']; ?><br >
				<?php echo $recibo['Entidad']['nombre']; ?><br >
			<?php else: ?>
				<?php echo $recibo['Recibo']['nombres']; ?>
			<?php endif; ?>

		</td>
	</tr>
	</table>
</div>


			<div>Importe: S/. <?php echo mformat($recibo['Recibo']['monto']); ?></div>

			<div>Importe en letras: <?php echo 	monto2words($recibo['Recibo']['monto']); ?>/100 NUEVOS SOLES</div>


<div style="height:100px"></div>




			<div>VERIFIQUE SU PAGO/NO SE ACEPTA DEVOLUCIONES</div>
			<div>Recibo <?php echo "N° ".recibo_id($recibo['Recibo']['id']) ?></div>
			<div><?php echo $recibo['P.Entidad']['codigo']."/".$recibo['P.Entidad']['nombre']." ".$recibo['Rubro']['codigo']; ?><br>
			<div><?php echo $recibo['Precio']['codigo']."/".$recibo['Precio']['nombre']; ?></div>


			<div><?php echo $recibo['Recibo']['created']; ?></div>



			<div style="font-size:24px"><?php echo "N° ".recibo_id($recibo['Recibo']['id']); ?></div>

</div>

</body>
</html>
