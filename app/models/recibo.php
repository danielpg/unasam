<?php
class Recibo extends AppModel {

	var $name = 'Recibo';
	var $order = "Recibo.created DESC";

	var $belongsTo = array(
		'Precio' => array(
			'className' => 'Precio',
			'foreignKey' => 'precio_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Persona' => array(
			'className' => 'Persona',
			'foreignKey' => 'persona_id',
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

	function _validationRules(){
		$this->validate = array(
			'precio_id' => array('rule'=>'numeric',"required"=>true,'message'=>__('No se selecciono precio.',true))
		);
	}

	function beforeSave(){
		$this->data["Recibo"]["login_id"] = Credentials::get("__credentials.Login.id");
		$this->data["Recibo"]["oficina_id"] = Credentials::get("__credentials.Login.oficina_id");

		if(isset($this->data["Recibo"]["extorno"]) && $this->data["Recibo"]["extorno"] == 1){
					$this->data["Recibo"]["fecha_extorno"] = date("Y-m-d H:i:s");
		}



		if(isset($this->data["Recibo"]["dni_ruc"]) && empty($this->data["Recibo"]["dni_ruc"])){
			ClassRegistry::getObject("controller")->_flash("DNI o RUC invalido.","error");
			return false;
		}

		if(!isset($this->data["Recibo"]["id"] )){
			$r = mysql_query("SELECT MAX(secuencia) as total FROM u_recibos WHERE oficina_id = ".$this->data["Recibo"]["oficina_id"]." AND year = ".date("Y")." LIMIT 1");
			$secuencia = 0;		
			while($row = mysql_fetch_array($r)){
				$secuencia = $row["total"];
			}

			if(empty($secuencia)){
				if(date("Y")=="2013"){
					if($this->data["Recibo"]["oficina_id"] == 1){
						$secuencia = 1;
					}elseif($this->data["Recibo"]["oficina_id"] == 2){
						$secuencia = 1;
					}else{
						$secuencia = 1;
					}
				} else {
					$secuencia = 1;
				}
			} else {
				$secuencia = $secuencia + 1;
			}
			$this->data["Recibo"]["secuencia"] = $secuencia;
			$this->data["Recibo"]["year"] = date("Y");
			$this->data["Recibo"]["codigo"] = recibo_id($this->data["Recibo"]);
		}



		if(empty($this->data["Recibo"]["persona_id"]) && isset($this->data["Recibo"]["dni_ruc"])){
			App::import("Model","Persona");
			$p = new Persona();
			$tipo = TPERSONA;
			$len = trim(strlen($this->data["Recibo"]["dni_ruc"]));
			if( $len > 8)	$tipo = TEMPRESA;

			if($len != 8 && $len != 11)	{
				ClassRegistry::getObject("controller")->_flash("DNI o RUC debe contener 8 y/o 11 digitos respectivamente.","error");
				return false;
			}

			$p->save(array("Persona"=>array("dni_ruc"=>$this->data["Recibo"]["dni_ruc"],"direccion"=>$this->data["Recibo"]["direccion"] ,"nombre"=>$this->data["Recibo"]["nombres"],"tipo"=>$tipo)));
			$this->data["Recibo"]["persona_id"] = $p->id;

		}

		return parent::beforeSave();
	}

	function afterSave($created = true){
		if($created){
			$p = new Precio();
			$p->unbindModelAll();
			$r = $p->find("first",array("conditions"=>array("Precio.id"=>$this->data["Recibo"]["precio_id"])));
			//var_dump($r);exit;
			$t = round($r["Precio"]["monto"] * $this->data["Recibo"]["cantidad"],2);
			$this->saveField("monto", $t);
		}
	}

}


?>
