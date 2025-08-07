<style>
#PrecioRubroId,#PrecioEntidadId {width:350px}
</style>

<div class="precios form">
<?php  $this->Form->create('Deposito');?>
<form enctype="multipart/form-data" method="post">
	<fieldset style="width:600px">
		<legend><?php __('Nuevo Deposito'); ?></legend>
	<?php
		echo $this->Form->input('entidad_id');
		echo $this->Form->input('rubro_id',array("label"=>"Clasificador"));
		echo $this->Form->input('monto',array("style"=>"width:200px;text-align:right"));
		echo $this->Form->input('banco',array("default"=>"BANCO DE LA NACION"));
		echo $this->Form->input('cuenta');
		echo $this->Form->input('codigo_voucher');

		echo $this->Form->input('file',array("type"=>"file"));
		echo str_replace("","",$this->Form->input('periodo',array("label"=>"Fecha de Deposito","type"=>"date","dateFormat"=>"DMY","maxYear"=>date("Y") + 1)));
	?>
	</fieldset>

<div class="submit">
	<input type="submit" value="Grabar" /> 
	<span class="lbutton"><?php echo $this->Html->link(__('Regresar', true), array('action' => 'index')); ?></span>
</div>
</form>

</div>
<div class="actions">
	<h3><?php __('Acciones'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Precios', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Rubros', true), array('controller' => 'rubros', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Rubro', true), array('controller' => 'rubros', 'action' => 'add')); ?> </li>
	</ul>
</div>

<script>
$(document).ready(function () {
    
    $('#PrecioMonto').keyup(function() {
        var $th = $(this);
        $th.val( $th.val().replace(/[^0-9.]/g, function(str) { return ''; } ) );
    });
});

</script>
