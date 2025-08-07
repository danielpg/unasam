<?php
/*
 * Created on 15/09/2011
 */
  
class Installer{
	public $dict;
	public $db;
	public $dbconfig;
	
	private $available_tables;
	
	function __construct(){
		//App::import('Vendor','adodb/adodb.inc.php');
		require_once APP . 'vendors/adodb/adodb.inc.php';

		$config = new DATABASE_CONFIG();
		$this->dbconfig = $config->default;

  		$this->db = NewADOConnection($config->default['driver']);

	  	$this->db->Connect( $config->default['host'], 
						  	$config->default['login'], 
						  	$config->default['password'], 
						  	$config->default['database']);
		$this->dict = NewDataDictionary($this->db);
	}	
	

    function install($version = null, $options = array())
    {
        $version = (is_null($version)) ? max($this->getAvailableVersions()) : $version;
        return $this->_upgradeOrDowngrade('up',  $version , $options);
    }

    function up($version = null, $options = array())
    {
        return $this->_upgradeOrDowngrade('up', $version, $options);
    }

    function uninstall($version = null, $options = array())
    {
        $version = (is_null($version)) ? 0 : $version;
        return $this->_upgradeOrDowngrade('down', $version, $options);
    }

    function down($version = null, $options = array())
    {
        return $this->_upgradeOrDowngrade('down', $version, $options);
    }

    function _upgradeOrDowngrade($action, $version = null, $options = array())
    {
       /* if(in_array('quiet',$options) && AK_ENVIRONMENT == 'development'){
            $this->vervose = false;
        }elseif(!empty($this->vervose) && AK_ENVIRONMENT == 'development'){
            $this->debug(true);
        }*/

        $current_version = $this->getInstalledVersion($options);
        $available_versions = $this->getAvailableVersions();

        $action = stristr($action,'down') ? 'down' : 'up';

        if($action == 'up'){
            $newest_version = max($available_versions);
            $version = isset($version[0]) && is_numeric($version[0]) ? $version[0] : $newest_version;
            $versions = range($current_version+1,$version);

            if($current_version > $version){
                echo sprintf("You can't upgrade to version %version, when you are currently on version %current_version", array('%version'=>$version,'%current_version'=>$current_version));
                exit;
                return false;
            }
        }else{
            $version = !empty($version[0]) && is_numeric($version[0]) ? $version[0] : 0;
            $versions = range($current_version, empty($version) ? 1 : $version+1);

            if($current_version == 0){
                return true;
            }elseif($current_version < $version){
               echo sprintf("You can't downgrade to version %version, when you just have installed version %current_version", array('%version'=>$version,'%current_version'=>$current_version));
                exit;
                return false;
            }
        }

        if($this->warn_if_same_version && $current_version == $version){
            echo sprintf("Can't go $action to version %version, you're already on version %version", array('%version'=>$version));
            exit;
            return false;
        }
/*
        if(AK_CLI && !empty($this->vervose) && AK_ENVIRONMENT == 'development'){
            echo Ak::t(ucfirst($action).'grading');
        }*/

        if(!empty($versions) && is_array($versions)){
            foreach ($versions as $version){
                if(!$this->_runInstallerMethod($action, $version, $options)){
                    return false;
                }
            }
        }else{
            return false;
        }

        return true;
    }

    function installVersion($version, $options = array())
    {
        return $this->_runInstallerMethod('up', $version, $options);
    }

    function uninstallVersion($version, $options = array())
    {
        return $this->_runInstallerMethod('down', $version, $options);
    }

    /**
     * Runs a a dow_1, up_3 method and wraps it into a transaction.
     */
    function _runInstallerMethod($method_prefix, $version, $options = array(), $version_number = null)
    {
        $method_name = $method_prefix.'_'.$version;
        if(!method_exists($this, $method_name)){
            return false;
        }

        $version_number = empty($version_number) ? ($method_prefix=='down' ? $version-1 : $version) : $version_number;

        $this->transactionStart();
        if($this->$method_name($options) === false){
            $this->transactionFail();
        }
        $success = !$this->transactionHasFailed();
        $this->transactionComplete();
        if($success){
            $this->setInstalledVersion($version_number, $options);
        }
        return $success;
    }

