<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

	<?php echo $javascript->link(array('jquery.min','jquery.form','jquery-ui-1.9.2.custom.min')); ?>
	<title>
		<?php __('DISA'); ?>
		<?php echo $title_for_layout; ?>
	</title>
	<!--meta http-equiv="Content-Type" content="text/html; charset=cp1252" /-->	
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />	

		<?php
		echo $this->Html->meta('icon');

		echo $this->Html->css('cake.generic');

		echo $scripts_for_layout;
	?>

<style>


.message{background-color:#E1EBF4;background-image:url(<?php echo Router::url('/img/notify_notice.png') ?>);background-repeat:no-repeat;background-position:4px 3px;border:1px solid #C4D1D9 ;padding:5px;padding-left:25px;margin-left:0px;margin-right:5px;margin-bottom:3px; }
.box_notice{background-color:#E1EBF4;background-image:url(<?php echo Router::url('/img/notify_notice.png') ?>);background-repeat:no-repeat;background-position:4px 3px;border:1px solid #C4D1D9 ;padding:5px;padding-left:25px;margin-left:0px;margin-right:5px;margin-bottom:3px; }
.box_error{background-color:#F8E3E0;background-image:url(<?php echo Router::url('/img/notify_error.png') ?>);background-repeat:no-repeat;background-position:4px 3px;border:1px solid #E2C6C5 ;padding:5px;padding-left:25px;margin-left:0px;margin-right:5px;margin-bottom:3px;  }
.box_success{background-color:#E5EBC5;background-image:url(<?php echo Router::url('/img/notify_success.png') ?>);background-repeat:no-repeat;background-position:4px 3px;border:1px solid #D4DAB6 ;padding:5px;padding-left:25px;margin-left:0px;margin-right:5px;margin-bottom:3px;  }
.box_warning{background-color:#FAF3C5;background-image:url(<?php echo Router::url('/img/notify_warning.png') ?>);background-repeat:no-repeat;background-position:4px 3px;border:1px solid #E4DBB0 ;padding:5px;padding-left:25px;margin-left:0px;margin-right:5px;margin-bottom:3px;  }

</style>
</head>
<body  >
<body>
	<div id="container">
		<div id="header">
			
		</div>
		<div id="content">

			<?php echo $this->Session->flash(); ?>
								
								<?php 
								/* if($session->check('Message.flash')){ 
										$session->flash();
									    $session->delete('Message.flash'); 
								  }*/
								
								 if($messages = $session->read('Message.multiFlash')) {
										//var_dump($messages);
                						foreach($messages as $k=>$v){
											echo "<div class='box_".$v["class"]."'>".$v["message"]."</div>";
											//$session->flash('multiFlash.'.$k);
										} 
        						 }
        						 $session->delete('Message.multiFlash'); 
        						?>		

			<?php echo $content_for_layout; ?>

		</div>
		<div id="footer">

		</div>
	</div>
	
</body>
</html>
