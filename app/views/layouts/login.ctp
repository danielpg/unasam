<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />	
	<?php echo $javascript->link(array('jquery.min','jquery.form')); ?>
	
	<title><?php echo $title_for_layout?></title>
	

	
<style>
body{
font-size:11px;
font-family: Verdana, Arial, sans-serif;
margin:0px;

}

img{border:0px}

a,a:VISITED,a:ACTIVE,a:FOCUS,a:LINK {
text-decoration:none;
font-weight: bold;
color:black;
}


input{border:1px solid gray;-moz-border-radius: 5px;border-radius:8px;padding:4px;margin-bottom:10px;margin-top:3px}

		.link_language{color:white;width:200px;text-align:left;font-size:80%;padding-right:5px}
		.link_language a{color:white;}

.message{background-color:#E1EBF4;background-image:url(<?php echo Router::url('/img/notify_notice.png') ?>);background-repeat:no-repeat;background-position:4px 6px;border:1px solid #C4D1D9 ;padding:5px;padding-left:25px;margin-left:0px;margin-right:5px;margin-bottom:3px; }
.box_notice{background-color:#E1EBF4;background-image:url(<?php echo Router::url('/img/notify_notice.png') ?>);background-repeat:no-repeat;background-position:4px 6px;border:1px solid #C4D1D9 ;padding:5px;padding-left:25px;margin-left:0px;margin-right:5px;margin-bottom:3px; }
.box_error{background-color:#F8E3E0;background-image:url(<?php echo Router::url('/img/notify_error.png') ?>);background-repeat:no-repeat;background-position:4px 6px;border:1px solid #E2C6C5 ;padding:5px;padding-left:25px;margin-left:0px;margin-right:5px;margin-bottom:3px;  }
.box_success{background-color:#E5EBC5;background-image:url(<?php echo Router::url('/img/notify_success.png') ?>);background-repeat:no-repeat;background-position:4px 6px;border:1px solid #D4DAB6 ;padding:5px;padding-left:25px;margin-left:0px;margin-right:5px;margin-bottom:3px;  }
.box_warning{background-color:#FAF3C5;background-image:url(<?php echo Router::url('/img/notify_warning.png') ?>);background-repeat:no-repeat;background-position:4px 6px;border:1px solid #E4DBB0 ;padding:5px;padding-left:25px;margin-left:0px;margin-right:5px;margin-bottom:3px;  }

.login_label{color:black}
</style>
</head>
<body>
<div id="container">


						
<div  style="text-align:center">
	<div align="center">

		<table border="0" width="400px" cellpadding="0" cellspacing="0" style="text-align:left">
		<tr><td height="190px" valign="bottom" colspan="2">


		<div style="font-size:18px;font-family:'Arial Black';text-align:center;margin-bottom:10px">SISTEMA DE CONTROL DE TESORERIA</div>
		<?php if($session->check('Message.flash')): ?>
		<?php $session->flash(); ?>
	    <?php $session->delete('Message.flash'); ?>
		<?php if($messages = $session->read('Message.multiFlash')) {
				foreach($messages as $k=>$v) $session->flash('multiFlash.'.$k);
        	  }
              $session->delete('Message.multiFlash'); 
        ?>	
								
		<?php endif; ?> 
		
		<tr>
		<td>		<?php echo $html->image("unasam3.jpg",array("width"=>100,"height"=>150)); ?>	</td>
		<td xalign="center">
				<div style="background:lightblue;padding:20px;border-radius:10px">
				<form  action="<?php echo $html->url(array('controller'=>'authentication','action'=>'login')) ?>" id="ajax_login" method="post" onsubmit="javascript:submitForm('#ajax_login');return false;">
					
					<div id="form_errors" style=" color:red; font-weight:bold"></div>
					
					<div style="width:148px;text-align:left;margin-left:35px">
					<span class="login_label"><?php __("Usuario") ?>: </span><br/>
					<input style="ime-mode: disabled;" autocomplete="off"  type="text" name="data[Login][username]" style="width:140px;padding:2px;" />
					<br />
					<span class="login_label"><?php __("Contrase&ntilde;a") ?>: </span><br/>
					<input style="ime-mode: disabled;" autocomplete="off"  type="password" name="data[Login][password]" style="width:140px;padding:2px" /><br/>
	
					<!--input type="hidden" name="data[Login][remember_me]" value="0" />
					<input type="checkbox" name="data[Login][remember_me]" value="1"/><span class="login_label"><?php __("Remember me") ?></span><br/-->
	
					<div style="text-align:right">
						<input type="submit" name="" value="<?php __("Ingresar") ?>" style="color:black;margin:0px;padding:2px 7px 2px;background:#B9C2C7;margin-top:5px" /></div>
					</div>
				</form>
				</div>
		</td></tr>
		</table>
	</div>
</div>
</div>

<script type="text/javascript">
function submitForm(f){
	 var options = {
	dataType: 'json',
	 success: processJson
	 };
	 jQuery(f).ajaxSubmit(options);
	 return false;
}

function processJson(data) {
	 if(data.error == 0){
		 jQuery("#form_errors").html("");
		 document.location.href=  data.url_redirect;//base_url_no_slash +
	 } else {
	 	jQuery("#form_errors").html(data.validation_errors);
	 }
}



</script>

</body>
</html>
