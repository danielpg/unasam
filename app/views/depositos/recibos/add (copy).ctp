<div class="recibos form">
<?php echo $this->Form->create('Recibo');?>
	<fieldset style="width:500px">
		<legend><?php __('Nuevo Recibo'); ?></legend>
	<?php
		echo $this->Form->input('precio_id',array("empty"=>true,"style"=>"width:400px"));
		//echo $this->Form->input('entidad_id',array("empty"=>true));

?>

<div class="input text"><label for="ReciboDniRuc">DNI o RUC</label><input maxlength="11" name="data[Recibo][dni_ruc]" style="width:100px" type="text" 
value="<?php if(isset($this->data["Recibo"]["dni_ruc"])) echo $this->data["Recibo"]["dni_ruc"] ?>" id="ReciboDniRuc" />
<span class="lbutton"><a href="#" onclick="buscar_persona()">Buscar</a></span><span style="display:none;color:blue;font-weight:bold" id="labelbuscando">Buscando...</span>
</div>

<?php
		echo $this->Form->input('nombres',array("label"=>"Nombre o Razon Social"));

		echo $this->Form->input('direccion',array("label"=>"Dirección"));

		echo $this->Form->input('persona_id',array("type"=>"hidden"));

		echo $this->Form->input('cantidad' , array("options" => array_combine(range(1,50),range(1,50))));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Grabar', true));?>
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

	/*$('#ReciboCantidad').change(function() {
	  alert('Handler for .change() called.');
	});*/

  </script>

<!--link rel="stylesheet" href="http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css" />
  <link 
