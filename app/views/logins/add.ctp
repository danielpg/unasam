
<div class="logins form">
<?php echo $this->Form->create('Login');?>
	<fieldset style="width:400px">
		<legend><?php __('Agregar Usuario'); ?></legend>

<?php
		echo $this->Form->input('role_id',array("label"=>"Rol")); ?>

<div id="div_oficina">
<?php	echo $this->Form->input('oficina_id',array("label"=>"Centro de Costos")); ?>
</div>

<?php	echo $this->Form->input('username',array("label"=>"Usuario"));

		echo $this->Form->input('password',array("label"=>"Contraseña"));
		echo $this->Form->input('nombres');
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

		<li><?php echo $this->Html->link(__('Listado Logins', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('Listado Registros', true), array('controller' => 'registros', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Registro', true), array('controller' => 'registros', 'action' => 'add')); ?> </li>
	</ul>
</div>


<script type="text/javascript">

$("#div_oficina").hide();

$('#LoginRoleId').change(function() {
	if($('#LoginRoleId').val()==1){
		$("#div_oficina").hide();
	} else {
		$("#div_oficina").show();
	}

});


</script>