    function getInstallerName()
    {
        return str_replace('installer','',strtolower(get_class($this)));
    }

    function _versionPath($options = array())
    {
        return APP.DS.'config'.DS.'versions'.DS.$this->getInstallerName().'_version.txt';
    }

    function getInstalledVersion($options = array())
    {
        $version_file = $this->_versionPath($options);

        if(!is_file($version_file)){
            $this->setInstalledVersion(0, $options);
        }
        return file_get_contents($this->_versionPath($options));
    }


    function setInstalledVersion($version, $options = array())
    {
        return file_put_contents($this->_versionPath($options), $version);
    }


    function getAvailableVersions()
    {
        $versions = array();
        foreach(get_class_methods($this) as $method_name){
            if(preg_match('/^up_([0-9]*)$/',$method_name, $match)){
                $versions[] = intval($match[1]);
            }
        }
        sort($versions);
        return $versions;
    }	

    function modifyTable($table_name, $column_options = null, $table_options = array())
    {
        return $this->_createOrModifyTable($table_name, $column_options, $table_options);
    }


    function addColumn($table_name, $column_details)
    {
		//$sqlarray = $this->dict->AddColumnSQL($table_name, $column_details);
		//$this->dict->ExecuteSQLArray($sqlarray);
        $column_details = $this->_getColumnsAsAdodbDataDictionaryString($column_details);
        return $this->dict->ExecuteSQLArray($this->dict->AddColumnSQL($table_name, $column_details));
    }

    function changeColumn($table_name, $column_details)
    {
        $column_details = $this->_getColumnsAsAdodbDataDictionaryString($column_details);
        return $this->dict->ExecuteSQLArray($this->dict->AlterColumnSQL($table_name, $column_details));
    }

    function removeColumn($table_name, $column_name)
    {
        return $this->dict->ExecuteSQLArray($this->dict->DropColumnSQL($table_name, $column_name));
    }

    function renameColumn($table_name, $old_column_name, $new_column_name)
    {
        return $this->db->renameColumn($table_name, $old_column_name, $new_column_name);
    }

    function createTable($table_name, $column_options = null, $table_options = array())
    {
        /*if($this->tableExists($table_name)){
            trigger_error(Ak::t('Table %table_name already exists on the database', array('%table_name'=>$table_name)), E_USER_NOTICE);
            return false;
        }*/
        
        // $this->timestamps = (!isset($table_options['timestamp']) || (isset($table_options['timestamp']) && $table_options['timestamp'])) &&
        //(!strstr($column_options, 'created') && !strstr($column_options, 'updated'));
        return $this->_createOrModifyTable($table_name, $column_options, $table_options);
    }

