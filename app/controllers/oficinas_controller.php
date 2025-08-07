<?php
class OficinasController extends AppController {

	var $name = 'Oficinas';

	function index() {
		$this->Oficina->recursive = 0;
		$this->set('oficinas', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->_flash(__('Invalid oficina', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('oficina', $this->Oficina->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->Oficina->create();
			if ($this->Oficina->save($this->data)) {
				$this->_flash(__('The oficina has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->_flash(__('The oficina could not be saved. Please, try again.', true));
			}
		}
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->_flash(__('Invalid oficina', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Oficina->save($this->data)) {
				$this->_flash(__('The oficina has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->_flash(__('The oficina could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Oficina->read(null, $id);
		}
	}

	function delete($id = null) {
		if (!$id) {
			$this->_flash(__('Invalid id for oficina', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Oficina->delete($id)) {
			$this->_flash(__('Oficina deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->_flash(__('Oficina was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
