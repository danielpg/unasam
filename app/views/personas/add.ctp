<div class="personas form">
<?php $this->Form->create('Persona');?>
<form method="post" >
	<fieldset>
	
	<?php
		//echo $this->Form->input('tipo',array("options"=>array(TEMPRESA=>"Empresa",TPERSONA=>"Persona Natural")));
	if($this->params["url"]["tipo"] == TEMPLEADO){
		echo "<legend>Agregar Empleado</legend>";
		echo $this->Form->input('dni_ruc',array("label"=>"DNI","style"=>"width:80px","maxlength"=>8));
		echo $this->Form->input('nombre',array("label"=>"Nombre"));
		echo $this->Form->input('tipo',array("type"=>"hidden","value" => TEMPLEADO));
	}else if($this->params["url"]["tipo"] == TDOCENTE){
		echo "<legend>Agregar Docente</legend>";
		echo $this->Form->input('dni_ruc',array("label"=>"DNI","style"=>"width:80px","maxlength"=>8));
		echo $this->Form->input('nombre',array("label"=>"Nombre"));
		echo $this->Form->input('tipo',array("type"=>"hidden","value" => TDOCENTE));
	}else if($this->params["url"]["tipo"] == TEGRESADO){
		echo "<legend>Agregar Egresado</legend>";
		echo $this->Form->input('dni_ruc',array("label"=>"DNI","style"=>"width:80px","maxlength"=>8));
		echo $this->Form->input('nombre',array("label"=>"Nombre"));
		echo $this->Form->input('tipo',array("type"=>"hidden","value" => TEGRESADO));
	}else if($this->params["url"]["tipo"] == TPERSONA){
		echo "<legend>Agregar Persona</legend>";
		echo $this->Form->input('dni_ruc',array("label"=>"DNI","style"=>"width:80px","maxlength"=>8));
		echo $this->Form->input('nombre',array("label"=>"Nombre"));
		echo $this->Form->input('tipo',array("type"=>"hidden","value" => TPERSONA));
	} else if($this->params["url"]["tipo"] == TEMPRESA){
		echo "<legend>Agregar Empresa</legend>";
		echo $this->Form->input('dni_ruc',array("label"=>"RUC","style"=>"width:80px","maxlength"=>11));
		echo $this->Form->input('nombre',array("label"=>"Razon Social"));
		echo $this->Form->input('direccion');
			echo $this->Form->input('tipo',array("type"=>"hidden","value" => TEMPRESA));
	} else if($this->params["url"]["tipo"] == TESTUDIANTE){
		echo "<legend>Agregar Estudiante</legend>";
		echo $this->Form->input('codigo_alumno',array("label"=>"Codigo Alumno","style"=>"width:80px","maxlength"=>14));
		echo $this->Form->input('dni_ruc',array("label"=>"DNI","style"=>"width:80px","maxlength"=>8));
		echo $this->Form->input('nombre',array("label"=>"Nombre"));
		echo $this->Form->input('entidad_id',array("label"=>"Facultad","type"=>"select","options"=>$entidades));
			echo $this->Form->input('tipo',array("type"=>"hidden","value" => TESTUDIANTE));
	} else {

			echo "asdasd";
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
