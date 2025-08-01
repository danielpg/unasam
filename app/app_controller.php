<?php
//if($_SERVER['SERVER_ADDR']=='192.168.1.60'){
if (version_compare(PHP_VERSION, '5.2.0', '<')) {
	require_once APP . "vendors/JSON.php";
	function json_encode($input){
		$json = new Services_JSON();
		return $json->encode($input);
	}
	
	function json_decode($input){
		$json = new Services_JSON();
		return $json->decode($input);
	}
}

App::import('Sanitize');
//    ./../cake/console/cake bake //view all
//find /var/www/unasam/app/views -name \*.ctp -exec sed -i "s/Submit/Grabar/g" {} \;
//find /var/www/unasam/app/views -name \*.ctp -exec sed -i "s/Are you sure you want to delete/Esta seguro de querer eliminar/g" {} \;

//find /var/www/unasam/app/views -name \*.ctp -exec sed -i "s/Edit/Editar/g" {} \;
//find /var/www/unasam/app/views -name \*.ctp -exec sed -i "s/Delete/Eliminar/g" {} \;
//find /var/www/unasam/app/views -name \*.ctp -exec sed -i "s/Created/Creado/g" {} \;
//find /var/www/unasam/app/views -name \*.ctp -exec sed -i "s/Modified/Actualizado/g" {} \;

//find /var/www/unasam/app/views -name \*.ctp -exec sed -i "s/sort('created/sort('Creado','created/g" {} \;
//find /var/www/unasam/app/views -name \*.ctp -exec sed -i "s/sort('modified'/sort('Actualizado','modified'/g" {} \;

//find /var/www/unasam/app/views -name \*.ctp -exec sed -i "s/Actions/Acciones/g" {} \;
//find /var/www/unasam/app/views -name \*.ctp -exec sed -i "s/echo $this->Html->link(__('V/$this->Html->link(__('V/g" {} \;

//find /var/www/unasam/app/views -name \*.ctp -exec sed -i "s/__('previous/__('Atras/g" {} \;
//find /var/www/unasam/app/views -name \*.ctp -exec sed -i "s/__('next/__('Siguiente/g" {} \;



//find /var/www/unasam/app/controllers -name \*.php -exec sed -i "s/Session->setFlash/_flash/g" {} \;

/*
Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end% 
Pagina %page% de %pages%, mostrando %current% filas de un total de %count%

find /var/www/unasam/app/views -name \*.ctp -exec sed -i "s/Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%/Pagina %page% de %pages%, mostrando %current% filas de un total de %count%/g" {} \;

*/
//Configure::write('debug',2);
class AppController extends Controller {
	var $helpers = array('Html','Form','Ajax','Javascript','Session');      
	var $components = array('RequestHandler',"Session",'Cookie');//
	var $menu_items;
	var $additional_menu_links = '';
	var $redirect_url = null;
	var $javascripts = array();
	//var $layout = "default2";
	public $perm_filters = array();
	private $perm_relations = array();
	private $exclude_actions = array();
	private $perm_custom_key = false;


	function beforeFilter(){
		//Registry::set('controller',$this);

		


		ClassRegistry::addObject('controller',$this);
		
		Credentials::session_connect($this->Session);//$this->Cookie);
		
		$new_params = array();
		$params = $this->params['url'];
		if(!empty($this->params['form']))$params = $this->params['form']; 
		unset($params['url']);
		foreach($params as $key => $value){
			if(is_array($value))continue;
			$new_params[str_replace('amp;','',$key)] = $value; 			
		}
		$this->params['get_params'] =  $new_params;



		$tmp = $this->params["url"];
		unset($tmp["url"]);
		$this->params["url_filtered"] = $tmp;
		
		App::import('Model','Query');

		Query::singleton();
		Query::squery('select 1');
/*
		Configure::write('debug',2);
		App::import('Model','Installer');
		require_once APP . "migrations/installer.php";
		$migration = new SystemInstaller();
		$migration->test1();*/
		

		if($this->RequestHandler->isAjax())Configure::write('debug',0);
		

		//$this->load_restrictions();

		if( Credentials::hasCredentials()==false){
					if($this->params['controller'] != 'authentication'){
						$this->redirect(array('controller'=>'authentication','action'=>'login'));
						exit;
					}
		}


	}


