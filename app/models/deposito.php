<?php
class Deposito extends AppModel {

	var $name = 'Deposito';

	var $order = "Deposito.periodo DESC";



	public $actsAs =
	 array('FileModel'=>
		    array(
		        'dir'=>array('uploads'),
		        'file_field'=>array('file'),
		        'file_db_file'=>array('archivo')
		        )
		  );

	var $belongsTo = array(
		'Entidad' => array(
			'className' => 'Entidad',
			'foreignKey' => 'entidad_id',
			'conditions' => '',

			'fields' => '',
			'order' => ''
		),
		'Rubro' => array(
			'className' => 'Rubro',
			'foreignKey' => 'rubro_id',
			'conditions' => '',

			'fields' => '',
			'order' => ''
		)
	);

	function _validationRules(){



		$this->validate = array(
			'monto' => array('rule'=>'numeric',"required"=>true,'message'=>__('Insertar monto.',true))
		);
	}

	function beforeSave(){

		$this->data["Deposito"]["login_id"] = Credentials::get("__credentials.Login.id");
		$this->data["Deposito"]["oficina_id"] = Credentials::get("__credentials.Login.oficina_id");

		return parent::beforeSave();
	}

}


?>
