<?php
class Persona extends AppModel {

	var $name = 'Persona';
	var $displayField = "nombre";


	var $validate = array(
      'nombre' => array(
			'allowEmpty' => false,
			'rule' => '/^[^1234567890!"#\[\]$%&¡\{\}*@¿_:;.-]+$/i',
			'message' => 'Valores invalidos.'
		)
		,  
		'dni_ruc' => array(
        	'rule' => 'checkUnique',
			'on' => "create",
			'message' => 'Valor ya existe.'
    	)
	);
//delete FROM `u_personas` WHERE id > 14
 		function checkUnique($c) { 
			//$c = $this->isUnique(array('dni_ruc' => $this->data['Persona']['dni_ruc']));
			//var_dump($c);exit;
			$c = $this->find("first",array("conditions"=>array('dni_ruc' => $c['dni_ruc'])));
			if(empty($c)){
				return true;
			} else {			
				return false;
			}
			//var_dump($c);
            return $c;
        } 
	function beforeSave(){
		//die("beforeSAVE");
		/*if($this->data["Persona"]["tipo"] != TEMPRESA){
			$rule = '/^[^1234567890!"#\[\]$%&¡\{\}*@¿_:;,.-\s]+/i';

			$cc = preg_match($rule ,"asdasd");//$this->data["Persona"]["nombre"]);
			var_dump($cc);


			$cc = preg_match($rule ,"asdasd123132");//$this->data["Persona"]["nombre"]);
			var_dump($cc);
			
			exit;
			ClassRegistry::get("controller")->_flash("Nombre Invalido.","error");
			return false;

		}*/



		return parent::beforeSave();
	}
	

}


?>