	function _deny_access_message(){
		if($this->RequestHandler->isAjax()){
			$this->renderJson(array("error"=>1,"validation_errors"=>__('You don\'t have permissions to perform that action', true)));	
		} else {
			$this->Session->setFlash(__('You don\'t have permissions to perform that action', true),'default',array('class'=>'box_warning'));
			//var_dump($this->referer());exit;
			//$this->redirect($this->referer());
			$this->redirect(array('controller'=>'pages','action'=>'empty'));			
		}
		exit;		
	}

	function load_restrictions(){
		$definiciones = array();

		if(Credentials::hasCredentials()){
				$role = Credentials::get("__credentials.Role.codigo");
				if($role == "admin"){ // permiso para todo

				}elseif($role == "consultor"){ //solo consulta
					if(in_array($this->params['action'],array('edit','delete','add'))){
						$this->flash("Acceso denegado.","error");					
						$this->redirect(array('controller'=>'recibos','action'=>'index'));
						exit;
					}

				}elseif($role == "operador"){ //ingresa registros
					if(in_array($this->params['controller'],array('logins'))){
						$this->flash("Acceso denegado.","error");					
						$this->redirect(array('controller'=>'recibos','action'=>'index'));
						exit;
					}

					if(in_array($this->params['controller'],array('categorias','rubros','oficinas',"precios"))){
						if(in_array($this->params['action'],array('edit','delete','add'))){
							$this->flash("Acceso denegado.","error");					
							$this->redirect(array('controller'=>'recibos','action'=>'index'));
							exit;
						}
					}
				}

		}
	}

	function draw_errors(){
		$output = '';
		$args = func_get_args();
		foreach($args as $model){
			$output .= $model->draw_errors(array('header'=>false));
		}
		if(!empty($output)){
			return '<div class="form-error-title">'.__('The following errors happened',true).' : </div>'.$output;
		}		
		return '';
	}
	    
    function get_errors($in){
    	$output = '';
    	foreach($in as $errors){
			if(is_array($errors)){
				foreach($errors as $sub_error){
					$output .= '<div class="error-message">'.$sub_error."</div>";
				}
			} else { 
				$output .= '<div class="error-message">'.$errors."</div>";
			}
		}
		return $output;
    }
    
    function get_fields($file){
		$fields = array();
		$content = file_get_contents($file);
		$content = str_replace("\r",'',$content);
		$content = explode("\n",$content);
		foreach($content as $line){
			if( strpos($line,'=') !== false ){
				$temp = explode('=',$line);
				$fields[trim($temp[0])] = trim($temp[1]);
			}else {
				if(!empty($line))$fields[trim($line)] = '';
			}
		}
		return $fields;
	}
    
    /* function to set multiple flash messages.
     * available types: box_notice,box_error,box_warning,box_success
     * how to use:
     * $this->_flash(__('Hello World',true));
     * $this->_flash(__('Hello World',true),'box_error');
     */

	function _flash($message,$type='notice') {
		if(strpos($message,"could not be saved") !== false ){
			$message = "No se pudieron guardar los datos.";
			$type = "error";
		}elseif(strpos($message,"has been saved") !== false ){
			$message = "El formulario ha sido guardado.";
			$type = "success";
		}elseif(strpos($message,"was not deleted") !== false ){
			$message = "No se pudo eliminar el registro.";
			$type = "error";
		}elseif(strpos($message,"deleted") !== false ){
			$message = "Se elimino el registro.";
			$type = "success";
		}

    	$messages = (array)$this->Session->read('Message.multiFlash');
        $messages[] = array('message'=>$message, 'layout'=>'default', 'class'=>$type,'params'=>array('class'=>$type));
        $this->Session->write('Message.multiFlash', $messages);
    }

	function renderJson($str){
		Configure::write('debug',0);
		$this->set('string_content',json_encode($str));
		$this->render('/elements/empty',null);
	}
	
