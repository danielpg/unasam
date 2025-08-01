<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head> 
<!--meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" /-->
	<!--meta http-equiv="Content-Type" content="text/html; charset=cp1252" /-->	
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" >	
	<title><?php echo $title_for_layout?></title>


	<?php echo $this->Html->css('cake.generic'); ?>
	<?php echo $html->css("superfish.css");	 ?>
	<?php echo $html->css("superfish-navbar.css");	 ?>
	<?php echo $html->css("smoothness/jquery-ui-1.9.2.custom.min.css");	 ?>

	<?php echo $javascript->link(array('jquery.min','jquery.form','jquery-ui-1.9.2.custom.min')); ?>
	<?php echo $javascript->link(array('superfish')); ?>
	<?php echo $javascript->link(array('jquery.jCombo.min')); ?>
	<?php echo $javascript->link(array('jquery.maskedit')); ?>
	<?php echo $javascript->link(array('jquery.maskedit')); ?>

	<script type="text/javascript">
	
		// initialise plugins
		jQuery(function(){
			jQuery('ul.sf-menu').superfish();

			$('#view_reg').dialog({
				autoOpen: false,
				 title: "Registro",
				height: 700,
				width: 900
			});

			/*jQuery('.popupdatepicker').datepick({dateFormat: 'dd/mm/yy'});

				jQuery('#add').click(function() {  
			    	return !jQuery('#multi_select1 option:selected').remove().appendTo('#multi_select2').attr('selected', false); ;  
			    });  
			    jQuery('#remove').click(function() {  
			    	return !jQuery('#multi_select2 option:selected').remove().appendTo('#multi_select1').attr('selected', false);  
			    });  */
			    //jQuery("input, textarea, select, button").uniform();
			    //jQuery("input:text,input:password,input:checkbox,textarea,button").uniform();
		});
		var imagePath = "img/tooltiparrow.gif";
		
		function loadajax(url,content){
			$('box_loading').show();
			jQuery(content).load(url);//$(content).slideUp('slow').fadeOut('slow');  
			$('box_loading').hide();
		}
		
		function submitLogin(f){
			$('box_loading').show();
			 var options = {
			dataType: 'json',
			 success: processJsonLogin
			 };
			 jQuery(f).ajaxGrabar(options);
			 return false;
		}

		function printIframe(reg,id)
		{
			///alert("<?php echo Router::url("/registros/print_view"); ?>/" + reg);
			$("#printframe").attr("src","<?php echo Router::url("/registros/print_view"); ?>/" + reg);			
			var iframe = document.frames ? document.frames[id] : document.getElementById(id);
			var ifWin = iframe.contentWindow || iframe;
			iframe.focus();
			ifWin.printMe();
			return false;
		}
		
		function processJsonLogin(data) {
			$('box_loading').hide();
			 if(data.error == 0){
				 jQuery("#login_error").html("");
				 document.location.href= base_url_no_slash + data.url_redirect;
			 } else {
			 	jQuery("#login_error").html(data.validation_errors);
			 }
			 
		}

	</script>
<script type="text/javascript">

	function f_view_reg(regid){
		
		$("#view_reg").load('<?php echo Router::url('/registros/view') ?>/'+ regid + "?rand=" + Math.random());
		$('#view_reg').dialog("open");
		return false;
	}

	function f_view_hist(regid){
		
		$("#view_reg").load('<?php echo Router::url('/registros/historial_view') ?>/'+ regid + "?rand=" + Math.random());
		$('#view_reg').dialog("open");
		return false;
	}