    function _createOrModifyTable($table_name, $column_options = null, $table_options = array())
    {
          if(empty($column_options)){
            trigger_error('You must supply details for the table you are creating.', E_USER_ERROR);
            return false;
        }

        $column_options = is_string($column_options) ? array('columns'=>$column_options) : $column_options;

        $default_column_options = array(
        'sequence_table' => false
        );
        $column_options = array_merge($default_column_options, $column_options);

        $default_table_options = array(
        'mysql' => 'TYPE=InnoDB',
        //'REPLACE'
        );
        $table_options = array_merge($default_table_options, $table_options);

        $column_string = $this->_getColumnsAsAdodbDataDictionaryString($column_options['columns']);

        $create_or_alter_table_sql = $this->dict->ChangeTableSQL($table_name, str_replace(array(' UNIQUE', ' INDEX', ' FULLTEXT', ' HASH'), '', $column_string), $table_options);
        $result = $this->dict->ExecuteSQLArray($create_or_alter_table_sql, false);

        if($result){
            $this->available_tables[] = $table_name;
        }else{
            trigger_error(sprintf("Could not create or alter table %s using the SQL \n--------\n%s\n--------\n", $table_name, $create_or_alter_table_sql[0]), E_USER_ERROR);
        }

        $columns_to_index = $this->_getColumnsToIndex($column_string);

        foreach ($columns_to_index as $column_to_index => $index_type){
            $this->addIndex($table_name, $column_to_index.($index_type != 'INDEX' ? ' '.$index_type : ''));
        }

        if(isset($column_options['index_columns'])){
            $this->addIndex($table_name, $column_options['index_columns']);

        }
        if($column_options['sequence_table'] || $this->_requiresSequenceTable($column_string)){
            $this->createSequence($table_name);
        }

        return $result;
    }

    function dropTable($table_name, $options = array())
    {
        $result = $this->tableExists($table_name) ? $this->db->execute('DROP TABLE '.$table_name) : true;
        if($result){
            unset($this->available_tables[array_search($table_name, $this->available_tables)]);
            if(!empty($options['sequence'])){
                $this->dropSequence($table_name);
            }
        }
    }

    function dropTables()
    {
        /*$args = func_get_args();
        if(!empty($args)){
            $num_args = count($args);
            $options = $num_args > 1 && is_array($args[$num_args-1]) ? array_shift($args) : array();
            $tables = count($args) > 1 ? $args : (is_array($args[0]) ? $args[0] : Ak::toArray($args[0]));
            foreach ($tables as $table){
                $this->dropTable($table, $options);
            }
        }*/
    }

    function addIndex($table_name, $columns, $index_name = '')
    {
        $index_name = ($index_name == '') ? 'idx_'.$table_name.'_'.$columns : $index_name;
        $index_options = array();
        if(preg_match('/(UNIQUE|FULLTEXT|HASH)/',$columns,$match)){
            $columns = trim(str_replace($match[1],'',$columns),' ');
            $index_options[] = $match[1];
        }
        return $this->tableExists($table_name) ? $this->dict->ExecuteSQLArray($this->dict->CreateIndexSQL($index_name, $table_name, $columns, $index_options)) : false;
    }

    function removeIndex($table_name, $columns_or_index_name)
    {
        if(!$this->tableExists($table_name)){
            return false;
        }
        $available_indexes = $this->db->MetaIndexes($table_name);
        $index_name = isset($available_indexes[$columns_or_index_name]) ? $columns_or_index_name : 'idx_'.$table_name.'_'.$columns_or_index_name;
        if(!isset($available_indexes[$index_name])){
            trigger_error(sprintf('Index %index_name does not exist.', array('%index_name'=>$index_name)), E_USER_NOTICE);
            return false;
        }
        return $this->dict->ExecuteSQLArray($this->dict->DropIndexSQL($index_name, $table_name));
    }

    function dropIndex($table_name, $columns_or_index_name)
    {
        return $this->removeIndex($table_name,$columns_or_index_name);
    }

    function createSequence($table_name)
    {
        $result = $this->tableExists('seq_'.$table_name) ? false : $this->db->CreateSequence('seq_'.$table_name);
        $this->available_tables[] = 'seq_'.$table_name;
        return $result;
    }

    function dropSequence($table_name)
    {
        $result = $this->tableExists('seq_'.$table_name) ? $this->db->DropSequence('seq_'.$table_name) : true;
        if($result){
            unset($this->available_tables[array_search('seq_'.$table_name, $this->available_tables)]);

        }
        return $result;
    }

    function getAvailableTables()
    {
        if(empty($this->available_tables)){
            $this->available_tables = $this->db->MetaTables();
        }
        return $this->available_tables;
    }

    function tableExists($table_name)
    {
        return in_array($table_name,$this->getAvailableTables());
    }