	function renderAjax($str){
		Configure::write('debug',0);
		$this->set('string_content',$str);
		$this->render('/elements/empty',null);		
	}
    
    function redirect($url, $status = null, $exit = true) {
		if(defined('SIMPLE_TEST')){
			$this->redirect_url = $url;
			$this->autoRender = false;
			return;
		}    	
		//$url['language'] = CURRENT_LANG;
		parent::redirect($url,$status,$exit);
	}

	function log($msg,$type = LOG_ERROR,$file = false,$line = false){
		if($file !== false && $line !== false)
			$msg = $msg.' in ['.$line.', line '.$line.']';
		
		parent::log($msg,$type);
	}


}


class Credentials{
	static $session;
	
	static $session_obj;
	
	function can($action, $__set_permissions_hack = false)
    {
    	static $_permissions;
    	
    	if($__set_permissions_hack != false){
    		$_permissions = $__set_permissions_hack;
    		return true;
    	} 
    	
    	if(@array_key_exists('admin_access', $_permissions)) return true;
    	if(@array_key_exists($action, $_permissions)) return true;
        if( @$_SESSION['__credentials']['is_admin'] == 1 ) return true;
        //if(!isset($_SESSION['__permissions'])) return false;
        return false;
    }
    
   	function canDo($action,$controller = null, $__set_permissions_hack = false)
    {
    	static $_permission;
    	if($__set_permissions_hack != false){
    		$_permission = $__set_permissions_hack;
    		return true;
    	}
    	//var_Dump('---------',$_permission,$action);
    	
    	if(Credentials::get('__credentials.is_admin'))return true;

		if($controller !== null){
			$uperms = Credentials::getPerms();
			if(isset($uperms[$controller])){
				if($uperms[$controller]['value'] >= $action)return true;
			}
			if(strpos($controller,'/')!==false){
				$parts = explode('/',$controller);
				if(isset($uperms[$parts[0]])){
					if($uperms[$controller]['value'] >= $action)return true;
				}
			}
			return false;		
		}

		
    	
    	if($_permission == 0)return false;
    	if($_permission >= $action)return true;
        return false;
    }
    
    
    function setPerms($value,$expire = null){
    	return Credentials::$session->write('perms',gzdeflate(serialize($value),5),$expire);	
    }
    
    function getPerms(){
    	return unserialize(gzinflate(Credentials::$session->read('perms')));
    }
    
    function session_connect($session){
    	//var_dump($session);
     	Credentials::$session = $session;
    }
    
      function setSessionObject($session_obj){
     	Credentials::$session_obj = $session_obj;
    }
    
    function setCredentials($data){
    	$_SESSION[$data];
    }
    
    function set($field,$value,$expire = null){
    	return Credentials::$session->write($field,$value,$expire);
	}
    
    function get($field){
    	return Credentials::$session->read($field);
	}
	
	function hasCredentials(){
		if(empty(Credentials::$session))return false;
		//return false;
		$value = Credentials::$session->read('logged_in');
		if(empty($value))
			return false;
		
	/*	$now = time();
		$last = Credentials::$session->read('last_activity');
		if(Credentials::get('remember_me') && (($now-$last) > LOGIN_TIMEOUT_RM)){
			return false;
		} else if(($now-$last) > LOGIN_TIMEOUT){
			return false;
		}*/
		/*
		if(!Credentials::get('remember_me')){
			Credentials::set('perms',$acl->perms,$time);
			Credentials::set('logged_in',1,$time);
			Credentials::set('__credentials',$result,$time);
			Credentials::set('last_activity',time(),$time);
		}
*/
		return true;
	}
}

function mformat($number){
	$number = str_replace(",","",$number);
	$t = number_format($number, 2, '.', ',');
	return $t;
}

function ndecimals($n){
	$n = str_replace(",","",$n);
	if(empty($n))$n = 0;
	$t = number_format($n, 2, '.', '');
	return $t;
}



function dev_dump($var){
	ob_start();
	var_dump($var);
	$c = ob_get_contents();
	ob_end_clean();
	return $c;
}

function array_cpval2key($v){
	$r = array_combine($v,$v);
	return $r;
}

