<?php  
/* 
 * Developed by The-Di-Lab 
 * www.the-di-lab.com 
 * www.startutorial.com 
 * contact at thedilab@gmail.com 
 * FileMode v2.0 support multiple fields 
 */ 
App::import('Core', array('Folder')); 
class FileModelBehavior extends ModelBehavior { 
    /** 
     * Model-specific settings 
     * @var array 
     */ 
    public $settings = array();     
    /** 
     * Setup 
     * @param unknown_type $model 
     * @param unknown_type $settings 
     */ 
    public function setup(&$Model, $settings) { 
        //Folder for setting up permission 
        if (!isset($this->Folder)) { 
            $this->Folder = new Folder(); 
        }         
        //default settings 
        if (!isset($this->settings[$Model->alias])) { 
            $this->settings[$Model->alias] = array( 
                'file_db_file'=>array('filename'), 
                'file_field'=>array('file'), 
                'dir' => array('uploads'), 
                'overwrite'=>1, 
            ); 
        }         
        $this->settings[$Model->alias] = array_merge( 
            $this->settings[$Model->alias], (array)$settings 
        );   

       $this->uploads      = array();  
   	 /* 
        //hold settings 
        $this->dir          = $this->settings[$Model->alias]['dir']; 
        $this->file_db_file = $this->settings[$Model->alias]['file_db_file']; 
        $this->file_field   = $this->settings[$Model->alias]['file_field']; 
 
        $this->overwrite    = $this->settings[$Model->alias]['overwrite']; 
		*/
		//var_dump($this->dir);exit; 
		//var_dump($this->settings,$this->dir);exit;
    }     

