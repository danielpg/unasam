<div class="recibos form">
<?php echo $this->Form->create('Recibo');?>
	<fieldset>
		<legend><?php __('Editar Recibo'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('precio_id');
		//echo $this->Form->input('entidad_id');
		echo $this->Form->input('nombres',array("disabled"=>"disabled"));
		echo $this->Form->input('cantidad' , array("label"=>"Cantidad/Dias","options" => array_combine(range(1,50),range(1,50))));
		//echo $this->Form->input('monto');
		if(date("Y-m-d")==substr($this->Form->value('created'),0,10)):
		echo $this->Form->input('extorno',array("label"=>"Extornar Recibo?","type"=>"select","options" => array(0 => "No",1 => "Si")));
		endif;
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

		<li><?php echo $this->Html->link(__('Eliminar', true), array('action' => 'delete', $this->Form->value('Recibo.id')), null, sprintf(__('Esta seguro de querer eliminar # %s?', true), $this->Form->value('Recibo.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Recibos', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Precios', true), array('controller' => 'precios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Precio', true), array('controller' => 'precios', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Entidades', true), array('controller' => 'entidades', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Entidad', true), array('controller' => 'entidades', 'action' => 'add')); ?> </li>
	</ul>
</div>
