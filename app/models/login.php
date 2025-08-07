<?php
class Login extends AppModel {

	var $name = 'Login';
	/*
	var $validate = array(
		'username' => array(
		'alphaNumeric'=>array('rule'=>'alphaNumeric','required'=>true,'message'=>'Username can only contains letters'),
		'between' => array('rule' => array('between', 4, 10),'message' => 'Between 4 to 10 characters')),
		'password' => array('rule'=>array('alphaNumeric'),'required'=>true,'message'=>'Password for login the system can only contains letters and numbers'),
		'user_id' => array('numeric')
	);
*/
	function _validationRules(){
		$this->validate = array(
			'username' => array(
				'notempty'=>array('rule'=>array('notempty'),'message'=>__('Usuario no valido',true)),
				'between' => array('rule' => array('between', 4, 20),'message' => __('Usuario debe tener entre 4 a 20 caracteres',true)),
				'isUnique' => array('rule' => array('isUnique'),'message' => __('Usuario ya existe',true))

			),
			'password' => array(
				'notempty'=>array('rule'=>array('notempty'),'message'=>__('Contraseña no valida',true))
			)
		);
	}
	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $belongsTo = array(
		'Oficina' => array(
			'className' => 'Oficina',
			'foreignKey' => 'oficina_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Role' => array(
			'className' => 'Role',
			'foreignKey' => 'role_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	
	function beforeSave(){
		if(isset($this->data['Login']['password'])) $this->data['Login']['password'] = md5($this->data['Login']['password']);

		return parent::beforeSave();
	}
	
	function authenticate($data){
		$this->data = $data;
		$this->recursive = 0;
		$result = $this->find('first',
			array('conditions'=>
				array('Login.username'=>$this->data['Login']['username'],
					  'Login.password'=>md5($this->data['Login']['password']),
			  		  'Login.estado'=> 1
				)
			)
		);
		


		if($result != false){
			/*
			if($data['Login']['remember_me']){
				$time = LOGIN_TIMEOUT_RM;
			}else{
				$time = LOGIN_TIMEOUT;
			}*/
	
		
			//$this->cleanMenuCache();
	
			//$acl = new ACL($result['Login']['id']);
			//Credentials::setPerms($acl->perms,$time);
			
			//$result['Roles'] = $acl->getUserRoles();

			$is_admin = false;
			//if(in_array(ROLE_ADMIN_ID,$result['Roles']))$is_admin = true;
			$result['is_admin'] = $is_admin;
			
			//var_dump($acl->perms);exit;
			Credentials::set('logged_in',1,$time);
			Credentials::set('remember_me',$data['Login']['remember_me'],$time);
			Credentials::set('__credentials',$result,$time);

			//Credentials::set('last_activity',time(),$time);
			//Credentials::can(false,$acl->perms);	

			return true;
		} else {
			
			return false;
		}
		
		
	}
	
	function cleanMenuCache(){
		if(!Credentials::hasCredentials())return false;
		
		$list = Configure::read('lang_list');
		foreach($list as $code => $tmp){
       		@unlink(Configure::read('asterisk.tmp_path').'menuuser-'.Credentials::get('__credentials.User.id').'-'.$code.'.txt');
		}
		return true;	
	}
	
	function afterSave($created = false){
		return true;
		if($created){
			$acl = new Acl();
			$acl->saveUserRole($this->id,Configuration::get('default_role'));
		}
	}
	
	function logout(){
		Credentials::set('__credentials',false);
		Credentials::set('perms',false);
		Credentials::set('logged_in',false);
		Credentials::set('remember_me',false);
		//Credentials::set('last_activity',false);
	}

}


?>
