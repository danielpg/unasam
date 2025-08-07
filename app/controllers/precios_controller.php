<?php
class PreciosController extends AppController {

	var $name = 'Precios';

    var $helpers = array('Form', 'Alaxos.AlaxosForm', 'Alaxos.AlaxosHtml');
    var $components = array('Alaxos.AlaxosFilter');
    var $paginate = array(   'limit' => 50    );



	function autocomplete(){
		header('Content-Type: text/html; charset=utf-8');
		$search = "";
		$this->autoRender = false;
		$this->Precio->recursive = -1;
		$term = trim($this->params["url"]["term"]);
		$parts = explode(" ",$term);
		//exit;
		if(is_numeric($term)){
			if(strlen($term)==4){
				$term = substr($term,0,2).".".substr($term,2,2);
			}
			$cd = array("Precio.codigo" => $term);
		}  else {
			$cd = array("Precio.nombre LIKE" => "%".$term."%");
		}

		$data = $this->Precio->find("all",array(
										  "limit"=>20,
										  "conditions"=>$cd
												)		
				);

		//var_dump($data, $term);		
		$list = array();
		foreach($data as $item){
			$list[] = $item["Precio"];
		}
		echo json_encode($list);
		exit;
	}

	function index() {
		$this->Precio->recursive = 0;
 $this->AlaxosFilter->set_auto_append_wildcard_characters(true);
		$conditions = $this->AlaxosFilter->get_filter();

		$this->set('precios', $this->paginate($this->Precio,$conditions));
	}

	function view($id = null) {
		if (!$id) {
			$this->_flash(__('Invalid precio', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('precio', $this->Precio->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->Precio->create();
			if ($this->Precio->save($this->data)) {
				$this->_flash(__('The precio has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->_flash(__('The precio could not be saved. Please, try again.', true));
			}
		}


		$entidades = $this->Precio->Entidad->find('list',array("recursive"=> -1,"conditions"=>array("estado" => 1 )));


		$rrubros = $this->Precio->Rubro->find('all',array("recursive"=> -1,"conditions"=>array("estado" => 1 ), "order" => "Rubro.nombre ASC"));
		$rubros = array();
		foreach($rrubros as $item){
			$rubros[$item["Rubro"]["id"]] = $item["Rubro"]["codigo"]." - ".$item["Rubro"]["nombre"];
		}

		$this->set(compact('rubros',"entidades"));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->_flash(__('Invalid precio', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Precio->save($this->data)) {
				$this->_flash(__('The precio has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->_flash(__('The precio could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Precio->read(null, $id);
		}
		$entidades = $this->Precio->Entidad->find('list',array("recursive"=> -1,"conditions"=>array("estado" => 1 )));

		$rrubros = $this->Precio->Rubro->find('all',array("recursive"=> -1,"conditions"=>array("estado" => 1 ), "order" => "Rubro.nombre ASC"));
		$rubros = array();
		foreach($rrubros as $item){
			$rubros[$item["Rubro"]["id"]] = $item["Rubro"]["codigo"]." - ".$item["Rubro"]["nombre"];
		}
		$this->set(compact('rubros',"entidades"));
	}

	function delete($id = null) {
		if (!$id) {
			$this->_flash(__('Invalid id for precio', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Precio->delete($id)) {
			$this->_flash(__('Precio deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->_flash(__('Precio was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
