<style>
#PrecioRubroId,#PrecioEntidadId {width:350px}
</style>

<div class="precios form">
<?php echo $this->Form->create('Precio');?>
	<fieldset style="width:400px">
		<legend><?php __('Editar Precio'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('rubro_id');
		echo $this->Form->input('entidad_id');
		echo $this->Form->input('nombre');
		echo $this->Form->input('codigo',array("style"=>"width:80px"));
		echo $this->Form->input('monto',array("style"=>"width:100px;text-align:right"));
		echo $this->Form->input('estado',array("label"=>"Estado","type"=>"select","options"=>array(1 => "Activo",0 => "Inactivo")));
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

		<li><?php echo $this->Html->link(__('Eliminar', true), array('action' => 'delete', $this->Form->value('Precio.id')), null, sprintf(__('Esta seguro de querer eliminar # %s?', true), $this->Form->value('Precio.id'))); ?></li>
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
