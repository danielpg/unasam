<?php
class EntidadesController extends AppController {

	var $name = 'Entidades';
    var $helpers = array('Form', 'Alaxos.AlaxosForm', 'Alaxos.AlaxosHtml');
    var $components = array('Alaxos.AlaxosFilter');
    var $paginate = array(   'limit' => 50    );


	function index() {
		$this->Entidad->recursive = 0;
 $this->AlaxosFilter->set_auto_append_wildcard_characters(true);
		$conditions = $this->AlaxosFilter->get_filter();

		$this->set('entidades', $this->paginate( $this->Entidad,$conditions ));
	}

	function view($id = null) {
		if (!$id) {
			$this->_flash(__('Invalid entidad', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('entidad', $this->Entidad->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->Entidad->create();
			if ($this->Entidad->save($this->data)) {
				$this->_flash(__('The entidad has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->_flash(__('The entidad could not be saved. Please, try again.', true));
			}
		}
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->_flash(__('Invalid entidad', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Entidad->save($this->data)) {
				$this->_flash(__('The entidad has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->_flash(__('The entidad could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Entidad->read(null, $id);
		}
	}

	function delete($id = null) {
		if (!$id) {
			$this->_flash(__('Invalid id for entidad', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Entidad->delete($id)) {
			$this->_flash(__('Entidad deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->_flash(__('Entidad was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
