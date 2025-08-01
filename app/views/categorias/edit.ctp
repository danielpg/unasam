<div class="entidades form">
<?php echo $this->Form->create('Categoria');?>
	<fieldset>
		<legend><?php __('Editar Categoria'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('nombre');
		echo $this->Form->input('codigo',array("style"=>"width:80px"));
		//echo $this->Form->input('estado',array("label"=>"Estado","type"=>"select","options"=>array(1 => "Activo",0 => "Inactivo")));
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

		<li><?php echo $this->Html->link(__('Eliminar', true), array('action' => 'delete', $this->Form->value('Entidad.id')), null, sprintf(__('Esta seguro de querer eliminar # %s?', true), $this->Form->value('Entidad.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Entidades', true), array('action' => 'index'));?></li>
	</ul>
</div>
