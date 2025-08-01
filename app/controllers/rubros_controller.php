<?php
class RubrosController extends AppController {

	var $name = 'Rubros';
    var $paginate = array(   'limit' => 50    );
    var $helpers = array('Form', 'Alaxos.AlaxosForm', 'Alaxos.AlaxosHtml');
    var $components = array('Alaxos.AlaxosFilter');

	function beforeFilter(){
	
		$this->set('title_for_layout', 'Clasificador'); 
		parent::beforeFilter();
	}


	function index() {

		$this->Rubro->recursive = 0;
		$this->AlaxosFilter->set_auto_append_wildcard_characters(true);
		$conditions = $this->AlaxosFilter->get_filter();

		$this->set('rubros', $this->paginate($this->Rubro,$conditions));
	}


	function in(){

/*
ALTER TABLE  `u_recibos` ADD  `year` INT NOT NULL ,ADD  `secuencia` INT NOT NULL;
ALTER TABLE  `u_personas` ADD  `escuela` VARCHAR( 200 ) NULL ,ADD  `facultad` VARCHAR( 200 ) NULL;
UPDATE u_recibos SET year = 2013;
UPDATE u_recibos SET secuencia = 1;

*/
	exit;
$tt =
"131111;PRODUCTOS FRUTICOLAS
1311199;OTROS PRODUCTOS AGRICOLAS Y FORESTALES
131211;VENTA DE ANIMALES
1312199;OTROS BIENES PECUARIOS
131411;ALIMENTOS Y BEBIDAS
1314199;OTROS PRODUCTOS INDUSTRIALES
131418;PRODUCTOS METALICOS
131511;VENTA DE PUBLIC. (LIBROS, BOLETINES,FOLLETOS,VIDEOS Y OTROS)
1315199;OTROS PRODUCTOS DE EDUCACION
131912;VENTA DE BASES PARA LICITACION PUBLICA,CONCURSO PUBLICO Y OTROS
1319199;OTROS BIENES
132311;CARNETS
132312;DERECHOS EXAMEN DE ADMISION
132313;GRADOS Y TITULOS
132314;CONSTANCIAS Y CERTIFICADOS
132315;DERECHOS DE INSCRIPCION
132316;PENSION DE ENSEÑANZA
132317;MATRICULAS
132318;TRASLADOS Y CONVALIDACIONES
132319;DERECHOS UNIVERSITARIOS
1323199;OTROS DERECHOS ADMINISTRATIVOS DE EDUCACION
1321011;FORMULARIOS
1321014;LEGALIZACION DE DOCUMENTOS
1321015;CERTIFICACIONES DIVERSAS
13210199;OTROS DERECHOS ADMINISTRATIVOS
133111;ANALISIS DE SUELOS
1331199;OTROS SERVICIOS AGROPECUARIOS
133121;CONTROL DE INSUMOS
1331299;OTROS SEVICIOS DE MINERIA
133311;ENSEÑANZA EN CENTRO PREUNIVERSITARIO
133312;SERVICIO DE CAPACITACION
133313;PENSION DE ENSEÑANZA
133314;DERECHO DE MATRICULA
133315;SERVICIOS ACADEMICOS
1333199;OTROS SERVICIOS DE EDUCACION
133321;VACACIONES UTILES
133411;ATENCION MEDICA
133412;ATENCION DENTAL
133421;EXAMENES DE LABORATORIO
133511;EDIFICIOS E INSTALACIONES
133923;SERVICIOS DE INVESTIGACION Y DESARROLLO
133925;SERVICIOS DE COMEDOR Y CAFETERIAS
133928;SERVICIOS DE PUBLICIDAD E IMPRESIÓN
1339213;SERVICIOS DE PROCESAMIENTO AUTOMATICO DE DATOS 
152211;SANCIONES DE ADMINISTRACION GENERAL";

		$r = explode("\n",$tt);
		App::import("Model","Rubro");
		$rubro = new Rubro();

		App::import("Model","Entidad");
		$entidad  = new Entidad();

		App::import("Model","Precio");
		$precio  = new Precio();

		App::import("Model","Categoria");
		$cat  = new Categoria();
		$cats = $cat->find("all");
		$list_cats = array();
		foreach($cats as $item){
			$list_cats[$item["Categoria"]["codigo"]] = $item["Categoria"]["id"];
		}

		$list_rubros = array();

		/*foreach($r as $item){
			list($cod,$text) = explode(";",$item);
			$text = trim($text);
			$text = str_replace(array("\r","\n"),"",$text);
			$rubro->save(array("Rubro"=>array("codigo"=>$cod,"nombre" => $text,"estado" => 1,"categoria_id"=>$list_cats[substr($cod,0,3)])));
			$list_rubros[$cod] = $rubro->id;
			$rubro->id = null;
		}*/

		$rrtt = $rubro->find("all");
		foreach($rrtt as $item){
			$list_rubros[$item["Rubro"]["codigo"]] = $item["Rubro"]["id"];
		}


		$row = 0;
		$list_ents = array();

		if (($handle = fopen(APP."/libs/TUPAUNASAM.csv", "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
				$row++;
				if($row == 1)continue;

				if(!isset($list_ents[$data[4]])){
					$entidad->save(array("Entidad"=>array("nombre"  => $data[3],"estado" => 1,"codigo" =>$data[4])));
					$list_ents[$data[4]] = $entidad->id;
					$entidad->id = null;
				} else {
					$ent_id = $list_ents[$data[4]];
				}

				$rub_id = $list_rubros[$data[5]];

				$precio->save(array("Precio"=>array("monto" =>$data[2],"nombre"  => $data[1],"entidad_id"=>$ent_id,"estado" => 1,
													"codigo"=>$data[0],"rubro_id"=>$rub_id)));
				$precio->id = null;
			}
			fclose($handle);
		} else {
			die("ERROR");
		}

	}

	function alumnos(){
		$row = 0;
		if (($handle = fopen(APP."/libs/Alumnos_UNASAM.csv", "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$row++;
				if($row == 1)continue;
				$sql = "INSERT INTO u_personas (nombre,tipo,codigo_alumno,escuela,facultad,created,modified) 
							VALUES ('".$data[1]."',".TESTUDIANTE.",'".$data[0]."','".$data[2]."','".$data[3]."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
				//echo $sql;
				//file_put_contents(APP."/libs/alumnos_import.sql", $sql.";\n",FILE_APPEND);
				//mysql_query($sql);
				//exit;
			}
			fclose($handle);
		} else {
			die("ERROR");
		}

		exit;
	}

	function view($id = null) {
		if (!$id) {
			$this->_flash(__('Invalid rubro', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('rubro', $this->Rubro->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->Rubro->create();
			if ($this->Rubro->save($this->data)) {
				$this->_flash(__('The rubro has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->_flash(__('The rubro could not be saved. Please, try again.', true));
			}
		}

		$categorias = $this->Rubro->Categoria->find('list');
		$this->set(compact('categorias'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->_flash(__('Invalid rubro', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Rubro->save($this->data)) {
				$this->_flash(__('The rubro has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->_flash(__('The rubro could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Rubro->read(null, $id);
		}

		$categorias = $this->Rubro->Categoria->find('list');
		$this->set(compact('categorias'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->_flash(__('Invalid id for rubro', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Rubro->delete($id)) {
			$this->_flash(__('Rubro deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->_flash(__('Rubro was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