    //call back 
    public function afterSave(&$Model,$created){ 
        //callback only if there is a file attached 
        if($this->_hasUploads($Model)){                 
                //save 
                if($created){ 
                    $id=$Model->getLastInsertId();     
                //update 
                }else{    

			            
                    //overwrite 
                    if($this->settings[$Model->alias]['overwrite']){     
						$Model->unbindmodelall(); 
						$fields = array($Model->alias.".".$Model->primaryKey);	   
					 	foreach($this->settings[$Model->alias]['file_db_file'] as $subfield){
							$fields[] = $Model->alias.".".$subfield;
						}
						 
                        $oldFile=$Model->find('first',array('contain'=>false, 'fields' => $fields,
                                                            'conditions'=>array($Model->primaryKey=>$Model->data[$Model->alias][$Model->primaryKey])));                                 

                        //delete all of the old files 
                        for($i=0;$i<sizeof($this->uploads);$i++){ 
                            $this->_delete($oldFile[$Model->alias][$this->settings[$Model->alias]['file_db_file'][$this->uploads[$i]]],
											$oldFile[$Model->alias][$Model->primaryKey],$this->uploads[$i],$Model); 
                        }                         
                         
                    }                 
                    $id=$Model->data[$Model->alias][$Model->primaryKey]; 
                }         
		
			

                //upload files         
                $uploadOk=true; 
                for($i=0;$i<sizeof($this->uploads);$i++){ 
                    $thisUploadOk = $this->_upload($Model->data[$Model->alias][$this->settings[$Model->alias]['file_field'][$this->uploads[$i]]],$id,$this->uploads[$i],$Model); 
                    $uploadOk=$uploadOk*$thisUploadOk; 
                    //get file name first 
                    $filename = $Model->data[$Model->alias][$this->settings[$Model->alias]['file_field'][$this->uploads[$i]]]['name'];     
                    //assign file name to updateModel 
                    $updateM[$Model->alias][$Model->primaryKey]=$id; 
                    $updateM[$Model->alias][$this->settings[$Model->alias]['file_db_file'][$this->uploads[$i]]]=$filename; 
                } 
                 
                if($uploadOk){ 
                        return $this->_customizedSave($Model,$updateM); 
                }else{ 
                        echo 'Upload failed,please try again.'; 
                        return false; 
                } 
                 
        }else{ 
                return true; 
        } 
    }     
    //call back 
    public function beforeDelete(&$Model){ 
        $data = $Model->read(null,$Model->id); 
        if (!empty($data[$Model->alias]['id'])) { 
                for($i=0;$i<sizeof($this->settings[$Model->alias]['file_db_file']);$i++){ 
                    $this->_delete($data[$Model->alias][$this->settings[$Model->alias]['file_db_file'][$i]],$data[$Model->alias][$Model->primaryKey],$i,$Model); 
                } 
                 
        } 
        return true; 
    } 
    //check if there is any uploads 
    private function _hasUploads($Model){ 
        //clear first 
        unset($this->uploads); 
        $this->uploads=array(); 
        for($i=0;$i<sizeof($this->settings[$Model->alias]['file_field']);$i++){ 
            //print_r($Model->data[$Model->alias]); 
            if(isset($Model->data[$Model->alias][$this->settings[$Model->alias]['file_field'][$i]]['size'])&& 
                    $Model->data[$Model->alias][$this->settings[$Model->alias]['file_field'][$i]]['size']!=0){ 
                        array_push($this->uploads,$i); 
					$parts = explode(".",$Model->data[$Model->alias][$this->settings[$Model->alias]['file_field'][$i]]['name']);
					$ext = array_pop($parts);
					$Model->data[$Model->alias][$this->settings[$Model->alias]['file_field'][$i]]['name'] = md5(date("Y-m-d H:i:s")."".rand(1,100000).$Model->data[$Model->alias][$this->settings[$Model->alias]['file_field'][$i]]['name']).".".$ext;
            } 
        } 
        if(sizeof($this->uploads)==0){ 
            return false; 
        } 
        return true; 
    } 
    private function _noUploads($Model){ 
        for($i=0;$i<sizeof($this->settings[$Model->alias]['file_field']);$i++){ 
            $Model->data[$Model->alias][$this->settings[$Model->alias]['file_field'][$i]]['size']=0; 
        } 
    } 
    private function _delete($filename,$id,$dirIndex,$Model){ 
		$len = strlen($id) - 1;
		$fullUploadDir = WWW_ROOT.$this->settings[$Model->alias]['dir'][$dirIndex].DS.$filename[0]; //]$id[$len]; 
		$parts = explode(".",$filename);
		$ext = array_pop($parts);
		//$filename = md5($filename).".".$ext;//$id.'.'.

        $path=$fullUploadDir.DS.$filename; 
        if (null!=$filename&&file_exists($path)) { 
            clearstatcache(); 
            $status = unlink($path); 
			return $status;
        }else{ 
            return false; 
        } 
    }     
    private function _customizedSave(&$Model,$modelDate){         
        //this will prevent it from calling the callback     
        $this->_noUploads($Model); 
        return $Model->save($modelDate); 
    }     
    private function _upload($file,$id,$dirIndex,$Model){         
        if($this->_validate($file)){    
			//$fullUploadDir = WWW_ROOT.$this->dir[$dirIndex].DS.$id; 
			$len = strlen($id) - 1;
			$fullUploadDir = WWW_ROOT.$this->settings[$Model->alias]['dir'][$dirIndex].DS.$file['name'][0];//]$id[$len]; 
			$parts = explode(".",$file['name']);
			$ext = array_pop($parts);
			//$filename = md5($file['name']).".".$ext;//$id.'.'.
            $des=$this->_createDir($fullUploadDir).DS.$file['name']; //$filename; 

			//$controller = ClassRegistry::getObject('controller');
			//$controller->_flash($fullUploadDir);
			//echo $des;
			//ClassRegistry::getObject('controller')->_flash(dev_dump($file).$des);   
            if (move_uploaded_file($file['tmp_name'], $des)) {  
                return true; 
            }else if (copy($file['tmp_name'],$des)) {  
                return true; 
            }else{ 
                return false; 
            } 
        }else{ 
                return false; 
        } 
         
    }     

	//private function _createDir($id,$dirIndex){ 
    private function _createDir($fullUploadDir){ 
        //$fullUploadDir = WWW_ROOT.$this->dir[$dirIndex].DS.$id; 
        //make sure the permission 
        if (!is_dir($fullUploadDir)) { 
            $this->Folder->create($fullUploadDir, 0777); 
             
        } else if (!is_writable($fullUploadDir)) { 
            $this->Folder->chmod($fullUploadDir, 0777, false);  
        } 
        return $fullUploadDir; 
    }     
    //give your own validation logic here 
    private function _validate($file){ 
        return true; 
    } 

         
} 
?>
