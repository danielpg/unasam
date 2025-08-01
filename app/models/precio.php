<?php
class Precio extends AppModel {

	var $name = 'Precio';
	var $displayField = "nombre";

	var $virtualFields = array(  
	   'fullname' => "CONCAT(Precio.codigo,' ',Precio.nombre,' (S/.',Precio.monto,')')"  
	 );  

	var $belongsTo = array(
		'Rubro' => array(
			'className' => 'Rubro',
			'foreignKey' => 'rubro_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Entidad' => array(
			'className' => 'Entidad',
			'foreignKey' => 'entidad_id',
			'conditions' => '',

			'fields' => '',
			'order' => ''
		)
	);
	

}


?>
