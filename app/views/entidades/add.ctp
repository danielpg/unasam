<div class="entidades form">
<?php echo $this->Form->create('Entidad');?>
	<fieldset>
		<legend><?php __('Agregar Entidad'); ?></legend>
	<?php
		echo $this->Form->input('nombre');
		echo $this->Form->input('codigo',array("style"=>"width:80px"));
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

		<li><?php echo $this->Html->link(__('List Entidades', true), array('action' => 'index'));?></li>
	</ul>
</div>
