<div class="recibos form">
<?php $this->Form->create('Recibo');?>
<form method="post" >
	<fieldset>
		<legend><?php __('Reporte'); ?></legend>
	<?php

		echo $this->Form->input('fecha_inicio',array("style"=>"width:80px","default"=>date("d/m/Y",strtotime("-30 days"))));
		echo $this->Form->input('fecha_final',array("style"=>"width:80px","default"=>date("d/m/Y")));

		echo $this->Form->input('tipo',array("options"=>array(
			1  => "RESUMEN CLASIFICADOR",
			2  => "DIARIO",
			3  => "RESUMEN MENSUAL", 
			4  => "EFECTIVO - VUELTO", 
			5  => "DEPOSITOS",
			10 => "INFORME MENSUAL")));

		echo $this->Form->input('oficina_id',array("label"=>"Centros de Costos","options"=>$oficinas));

	?>
	</fieldset>

<div class="submit">
	<input type="submit" value="Descargar" /> 
	<span class="lbutton"><?php echo $this->Html->link(__('Regresar', true), array('action' => 'index')); ?></span>
</div>
</form>

</div>

<script>

		$('#ReciboFechaInicio').datepicker({
			showOn: "button",
			buttonImage: "<?php echo Router::url("/img/calendar.gif") ?>",
			buttonImageOnly: true,
			dateFormat: "dd/mm/yy",
			//minDate: -90, 
			maxDate: 0,
			onSelect: function( selectedDate ) {

			}
		});
		
		//$('#txtstartdate').datepicker({showOn:'focus'}).focus();
	
		//$('#txtenddate').unbind();
		$('#ReciboFechaFinal').datepicker({
			showOn: "button",
			buttonImage: "<?php echo Router::url("/img/calendar.gif") ?>",
			buttonImageOnly: true,
			dateFormat: "dd/mm/yy",
			//minDate: -90,
			maxDate: 0,
			onSelect: function( selectedDate ) {

			}
		});
		

</script>
