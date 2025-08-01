<?php
//Configure::write('debug',2);
//require_once(APP."vendors/acl/class.acl.php");
class AuthenticationController extends AppController {

	var $uses = array();
	
	function index(){
		$this->redirect(array('action'=>'login'));exit;
	}
	
	
	function beforeFilter(){
		parent::beforeFilter();	
		$this->set("title_for_layout","Iniciar Sesion");	
 
 		if($this->action!='logout' && Credentials::hasCredentials()==true){
 			$this->redirect(array('controller'=>'recibos','action'=>'index'));
 			exit;
 		}
 		
	}

	function login() {

		if (!empty($this->data)) {
			
			App::import('Model','Login');
			$login = new Login();

			if ($login->authenticate($this->data)) {
				if ($this->params["isAjax"]) {
					Configure::write('debug',0);
					echo json_encode(array("error"=>0,"url_redirect"=>Router::url(array('controller'=>'recibos','action'=>'index'))));exit;
				} else {
					//echo json_encode(array("error"=>0,"url_redirect"=>"recibos/intro"));exit;
					$this->redirect(array('controller'=>'recibos','action'=>'index'));	
				}
			} else {
				if ($this->RequestHandler->isAjax()) {
					Configure::write('debug',0);
					echo json_encode(array("error"=>1,"validation_errors"=>'<div class="error_message">'.__('Usuario y contrase&ntilde;a invalidos.',true).'</div>'));exit;
				} else {
					$this->redirect(array('action'=>'index'));	
				}
			}
		}
		$this->render('empty','login');
	}

	function logout($id = null) {
		$this->autoRender = false;
		App::import('Model','Login');
		$login = new Login();
		$login->logout();
		//$this->_flash(__('Successful Logout', true),'default',array('class'=>'box_notice'));
		$this->redirect(array('action'=>'login'));
	}
}
?>
