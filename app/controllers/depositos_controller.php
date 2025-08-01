<?php

class DepositosController extends AppController {

	var $name = 'Depositos';
	//var $uses = array("Deposito");
    var $helpers = array('Form', 'Alaxos.AlaxosForm', 'Alaxos.AlaxosHtml');
    var $components = array('Alaxos.AlaxosFilter');
    var $paginate = array(   'limit' => 50    );


	function index() {
		$this->Deposito->recursive = 0;

		$this->AlaxosFilter->set_auto_append_wildcard_characters(true);
		$conditions = $this->AlaxosFilter->get_filter();
		if(in_array(Credentials::get("__credentials.Login.role_id"),array(2,3))){
			$conditions["oficina_id"] = Credentials::get("__credentials.Login.oficina_id");
		}

		$entidades = array();
		$a_entidades = $this->Deposito->Entidad->find('all',array("recursive"=> -1));
		foreach($a_entidades as $item){
			$entidades[$item["Entidad"]["id"]] = $item["Entidad"]["nombre"]." - ".$item["Entidad"]["codigo"];
		}


		//var_dump($conditions);
		$this->set('entidades', $entidades);
		$this->set('depositos', $this->paginate($this->Deposito,$conditions));
	}

	function view($id = null) {
		if (!$id) {
			$this->_flash(__('Invalid deposito', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('deposito', $this->Deposito->read(null, $id));
	}

	function add() {

		if (!empty($this->data)) {
			$this->Deposito->create();
			if ($this->Deposito->save($this->data)) {
				$this->_flash(__('The deposito has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->_flash(__('The deposito could not be saved. Please, try again.', true));
			}
		}


		//$entidades = $this->Deposito->Entidad->find('list',array("recursive"=> -1,"conditions"=>array("estado" => 1 )));
		$entidades = array();
		$a_entidades = $this->Deposito->Entidad->find('all',array("recursive"=> -1,"conditions"=>array("estado" => 1 )));
		foreach($a_entidades as $item){
			$entidades[$item["Entidad"]["id"]] = $item["Entidad"]["codigo"]." - ".$item["Entidad"]["nombre"];
		}

		$rubros = $this->Deposito->Rubro->find('list',array("recursive"=> -1,"conditions"=>array("estado" => 1 )));
		$this->set(compact("entidades","rubros"));
	}
//634 681 430 - 6701

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->_flash(__('Invalid deposito', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Deposito->save($this->data)) {
				$this->_flash(__('The deposito has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->_flash(__('The deposito could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Deposito->read(null, $id);
		}

		//$entidades = $this->Deposito->Entidad->find('list',array("recursive"=> -1,"conditions"=>array("estado" => 1 )));
		$entidades = array();
		$a_entidades = $this->Deposito->Entidad->find('all',array("recursive"=> -1,"conditions"=>array("estado" => 1 )));
		foreach($a_entidades as $item){
			$entidades[$item["Entidad"]["id"]] = $item["Entidad"]["codigo"]." - ".$item["Entidad"]["nombre"];
		}
		$rubros = $this->Deposito->Rubro->find('list',array("recursive"=> -1,"conditions"=>array("estado" => 1 )));
		$this->set(compact("entidades","rubros"));
	}

	function delete($id = null) {
		if (!$id) {
			$this->_flash(__('Invalid id for deposito', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Deposito->delete($id)) {
			$this->_flash(__('Deposito deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->_flash(__('Deposito was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
