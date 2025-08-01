<div class="oficinas form">
<?php echo $this->Form->create('Oficina');?>
	<fieldset>
		<legend><?php __('Agregar Centro de Costos'); ?></legend>
	<?php
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

		<li><?php echo $this->Html->link(__('List Oficinas', true), array('action' => 'index'));?></li>
	</ul>
</div>
