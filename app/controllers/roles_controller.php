<?php



class RolesController extends AppController {
	//var $uses = array();
	var $name = 'Roles';

	function index() {
		$this->Role->recursive = 0;
		$this->set('roles', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->_flash(__('Invalid role', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('role', $this->Role->read(null, $id));
	}

	function add() {
		$this->redirect(array('action' => 'index'));exit;if (!empty($this->data)) {
			$this->Role->create();
			if ($this->Role->save($this->data)) {
				$this->_flash(__('The role has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->_flash(__('The role could not be saved. Please, try again.', true));
			}
		}
	}

	function edit($id = null) {
		$this->redirect(array('action' => 'index'));exit;if (!$id && empty($this->data)) {
			$this->_flash(__('Invalid role', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Role->save($this->data)) {
				$this->_flash(__('The role has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->_flash(__('The role could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Role->read(null, $id);
		}
	}

	function delete($id = null) {
		$this->redirect(array('action' => 'index'));exit;if (!$id) {
			$this->_flash(__('Invalid id for role', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Role->delete($id)) {
			$this->_flash(__('Role deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->_flash(__('Role was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
