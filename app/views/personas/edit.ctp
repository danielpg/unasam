<div class="personas form" style="width:500px">
<?php echo $this->Form->create('Persona');?>
	<fieldset>
	<?php
		echo $this->Form->input('id');
	if($this->Form->value('tipo') == TEMPLEADO){
		echo "<legend>Editar Empleado</legend>";
		echo $this->Form->input('dni_ruc',array("label"=>"DNI","style"=>"width:80px","maxlength"=>8));
		echo $this->Form->input('nombre',array("label"=>"Nombre"));
		echo $this->Form->input('tipo',array("type"=>"hidden","value" => TEMPLEADO));
	}else if($this->Form->value('tipo') == TDOCENTE){
		echo "<legend>Editar Docente</legend>";
		echo $this->Form->input('dni_ruc',array("label"=>"DNI","style"=>"width:80px","maxlength"=>8));
		echo $this->Form->input('nombre',array("label"=>"Nombre"));
		echo $this->Form->input('tipo',array("type"=>"hidden","value" => TDOCENTE));
	}else if($this->Form->value('tipo') == TEGRESADO){
		echo "<legend>Editar Egresado</legend>";
		echo $this->Form->input('dni_ruc',array("label"=>"DNI","style"=>"width:80px","maxlength"=>8));
		echo $this->Form->input('nombre',array("label"=>"Nombre"));
		echo $this->Form->input('tipo',array("type"=>"hidden","value" => TEGRESADO));
	}else if($this->Form->value('tipo') == TPERSONA){
		echo "<legend>Editar Persona</legend>";
		echo $this->Form->input('dni_ruc',array("label"=>"DNI","style"=>"width:80px","maxlength"=>8));
		echo $this->Form->input('nombre',array("label"=>"Nombre"));
		echo $this->Form->input('tipo',array("type"=>"hidden","value" => TPERSONA));
	} else if($this->Form->value('tipo') == TEMPRESA){
		echo "<legend>Editar Empresa</legend>";
		echo $this->Form->input('dni_ruc',array("label"=>"RUC","style"=>"width:80px","maxlength"=>11));
		echo $this->Form->input('nombre',array("label"=>"Razon Social"));
		echo $this->Form->input('direccion');
			echo $this->Form->input('tipo',array("type"=>"hidden","value" => TEMPRESA));
	} else if($this->Form->value('tipo') == TESTUDIANTE){
		echo "<legend>Editar Estudiante</legend>";
		echo $this->Form->input('codigo_alumno',array("label"=>"Codigo Alumno","maxlength"=>14));
		echo $this->Form->input('dni_ruc',array("label"=>"DNI","style"=>"width:80px","maxlength"=>8));
		echo $this->Form->input('nombre',array("label"=>"Nombre"));
		echo $this->Form->input('entidad_id',array("label"=>"Facultad","type"=>"select","options"=>$entidades));
			echo $this->Form->input('tipo',array("type"=>"hidden","value" => TESTUDIANTE));
	}


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

		<li><?php echo $this->Html->link(__('Eliminar', true), array('action' => 'delete', $this->Form->value('Persona.id')), null, sprintf(__('Esta seguro de querer eliminar # %s?', true), $this->Form->value('Persona.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Personas', true), array('action' => 'index'));?></li>
	</ul>
</div>


<script>
$(document).ready(function () {
    
    $('#PersonaDniRuc,#PersonaCodigoAlumno').keyup(function() {
        var $th = $(this);
        $th.val( $th.val().replace(/[^0-9]/g, function(str) { return ''; } ) );
    });
});

</script>
