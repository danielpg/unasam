<div class="oficinas form">
<?php echo $this->Form->create('Oficina');?>
	<fieldset>
		<legend><?php __('Editar Centros de Costos'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('nombre');
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

		<li><?php echo $this->Html->link(__('Eliminar', true), array('action' => 'delete', $this->Form->value('Oficina.id')), null, sprintf(__('Esta seguro de querer eliminar # %s?', true), $this->Form->value('Oficina.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Oficinas', true), array('action' => 'index'));?></li>
	</ul>
</div>