function recibo_id($vars){
	if(empty($vars))return  "-";
	$r = $vars["secuencia"];
	$tmp = explode(" ",$vars["created"]);
	list($y,$m,$d) = explode("-",$tmp[0]);
	$len = 6;
	$curlen = strlen($r);	
	$pad = str_repeat("0",$len - $curlen);
	return $y."-".$pad.$r;
}

function monto2words($int,$show_cents = true){
	if(empty($int))return "";

	$defs = array(
		0 => "CERO",
		1 => "UNO",
		2 => "DOS",
		3 => "TRES",
		4 => "CUATRO",
		5 => "CINCO",
		6 => "SEIS",
		7 => "SIETE",
		8 => "OCHO",
		9 => "NUEVE",
		10 => "DIEZ",
		11 => "ONCE",
		12 => "DOCE",
		13 => "TRECE",
		14 => "CATORCE",
		15 => "QUINCE",
		16 => "DIECISEIS",
		17 => "DIECISIETE",
		18 => "DIECIOCHO",
		19 => "DIECINUEVE",
		20 => "VEINTE",
		30 => "TREINTA",
		40 => "CUARENTA",
		50 => "CINCUENTA",
		60 => "SESENTA",
		70 => "SETENTA",
		80 => "OCHENTA",
		90 => "NOVENTA",
		100 => "CIEN",
		500 => "QUINIENTOS"
	);

	$s = $part2 = "";
	$t = (int)$int;
	$cents = round($int - $t,2);
	$r = (string)$t;
	$len = strlen($r);
	if($len > 3 ){
		if($len == 4) $kk = 1;
		if($len == 5) $kk = 2;
		if($len == 6) $kk = 3;
		$part2 = substr($r,0,$kk);
		$m = substr($r,-3,3);
		//var_dump($part2,$m);
		$t = (int)$m;
	} else {
		$m = $r;
		$t = (int)$m;
	}

	if($part2 == "1"){
		$s = "UN MIL ";
	} else {
		$ooo = monto2words($part2,false);
		if(!empty($ooo))$s .= $ooo." MIL ";
	}


	if($t < 21){
		$s .= $defs[$t];
	} else if ($t < 101) {
		$u = $m[1];
		$t = $t - $u;
		//echo "-".var_dump($t)."-";
		$s .= $defs[$t];
		if($u != 0) $s .= "I".$defs[$u];			
	} else {
		$cientos = $m[0];
		$s .= $defs[$cientos]."CIENTOS ";		
		if($cientos == 1) $s = "CIENTO ";

		$jj = pow(10,$len - 1) * $cientos;
		$t = $t - $jj;

		$s .= monto2words(substr($m,1),false);

		/*$u = $m[2];
		$t = $t - $u;
		//echo "-".var_dump($t)."-";
		$s .= $defs[$t];
		if($u != 0) $s .= "I".$defs[$u];	*/

	}
	
	if($show_cents){
		if($cents > 0) $s .= " CON ".(round($cents,2)*100);
		if($cents== 0) $s .= " CON 00";
	}

	$s = str_replace("CEROI","",$s);
	$s = str_replace("CINCOCIENTOS","QUINIENTOS",$s);

	$len = strlen($r);

	return $s;
}




function createColumnsArray($end_column, $first_letters = '')
{
  $columns = array();
  $length = strlen($end_column);
  $letters = range('A', 'Z');

  // Iterate over 26 letters.
  foreach ($letters as $letter) {
      // Paste the $first_letters before the next.
      $column = $first_letters . $letter;

      // Add the column to the final array.
      $columns[] = $column;

      // If it was the end column that was added, return the columns.
      if ($column == $end_column)
          return $columns;
  }

  // Add the column children.
  foreach ($columns as $column) {
      // Don't itterate if the $end_column was already set in a previous itteration.
      // Stop iterating if you've reached the maximum character length.
      if (!in_array($end_column, $columns) && strlen($column) < $length) {
          $new_columns = createColumnsArray($end_column, $column);
          // Merge the new columns which were created with the final columns array.
          $columns = array_merge($columns, $new_columns);
      }
  }

  return $columns;
}
?>
