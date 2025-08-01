<?php
class RecibosController extends AppController {

	var $name = 'Recibos';
    var $paginate = array(   'limit' => 50    );
    var $helpers = array('Form', 'Alaxos.AlaxosForm', 'Alaxos.AlaxosHtml');
    var $components = array('Alaxos.AlaxosFilter');
	function index() {

		$this->AlaxosFilter->set_auto_append_wildcard_characters(true);
		$conditions = $this->AlaxosFilter->get_filter();
		$conditions["OR"] = array("Recibo.extorno !=" => "1","Recibo.extorno" =>null);
		if(in_array(Credentials::get("__credentials.Login.role_id"),array(2,3))){
			$conditions["oficina_id"] = Credentials::get("__credentials.Login.oficina_id");
		}

		$this->Recibo->recursive = 0;
		$this->set('recibos', $this->paginate("Recibo",$conditions));
	}



	function extorno() {
		$this->Recibo->recursive = 0;

		$conditions = array("Recibo.extorno" => 1);
		if(in_array(Credentials::get("__credentials.Login.role_id"),array(2,3))){
			$conditions["oficina_id"] = Credentials::get("__credentials.Login.oficina_id");

		}

		$this->set('recibos', $this->paginate("Recibo",$conditions));
	}

/*
update u_precios set estado = 1;
update u_rubros set estado = 1;
update u_entidades set estado = 1;
*/

	function view($id = null) {
		if (!$id) {
			$this->_flash(__('Invalid recibo', true));
			$this->redirect(array('action' => 'index'));
		}
		$recibo = $this->Recibo->read(null, $id);

		App::import("Model","Login");
		App::import("Model","Oficina");
		$login  = new Login();
		$oficina= new Oficina();

		$dlogin = $login->read(null,$recibo["Recibo"]["login_id"]);
		$doficina = $oficina->read(null,$recibo["Recibo"]["oficina_id"]);

		$recibo["Usuario"] = $dlogin["Login"]; 
		$recibo["Oficina"] = $doficina["Oficina"];

		$this->set('recibo', $recibo);
	}

