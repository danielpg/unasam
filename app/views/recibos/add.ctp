<div class="recibos form">
<?php echo $this->Form->create('Recibo');?>
	<fieldset style="width:700px">
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
<input tabindex="2" maxlength="11" name="data[Recibo][dni_ruc]" style="width:100px" type="text" 
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
	<span class="lbutton"><?php echo $this->Html->link(__('Regresar', true), array('action' => 'index')); ?></span>
</div>
</form>

</div>

<script>
$(document).ready(function () {
    $('#ReciboDniRuc').keyup(function() {
        var $th = $(this);
        $th.val( $th.val().replace(/[^0-9]/g, function(str) { return ''; } ) );
    });
});

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
/*
  (function( $ ) {
    $.widget( "ui.combobox", {
      _create: function() {
        var input,
          that = this,
          wasOpen = false,
          select = this.element.hide(),
          selected = select.children( ":selected" ),
          value = selected.val() ? selected.text() : "",
          wrapper = this.wrapper = $( "<span>" )
            .addClass( "ui-combobox" )
            .insertAfter( select );
 
        function removeIfInvalid( element ) {
          var value = $( element ).val(),
            matcher = new RegExp( "^" + $.ui.autocomplete.escapeRegex( value ) + "$", "i" ),
            valid = false;
          select.children( "option" ).each(function() {
            if ( $( this ).text().match( matcher ) ) {
              this.selected = valid = true;
              return false;
            }
          });
 
          if ( !valid ) {
            // remove invalid value, as it didn't match anything
            $( element )
              .val( "" )
              .attr( "title", value + " no se encontro coincidencia" )
              .tooltip( "open" );
            select.val( "" );
            setTimeout(function() {
              input.tooltip( "close" ).attr( "title", "" );
            }, 2500 );
            input.data( "ui-autocomplete" ).term = "";
          }
        }
 
        input = $( "<input>" )
          .appendTo( wrapper )
          .val( value )
		  .attr( "tabIndex", 1 )
          .attr( "title", "" )
          .addClass( "ui-state-default ui-combobox-input" )
          .autocomplete({
            delay: 0,
            minLength: 0,
            source: function( request, response ) {
              var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
              response( select.children( "option" ).map(function() {
                var text = $( this ).text();
                if ( this.value && ( !request.term || matcher.test(text) ) )
                  return {
                    label: text.replace(
                      new RegExp(
                        "(?![^&;]+;)(?!<[^<>]*)(" +
                        $.ui.autocomplete.escapeRegex(request.term) +
                        ")(?![^<>]*>)(?![^&;]+;)", "gi"
                      ), "<strong>$1</strong>" ),
                    value: text,
                    option: this
                  };
              }) );
            },
            select: function( event, ui ) {
              ui.item.option.selected = true;
              that._trigger( "selected", event, {
                item: ui.item.option
              });
            },
            change: function( event, ui ) {
              if ( !ui.item ) {
                removeIfInvalid( this );
              }
            }
          })
          .addClass( "ui-widget ui-widget-content ui-corner-left" );
 
        input.data( "ui-autocomplete" )._renderItem = function( ul, item ) {
          return $( "<li>" )
            .append( "<a>" + item.label + "</a>" )
            .appendTo( ul );
        };
 
        $( "<a>" )
          .attr( "tabIndex", -1 )
          .attr( "title", "Mostrar todos" )
          .tooltip()
          .appendTo( wrapper )
          .button({
            icons: {
              primary: "ui-icon-triangle-1-s"
            },
            text: false
          })
          .removeClass( "ui-corner-all" )
          .addClass( "ui-corner-right ui-combobox-toggle" )
          .mousedown(function() {
            wasOpen = input.autocomplete( "widget" ).is( ":visible" );
          })
          .click(function() {
            input.focus();
 
            // close if already visible
            if ( wasOpen ) {
              return;
            }
 
            // pass empty string as value to search for, displaying all results
            input.autocomplete( "search", "" );
          });
 
        input.tooltip({
          tooltipClass: "ui-state-highlight"
        });
      },
 
      _destroy: function() {
        this.wrapper.remove();
        this.element.show();
      }
    });
  })( jQuery );
 
  $(function() {
    $( "#ReciboPrecioId" ).combobox();

    $( "#toggle" ).click(function() {
      $( "#combobox" ).toggle();
    });
  });
*/
	/*$('#ReciboCantidad').change(function() {
	  alert('Handler for .change() called.');
	});*/

  $(function() {
/*
    $( "#ReciboPrecio" ).autocomplete({
      minLength: 0,
      source: "<?php echo Router::url('/precios/autocomplete') ?>",
      focus: function( event, ui ) {
        $( "#ReciboPrecio" ).val( ui.item.fullname );
        return false;
      },
      select: function( event, ui ) {
        $( "#ReciboPrecio" ).val( ui.item.fullname );
        $( "#ReciboPrecioId" ).val( ui.item.id );
        $( "#ReciboPrecioMonto" ).val( ui.item.monto );
        return false;
      },
       change: function( event, ui ) {
              if ( !ui.item ){
       			 $( "#ReciboPrecio" ).val("" );
       			 $( "#ReciboPrecioId" ).val( "" );
		         $( "#ReciboPrecioMonto" ).val( 0 );
				// $( "#check" ).val( "");
                //return removeIfInvalid( this );
			}
       }
    })
    .data( "autocomplete" )._renderItem = function( ul, item ) {
      return $( "<li>" )
        .data( "item.autocomplete", item )
        .append( "<a>" + item.fullname + "</a>" )
        .appendTo( ul );
    };
*/

    $('#ReciboEfectivo').keyup(function() {
		$('#ReciboVuelto').val($('#ReciboEfectivo').val() - $('#ReciboPrecioMonto').val()*$('#ReciboCantidad').val() );
    });

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
