<div class="recibos form">
<form method="post" action="<?php echo Router::url("/recibos/importar") ?>" enctype="multipart/form-data" >
	<fieldset>
		<legend>Importar</legend>


		 Archivo: <input type="file" name="data[archivo]" />


	</fieldset>

<div class="submit">
	<input type="submit" value="Importar" /> 
	<span class="lbutton"><?php echo $this->Html->link(__('Regresar', true), array('action' => 'index')); ?></span>
</div>
</form>