	function add() {
		if (!empty($this->data)) {
			$this->Recibo->create();
			if ($this->Recibo->save($this->data)) {
				$this->_flash(__('The recibo has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->_flash(__('The recibo could not be saved. Please, try again.', true));
			}
		}

		$rprecios = $this->Recibo->Precio->find('all',array("recursive"=> -1,"conditions"=>array("estado" => 1 ), "order" => "Precio.nombre ASC"));
		$precios = array();
		foreach($rprecios as $item){
			$precios[$item["Precio"]["id"]] = $item["Precio"]["codigo"]." - ".$item["Precio"]["nombre"]." (S/. ".mformat($item["Precio"]["monto"]).")";
		}

		$entidades = $this->Recibo->Entidad->find('list');
		$this->set(compact('precios', 'entidades'));
	}


	function add_super() {
		if (!empty($this->data)) {
			$this->Recibo->create();
			if ($this->Recibo->save($this->data)) {
				//$this->_flash(__('The recibo has been saved', true));
				echo $this->Recibo->id;exit;
				//$this->redirect(array('action' => 'index'));
			} else {
				echo 0;exit;//$this->_flash(__('The recibo could not be saved. Please, try again.', true));
			}
		}

		$rprecios = $this->Recibo->Precio->find('all',array("recursive"=> -1,"conditions"=>array("estado" => 1 ), "order" => "Precio.nombre ASC"));
		$precios = array();
		foreach($rprecios as $item){
			$precios[$item["Precio"]["id"]] = $item["Precio"]["codigo"]." - ".$item["Precio"]["nombre"]." (S/. ".mformat($item["Precio"]["monto"]).")";
		}

		$entidades = $this->Recibo->Entidad->find('list');
		$this->set(compact('precios', 'entidades'));
	}


	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->_flash(__('Invalid recibo', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Recibo->save($this->data)) {
				$this->_flash(__('The recibo has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->_flash(__('The recibo could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Recibo->read(null, $id);
		}

		$rprecios = $this->Recibo->Precio->find('all',array("recursive"=> -1,"conditions"=>array("estado" => 1 ), "order" => "Precio.nombre ASC"));
		$precios = array();
		foreach($rprecios as $item){
			$precios[$item["Precio"]["id"]] = $item["Precio"]["codigo"]." - ".$item["Precio"]["nombre"]." (S/. ".mformat($item["Precio"]["monto"]).")";
		}

		$entidades = $this->Recibo->Entidad->find('list');
		$this->set(compact('precios', 'entidades'));
	}


	function imp($id = null){
		header('Content-Type: text/html; charset=utf-8');	
		$this->layout = false;

		if (!$id) {
			$this->_flash(__('Invalid recibo', true));
			$this->redirect(array('action' => 'index'));
		}

		$recibo = $this->Recibo->read(null, $id);
		App::import("Model","Rubro");
		$obj = new Rubro();
		App::import("Model","Entidad");


		$ent = new Entidad();
		$rubro = $obj->read(null,$recibo["Precio"]["rubro_id"]);
		$entidad = $ent->read(null,$recibo["Precio"]["entidad_id"]);
		$perentidad = $ent->read(null,$recibo["Persona"]["entidad_id"]);
		$recibo["Rubro"] = $rubro["Rubro"];
		$recibo["P.Entidad"] = $entidad["Entidad"];
		$recibo["Entidad"] = $perentidad["Entidad"];
		$recibo["Categoria"] = $rubro["Categoria"]; 

		//var_Dump($recibo);

		$this->set('recibo', $recibo );
	}


	function delete($id = null) {
		if (!$id) {
			$this->_flash(__('Invalid id for recibo', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Recibo->delete($id)) {
			$this->_flash(__('Recibo deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->_flash(__('Recibo was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}

//RECIBO	FECHA	     DATOS	               CODPAGO	MONTO	OFICINA	NOM_OFIC	            CONT	M_LETRAS
//18908 	5/2/2012	SOBERANIS LORENZO EDER	03.01	15.00	III	    COMEDOR UNIVERSITARIO	133925	QUINCE CON 00

	function report(){

		if(!empty($this->data)){
			set_include_path(get_include_path() . PATH_SEPARATOR .  APP.'/vendors/');

			require_once APP.'/vendors/PHPExcel.php';
			require_once  APP.'/vendors/PHPExcel/IOFactory.php';

			require APP."/vendors/exportdata.class.php";

			//$bigletters = createColumnsArray("GZ");

			$objPHPExcel = new PHPExcel();
			$objPHPExcel->getProperties()->setCreator("UNASAM")
							->setLastModifiedBy("UNASAM")
							->setTitle("Reporte")
							->setSubject("Reporte")
							->setDescription("Reporte")
							->setKeywords("UNASAM")
							->setCategory("Reporte");
			$objPHPExcel->createSheet(0 );
			$objPHPExcel->setActiveSheetIndex( 0 );
			$objPHPExcel->getActiveSheet()->setTitle( "Reporte");

			$letters = range("a","z");

			list($d,$m,$y) = explode("/",$this->data["Recibo"]["fecha_inicio"] );
			$start_date = $y."-".$m."-".$d;

			$year = $y;

			list($d,$m,$y) = explode("/",$this->data["Recibo"]["fecha_final"] );
			$end_date = $y."-".$m."-".$d;

			//$start_date = "2011-01-01";
			//$end_date = date("Y-m-d");

			APP::import("Model","Categoria");
			$cat = new Categoria();
			$cats = $cat->find("all",array("order"=>"Categoria.codigo ASC"));
			$tmp = $cats[0];
			unset($cats[0]);
			$cats[] = $tmp;
			$categorias = array();
			foreach($cats as $item){
				$categorias[$item["Categoria"]["id"]] = $item["Categoria"];
			}

			APP::import("Model","Rubro");
			$rubro = new Rubro();
			$rubros = $rubro->find("all",array("order"=>"Rubro.categoria_id ASC, Rubro.codigo ASC"));

			APP::import("Model","Entidad");
			$Entidad = new Entidad();
			$entidades = $Entidad->find("all");

			//var_dump($letters);exit;

			//  CONSOLIDADO XML ###########################################################################################################################
			if($this->data["Recibo"]["tipo"] ==  1111) {
					$r = mysql_query("SELECT d.*,e.nombre AS entidad_nombre, o.nombre as oficina_nombre 
								 FROM u_depositos d
								 LEFT JOIN u_entidades e ON d.entidad_id = e.id
								 LEFT JOIN u_oficinas o ON d.oficina_id = o.id
								 WHERE d.periodo BETWEEN '".$start_date." 00:00:00' AND '".$end_date." 23:59:59' 
								".(empty($this->data["Recibo"]["oficina_id"])?"":" AND d.oficina_id = ".$this->data["Recibo"]["oficina_id"]." ")."
								 ORDER BY periodo ASC
				 ");
				$depositos = array();
				while($row = mysql_fetch_array($r)){
					$depositos[] = $row;
				}

				
				$min_recibo = $max_recibo = 0;

				$r = mysql_query("SELECT id
								 FROM u_recibos r
								 WHERE r.created BETWEEN '".$start_date." 00:00:00' AND '".$end_date." 23:59:59'  
								ORDER BY created ASC  LIMIT 1");
				$row = mysql_fetch_array($r);
				if(!empty($row))$min_recibo = $row["id"];

				$r = mysql_query("SELECT id
								 FROM u_recibos r
								 WHERE r.created BETWEEN '".$start_date." 00:00:00' AND '".$end_date." 23:59:59'  ORDER BY created DESC  LIMIT 1");
				$row = mysql_fetch_array($r);
				if(!empty($row))$max_recibo = $row["id"];


				$r = mysql_query("SELECT SUM(r.monto) as total,ru.codigo AS rubro_codigo,ru.id as ru_rubro_id,ru.nombre as rubro_nombre,ru.categoria_id as rubro_categoria_id
								 FROM u_recibos r
								 LEFT JOIN u_precios p    ON r.precio_id = p.id
								 LEFT JOIN u_entidades e  ON p.entidad_id = e.id
								 LEFT JOIN u_rubros ru    ON p.rubro_id = ru.id
								 LEFT JOIN u_categorias c ON ru.categoria_id = c.id
								 WHERE r.created BETWEEN '".$start_date." 00:00:00' AND '".$end_date." 23:59:59'   AND (extorno != 1 OR extorno IS NULL) 
								".(empty($this->data["Recibo"]["oficina_id"])?"":" AND r.oficina_id = ".$this->data["Recibo"]["oficina_id"]." ")."
								 GROUP BY ru.id
								 ORDER BY c.codigo ASC
				 ");

				$exporter = new ExportDataExcel('browser', 'reporte.xls');
				$exporter->initialize(); // starts streaming data to web browser


				$exporter->addRow(array(
					   "",
					   "REGISTRO SIAF ",
					   "",
					   "",""
					));

				$exporter->addRow(array(
					   "",
					   "CAPTACION DE INGRESOS DEL:".$this->data["Recibo"]["fecha_inicio"]." - ".$this->data["Recibo"]["fecha_final"],
					   "",
					   "",""
					));

				$exporter->addRow(array(
					   "",
					   "Recibos:".recibo_id($min_recibo)." - ".recibo_id($max_recibo),
					   "",
					   "",""
					));


				//echo "<table>";
				$data = array();
				$cats_total = array();
				while($row = mysql_fetch_array($r)){
					$data[$row["ru_rubro_id"]] = $row;
					if(!isset($cats_total[$row["rubro_categoria_id"]]))$cats_total[$row["rubro_categoria_id"]] = 0;
					$cats_total[$row["rubro_categoria_id"]] += $row["total"];
				}

				$cat_reg = array();
				$row_nr = 5;

				//var_dump($categorias);
				$cat_rows_ref = array();
				$j = 0;
				foreach( $rubros as  $tmp => $item ){
					$row_nr++;
					if(!isset($cat_reg[$item["Rubro"]["categoria_id"]])){

						$cat_rows_ref[] = $row_nr;
						$cat_reg[$item["Rubro"]["categoria_id"]] = 1;

						if(isset( $cats_total[$item["Rubro"]["categoria_id"]] ))  $cattotal =  $cats_total[$item["Rubro"]["categoria_id"]];
						else $cattotal = 0;		

						$exporter->addRow(array(
							   $categorias[$item["Rubro"]["categoria_id"]]["codigo"],
							    $categorias[$item["Rubro"]["categoria_id"]]["nombre"],
							   "",
							   ndecimals($cattotal),
								$letters[$j]		
							));
						$j++;
					}


					if(isset( $data[$item["Rubro"]["id"]] ))  $cattotal =  $data[$item["Rubro"]["id"]]["total"];
					else $cattotal = 0;	

					$exporter->addRow(array(
						   $item["Rubro"]["codigo"],
						   $item["Rubro"]["nombre"],
						   "",
						   ndecimals($cattotal)
						));

				}

				$exporter->addRow(array(   "",  "",   "",  ""));
				$exporter->addRow(array(   "",  "",   "",  ""));

				$sum_letters = "";
				for($i = 0 ;$i < $j ;$i++){
					$sum_letters .= $letters[$i]."+";
				}
				$sum_letters = rtrim($sum_letters,"+");

				$exporter->addRow(array(
					   "",
					
					   "TOTAL INGRESOS (".$sum_letters.") S/." ,   "",
					   ndecimals(array_sum( $cats_total)),
						$letters[$j++]
					));

				$exporter->addRow(array(   "",  "",   "",  ""));
				$exporter->addRow(array(   "",  "",   "",  ""));
				$exporter->addRow(array(   "",  "",   "",  ""));

				$total_deps = 0;
				$exporter->addRow(array(   "CTA. CTE",  "DEPOSITOS",   "",  "MONTO"));
				$exporter->addRow(array(   "",  "",   "",  ""));

				$new_start = $row_nr + 1;
				foreach( $depositos as  $tmp => $item ){
					$row_nr++;
					$exporter->addRow(array(
						   $item["cuenta"],
						  $item["codigo_voucher"],
						   "",
						  ndecimals($item["monto"])
						));

					$total_deps += $item["monto"];
				}

				$exporter->addRow(array(   "",  "",   "",  ""));
				//die("=SUM(D".$new_start.":D".($row_nr - 1).")");

				$exporter->addRow(array(
					   "",
					   "",
					   "TOTAL S/." ,
					   ndecimals($total_deps),
						$letters[$j]
					));

				$exporter->addRow(array(   "",  "",   "",  ""));
				$exporter->addRow(array(   "",  "",   "",  ""));
				$exporter->addRow(array(   "",  "",   "",  ""));

				$exporter->addRow(array(   "",  "ASIENTO CONTABLE",   "",  ""));
	
				$new_start = $row_nr + 1;
				foreach($categorias as $item){
					if(isset( $cats_total[$item["id"]] ))  $cattotal =  $cats_total[$item["id"]];
					else $cattotal = 0;		

					$exporter->addRow(array(
						   "",
						   $item["nombre"],
						   "" ,
						  ndecimals($cattotal)
						));

				}

				//var_dump($cats_total,array_sum($cats_total));exit;
			
				$exporter->addRow(array(
					   "",
					   "",
					   "TOTAL S/." ,
					   ndecimals((string)array_sum($cats_total))
					));
			
				$exporter->finalize(); 
				exit();

			//diaria por rubro // INFORME MENSUAL #################################################################################################################
			} else if($this->data["Recibo"]["tipo"] ==  10) {
				$row_nr = 1;
				$total_anual = array();
					
				$lowest_datetime = strtotime($start_date);
				$highest_datetime =  strtotime($end_date);
				//make ref dates
				$fechas = $fechas2 = array();
				$fechas2 = array("CODIGO","DENOMINACION","MENSUAL","ACUMULADO");
				for($i = $lowest_datetime; $i<= $highest_datetime; ($i += 3600*24) ){
					$fechas[] = date("Y-m-d",$i); 
					$fechas2[] = date("d/m/Y",$i); 
				}

				//$exporter = new ExportDataExcel('browser', 'reporte.xls');
				//$exporter->initialize(); // starts streaming data to web browser

				error_reporting(0);

				$sql = "SELECT ru.id,ru.codigo,SUM( r.monto ) as total
						FROM u_recibos r
						LEFT JOIN u_precios p ON r.precio_id = p.id
						LEFT JOIN u_rubros ru ON p.rubro_id = ru.id
						WHERE  (extorno != 1 OR extorno IS NULL)  AND r.created BETWEEN '".$year."-01-01 00:00:00' AND '".date("Y-m-t",strtotime($year."-12-01"))." 23:59:59' 
						".(empty($this->data["Recibo"]["oficina_id"])?"":" AND r.oficina_id = ".$this->data["Recibo"]["oficina_id"]." ")."
						GROUP BY rubro_id";
				$q_total_anual = mysql_query($sql);
				while($row = mysql_fetch_array( $q_total_anual )){
					$total_anual[$row["codigo"]] = $row["total"];
				}

				$sql = "SELECT ru.id,ru.codigo,SUM( d.monto ) as total
						FROM u_depositos d
						LEFT JOIN u_rubros ru ON d.rubro_id = ru.id
						WHERE d.periodo BETWEEN '".$year."-01-01 00:00:00' AND '".date("Y-m-t",strtotime($year."-12-01"))." 23:59:59'
					".(empty($this->data["Recibo"]["oficina_id"])?"":" AND d.oficina_id = ".$this->data["Recibo"]["oficina_id"]." ")."
						GROUP BY rubro_id";
				$q_total_anual = mysql_query($sql);
				while($row = mysql_fetch_array( $q_total_anual )){
					if(!isset($total_anual[$row["codigo"]]))$total_anual[$row["codigo"]] = 0;
					$total_anual[$row["codigo"]] += $row["total"];
				}
				//echo $sql;var_dump($total_anual);exit;

				$sql = "SELECT SUM(d.monto) as total,LEFT(d.periodo,10) as dia_recibo,d.rubro_id as rubro_id,ru.categoria_id as rubro_categoria_id,entidad_id
								 FROM u_depositos d
								 LEFT JOIN u_entidades e    ON d.entidad_id = e.id
								 LEFT JOIN u_rubros ru ON d.rubro_id = ru.id
								 WHERE d.periodo BETWEEN '".$start_date." 00:00:00' AND '".$end_date." 23:59:59'
								".(empty($this->data["Recibo"]["oficina_id"])?"":" AND d.oficina_id = ".$this->data["Recibo"]["oficina_id"]." ")."
								 GROUP BY d.entidad_id, d.rubro_id order BY ru.codigo ASC
					 ";
				$dep_result = mysql_query($sql);	

				$data = array();	
				$cats_total = array();

				while($row = mysql_fetch_array($dep_result )){
					if(!isset($data[$row["entidad_id"]])){
						$data[$row["entidad_id"]] = array();
					}

					if(!isset($data[$row["entidad_id"]][$row["rubro_id"]])){
						$data[$row["entidad_id"]][$row["rubro_id"]] = array();
					}
					$data[$row["entidad_id"]][$row["rubro_id"]] = $row["total"];

					if(!isset($cats_total[$row["entidad_id"]])){
						$cats_total[$row["entidad_id"]] = array();
					}

					if(!isset($cats_total[$row["entidad_id"]][$row["rubro_categoria_id"]])){
						$cats_total[$row["entidad_id"]][$row["rubro_categoria_id"]] = 0;
					}

					$cats_total[$row["entidad_id"]][$row["rubro_categoria_id"]] += $row["total"];
				}

				$newdata = array();
				foreach($data as $entidad_id => $item ){
					foreach($rubros as $rubro){
						$tttotal = "0.00";
						if(isset($data[$entidad_id][$rubro["Rubro"]["id"]])){
							$tttotal = $data[$entidad_id][$rubro["Rubro"]["id"]];
						}
						$newdata[$entidad_id][$rubro["Rubro"]["id"]] = $tttotal;
					}
				}

				$cat_reg = array();
				$cache_deps = array();
				foreach($rubros as $rubro){
					if(!isset($cat_reg[$rubro["Rubro"]["categoria_id"]])){
						$cat_reg[$rubro["Rubro"]["categoria_id"]] = 1;
						$new_tmp = array();//array($categorias[$rubro["Rubro"]["categoria_id"]]["codigo"],$categorias[$rubro["Rubro"]["categoria_id"]]["nombre"]);
						foreach($data as $entidad_id => $subitem){
							$mmm = "0.00";
							if(isset($cats_total[$entidad_id][$rubro["Rubro"]["categoria_id"]])){
								$mmm = $cats_total[$entidad_id][$rubro["Rubro"]["categoria_id"]];
							}
							$new_tmp[] = $mmm;
						}
						$cache_deps[] = $new_tmp;

						foreach($headers as $new_tmp => $value){
							$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($int, $row_nr, $value);
						}
					}

					$tmp = array();					
					//$tmp[] = $rubro["Rubro"]["codigo"];
					//$tmp[] = $rubro["Rubro"]["nombre"];
					foreach($data as $entidad_id => $item){
						$tmp[] = ndecimals($newdata[$entidad_id][$rubro["Rubro"]["id"]]);
					}

					$cache_deps[] = $tmp;
				}


				App::import("Model","Entidad");
				$Entidad_obj = new Entidad();
				$ent_names = array();
				foreach($data as $entidad_id => $tmp){
					$tmp = $Entidad_obj->find("first",array("conditions"=>array("Entidad.id"=>$entidad_id)));
					$ent_names[] = $tmp["Entidad"]["nombre"];
				}

				$r = mysql_query("SELECT SUM(r.monto) as total,LEFT(r.created,10) as dia_recibo,p.rubro_id as rubro_id,ru.categoria_id as rubro_categoria_id
								 FROM u_recibos r
								 LEFT JOIN u_precios p    ON r.precio_id = p.id
								 LEFT JOIN u_rubros ru ON p.rubro_id = ru.id
								 WHERE r.created BETWEEN '".$start_date." 00:00:00' AND '".$end_date." 23:59:59'   AND (extorno != 1 OR extorno IS NULL) 
								".(empty($this->data["Recibo"]["oficina_id"])?"":" AND r.oficina_id = ".$this->data["Recibo"]["oficina_id"]." ")."
								 GROUP BY dia_recibo, p.rubro_id order BY ru.codigo ASC
					 ");	




				$first = array("","UNIVERSIDAD NACIONAL SANTIAGO ANTUNEZ DE MAYOLO");
				foreach($fechas as $fe){
					$first[] = "";
				}
				for($i = 0; $i < 100; $i++){
					$first[] = "";
				}

				$heads_hh1 = $first;
				$heads_hh2 = array("","OFICINA GENERAL DE ECONOMIA Y ABASTECIMIENTO - UNIDAD DE TESORERIA");
				$heads_hh3 = array("","INFORME MENSUAL DE RECURSOS DIRECTAMENTE RECAUDADOS");
				$heads_hh4 = array(   "",  "",   "",  "");
				$heads_hh5 = array("SECCION : 02  INSTANCIAS DESCENTRALIZADOS","","SECTOR : 10 EDUCACION");
				$heads_hh6 = array("PLIEGO : 532 UNASAM","","DESDE:".date("d/m/Y",strtotime($start_date)));
				$heads_hh7 = array("UNIDAD EJECUTORA : 0110 UNASAM","","HASTA:".date("d/m/Y",strtotime($end_date)));
				$heads_hh8 = array(   "",  "",   "",  "");

				$row_nr++;
				foreach($heads_hh1 as $int => $value){
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($int, $row_nr, $value);
				}
				$row_nr++;
				foreach($heads_hh2 as $int => $value){
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($int, $row_nr, $value);
				}
				$row_nr++;
				foreach($heads_hh3 as $int => $value){
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($int, $row_nr, $value);
				}
				$row_nr++;
				foreach($heads_hh4 as $int => $value){
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($int, $row_nr, $value);
				}
				$row_nr++;
				foreach($heads_hh5 as $int => $value){
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($int, $row_nr, $value);
				}
				$row_nr++;
				foreach($heads_hh6 as $int => $value){
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($int, $row_nr, $value);
				}
				$row_nr++;
				foreach($heads_hh7 as $int => $value){
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($int, $row_nr, $value);
				}
				$row_nr++;
				foreach($heads_hh8 as $int => $value){
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($int, $row_nr, $value);
				}
				$data = array();	

				$cats_total = array();

				while($row = mysql_fetch_array($r)){
					if(!isset($data[$row["dia_recibo"]])){
						$data[$row["dia_recibo"]] = array();
					}

					if(!isset($data[$row["dia_recibo"]][$row["rubro_id"]])){
						$data[$row["dia_recibo"]][$row["rubro_id"]] = array();
					}
					$data[$row["dia_recibo"]][$row["rubro_id"]] = $row["total"];

					if(!isset($cats_total[$row["dia_recibo"]])){
						$cats_total[$row["dia_recibo"]] = array();
					}

					if(!isset($cats_total[$row["dia_recibo"]][$row["rubro_categoria_id"]])){
						$cats_total[$row["dia_recibo"]][$row["rubro_categoria_id"]] = 0;
					}

					$cats_total[$row["dia_recibo"]][$row["rubro_categoria_id"]] += $row["total"];
				}

				$new_cats_day = array();
				$neworder = array();
				foreach($fechas as $fe){
					$neworder[$fe] = array();
					foreach($rubros as $rubro){
						if(isset($data[$fe][$rubro["Rubro"]["id"]])){
							$neworder[$fe][$rubro["Rubro"]["id"]] = ndecimals($data[$fe][$rubro["Rubro"]["id"]]);
						} else {
							$neworder[$fe][$rubro["Rubro"]["id"]] = "0.00";
						}
					}
				}

				$heads = array_merge($fechas2,$ent_names);
				//$exporter->addRow($heads);

				$row_nr++;
				foreach($heads as $int => $value){
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($int, $row_nr, $value);
				}

				$cat_day_data = array();
				foreach($fechas as $fe){
					$cat_day_data[$fe] = array();
					foreach($categorias as $categoria){
						if(isset($cats_total[$fe][$categoria["id"]])){
							$cat_day_data[$fe][$categoria["id"]] = ndecimals($cats_total[$fe][$categoria["id"]]);
						} else {
							$cat_day_data[$fe][$categoria["id"]] = "0.00";
						}
					}
				}

				$cat_reg = array();
				$z = 0;
				foreach($rubros as $rubro){

					if(!isset($cat_reg[$rubro["Rubro"]["categoria_id"]])){
						$row_nr++;
						$cat_reg[$rubro["Rubro"]["categoria_id"]] = 1;
		
						$new_tmp = array($categorias[$rubro["Rubro"]["categoria_id"]]["codigo"],$categorias[$rubro["Rubro"]["categoria_id"]]["nombre"]);

						if($z==0){
							$new_tmp[] = "";"SUBTOTAL(9,C12:C".($row_nr-1).")";
							$new_tmp[] = "";
						} else {
							$new_tmp1 = "=SUBTOTAL(9,C".($rubro_start_ref+1).":C".($row_nr-1).")";
							$new_tmp2 = "=SUBTOTAL(9,D".($rubro_start_ref+1).":D".($row_nr-1).")";
							$new_tmp[] = "";
							$new_tmp[] = "";
							$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 2 , $rubro_start_ref, $new_tmp1);
							$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 3, $rubro_start_ref, $new_tmp2);
						}

						foreach($cat_day_data as $day => $subitem){
							$new_tmp[] = $subitem[$rubro["Rubro"]["categoria_id"]];
						}

						$new_tmp = array_merge($new_tmp,$cache_deps[$z]);


						foreach($new_tmp as $int => $value){
							$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( $int , $row_nr, $value);
						}
						//$exporter->addRow($new_tmp);
						$rubro_start_ref = $row_nr;
						$z++;
					}

					$row_nr++;

					$tmp = array();					
					$tmp[] = $rubro["Rubro"]["codigo"];
					$tmp[] = $rubro["Rubro"]["nombre"];
  
					$total_cod_tmp = 0;
					if($total_anual[$rubro["Rubro"]["codigo"]]) $total_cod_tmp = $total_anual[$rubro["Rubro"]["codigo"]];
					$tmp[] = "=SUM(E".$row_nr.":".PHPExcel_Cell::stringFromColumnIndex((count($fechas) + 7 +  count($cache_deps[0]))).$row_nr.")";
					$tmp[] = $total_cod_tmp;


					foreach($fechas as $fe){
						$tmp[] = $neworder[$fe][$rubro["Rubro"]["id"]];
					}

					$tmp = array_merge($tmp,$cache_deps[$z]);
					//$exporter->addRow($tmp);
					foreach($tmp as $int => $value){
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($int, $row_nr, $value);
					}
					$z++;
				}

				$new_tmp1 = "=SUBTOTAL(9,C".($rubro_start_ref+1).":C".($row_nr).")";
				$new_tmp2 = "=SUBTOTAL(9,D".($rubro_start_ref+1).":D".($row_nr).")";
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 2 ,$rubro_start_ref, $new_tmp1);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 3, $rubro_start_ref, $new_tmp2);

				$row_nr++;
				$new_tmp1 = "=SUBTOTAL(9,C11:C".($row_nr-1).")";
				$new_tmp2 = "=SUBTOTAL(9,D11:D".($row_nr-1).")";
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 1 ,$row_nr, "TOTAL S/.");
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 2 ,$row_nr, $new_tmp1);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 3, $row_nr, $new_tmp2);


				$objPHPExcel->getActiveSheet()->getStyle('C11:D'.$row_nr)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);

				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header('Content-Disposition: attachment;filename="INFORME_MENSUAL.xlsx"');
				header('Cache-Control: max-age=0');
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
				$objWriter->save('php://output');
				//$exporter->finalize(); 
				exit(); 

			//depositos #################################################################################################################
			} elseif($this->data["Recibo"]["tipo"] ==  5) {
				$row_nr = 1;
				$r = mysql_query("SELECT d.*,e.nombre AS entidad_nombre, o.nombre as oficina_nombre,ru.codigo as rubro_codigo
								 FROM u_depositos d
								 LEFT JOIN u_entidades e ON d.entidad_id = e.id
								 LEFT JOIN u_oficinas o ON d.oficina_id = o.id
								 LEFT JOIN u_rubros ru ON d.rubro_id = ru.id
								 WHERE d.periodo BETWEEN '".$start_date." 00:00:00' AND '".$end_date." 23:59:59' 
								".(empty($this->data["Recibo"]["oficina_id"])?"":" AND d.oficina_id = ".$this->data["Recibo"]["oficina_id"]." ")."
								 ORDER BY periodo ASC
				 ");

				$headers = array(
					   "FECHA",
					   "OFICINA",
						"ENTIDAD",
						"BANCO",
						"CUENTA",
					   "VOUCHER",
						"MONTO",
						"CONT"
					);

				foreach($headers as $int => $value){
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($int, $row_nr, $value);
				}

				$nr_deps = 0;
				while($row = mysql_fetch_array($r)){
					$tdata = array(
						   $row["periodo"],
						    $row["oficina_nombre"],
							 $row["entidad_nombre"],
							 $row["banco"],
							 $row["cuenta"],
						    $row["codigo_voucher"],
							 ndecimals($row["monto"]),
							$row["rubro_codigo"]
						);

					$row_nr++;
					foreach($tdata as $int => $value){
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($int, $row_nr, $value);
					}
					$nr_deps++;
				}

				$row_nr++;	
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row_nr, "TOTAL S/.");
				if($nr_deps!=0)$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row_nr, "=SUM(F2:F".($row_nr-1).")");

				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header('Content-Disposition: attachment;filename="DEPOSITOS.xlsx"');
				header('Cache-Control: max-age=0');
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
				$objWriter->save('php://output');
				exit(); 

			//efectivo y vueltos #################################################################################################################
			} else if($this->data["Recibo"]["tipo"] ==  4) {
				$row_nr = 1;
				//$exporter = new ExportDataExcel('browser', 'reporte.xls');
				//$exporter->initialize(); // starts streaming data to web browser

				$r = mysql_query("SELECT r.*,p.codigo,p.nombre as precio_nombre,e.nombre as entidad_nombre,
								e.codigo as entidad_codigo,o.nombre as oficina_nombre,ru.codigo AS rubro_codigo,l.nombres as login_nombre
								 FROM u_recibos r
								 LEFT JOIN u_precios p ON r.precio_id = p.id
								 LEFT JOIN u_oficinas o ON r.oficina_id = o.id
								 LEFT JOIN u_logins l ON r.login_id = l.id
								 LEFT JOIN u_entidades e ON p.entidad_id = e.id
								 LEFT JOIN u_rubros ru ON p.rubro_id = ru.id
								 WHERE r.created BETWEEN '".$start_date." 00:00:00' AND '".$end_date." 23:59:59' AND (extorno != 1 OR extorno IS NULL)
								".(empty($this->data["Recibo"]["oficina_id"])?"":" AND r.oficina_id = ".$this->data["Recibo"]["oficina_id"]." ")."
								 ORDER BY r.created ASC
				 ");

				$headers = array(
						"OPERADOR",
					   "RECIBO",
						"FECHA",
					   "EFECTIVO",
					   "VUELTO"
					);

				foreach($headers as $int => $value){
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($int, $row_nr, $value);
				}
				//$exporter->addRow($headers);

				while($row = mysql_fetch_array($r)){
					$tdata = array(
					   $row["login_nombre"],
					   recibo_id($row),
					   $row["created"],
					   $row["efectivo"],
					   $row["vuelto"]
					);

					//$exporter->addRow($tdata); 


					$row_nr++;
					foreach($tdata as $int => $value){
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($int, $row_nr, $value);
					}
				}

				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, ($row_nr + 1), "TOTAL S/.");
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, ($row_nr + 1), "=SUM(D1:D".$row_nr.")");
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, ($row_nr + 1), "=SUM(E1:E".$row_nr.")");

				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header('Content-Disposition: attachment;filename="EFECTIVO_VUELTOS.xlsx"');
				header('Cache-Control: max-age=0');
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
				$objWriter->save('php://output');
				//$exporter->finalize(); 
				exit(); 

			//listado // RESUMEN CLASIFICADOR ####################################################################################################################
			} else if($this->data["Recibo"]["tipo"] == 1) {
				$row_nr = 1;
			//	$exporter = new ExportDataExcel('browser', 'reporte.xls');
				//$exporter->initialize(); // starts streaming data to web browser

				$sql = "SELECT SUM( d.monto ) as total
						FROM u_depositos d
						WHERE d.periodo BETWEEN '".$start_date." 00:00:00' AND '".$end_date." 23:59:59' 
						".(empty($this->data["Recibo"]["oficina_id"])?"":" AND d.oficina_id = ".$this->data["Recibo"]["oficina_id"]." ")."
						";
				$q_total_anual = mysql_query($sql);
				$total_depositos = 0;
				while($row = mysql_fetch_array( $q_total_anual )){
						$total_depositos = $row["total"];
				}

				$r = mysql_query("SELECT r.*,p.codigo,p.nombre as precio_nombre,e.nombre as entidad_nombre,
								e.codigo as entidad_codigo,o.nombre as oficina_nombre,ru.codigo AS rubro_codigo
								 FROM u_recibos r
								 LEFT JOIN u_precios p ON r.precio_id = p.id
								 LEFT JOIN u_oficinas o ON r.oficina_id = o.id
								 LEFT JOIN u_entidades e ON p.entidad_id = e.id
								 LEFT JOIN u_rubros ru ON p.rubro_id = ru.id
								 WHERE r.created BETWEEN '".$start_date." 00:00:00' AND '".$end_date." 23:59:59'
								".(empty($this->data["Recibo"]["oficina_id"])?"":" AND r.oficina_id = ".$this->data["Recibo"]["oficina_id"]." ")."
								 ORDER BY r.created ASC
				 ");

				$headers = array(
					   "RECIBO",
					   "FECHA",
					   "DATOS",
					   "CODPAGO",
					   "MONTO",
					   "OFICINA",
					   "NOMBRE OFICINA",
					   "CONT",
					   "M_LETRAS"
					);

				//$exporter->addRow( $headers );

				foreach($headers as $int => $value){
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($int, $row_nr, $value);
				}

				$tmp_resumen = array();
				$tmp_total = 0;
				
				while($row = mysql_fetch_array($r)){
					if($row["extorno"]){
						$row["nombres"] = "EXTORNO";
						$row["monto"] = 0;
					}

					if(!isset($tmp_resumen[$row["rubro_codigo"]])) $tmp_resumen[$row["rubro_codigo"]] = 0;
					$tmp_resumen[$row["rubro_codigo"]] += $row["monto"];
					$tmp_total += $row["monto"];

					$tdata = array(
					   recibo_id($row),
					   date("d/m/Y",strtotime($row["created"])),
					   $row["nombres"],
					   sprintf("%05.2f",$row["codigo"]),
					   ndecimals($row["monto"]),
					   $row["entidad_codigo"],
					   $row["entidad_nombre"],
					   $row["rubro_codigo"],
					   monto2words($row["monto"])
					);

					//$exporter->addRow($tdata); 

					$row_nr++;
					foreach($tdata as $int => $value){
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($int, $row_nr, $value);
					}
				}

				$row_nr++;
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row_nr, "TOTAL S/.");
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row_nr, "=SUM(E2:E".($row_nr-1).")");
				$objPHPExcel->getActiveSheet()->getStyle("E2:E".($row_nr))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);

				//depositos
				$row_nr = $row_nr + 4;
				$row_nr++;
				$dep_ref_start = $row_nr;
				$r = mysql_query("SELECT d.*,e.nombre AS entidad_nombre, o.nombre as oficina_nombre,ru.codigo as rubro_codigo
								 FROM u_depositos d
								 LEFT JOIN u_entidades e ON d.entidad_id = e.id
								 LEFT JOIN u_oficinas o ON d.oficina_id = o.id
								 LEFT JOIN u_rubros ru ON d.rubro_id = ru.id
								 WHERE d.periodo BETWEEN '".$start_date." 00:00:00' AND '".$end_date." 23:59:59' 
								".(empty($this->data["Recibo"]["oficina_id"])?"":" AND d.oficina_id = ".$this->data["Recibo"]["oficina_id"]." ")."
								 ORDER BY periodo ASC
				 ");

				$headers = array(
					   "FECHA",
					   "OFICINA",
						"ENTIDAD",
						"BANCO",
						"CUENTA",
					   "VOUCHER",
						"MONTO",
						"CONT"
					);

				foreach($headers as $int => $value){
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($int, $row_nr, $value);
				}

				$nr_deps = 0;
				while($row = mysql_fetch_array($r)){
					$tdata = array(
						   $row["periodo"],
						    $row["oficina_nombre"],
							 $row["entidad_nombre"],
							 $row["banco"],
							 $row["cuenta"],
						    $row["codigo_voucher"],
							 ndecimals($row["monto"]),
							$row["rubro_codigo"]
						);

					$row_nr++;
					foreach($tdata as $int => $value){
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($int, $row_nr, $value);
					}
					$nr_deps++;
				}

				$row_nr++;	
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row_nr, "TOTAL S/.");
				if($nr_deps!=0)$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row_nr, "=SUM(G".($dep_ref_start +1).":G".($row_nr-1).")");
				//end depositos


				$row_nr = $row_nr + 4;
				$resumen_start = $row_nr;
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row_nr, "Resumen");	
				/*foreach($tmp_resumen as $codigo => $total){
						$row_nr++;
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row_nr, $codigo);	
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row_nr, $total);					
				}*/

				foreach($rubros as $rubro){
						$row_nr++;
						$total = "";
						if(isset($tmp_resumen[$rubro["Rubro"]["codigo"]]))$total = $tmp_resumen[$rubro["Rubro"]["codigo"]];
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row_nr, $rubro["Rubro"]["codigo"]);	
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row_nr, $total);					
				}

