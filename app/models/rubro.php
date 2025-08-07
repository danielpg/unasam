<?php
class Rubro extends AppModel {

	var $name = 'Rubro';
	var $displayField = "nombre";

	var $belongsTo = array(
		'Categoria' => array(
			'className' => 'Categoria',
			'foreignKey' => 'categoria_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}


?>