</script>
 <script>
  $(function() {

    $( "#FormRegistroName" ).autocomplete({
      minLength: 0,
      source: "<?php echo Router::url('/registros/autocomplete') ?>",
      focus: function( event, ui ) {
        $( "#FormRegistroName" ).val( ui.item.fullname );
        return false;
      },
      select: function( event, ui ) {
        $( "#FormRegistroName" ).val( ui.item.fullname );
        $( "#FormRegistroId" ).val( ui.item.id );
       // $( "#check" ).val( ui.item.id );
        //$( "#project-description" ).html( ui.item.desc );
        //$( "#project-icon" ).attr( "src", "images/" + ui.item.icon );
 
        return false;
      },
       change: function( event, ui ) {
              if ( !ui.item ){
       			 $( "#FormRegistroName" ).val("" );
       			 $( "#FormRegistroId" ).val( "" );
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


  });

	$.datepicker.regional['es'] = {
		closeText: 'Cerrar',
		prevText: '&#x3C;Ant',
		nextText: 'Sig&#x3E;',
		currentText: 'Hoy',
		monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
		'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
		monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun',
		'Jul','Ago','Sep','Oct','Nov','Dic'],
		dayNames: ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'],
		dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
		dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
		weekHeader: 'Sm',
		dateFormat: 'dd/mm/yy',
		firstDay: 1,
		isRTL: false,
		showMonthAfterYear: false,
		yearSuffix: ''};
		$.datepicker.setDefaults($.datepicker.regional['es']);

   </script>

	<style>

.icon_view{padding-left:16px;font-size:14px; background-image:url(<?php echo Router::url('/img/icon_view.png') ?>); background-repeat:no-repeat}
.icon_edit{padding-left:16px;font-size:14px; background-image:url(<?php echo Router::url('/img/icon_edit.png') ?>); background-repeat:no-repeat}
.icon_pre_html{padding-left:16px;font-size:14px; background-image:url(<?php echo Router::url('/img/layout.png)') ?>; background-repeat:no-repeat}
.icon_user{padding-left:16px;font-size:14px; background-image:url(<?php echo Router::url('/img/user.png') ?>); background-repeat:no-repeat}
.icon_review{padding-left:16px;font-size:14px; background-image:url(<?php echo Router::url('/img/icon_edit.png') ?>); background-repeat:no-repeat}
.icon_word{padding-left:16px;font-size:14px; background-image:url(<?php echo Router::url('/img/page_word.png') ?>); background-repeat:no-repeat}
.icon_pdf{padding-left:16px;font-size:14px; background-image:url(<?php echo Router::url('/img/pdf_icon.gif') ?>); background-repeat:no-repeat}
.icon_mail{padding-left:16px;font-size:14px; background-image:url(<?php echo Router::url('/img/email.png') ?>); background-repeat:no-repeat}
.icon_suit{padding-left:16px;font-size:14px; background-image:url(<?php echo Router::url('/img/suit16x.png') ?>); background-repeat:no-repeat}
.icon_bar{padding-left:16px;font-size:14px; background-image:url(<?php echo Router::url('/img/chart_bar.png') ?>); background-repeat:no-repeat}
.icon_del{padding-left:16px;font-size:14px; background-image:url(<?php echo Router::url('/img/icon_delete.png') ?>); background-repeat:no-repeat}

.icon_table{padding-left:14px;font-size:14px; background-image:url(<?php echo Router::url('/img/date.png') ?>); background-repeat:no-repeat}
.icon_mov{padding-left:14px;font-size:14px; background-image:url(<?php echo Router::url('/img/arrow_switch.png') ?>); background-repeat:no-repeat}

.message{background-color:#E1EBF4;font-size:12px;background-image:url(<?php echo Router::url('/img/notify_notice.png') ?>);background-repeat:no-repeat;background-position:4px 3px;border:1px solid #C4D1D9 ;padding:5px;padding-left:25px;margin-left:0px;margin-right:5px;margin-top:3px; }
.box_notice{background-color:#E1EBF4;font-size:12px;background-image:url(<?php echo Router::url('/img/notify_notice.png') ?>);background-repeat:no-repeat;background-position:4px 3px;border:1px solid #C4D1D9 ;padding:5px;padding-left:25px;margin-left:0px;margin-right:5px;margin-top:3px; }
.box_error{background-color:#F8E3E0;font-size:12px;background-image:url(<?php echo Router::url('/img/notify_error.png') ?>);background-repeat:no-repeat;background-position:4px 3px;border:1px solid #E2C6C5 ;padding:5px;padding-left:25px;margin-left:0px;margin-right:5px;margin-top:3px;  }
.box_success{background-color:#E5EBC5;font-size:12px;background-image:url(<?php echo Router::url('/img/notify_success.png') ?>);background-repeat:no-repeat;background-position:4px 3px;border:1px solid #D4DAB6 ;padding:5px;padding-left:25px;margin-left:0px;margin-right:5px;margin-top:3px;  }
.box_warning{background-color:#FAF3C5;font-size:12px;background-image:url(<?php echo Router::url('/img/notify_warning.png') ?>);background-repeat:no-repeat;background-position:4px 3px;border:1px solid #E4DBB0 ;padding:5px;padding-left:25px;margin-left:0px;margin-right:5px;margin-top:3px;  }

@media print
  {
		
	/*#container{display:none;}*/
	.sf-menu{display:none}
	.divlogout{display:none}

  }

</style>
<style>
	.fs_left{float:left;width:180px}
	.fs_right{float:left}
	
	.perm_table label{display:inline}
   .perm_table  .focused
    {
        background-color: #ccc;
       /* font-style: italic;*/
        color:blue;
        font-weight:bold;
    }

.top_table_middle{background:#5C6467}
</style>

</head>
<?php //file_put_contents('C:\Appserv\www\params.txt',get_dump($this->params),FILE_APPEND); ?>
<body>


<div id="view_reg" style="display:none"></div>


<style>
.table_fields td {padding:4px;width:180px}
</style>
	<div id="container">
		
		<!-- START marco principal -->
		<div align="center"><table width="1019px"><tr><td><div id="content">
		
				<table id="top_table" width="1018px" cellpadding="0" cellspacing="0">
				<tr class="top_table_top">
					<td style="text-align:left">
						<?php echo $html->image("logodisa.png") ?>
						
						<span id="box_loading" style="display:none;"><?php echo $html->image('loading_green_trans.gif')?> <?php __('Loading') ?>...</span>
					</td>
					<td >
						<div class="divlogout" style="text-align:right;">
						
								<div style="color:#DBA80E;font-weight:bold;width:100%">
								<?php //echo Credentials::get('__credentials.Login.username') ?> | <a style="color:#DBA80E;" href="<?php echo $html->url(array('controller'=>'authentication','action'=>'logout')) ?>"><?php __("Cerrar Sesi&oacute;n") ?></a>
								</div>
							
							<!-- Settings | Logout -->
						</div>
					</td>
				</tr>
				<tr class="top_table_middle">
					<td colspan="2">
						<table cellpadding="0" cellspacing="0" border="0" width="100%"><tr>
						<td width="100%">
								<?php if (!($this->params["controller"]=="registros" && $this->params["action"]=="intro")): ?>
								<ul class="sf-menu">
								<?php if(false && Credentials::get("__credentials.Login.role_id")==1): ?>
								<li><a href="#">Definiciones&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
									<ul>
										<li><?php echo $this->Html->link(__('Areas', true), array('controller' => 'areas', 'action' => 'index')); ?> </li>
										<li><?php echo $this->Html->link(__('Organos', true), array('controller' => 'organos', 'action' => 'index')); ?> </li>
										<li><?php echo $this->Html->link(__('Cargos', true), array('controller' => 'cargos', 'action' => 'index')); ?> </li>
										<li><?php echo $this->Html->link(__('Profesiones', true), array('controller' => 'profesions', 'action' => 'index')); ?> </li>
										<li><?php echo $this->Html->link(__('Condiciones Laborales', true), array('controller' => 'condicions', 'action' => 'index')); ?> </li>
										<li><?php echo $this->Html->link(__('Grupo Ocupacional', true), array('controller' => 'grupo_ocupacionals', 'action' => 'index')); ?> </li>
										<li><?php echo $this->Html->link(__('Nivel Ocupacional', true), array('controller' => 'nivel_ocupacionals', 'action' => 'index')); ?> </li>
										<li><?php echo $this->Html->link(__('Estado Civil', true), array('controller' => 'estado_civils', 'action' => 'index')); ?> </li>
										<!--li><?php //echo $this->Html->link(__('Capacitaciones', true), array('controller' => 'capacitacions', 'action' => 'index')); ?> </li-->
										<!--li><?php echo $this->Html->link(__('Documentos', true), array('controller' => 'documentos', 'action' => 'index')); ?> </li-->
										<!--li><?php //echo $this->Html->link(__('Nuevo Documento', true), array('controller' => 'documentos', 'action' => 'add')); ?> </li-->
										<li><?php echo $this->Html->link(__('Campos Personalizados', true), array('controller' => 'campos', 'action' => 'index')); ?> </li>
										<li><?php echo $this->Html->link(__('Tipo de Personal', true), array('controller' => 'tipo_personals', 'action' => 'index')); ?> </li>
										<li><?php echo $this->Html->link(__('Tipo Capacitaciones', true), array('controller' => 'tipo_capacitacions', 'action' => 'index')); ?> </li>
										<li><?php echo $this->Html->link(__('Legajo de Documentos', true), array('controller' => 'cat_documentos', 'action' => 'index')); ?> </li>
										<li><?php echo $this->Html->link(__('Tipo de Documentos', true), array('controller' => 'tipo_documentos', 'action' => 'index')); ?> </li>
									</ul>
								</li>
								<?php endif; ?>
								<li><a href="#">Registros</a>
									<ul>
										<li><?php echo $this->Html->link(__('Listado', true), array('controller' => 'registros', 'action' => 'index')); ?></li>
										<li><?php echo $this->Html->link(__('Nuevo', true), array('controller' => 'registros', 'action' => 'add')); ?></li>
									</ul>	
								</li>
								<li><?php echo $this->Html->link(__('Vacantes', true), array('controller' => 'registros', 'action' => 'vacantes')); ?>
								</li>
								<?php if(false &&Credentials::get("__credentials.Login.role_id")==1): ?>
								<li><a href="#">Sistema</a>
									<ul>
										<li><?php echo $this->Html->link(__('Usuarios', true), array('controller' => 'logins', 'action' => 'index')); ?></li>
										<li><?php echo $this->Html->link(__('Nuevo Usuario', true), array('controller' => 'logins', 'action' => 'add')); ?></li>
									</ul>	
								</li>
								<?php endif; ?>
								<li><a href="#">Reportes</a>
									<ul>
										<li><a href="#">Generales</a>

										<li><a href="#">Estad&iacute;sticas B&aacute;sicas</a> 							
											<ul>
												<li><a href="#">por Cargo</a>
												<li><a href="#">por Organo</a>
												<li><a href="#">por Ubigeo</a>
											</ul>	
										</li>

										<li><a href="#">Niveles Educativos</a>

									</ul>	
								</li>
							</ul>
							<?php endif; ?>
						</td>
						</tr></table>
					</td>
				</tr>
				</table>
					
				<table id="middle_table" width="1018px" cellpadding="0" cellspacing="0">
				<tr>
						<!-- LEFT SIDE -->
						<td class="middle_table_left" valign="top" width="100%">
						
							<div class="main_container" style="min-height:800px" >
								<?php echo $this->Session->flash(); ?>
								
								<?php 
						
								 if($messages = $session->read('Message.multiFlash')) {
										//var_dump($messages);
                						foreach($messages as $k=>$v){
											echo "<div class='box_".$v["class"]."'>".$v["message"]."</div>";
											//$session->flash('multiFlash.'.$k);
										} 
        						 }
        						 $session->delete('Message.multiFlash'); 
        						?>	


							<?php echo $content_for_layout ?>

							</div>
						</td>
				</tr>
				</table>


		<!-- END marco principal -->
		</div></td></tr></table></div>
					<!--div style="background:white;color:black;margin-top:100px">
						<?php //  echo str_replace("LEFT JOIN","<br />LEFT JOIN",$this->element('sql_dump')); ?>
					</div-->	

		<?php //echo $javascript->link(array('javascript.tooltip')); ?>

		<?php //echo $javascript->link(array('calendar_click_events')); ?>
<!--[if lte IE 6]>
<?php //echo $javascript->link(array('pngfix')); ?>
<![endif]-->

<iframe src="" id="printframe" border=0 style="display:none"></iframe>
</body>
</html>
