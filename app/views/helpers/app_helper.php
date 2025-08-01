<?php

class AppHelper extends Helper {
	var $helpers = array('text','html');//,'form','paginator','Alaxos.AlaxosForm','Alaxos.AlaxosHtml');
   
   	function tooltip($text){
   		//return '<img title="'.$text.'" class="addtooltip" />';
		return $this->html->image('icon_question.png',array('class'=>'addtooltip','title'=>$text));
	} 
   
	function help_text($file){
		$file_path = APP.'locale'.DS.CURRENT_LANG.DS.'help'.DS.$file.'.ctp';
		if(!file_exists($file_path)) $file_path = APP.'locale'.DS.'eng'.DS.'help'.DS.$file.'.ctp';
		echo '<a href="#TB_inline?height=250&width=360&inlineId='.$file.'" class="thickbox">'.$this->image('icons/help.png',array('align'=>'absmiddle')).'</a>';
		echo '<div id="'.$file.'" style="display:none"><p>'.file_get_contents($file_path).'</p></div>';
	}

	function data2img($data,$dir,$field){
		$tmp = explode(".",$data[$field]);
		$len = strlen($data["id"]) - 1;
		return $this->html->url('/'.$dir.'/'.$data[$field][0]."/".$data[$field], true); 
		//return $this->html->url('/'.$dir.'/'.$data['id'][$len]."/".md5($data['id'].'.'.$data[$field]).".".array_pop($tmp), true); 
	}

	function filepath($data,$dir,$field){
		$tmp = explode(".",$data[$field]);
		$len = strlen($data["id"]) - 1;
		return $this->html->url('/'.$dir.'/'.$data[$field][0]."/".$data[$field], true); 
		//return $this->html->url('/'.$dir.'/'.$data['id'][$len]."/".md5($data['id'].'.'.$data[$field]).".".array_pop($tmp), true); 
	}

	function cdate($var){
		if(strpos($var,"-")!==false){
			$time = "";
			if(strpos($var," ")!==false){
				list($date,$time) = explode(" ",$var);
				$time = " ".$time;
			} else {
				$date = $var;
			}

			list($y,$m,$d) = explode("-",$date);
			$tmp = $d."/".$m."/".$y.$time;
			return $tmp;
		}
		return $Var;
	}

	function servicio($s,$e){
		if( $s == "0000-00-00" || $s == "00/00/0000" || empty($s)){
			return "-";
		}

		if(empty($e)) $e= date("Y-m-d");
		if( $e == "0000-00-00") $e = date("Y-m-d");
		if( $e == "00/00/0000") $e = date("Y-m-d");
		$time = strtotime($e) - strtotime($s);
		$re = $time/(3600*24);
		$dias = ceil($re);

		$meses = round($dias/30,1);

		$s = $dias." dias ";
		if($meses > 1)$s .= "( ".$meses." meses )";

		return $s;
	}

	function icon_play(){
		
	}
	
	function icon_record(){
		
	}

	function xlink($title, $url = null, $options = array(), $confirmMessage = false){
		//$text = "asdasdas";
		return $this->html->link($title,$url,$options,$confirmMessage);
	}
	
	function tooltip_access($name){
		//$name = trim($name,CONTEXT_SEPARATOR);
		//$name = str_replace(CONTEXT_SEPARATOR,"<br />",$name);
		return $this->tooltip($name);
	}
	
	function currency_format($data){
		return number_format($data, 2, '.', '');
	}

	//$options = array('name'=>'data[Phone][test][]','options' => array(),'selected' => array(),'form_id' => 'form_id','size'=>10);

	function select_transfer($options){
		if(!is_array($options['selected']))$options['selected'] = array();
		if(!is_array($options['options']))$options['options'] = array();
		if(!isset($options['size']))$options['size'] = 10;
		if(!isset($options['label']))$options['label'] = '';
		
		$buffer = '';
		$buffer .= '<table border="0" cellspacing="0" width="100%"><tr><td width="50%" valign="top">';

		$buffer .= '<div style="">' .
					'<select size="'.$options['size'].'" multiple id="multi_select1" style="width:90%;background:#F5F5F5">';
		foreach($options['options'] as $value => $label){
			if(in_array($label,$options['selected']))continue;
			$buffer.= '<option value="'.$label.'">'.$label.'</option>';
		}		

		$buffer .= '</select></div><a href="#" id="add">'.__('Add',true).' &raquo;</a>  ';
		
		$buffer .= '</td><td valign="top">';	
		$buffer .= '<div style="border:4px solid green;background:green"><b style="color:white">'.$options['label'].'</b><select  size="'.$options['size'].'" multiple id="multi_select2" name="'.$options['name'].'" style="width:100%">';
		foreach($options['selected'] as $value => $label){
			$buffer .= '<option value="'.$label.'">'.$label.'</option>';
		}	

		$buffer .= '</select></div> <a href="#" id="remove">&laquo; '.__('Delete',true).'</a>  ';
		
		$buffer .= '</td></tr></table>';	
		/*
		$buffer .= "
		<script type='text/javascript'>  
		//	jQuery().ready(function() {  
				jQuery('#add').click(function() {  
			    	return !jQuery('#select1 option:selected').remove().appendTo('#select2');  
			    });  
			    jQuery('#remove').click(function() {  
			    	return !jQuery('#select2 option:selected').remove().appendTo('#select1');  
			    });  
		//	});  */
		$buffer .= "
		<script type='text/javascript'> 
			jQuery('#".$options['form_id']."').submit(function() {  
				jQuery('#multi_select2 option').each(function(i) {  
			    	jQuery(this).attr('selected', 'selected');  
			    });  
			});  
  		</script>";
  		return $buffer;
	}

	
	function standart_input($name,$options = array()){
		static $i;
		if(!isset($options['error_class'])) $error_class = 'error-message';
	/*
		$v = ClassRegistry::keys();
		$v = ClassRegistry::getObject('view');
		var_dump($this);exit;*/
		$class  = '';
			if ($i++ % 2 == 0) {
			$class = '#efefef';
		}

		$options['div'] = array('style' =>'background-color:'.$class);
		$options['before'] = "\n".'<div  class="fs_left">'."\n";
		$options['between'] = "\n".'</div><div class="fs_right">'."\n";
		$options['after'] = "\n".'</div><div style="clear:both"></div>'."\n";
		
		//$options['escape'] = true;
		return $this->form->input($name,$options);		
	}

	
	function icon($type,$url,$options = array(),$confirmMessage = false){
		switch($type){
			case 'edit':
				$content = '<span class="icon_edit"></span>';
				break;
			case 'delete':
				$content = '<span class="icon_delete"></span>';
				break;
			case 'view':
				$content = '<span class="icon_view"></span>';
				break;
		}
		$options['escape'] = false;
		
		return $this->html->link($content,$url,$options,$confirmMessage);
	}
	
	function xpaging(){
		$buffer = '';
		$buffer .= $this->paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));
 		$buffer .=  ' | '.$this->paginator->numbers();
		$buffer .= $this->paginator->next(__('next', true).' >>', array(), null, array('class'=>'disabled'));
		return $buffer;
	}
	
	/*
	function link($title, $url = null, array $options = array(),$confirmMessage = false){
		if(!isset($options['escape']))$options['escape'] = false;
		var_dump(asdasdasdasdasd);
		return parent::link($title,$url,$options,$confirmMessage);
	}*/
	
}

?>
