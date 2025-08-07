<?php
class PersonasController extends AppController {

	var $name = 'Personas';

	function index() {
		$this->Persona->recursive = 0;
		$this->set('personas', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->_flash(__('Invalid persona', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('persona', $this->Persona->read(null, $id));
	}

	function search(){
		$this->layout = null;
		header('Content-Type: text/html; charset=utf-8');
		$r = array();
		$data["flag"] = 0;

		if(!empty($this->params["url"]["dni_ruc"])){
			if(strpos($this->params["url"]["dni_ruc"],".")!==false){
				$r = $this->Persona->find("first",array("conditions"=>array("codigo_alumno"=>$this->params["url"]["dni_ruc"])));
			} else {
				$r = $this->Persona->find("first",array("conditions"=>array("dni_ruc"=>$this->params["url"]["dni_ruc"])));
			}

			if($r){
				$data = $r["Persona"];
				$data["flag"] = 1;
			} 
		}


		echo json_encode($data);
		exit;
	}

	function add() {
		if (!empty($this->data)) {
			$this->Persona->create();
			$validate = true;

			if($this->data["Persona"]["tipo"]==TEMPRESA){
				unset($this->Persona->validate['nombre']); 
				$validate = false;
			}

			if ($this->Persona->save($this->data,$validate)) {
				$this->_flash(__('The persona has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->_flash(__('The persona could not be saved. Please, try again.', true));
			}
		}

		App::import("Model","Entidad");
		$this->Entidad = new Entidad();
		$entidades = $this->Entidad->find('list');
		$this->set(compact('entidades'));

	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->_flash(__('Invalid persona', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {

			$validate = true;

			if($this->data["Persona"]["tipo"]==TEMPRESA){
				unset($this->Persona->validate['nombre']); 
				$validate = false;
			}

			if ($this->Persona->save($this->data,$validate)) {
				$this->_flash(__('The persona has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->_flash(__('The persona could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Persona->read(null, $id);
		}

		App::import("Model","Entidad");
		$this->Entidad = new Entidad();
		$entidades = $this->Entidad->find('list');
		$this->set(compact('entidades'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->_flash(__('Invalid id for persona', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Persona->delete($id)) {
			$this->_flash(__('Persona deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->_flash(__('Persona was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}


	function impp(){
		exit;

		App::import("Model","Persona");
		$persona = new Persona();
		if (($handle = fopen(APP."/libs/uupersonal.csv", "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 5000, ",")) !== FALSE) {
				$persona = new Persona();
				$check =$persona->save(array("Persona"=>array("nombre" =>$data[1],"dni_ruc"=>$data[0],"tipo"=>$data[2])));
				$persona->id = null;
				
				//echo $check."__".$data[0]."<br>";//var_dump($persona->validationErrors); 
			}
			fclose($handle);
		} else {
			die("ERROR");
		}
		die("listo");
	}
}