    function _getColumnsAsAdodbDataDictionaryString($columns)
    {
        $columns = $this->_setColumnDefaults($columns);
        $this->_ensureColumnNameCompatibility($columns);

        $equivalences = array(
        '/ ((limit|max|length) ?= ?)([0-9]+)([ \n\r,]+)/'=> ' (\3) ',
        '/([ \n\r,]+)default([ =]+)([^\'^,^\n]+)/i'=> ' DEFAULT \'\3\'',
        '/([ \n\r,]+)(integer|int)([( \n\r,]+)/'=> '\1 I \3',
        '/([ \n\r,]+)float([( \n\r,]+)/'=> '\1 F \2',
        '/([ \n\r,]+)decimal([( \n\r,]+)/'=> '\1 N \2',
        '/([ \n\r,]+)datetime([( \n\r,]+)/'=> '\1 T \2',
        '/([ \n\r,]+)date([( \n\r,]+)/'=> '\1 D \2',
        '/([ \n\r,]+)timestamp([( \n\r,]+)/'=> '\1 T \2',
        '/([ \n\r,]+)time([( \n\r,]+)/'=> '\1 T \2',
        '/([ \n\r,]+)text([( \n\r,]+)/'=> '\1 XL \2',
        '/([ \n\r,]+)string([( \n\r,]+)/'=> '\1 C \2',
        '/([ \n\r,]+)binary([( \n\r,]+)/'=> '\1 B \2',
        '/([ \n\r,]+)boolean([( \n\r,]+)/'=> '\1 L'.($this->dbconfig['driver']=='mysql'?'(1)':'').' \2',
        '/ NOT( |_)?NULL/i'=> ' NOTNULL',
        '/ AUTO( |_)?INCREMENT/i'=> ' AUTO ',
        '/ +/'=> ' ',
        '/ ([\(,]+)/'=> '\1',
        '/ INDEX| IDX/i'=> ' INDEX ',
        '/ UNIQUE/i'=> ' UNIQUE ',
        '/ HASH/i'=> ' HASH ',
        '/ FULL_?TEXT/i'=> ' FULLTEXT ',
        '/ ((PRIMARY( |_)?)?KEY|pk)/i'=> ' KEY',
        );

        return trim(preg_replace(array_keys($equivalences),array_values($equivalences), ' '.$columns.' '), ' ');
    }



    function stringToArray($string)
    {
        $args = $string;
        if(count($args) == 1 && !is_array($args)){
        (array)$args = array_unique(array_map('trim',array_diff(explode(',',strtr($args.',',';|-',',,,')),array(''))));
        }
        return $args;
    }


    function toArray()
    {
        $args = func_get_args();
        return is_array($args[0]) ? $args[0] : (func_num_args() === 1 ? $this->stringToArray($args[0]) : $args);
    }

    function _setColumnDefaults($columns)
    {
        $columns = $this->toArray($columns);
        foreach ((array)$columns as $column){
            $column = trim($column, "\n\t\r, ");
            if(!empty($column)){
                $single_columns[$column] = $this->_setColumnDefault($column);
            }
        }
     /*   if(!empty($this->timestamps) && !isset($single_columns['created_at']) &&  !isset($single_columns['updated_at'])){
            $single_columns['updated_at'] = $this->_setColumnDefault('updated_at');
            $single_columns['created_at'] = $this->_setColumnDefault('created_at');
        }*/
        return join(",\n", $single_columns);
    }

    function _setColumnDefault($column)
    {
        return $this->_needsDefaultAttributes($column) ? $this->_setDefaultAttributes($column) : $column;
    }

    function _needsDefaultAttributes($column)
    {
        return preg_match('/^(([A-Z0-9_\(\)]+)|(.+ string[^\(.]*)|(\*.*))$/i',$column);
    }