				$row_nr++;
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row_nr, "DEPOSITOS");	
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row_nr, $total_depositos);	


				$row_nr++;
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row_nr, "TOTAL S/.");
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row_nr, "=SUM(F".$resumen_start.":F".($row_nr-1).")");
				$objPHPExcel->getActiveSheet()->getStyle('F'.($resumen_start+1).':F'.$row_nr)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);

				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header('Content-Disposition: attachment;filename="RESUMEN_CLASIFICADOR.xlsx"');
				header('Cache-Control: max-age=0');
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
				$objWriter->save('php://output');
				//$exporter->finalize(); 
				exit(); 

			//full recibos // RESUMEN MENSUAL ####################################################################################################################
			} else if($this->data["Recibo"]["tipo"] == 3) {
				$row_nr = 1;
				$resumen_entidades = array();

				$headers= array(
						"RECIBO",
					   "FECHA",
					   "NOMBRE",
					   "DNI RUC",
					   "CODPAGO",
					   "CONCEPTO",
					   "MONTO",
					   "OFICINA",
					   "NOMBRE OFICINA",
					   "CONT",
					   "M_LETRAS"
					);

				foreach($headers as $int => $value){
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($int, $row_nr, $value);
				}

				$sql = "SELECT entidad_id,SUM( d.monto ) as total
						FROM u_depositos d
						WHERE d.periodo BETWEEN '".$start_date." 00:00:00' AND '".$end_date." 23:59:59' 
						".(empty($this->data["Recibo"]["oficina_id"])?"":" AND d.oficina_id = ".$this->data["Recibo"]["oficina_id"]." ")."
						GROUP BY entidad_id";
				$q_total_anual = mysql_query($sql);
				while($row = mysql_fetch_array( $q_total_anual )){
					if(!isset($resumen_entidades[$row["entidad_id"]])) $resumen_entidades[$row["entidad_id"]] = 0;
					$resumen_entidades[$row["entidad_id"]] += $row["total"];
				}

				$r = mysql_query("SELECT r.*,p.codigo,p.nombre as precio_nombre,e.nombre as entidad_nombre,e.id as entidad_id, pe.dni_ruc, 
								e.codigo as entidad_codigo,o.nombre as oficina_nombre,ru.codigo AS rubro_codigo
								 FROM u_recibos r
								 LEFT JOIN u_precios p ON r.precio_id = p.id
								 LEFT JOIN u_oficinas o ON r.oficina_id = o.id
								 LEFT JOIN u_entidades e ON p.entidad_id = e.id
  							     LEFT JOIN  u_personas pe ON r.persona_id = pe.id
								 LEFT JOIN u_rubros ru ON p.rubro_id = ru.id
								 WHERE r.created BETWEEN '".$start_date." 00:00:00' AND '".$end_date." 23:59:59' 
								".(empty($this->data["Recibo"]["oficina_id"])?"":" AND r.oficina_id = ".$this->data["Recibo"]["oficina_id"]." ")."
								 ORDER BY r.created ASC
				 ");


				while($row = mysql_fetch_array($r)){
					if($row["extorno"]){
						$row["nombres"] = "EXTORNO";
						$row["monto"] = 0;
					}

					$tdata = array(
					   recibo_id($row),
					   date("d/m/Y",strtotime($row["created"])),
					   $row["nombres"],
					   $row["dni_ruc"],
					   sprintf("%05.2f",$row["codigo"]),
					   $row["precio_nombre"],
					   ndecimals($row["monto"]),
					   $row["entidad_codigo"],
					   $row["entidad_nombre"],
					   $row["rubro_codigo"],
					   monto2words($row["monto"])
					);

					if(!isset($resumen_entidades[$row["entidad_id"]])) $resumen_entidades[$row["entidad_id"]] = 0;
					$resumen_entidades[$row["entidad_id"]] += $row["monto"];

					//$exporter->addRow($tdata); 

					$row_nr++;
					foreach($tdata as $int => $value){
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($int, $row_nr, $value);
					}
				}

				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, ($row_nr+1), "TOTAL S/.");
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, ($row_nr+1), "=SUM(G2:G".$row_nr.")");
				$objPHPExcel->getActiveSheet()->getStyle('G2:G'.($row_nr+1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);



				//depositos
				$row_nr = $row_nr + 4;
				$row_nr++;
				$dep_ref_start = $row_nr;
				$r = mysql_query("SELECT d.*,e.nombre AS entidad_nombre, o.nombre as oficina_nombre,ru.codigo as rubro_codigo
								 FROM u_depositos d
								 LEFT JOIN u_entidades e ON d.entidad_id = e.id
								 LEFT JOIN u_oficinas o ON d.oficina_id = o.id
								 LEFT JOIN u_rubros ru ON d.rubro_id = ru.id
								 WHERE d.periodo BETWEEN '".$start_date." 00:00:00' AND '".$end_date." 23:59:59' 
								".(empty($this->data["Recibo"]["oficina_id"])?"":" AND d.oficina_id = ".$this->data["Recibo"]["oficina_id"]." ")."
								 ORDER BY periodo ASC
				 ");

				$headers = array(
					   "FECHA",
					   "OFICINA",
						"ENTIDAD",
						"BANCO",
						"CUENTA",
					   "VOUCHER",
						"MONTO",
						"CONT"
					);

				foreach($headers as $int => $value){
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($int, $row_nr, $value);
				}

				$nr_deps = 0;
				while($row = mysql_fetch_array($r)){
					$tdata = array(
						   $row["periodo"],
						    $row["oficina_nombre"],
							 $row["entidad_nombre"],
							 $row["banco"],
							 $row["cuenta"],
						    $row["codigo_voucher"],
							 ndecimals($row["monto"]),
							$row["rubro_codigo"]
						);

					$row_nr++;
					foreach($tdata as $int => $value){
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($int, $row_nr, $value);
					}
					$nr_deps++;
				}

				$row_nr++;	
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row_nr, "TOTAL S/.");
				if($nr_deps!=0)$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row_nr, "=SUM(G".($dep_ref_start +1).":G".($row_nr-1).")");
				//end depositos
	

				$row_nr = $row_nr + 5;
				$ref_resumen_start = $row_nr + 1;

				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row_nr, "ENTIDADES");
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row_nr, "ING.BRUTO");			

				foreach($entidades as $item){
						$row_nr++;
						$total = 0;
						if(isset($resumen_entidades[$item["Entidad"]["id"]])) $total = $resumen_entidades[$item["Entidad"]["id"]];
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row_nr, $item["Entidad"]["nombre"]);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row_nr, $total);					
				}

				$row_nr++;
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row_nr, "TOTAL S/.");
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row_nr, "=SUM(E".$ref_resumen_start.":E".($row_nr-1).")");	

				$objPHPExcel->getActiveSheet()->getStyle('E'.$ref_resumen_start.':E'.($row_nr))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);


				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header('Content-Disposition: attachment;filename="RESUMEN_MENSUAL.xlsx"');
				header('Cache-Control: max-age=0');
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
				$objWriter->save('php://output');
				exit;

				//$exporter->finalize(); 
				//exit(); 

			//consolidado // DIARIO ####################################################################################################################
			} else {


				$style1 =	array(
									'font'    => array(
										'bold'      => true
									),
									'alignment' => array(
										'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
									),
									'borders' => array(
										'top'     => array(
						 					'style' => PHPExcel_Style_Border::BORDER_THIN
						 				)
									),
									'fill' => array(
							 			'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
							  			'rotation'   => 90,
							 			'startcolor' => array(
							 				'argb' => 'FFA0A0A0'
							 			),
							 			'endcolor'   => array(
							 				'argb' => 'FFFFFFFF'
							 			)
							 		)
								);

				$style3 = array(
					'borders' => array(
						'outline' => array(
							'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
							'color' => array('argb' => 'FF000000'),
						)
					)
				);

				$style_border = array(
					'borders' => array(
						'outline' => array(
							'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
							'color' => array('argb' => 'FF993300'),
						),
					),
				);


				$style2 =	array(
									'alignment' => array(
										'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
									)
								);

				$style_left =	array(
									'alignment' => array(
										'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
									)
								);

				$r = mysql_query("SELECT d.*,e.nombre AS entidad_nombre, o.nombre as oficina_nombre 
								 FROM u_depositos d
								 LEFT JOIN u_entidades e ON d.entidad_id = e.id
								 LEFT JOIN u_oficinas o ON d.oficina_id = o.id
								 WHERE d.periodo BETWEEN '".$start_date." 00:00:00' AND '".$end_date." 23:59:59' 
								".(empty($this->data["Recibo"]["oficina_id"])?"":" AND d.oficina_id = ".$this->data["Recibo"]["oficina_id"]." ")."
								 ORDER BY periodo ASC
				 ");
				$depositos = array();
				while($row = mysql_fetch_array($r)){
					$depositos[] = $row;
				}
				
				$min_recibo = $max_recibo = 0;

				$r = mysql_query("SELECT created,secuencia 
								 FROM u_recibos r
								 WHERE r.created BETWEEN '".$start_date." 00:00:00' AND '".$end_date." 23:59:59' ".(empty($this->data["Recibo"]["oficina_id"])?"":" AND r.oficina_id = ".$this->data["Recibo"]["oficina_id"]." ")." ORDER BY created ASC  LIMIT 1");
				$row = mysql_fetch_array($r);
				if(!empty($row))$min_recibo = $row;

				$r = mysql_query("SELECT created,secuencia
								 FROM u_recibos r
								 WHERE r.created BETWEEN '".$start_date." 00:00:00' AND '".$end_date." 23:59:59' ".(empty($this->data["Recibo"]["oficina_id"])?"":" AND r.oficina_id = ".$this->data["Recibo"]["oficina_id"]." ")." ORDER BY created DESC  LIMIT 1");
				$row = mysql_fetch_array($r);
				if(!empty($row))$max_recibo = $row;


				$r = mysql_query("SELECT SUM(r.monto) as total,ru.codigo AS rubro_codigo,ru.id as ru_rubro_id,ru.nombre as rubro_nombre,ru.categoria_id as rubro_categoria_id
								 FROM u_recibos r
								 LEFT JOIN u_precios p    ON r.precio_id = p.id
								 LEFT JOIN u_entidades e  ON p.entidad_id = e.id
								 LEFT JOIN u_rubros ru    ON p.rubro_id = ru.id
								 LEFT JOIN u_categorias c ON ru.categoria_id = c.id
								 WHERE r.created BETWEEN '".$start_date." 00:00:00' AND '".$end_date." 23:59:59'   AND (extorno != 1 OR extorno IS NULL) 
								".(empty($this->data["Recibo"]["oficina_id"])?"":" AND r.oficina_id = ".$this->data["Recibo"]["oficina_id"]." ")."
								 GROUP BY ru.id
								 ORDER BY c.codigo ASC	 ");

				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, "REGISTRO SIAF");
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 2, "CAPTACION DE INGRESOS DEL:".$this->data["Recibo"]["fecha_inicio"]." - ".$this->data["Recibo"]["fecha_final"]);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 3, "Recibos:".recibo_id($min_recibo)." - ".recibo_id($max_recibo));

				//echo "<table>";
				$data = array();
				$cats_total = array();
				while($row = mysql_fetch_array($r)){
					$data[$row["ru_rubro_id"]] = $row;
					if(!isset($cats_total[$row["rubro_categoria_id"]]))$cats_total[$row["rubro_categoria_id"]] = 0;
					$cats_total[$row["rubro_categoria_id"]] += $row["total"];
				}

				$cat_reg = array();
				$row_nr = 5;

				//var_dump($categorias);
				$cat_rows_ref = array();

				$j = 0;
				foreach( $rubros as  $tmp => $item ){
					$row_nr++;
					if(!isset($cat_reg[$item["Rubro"]["categoria_id"]])){

						$cat_rows_ref[] = $row_nr;
						$cat_reg[$item["Rubro"]["categoria_id"]] = 1;
				
						/*$exporter->addRow(array(
							$categorias[$row["rubro_categoria_id"]]["codigo"],
							$categorias[$row["rubro_categoria_id"]]["nombre"],
							mformat($cats_total[$row["rubro_categoria_id"]])
						)); */
						if(isset( $cats_total[$item["Rubro"]["categoria_id"]] ))  $cattotal =  mformat($cats_total[$item["Rubro"]["categoria_id"]]);
						else $cattotal = 0;		
						//echo $item["Rubro"]["categoria_id"]."xxxx".$categorias[$item["Rubro"]["categoria_id"]]["nombre"]."xxxx";			
			

						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row_nr, $categorias[$item["Rubro"]["categoria_id"]]["codigo"]);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row_nr, $categorias[$item["Rubro"]["categoria_id"]]["nombre"]);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row_nr, $cattotal);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row_nr, $letters[$j]);

						$objPHPExcel->getActiveSheet()->getStyle('A'.$row_nr.':E'.$row_nr)->applyFromArray($style1);
						$objPHPExcel->getActiveSheet()->getStyle('D'.$row_nr.':E'.$row_nr)->applyFromArray($style2);						
						$row_nr++;
						$j++;
					}


					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row_nr, $item["Rubro"]["codigo"]);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row_nr, $item["Rubro"]["nombre"]);

					if(isset( $data[$item["Rubro"]["id"]] ))  $cattotal =  mformat($data[$item["Rubro"]["id"]]["total"]);
					else $cattotal = 0;	

					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row_nr,$cattotal );	
				
					//$exporter->addRow(array($row["rubro_codigo"], $row["rubro_nombre"], mformat($row["total"]) )); 
					//echo sprintf("<tr><td>%s</td><td>%s</td><td>%s</td></tr>",$row["rubro_codigo"],$row["rubro_nombre"],$row["total"]);
				}

				$sum_letters = "";
				for($i = 0 ;$i < $j ;$i++){
					$sum_letters .= $letters[$i]."+";
				}
				$sum_letters = rtrim($sum_letters,"+");

				//total
				$row_nr++;
				$row_nr++;
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row_nr,"TOTAL (".$sum_letters.") S/." );
				//$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row_nr, array_sum( $cats_total));
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row_nr,"=D".implode("+D",$cat_rows_ref));
				$objPHPExcel->getActiveSheet()->getStyle('D'.$row_nr)->applyFromArray($style_border);

				$row_nr += 4;
				$total_deps = 0;
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row_nr, "CTA. CTE");
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row_nr, "DEPOSITOS");
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row_nr, "MONTO");
				$objPHPExcel->getActiveSheet()->getStyle('A'.$row_nr.':D'.$row_nr)->applyFromArray($style1);
				$row_nr++;

				$new_start = $row_nr + 1;
				foreach( $depositos as  $tmp => $item ){
					$row_nr++;

					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row_nr, $item["cuenta"]);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row_nr, $item["codigo_voucher"]);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row_nr, $item["monto"]);

					$total_deps += $item["monto"];
					//$exporter->addRow(array($row["rubro_codigo"], $row["rubro_nombre"], mformat($row["total"]) )); 
					//echo sprintf("<tr><td>%s</td><td>%s</td><td>%s</td></tr>",$row["rubro_codigo"],$row["rubro_nombre"],$row["total"]);
				}

				$row_nr++;
				//die("=SUM(D".$new_start.":D".($row_nr - 1).")");
				if($total_deps == 0){
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row_nr, $total_deps);
				} else {
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row_nr, "=SUM(D".$new_start.":D".($row_nr - 1).")");
				}

				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row_nr,"TOTAL S/." );
				$objPHPExcel->getActiveSheet()->getStyle('D'.$row_nr)->applyFromArray($style_border);

				$row_nr += 4;
				$row_nr++;
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row_nr, "ASIENTO CONTABLE");
				$objPHPExcel->getActiveSheet()->getStyle('A'.$row_nr.':D'.$row_nr)->applyFromArray($style1);

				$new_start = $row_nr + 1;
				foreach($categorias as $item){
					$row_nr++;
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row_nr, $item["nombre"]);

					if(isset( $cats_total[$item["id"]] ))  $cattotal =  mformat($cats_total[$item["id"]]);
					else $cattotal = 0;		
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row_nr,$cattotal );	

				}

				$row_nr++;
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row_nr, array_sum( $cats_total));
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row_nr, "=SUM(D".$new_start.":D".($row_nr-1).")");
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row_nr,"TOTAL  S/." );
				$objPHPExcel->getActiveSheet()->getStyle('D'.$row_nr)->applyFromArray($style_border);

				$objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(12);
				$objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(48);
				$objPHPExcel->getActiveSheet()->getColumnDimension("C")->setWidth(12);
				$objPHPExcel->getActiveSheet()->getColumnDimension("D")->setWidth(12);


				$objPHPExcel->getActiveSheet()->getStyle('D1:D'.$row_nr)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
				$objPHPExcel->getActiveSheet()->getStyle('A1:A'.$row_nr)->applyFromArray($style_left);	

				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header('Content-Disposition: attachment;filename="REPORTE_DIARIO.xlsx"');
				header('Cache-Control: max-age=0');
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
				$objWriter->save('php://output');
				exit;


			} 
			/*
			$exporter->addRow(array("This", "is", "a", "test")); 
			$exporter->addRow(array(1, 2, 3, "123-456-7890"));
			*/
		}


		App::import("Model","Oficina");
		$o = new Oficina();
		$t = $o->find("list");
		array_unshift($t,"TODOS");
		$this->set("oficinas",$t);
	}

	function report2(){
	
	}

	function testz(){

		new ZipArchive();
		die("AXXXX");
	}

	function importar(){
		App::import("Model","Oficina");
		$o = new Oficina();
		$t = $o->find("list");
		$this->set("oficinas",$t);

		if (!empty($this->data)) {
			$validate = true; $ext = "";

			App::import("Model","Persona");
			App::import("Model","Precio");
			App::import("Model","Entidad");
			$this->Persona = new Persona();
			$this->Precio  = new Precio();
			$this->Entidad = new Entidad();

			if(!empty($this->data["archivo"]["name"])){
				$parts = explode(".",$this->data["archivo"]["name"]);
				$ext = array_pop($parts);
			}



			if($ext != "xls" && $ext != "xlsx"  && $ext != "csv"){
				$validate = false;
				$error = "Archivo invalido.";
			} else if(empty($this->data["archivo"]["name"])){
				$validate = false;
				$error = "Archivo invalido.";
			} else if($this->data["archivo"]["error"] != 0){
				$validate = false;
				$error = "Archivo invalido.";
			}

			if($validate){

				$file = md5(rand(1,1000000).date("Y-m-d H:i:s")).".".$ext;
				move_uploaded_file($this->data["archivo"]["tmp_name"],APP."/tmp/uploads/".$file);

				set_include_path(get_include_path() . PATH_SEPARATOR .  APP.'/vendors/');
				include APP.'/vendors/PHPExcel/IOFactory.php';
				//$bigletters = createColumnsArray("GZ");

					$objPHPExcel = PHPExcel_IOFactory::load(APP."/tmp/uploads/".$file);
					$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

					$precios_req  = $this->Precio->find("all");
					$precios_list = array();
					foreach($precios_req as $item){
						$precios_list[$item["Precio"]["codigo"]] = $item["Precio"]["id"];
					}

					$entidad_req  = $this->Entidad->find("all");
					$entidad_list = array();
					foreach($entidad_req as $item){
						$entidad_list[$item["Entidad"]["codigo"]] = $item["Entidad"]["id"];
					}

					$j = 0;
					$ii = 0;
					foreach($sheetData as $data){
						$j++;
						if($j <= 1)continue;

						$ex_id = $data["A"];
						$ex_id = ltrim($ex_id,"0");
						$ex_nombre = $data["B"];
						$ex_ruc_dni = $data["C"];
						$ex_precio = $data["D"];
						$ex_monto = str_replace(array("S","/"," ",",","s"),"",$data["E"]);
						$ex_fecha = $data["F"];
						$ex_entidad = $data["G"];
						list($d,$m,$y) = explode("/",$ex_fecha);
						$ex_fecha = $y."-".$m."-".$d;

						$tipo_persona = TPERSONA;
						if(strlen($ex_ruc_dni)==11)	$tipo_persona = TEMPRESA;

						$this->Persona->id = null;	
						$tmp = $this->Persona->save(array("nombre"=> $ex_nombre, "dni_ruc" => $ex_ruc_dni , "tipo" => $tipo_persona));
						$precio_id = $tmp["Precio"]["id"];

						if(!isset($precios_list[$ex_precio])){
							$this->_flash("El codigo de Precio no existe:".$ex_precio,"error");
							continue;
						}

						if(!isset($entidad_list[$ex_entidad])){
							$this->_flash("El codigo de Entidad no existe:".$ex_entidad,"error");
							continue;
						}

						$check = $this->Recibo->find("first",array("recursive"=> -1, "conditions"=>array("Recibo.secuencia"=>$ex_id,"Recibo.year"=>$y,"Recibo.oficina_id"=>$this->data["Recibo"]["oficina_id"])));
						if(!empty($check)){
							$this->_flash("Recibo ya existente:".$ex_id,"error");							
							continue;
						}

						$this->Recibo->id = null;
						$this->Recibo->save(array(
							"secuencia" => $ex_id,
							"year"      => $y,
							"nombres"   => $ex_nombre,
							"precio_id" => $precios_list[$ex_precio],
							"monto"    => $ex_monto,
							"modified" => $ex_fecha,
							"created"  => $ex_fecha,
							"cantidad" => 1,
							"persona_id" => $this->Persona->id,
							"entidad_id" => $entidad_list[$ex_entidad],
							"login_id"   => Credentials::get("__credentials.Login.id"),
							"oficina_id" => $this->data["Recibo"]["oficina_id"]
						));
						$ii++;
					}
	
				@unlink(APP."/tmp/uploads/".$file);
				$this->_flash("Se importaron : ".$ii." filas.","success");
				$this->redirect(array("action"=>"index"));
			} else {
				$this->_flash($error,"error");
				//$this->redirect(array("action"=>"index"));
			}
		}


	}


}
