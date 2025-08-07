
<table border=0 cellspacing=0 cellpadding=0><tr><td>
<div class="recibos form">
<?php echo $this->Form->create('Recibo');?>
	<fieldset style="width:470px">
		<legend><?php __('Nuevo Recibo'); ?></legend>
<?php
		//echo $this->Form->input('precio_id',array("empty"=>true,"style"=>"width:400px"));
		//echo $this->Form->input('entidad_id',array("empty"=>true));
?>

<div>
<div style="float:left">
<?php	echo $this->Form->input('precio_codigo',array("style"=>"width:40px","maxlength" => 4, "tabindex"=> 1 )); ?>
</div>
<div style="float:left;padding-top:20px">
<div id="PrecioShow" style="font-size:12px">
<?php 
if($this->data["Recibo"]["precio"]) echo $this->data["Recibo"]["precio"];
?>
</div>
</div>
<div style="clear:both"></div>
</div>
		
<?php	echo $this->Form->input('precio_id',array("type"=>"hidden"));
		echo $this->Form->input('precio_monto',array("type"=>"hidden"));
		echo $this->Form->input('precio',array("type"=>"hidden"));
?>

<div class="input text"><label for="ReciboDniRuc">DNI o RUC</label>
<input tabindex="2" maxlength="15" name="data[Recibo][dni_ruc]" style="width:100px" type="text" 
value="<?php if(isset($this->data["Recibo"]["dni_ruc"])) echo $this->data["Recibo"]["dni_ruc"] ?>" id="ReciboDniRuc" />
<span class="lbutton"><a href="#" onclick="buscar_persona()"  tabindex="3">Buscar</a></span><span style="display:none;color:blue;font-weight:bold" id="labelbuscando">Buscando...</span>
</div>

<?php
		echo $this->Form->input('nombres',array("label"=>"Nombre o Razon Social", "tabindex"=>"4"));

		//echo $this->Form->input('direccion',array("label"=>"Dirección", "tabindex"=>"5"));

		echo $this->Form->input('persona_id',array("type"=>"hidden"));

		echo $this->Form->input('cantidad' , array("label"=>"Cantidad/Dias","options" => array_combine(range(1,50),range(1,50)), "tabindex"=>"5"));
	?>


	<?php	echo $this->Form->input('efectivo',array("style"=>"width:100px", "tabindex"=>"6")); ?>

	<?php	echo $this->Form->input('vuelto',array("style"=>"width:100px;background:#efefef","readonly"=>"readonly", "tabindex"=>"-1")); ?>

	</fieldset>

<div class="submit">
	<input type="submit" value="Grabar"   tabindex="7" /> 
	<span class="lbutton"><?php echo $this->Html->link(__('Regresar', true), array('action' => 'index'), array('tabindex' => '-1')); ?></span>
</div>


	<div id="focusend" tabindex="8" /> </div>

</form>

</div>

</td><td>
<iframe  style="border:1px solid blue;width:470px;height:470px;" scrolling="no" src="" id="iview_recibo"></iframe>
<!--iframe width="500px" height="600px" id="iview_recibo"  /-->

</td></tr></table>

<script>
$(document).ready(function () {
    $('#ReciboDniRuc').keyup(function() {
        var $th = $(this);
        $th.val( $th.val().toUpperCase().replace(/[^0-9.A-Z]/g, function(str) { return ''; } ) );
    });
});

function clear_add_form(){
	$("#ReciboPrecioCodigo").focus();	
	$("#ReciboPersonaId").val( 0 );
	$("#labelbuscando").html("");
	$( "#PrecioShow" ).html("");
	$( "#ReciboPrecio" ).val("" );
	$( "#ReciboPrecioId" ).val( "" );
	$( "#ReciboPrecioMonto" ).val( 0 );	


	$( "#ReciboPrecioCodigo" ).val( "" );	
	$( "#ReciboDniRuc" ).val( "" );	
	$("#ReciboNombres").val("");
	$( "#ReciboCantidad" ).val( 1 );	
	$( "#ReciboEfectivo" ).val( "" );	
	$( "#ReciboVuelto" ).val( "" );	

}

function buscar_persona(){

	$("#labelbuscando").html("Buscando...");
	$("#labelbuscando").show();

		$.getJSON('<?php echo Router::url("/personas/search") ?>?dni_ruc=' + $("#ReciboDniRuc").val(), function(data) {
			if(data.flag == 1){
				$("#ReciboNombres").val(data.nombre);
				$("#ReciboPersonaId").val(data.id);
				//$("#labelbuscando").hide();
				$("#labelbuscando").html("<span style='color:green'>Validado.</span>");
			} else {
				$("#labelbuscando").html("No se encontro registro.");
				$("#labelbuscando").show();
				$("#ReciboNombres").val("");
				$("#ReciboPersonaId").val(0);
			}
		});
	return false;
}

</script>
<style>
form fieldset div input, textarea, keygen, select, button, isindex {
margin: 0em;
font: -webkit-small-control;
color: initial;
letter-spacing: normal;
word-spacing: normal;
text-transform: none;
text-indent: 0px;
text-shadow: none;
display: inline-block;
text-align: start;
}

  .ui-combobox {
    position: relative;
    display: inline-block;
	width:400px
  }
   .ui-combobox-toggle {
    position: absolute;
    top: 0;
    bottom: 0;
    margin-left: 402px;
    padding: 0;
    /* support: IE7 */
    *height: 1.7em;
    *top: 0.1em;
  }
    .ui-combobox-input {
    margin: 0;
    padding: 0.3em;
  }
  </style>
  <script>

var sa_options = { 
        success:       showResponseAdd
}

$('#focusend').on('focus', function() {
  // "last" focus guard got focus: set focus to the first field
	$("#ReciboPrecioCodigo").focus();	
});

function showResponseAdd(responseText, statusText, xhr, $form)  { 
	if(responseText==0){
		alert("Error al guardar formulario");
	} else {
		clear_add_form();
		$('#iview_recibo').attr("src","<?php echo Router::url('/recibos/imp') ?>/" + responseText)  ;
	}
}


  $(function() {

    $('#ReciboEfectivo').keyup(function() {
		$('#ReciboVuelto').val($('#ReciboEfectivo').val() - $('#ReciboPrecioMonto').val()*$('#ReciboCantidad').val() );
    });


	$('#ReciboAddSuperForm').ajaxForm(sa_options);

    $('#ReciboPrecioCodigo').keyup(function() {
		th = $(this).val();
		if( th.length == 4){

			$.ajax({
				  url: "<?php echo Router::url('/precios/autocomplete') ?>?term=" + th,
				  cache: false,
				  dataType: "json",
			}).done(function( data ) {
				if(data.length == 0){
					$( "#PrecioShow" ).html("<span style='color:red'>No se encontro dato.</span>");
		   			$( "#ReciboPrecio" ).val("" );
		   			$( "#ReciboPrecioId" ).val( "" );
				    $( "#ReciboPrecioMonto" ).val( 0 );	
				} else {
					$.each(data, function(key, val) {
						//items.push('<li id="' + key + '">' + val + '</li>');
						$( "#ReciboPrecio" ).val( val.fullname );
						$( "#PrecioShow" ).html( val.fullname );
						$( "#ReciboPrecioId" ).val( val.id );
						$( "#ReciboPrecioMonto" ).val( val.monto );
					});
				}
			});
		}
    });

  });
</script>
