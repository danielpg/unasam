<?php
class Query extends AppModel {
	var $useTable = false;
	static $singleton = false;
	var $name = 'Query';
	
	function singleton(){
		if(Query::$singleton==false){
			Query::$singleton = new Query;
		} else {
			return $this;
		}		
	}
	
	function squery($data){
		if(Query::$singleton === false)Query::$singleton = new Query;
		$tmp = Query::$singleton->query($data);
		if(empty($tmp)) $tmp = array();
		return $tmp;		
	}

}
?>