    function _setDefaultAttributes($column)
    {
        $rules = $this->getDefaultColumnAttributesRules();
        foreach ($rules as $regex=>$replacement){
            if(is_string($replacement)){
                $column = preg_replace($regex,$replacement,$column);
            }elseif(preg_match($regex,$column,$match)){
                $column = call_user_func_array($replacement,$match);
            }
        }
        return $column;
    }

    /**
     * Returns a key => value pair of regular expressions that will trigger methods 
     * to cast database columns to their respective default values or a replacement expression.
     */
    function getDefaultColumnAttributesRules()
    {
        return array(
        //'/^\*(.*)$/i' => array(&$this,'_castToMultilingualColumn'),
        '/^(description|content|body|details)$/i' => '\1 text',
        '/^(lock_version)$/i' => '\1 integer default \'1\'',
        '/^(.+_count)$/i' => '\1 integer default \'0\'',
        '/^(id)$/i' => 'id integer not null auto_increment primary_key',
        '/^(.+)_(id|by)$/i' => '\1_\2 integer  not null index',
        '/^(position)$/i' => '\1 integer index',
        '/^(.+_at)$/i' => '\1 datetime',
        '/^(.+_on)$/i' => '\1 date',
        '/^(is_|has_|do_|does_|are_)([A-Z0-9_]+)$/i' => '\1\2 boolean not null default \'0\' index', //
        '/^([A-Z0-9_]+) *(\([0-9]+\))?$/i' => '\1 string\2', // Everything else will default to string
        '/^((.+ )string([^\(.]*))$/i' => '\2string(255)\3', // If we don't set the string lenght it will fail, so if not present will set it to 255
        );
    }

    function _castToMultilingualColumn($found, $column)
    {
        $columns = array();
        foreach (Ak::langs() as $lang){
            $columns[] = $lang.'_'.ltrim($column);
        }
        return $this->_setColumnDefaults($columns);
    }

    function _getColumnsToIndex($column_string)
    {
        $columns_to_index = array();
        foreach (explode(',',$column_string.',') as $column){
            if(preg_match('/([A-Za-z0-9_]+) (.*) (INDEX|UNIQUE|FULLTEXT|HASH) ?(.*)$/i',$column,$match)){
                $columns_to_index[$match[1]] = $match[3];
            }
        }
        return $columns_to_index;
    }

    function _getUniqueValueColumns($column_string)
    {
        $unique_columns = array();
        foreach (explode(',',$column_string.',') as $column){
            if(preg_match('/([A-Za-z0-9_]+) (.*) UNIQUE ?(.*)$/',$column,$match)){
                $unique_columns[] = $match[1];
            }
        }
        return $unique_columns;
    }

    function _requiresSequenceTable($column_string)
    {
        if(in_array($this->dbconfig['driver'],array('mysql','postgre'))){
            return false;
        }
        foreach (explode(',',$column_string.',') as $column){
            if(preg_match('/([A-Za-z0-9_]+) (.*) AUTO (.*)$/',$column)){
                return true;
            }
            if(preg_match('/^id /',$column)){
                return true;
            }
        }
        return false;
    }


    /**
    * Transaction support for database operations
    *
    * Transactions are enabled automatically for Intaller objects, But you can nest transactions within models.
    * This transactions are nested, and only the othermost will be executed
    *
    *   $UserInstalller->transactionStart();
    *   $UserInstalller->addTable('id, name');
    *
    *    if(!isCompatible()){ 
    *       $User->transactionFail();
    *    }
    *
    *   $User->transactionComplete();
    */
    function transactionStart()
    {
        return $this->db->startTransaction();
    }

    function transactionComplete()
    {
        return $this->db->stopTransaction();
    }

    function transactionFail()
    {
        return $this->db->failTransaction();
    }

    function transactionHasFailed()
    {
        return $this->db->hasTransactionFailed();
    }

