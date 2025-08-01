<?php
class LoginsController extends AppController {



	var $name = 'Logins';
	//var $uses = array("Login","Role");

	function index() {
		$this->Login->recursive = 1;
		$this->set('logins', $this->paginate());
	}

	function beforeFilter(){
		parent::beforeFilter();
		
		$this->set('title_for_layout', 'Usuarios'); 
	}


	function view($id = null) {
		if (!$id) {
			$this->_flash(__('Invalid login', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('login', $this->Login->read(null, $id));
		$this->set('roles', $this->Login->Role->find('list'));
	}

	function add() {
		if (!empty($this->data)) {
			$this->Login->create();
			if ($this->Login->save($this->data)) {
				$this->_flash(__('The login has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->_flash(__('The login could not be saved. Please, try again.', true));
			}
		}
		//$registros = $this->Login->Registro->find('list');
		$this->set('roles', $this->Login->Role->find('list'));
		$this->set('oficinas', $this->Login->Oficina->find('list'));
		//$this->set(compact('registros'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->_flash(__('Invalid login', true));
			$this->redirect(array('action' => 'index'));
		}

		if(!empty($this->data) && !empty($this->data["Login"]["password"]) && ($this->data["Login"]["password"] != $this->data["Login"]["confirm_password"])){
			$this->_flash("La confirmación de contraseña no coincide.","error");
		} else {
			if (!empty($this->data)) {
				if(empty($this->data["Login"]["password"]))unset($this->data["Login"]["password"]);

				if ($this->Login->save($this->data)) {
					$this->_flash(__('The login has been saved', true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->_flash(__('The login could not be saved. Please, try again.', true));
				}
			}
		}

		if (empty($this->data)) {
			$data = $this->Login->read(null, $id);
			//unset($data["Login"]["password"]);
			$data["Login"]["password"] = "";
			$this->data = $data;
			
		}


		$this->set('roles', $this->Login->Role->find('list'));
		$this->set('oficinas', $this->Login->Oficina->find('list'));
		//$registros = $this->Login->Registro->find('list');
		//$this->set(compact('registros'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->_flash(__('Invalid id for login', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Login->delete($id)) {
			$this->_flash(__('Login deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->_flash(__('Login was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