    /**
     * Promts for a variable on console scripts
     */
    function promptUserVar($message, $options = array())
    {
        $f = fopen("php://stdin","r");
        $default_options = array(
        'default' => null,
        'optional' => false,
        );

        $options = array_merge($default_options, $options);

        echo "\n".$message.(empty($options['default'])?'': ' ['.$options['default'].']').': ';
        $user_input = fgets($f, 25600);
        $value = trim($user_input,"\n\r\t ");
        $value = empty($value) ? $options['default'] : $value;
        if(empty($value) && empty($options['optional'])){
            echo "\n\nThis setting is not optional.";
            fclose($f);
            return $this->promptUserVar($message, $options);
        }
        fclose($f);
        return empty($value) ? $options['default'] : $value;
    }

    function _ensureColumnNameCompatibility($columns)
    {
        $columns = explode(',',$columns.',');
        foreach ($columns as $column){
            $column = trim($column);
            $column = substr($column, 0, strpos($column.' ',' '));
            $this->_canUseColumn($column);
        }
    }

    function _canUseColumn($column_name)
    {
        $invalid_columns = $this->_getInvalidColumnNames();
        if(in_array($column_name, $invalid_columns)){
			die("invalid column");exit;
           /* $method_name_part = AkInflector::camelize($column_name);
            require_once(AK_LIB_DIR.DS.'AkActiveRecord.php');
            $method_name = (method_exists(new AkActiveRecord(), 'set'.$method_name_part)?'set':'get').$method_name_part;

            trigger_error(Ak::t('A method named %method_name exists in the AkActiveRecord class'.
            ' which will cause a recusion problem if you use the column %column_name in your database. '.
            'You can disable automatic %type by setting the constant %constant to false '.
            'in your configuration file.', array(
            '%method_name'=> $method_name,
            '%column_name' => $column_name,
            '%type' => Ak::t($method_name[0] == 's' ? 'setters' : 'getters'),
            '%constant' => Ak::t($method_name[0] == 's' ? 'AK_ACTIVE_RECORD_ENABLE_CALLBACK_SETTERS' : 'AK_ACTIVE_RECORD_ENABLE_CALLBACK_GETTERS'),
            ''
            )), E_USER_ERROR);*/
        }
    }

    function _getInvalidColumnNames()
    {
        return defined('AK_INVALID_ACTIVE_RECORD_COLUMNS') ? explode(',',AK_INVALID_ACTIVE_RECORD_COLUMNS) : array('sanitized_conditions_array','conditions','inheritance_column','inheritance_column',
        'subclasses','attribute','attributes','attribute','attributes','accessible_attributes','protected_attributes',
        'serialized_attributes','available_attributes','attribute_caption','primary_key','column_names','content_columns',
        'attribute_names','combined_subattributes','available_combined_attributes','connection','connection','primary_key',
        'table_name','table_name','only_available_atrributes','columns_for_atrributes','columns_with_regex_boundaries','columns',
        'column_settings','column_settings','akelos_data_type','class_for_database_table_mapping','display_field','display_field',
        'internationalized_columns','available_locales','current_locale','attribute_by_locale','attribute_locales',
        'attribute_by_locale','attribute_locales','attributes_before_type_cast','attribute_before_type_cast','serialize_attribute',
        'available_attributes_quoted','attributes_quoted','column_type','value_for_date_column','observable_state',
        'observable_state','observers','errors','base_errors','errors_on','full_error_messages','array_from_ak_string',
        'attribute_condition','association_handler','associated','associated_finder_sql_options','association_option',
        'association_option','association_id','associated_ids','associated_handler_name','associated_type','association_type',
        'collection_handler_name','model_name','model_name','parent_model_name','parent_model_name');
    }

    function execute($sql)
    {
        return $this->db->execute($sql);
    }

    function debug($toggle = null)
    {
        $this->db->debug = $toggle === null ? !$this->db->debug : $toggle;
    }


}
 
?>
