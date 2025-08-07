<?php
/*
==============================================================================
	Openc2c 

	Copyright (c) 2008 

	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.

	See the GNU General Public License for more details.

==============================================================================
*/

class Openc2cInstaller extends AkInstaller
{ 
	//added by jmontoya
	function up_1()
	{
		// Users
		$this->createTable('users',
			'id,
			login,
			password,
			generated_password,
			email,
			title,
			firstname,
			middlename,
			lastname,
			gender,
			phone_1,
			phone_2,
			total_sales integer default 0,
			birth_on,
			image,
			mimetype,
			t_zone(80),
			is_enabled bool default 0,
			is_admin  bool default 0,
			is_seller bool default 0,
			created_at,
			activated_at,
			last_login_at'
		);

		$this->createTable('user_addres_books' ,
			'id , 
			name,
			address_1 ,
			address_2 ,
			postal_code,
			state,  
			city_id ,
			country_id ,
			user_id ', array('timestamp'=>false)
		);

		// Favorites user
		$this->createTable('user_favorites' ,
			'id ,  
			created_at ,
			from_user_id ,
			to_user_id '
		);

		// Some user statics
		$this->createTable('user_details' ,
			'id ,  
			items_won ,
			rating ,
			created_at ,  
			to_user_id ,
			from_user_id'
		);
		
		// Reviews between users
		$this->createTable('user_reviews' ,
			'id ,  
			user_id, 
			seller_user_id,
			product_id,  
			title_product,
			comment,
			replication,
			rating, 
			created_at,
			last_updated_at'
		);
		
		// messages between users
		$this->createTable('user_messages' ,
			'id , 
			message(255),
			from_user_id,
			to_user_id, 
			product_id,
			visibility_code int,
			created_at'
		);
		
		// Reviews between users
		$this->createTable('user_reviews_users' ,
			'id ,  
			user_review_id  ,
			user_id ', array('timestamp'=>false)
		);

//		// tags 
//		$this->createTable('tags',
//			'id ,
//			name ,
//			is_active', array('timestamp'=>false)
//		);
//
//		$this->createTable('relation_tags' ,
//			'id ,
//			user_id ,
//			product_id ,
//			tag_id ', array('timestamp'=>false)
//		);  
//
//		// favorite products or favourite tags or favorite keywords 
//		$this->createTable('tag_favorites' ,
//			'id ,  
//			name ,  
//			created_at ,  
//			user_id '
//		);
		
		//User group
		$this->createTable('groups' ,
			'id ,  
			name(20) ,
			description ', array('timestamp'=>false)
		);

		$this->createTable('group_users' ,
			'id ,  
			group_id ,
			user_id' , array('timestamp'=>false)
		);

		$this->createTable('roles' ,
			'id ,
			name(20) ,
			description' , array('timestamp'=>false)
		);
	
		$this->createTable('permissions' ,
			'id,
			name,
			system_module_id,
			description',			
			array('timestamp'=>false)		
		);

		$this->createTable('roles_users', '
			id,
			user_id,
			role_id', array('timestamp'=>false)
		);		

		$this->createTable('permissions_roles', ' 
			id,
			role_id,
			system_module_id,
			permission_id,
			is_enabled bool default 0',
			array('timestamp'=>false)
		);

		$this->createTable('countries' ,
			'id ,
			name' , array('timestamp'=>false)
		);  
		
		$this->createTable('cities' ,
			'id ,
			name,
			country_id', array('timestamp'=>false)
		);
		
		// Notifications preferences or site preferences 
		$this->createTable('user_preferences' ,
			'id ,
			name,
			title,
			value ,
			is_editable ,
			user_id' , array('timestamp'=>false)
		);

		// Account
		$this->createTable('user_payment_options',
			'id ,
			name,
			card_holder_name ,
			credit_card_number varchar(32),
			expiration_month , 
			expiration_year ,
			user_id ,
			payment_method_id ', array('timestamp'=>false)
		);

		// Paypal - Mastercard - Visa  
		$this->createTable('payment_methods' ,
			'id ,
			name(20) ,
			code(20) ,
			description(200) ', array('timestamp'=>false)
		);  

		// Users log  
		$this->createTable('log_users' ,
			'id ,
			login_at ,
			logout_at ,
			user_id', array('timestamp'=>false)
		); 

		$this->createTable('site_preferences' ,
		     'id,
	         name,
	         title,
	         value text,
	         parent_id,
	         extension_id,
	         is_editable,
	         is_core', array('timestamp'=>false)
		);
		
		$this->createTable('languages',
	         'id,'.      // the key
	         'english_name(100),'. 
	         'orig_name(100),'. 
		     'code(2)' , array('timestamp'=>false)
	    );
	        
		$this->createTable('product_details',
	         'id,'.          // the key
	         'title(70),'.  
	         'description,'.
	         'language_id,'.
	         'product_id', array('timestamp'=>false)
	   );
	       
		$this->createTable('downloads',
	         'id,'.          // the key
			 'file_id,'.
	         'user_id,'.
	         'sale_id,'.
	         'created_at'
	    );
	       	            
		$this->createTable('categories', 
	         'id,'.     
			 'catdepth I INDEX,'.			 
			 'parent_id,'.
			 'display_order,'.			 
			 'name(100),'.
		     'counter int not null default 0,'.
	         'lft int,'.
	         'rgt int' , array('timestamp'=>false)
	    );
	    
		$this->createTable('screenshots', 
	         'id,'.    
	         'product_id,'.      
	         'file(32),'.      
	         'title(40),'.
	         'main_image bool' , array('timestamp'=>false)
	    );
	            
	    $this->createTable('products',
	         'id,'.          // the key
	         'catlevel1 I INDEX,'.
	         'catlevel2 I INDEX,'.
	         'catlevel3 I INDEX,'.
	         'catlevel4 I INDEX,'.
	         'catlevel5 I INDEX,'.
	         'product_detail_id,'.
	         'product_format_id,'.
	         'size_id,'.
	         'language_id,'.
	         'category_id,'.
	         'license(100),'.
	         'cost_id,'.
	         'created_at,'.
	         'updated_at,'.
	         'deleted_on,'.
	         'quantity int,'.
	         'visits_counter int,'. 
   	         'sales_counter int,'.
   	         'is_enabled bool,'.
   	         'is_active bool,'.
   	         'average_rating int,'.
   	         'selling_mode int,'.
   	         'is_downloadable bool,'.
   	         'download_maxattempts int,'.
   	         'download_maxdays int,'.
   	         'file(32),'.
   	         'original_file(100),'.
	         'user_id'
	    );
		
		$this->createTable('currencies', 
	         'id,  
	         name(50), 
	         code(3) ,
	         symbol(10),     
	   		 delimiter(1),
	   		 cseparator(1),
	   		 position(5) ,
	   		 is_integer bool', array('timestamp'=>false)
	    );
	    
	  	$this->createTable('sales', 
	         'id,'.
	         'buyer_id I,'.
	         'seller_id I,'.
	         'product_id I,'.
 	         'quantity I,'.
	         'unit_price F,'.
	         'total F,'.
	         'currency_id I,'.
	         'fee F,'.
	         'payment_status C(9),'.
	         'status_id I,'.
	         'rated_by_buyer I,'.
	         'rated_by_seller I,'.
	         'created_at D'
	    );
	    
	  	$this->createTable('orders', 
	         'id,'.
	         'seller_id int,'.
	         'seller_firstname(50),'.
	         'seller_lastname(50),'.
	         'seller_telephone(30),'.
	         'seller_address(200),'.     
	         'seller_country_id int,'.
	         'buyer_id int,'.
	         'buyer_firstname(50),'.
	         'buyer_lastname(50),'.
	         'buyer_telephone(30),'.
	         'buyer_address(200),'.
	         'buyer_country_id int,'.
	         'product_title(200),'.
   	         'quantity int,'.
	         'paid float(10.2),'.
	         'currency_id,'.
	         'created_at,'.
	         'sale_id'
	    );	            
	
	    $this->createTable('status', 
	         'id,'.      // the key
	 		 'name(20)', array('timestamp'=>false)
	    );
	       	
		$this->createTable('costs', 
	         'id,'.      // the key
	         'currency_id,'.
	         'name float(10.2) unsigned' , array('timestamp'=>false)
	    );
	    
		$this->createTable('product_reviews' ,
			 'id ,
			 comment(200) ,
			 rating int(2) ,
			 buyer_id ,
			 seller_id ,
			 user_id ,
			 product_id,
			 created_at '
		);
			
		$this->createTable('visibilities' ,
			 'id ,  
			 name(10) ', array('timestamp'=>false)
		);
			
		$this->createTable('attachments' ,
			 'id ,  
			 file(32),
			 original_name(32),
			 filetype(40),
			 description(200),
			 created_at,
			 product_id,
			 visibility_id',  array('timestamp'=>false)
		);
		
		$this->createTable('themes',
			 'id,
			  name(40),
			  filepath(40), 
			  screenshot(32),
				is_active int(2),
				description(200)',  array('timestamp'=>false)
		);	

		$this->createTable('blocks',
			 'id, 
			 name(100),
			 code_name(100)',  array('timestamp'=>false)
		);

		$this->createTable('zones',
			 'id,
			 name(50)',  array('timestamp'=>false)
		);
		
		$this->createTable('zone_blocks',
			 'id,
			 zone_id,
			 block_id, 
			 block_order',  array('timestamp'=>false)
		);
		
		$this->createTable('block_parameters',
			 'id,
			 block_id,
			 parameter,
			 value,
			 description',  array('timestamp'=>false) 
		);
		
		$this->createTable('system_modules',
			'id,
			 name,	 
			description', array('timestamp' => false)
		);
		//$this->installProfiles(1, array('base_system'));
				
		$this->createTable('tags',
			 'id,
			  name(50)',
			  array('timestamp'=>false)
		);
		
		$this->createTable('products_tags',
			 'id,
			  product_id,
			  tag_id', 
			  array('timestamp'=>false)
		);
		
		$this->createTable('product_formats',
			 'id,
			  name(50),
			  description',
			  array('timestamp'=>false)
		);
		
		$this->createTable('relations',
			 'id,
			  name(50)',
			  array('timestamp'=>false)
		);
		
		$this->createTable('product_relations',
			 'id,
			  product_id,
			  related_product_id,
			  product_relation_id',
			  array('timestamp'=>false)
		);
		
		$this->createTable('advertisements', 
			'id, 
			 position(200),
			 file text,
			 target(50),
			 description(200),
			 is_enable(2),
			 started_on,
			 ending_on,
			 filetype(200)
			'
		);
    }   	   	
    function down_1()
	{
		$this->dropTables(
			'users', 'user_addres_books' , 'user_favorites', 'user_details' , 'user_reviews' , 'user_messages' ,
			'user_review_users' , 'roles', 'permissions' , 
			'groups' , 'group_users' ,				
			'user_reviews' , 'orders' ,
			'countries' , 'cities' , 'states' ,				
			'tags' , 'relation_tags' , 'tag_favorites' ,
			'accounts' , 'payment_methods' , 
			'user_preferences' , 'site_preferences'  ,
			'log_users',
			'products','product_reviews','product_details', 'product_formats',
     		'categories','screenshots','attachments','visibilities','downloads',
      	   	'sales','status','costs',
      		'languages','currencies', 'blocks', 'zone_blocks', 'zones', 'block_parameters', 'system_modules', 'advertisements'
		);
	}
	
    // Install functions
    function installProfiles($version, $profiles)
    {
        foreach($profiles as $profile)
        {
            $profile_file = AK_APP_DIR.DS.'installers'.DS.'openc2c_'.$version.DS.'profiles'.DS.$profile.DS.'profile.php';
            
            if(file_exists($profile_file))
            {
                include_once($profile_file);
                
                $profile_class_name = AkInflector::camelize($profile).'Profile';
                if(class_exists($profile_class_name))
                {                	
                    $Profile = new $profile_class_name();
                    $Profile->Installer =& $this;
                    $Profile->install(); 
                }
            }
        }
    }
    
    //
    //
    //
    
    function installDataFiles($version)
    {
        $files = Ak::dir(AK_APP_DIR.DS.'installers'.DS.'openc2c_'.$version.DS.'data');
        sort($files);
             
 		foreach ($files as $file) {
            if($file[0] == '_' || !strstr($file,'yaml')) {
                continue;
            }
            $file = preg_replace('/^([0-9]*_)/','', $file);            
		
            $this->addBatchRecords
            (
            	AkInflector::camelize(substr($file, 0, strrpos($file,'.'))),
            	Ak::convert('yaml','array',file_get_contents(AK_APP_DIR.DS.'installers'.DS.'openc2c_'.$version.DS.'data'.DS.$file))
            );  
        }
    }
    
    
    function addBatchRecords($model, $record_details)
    {
        Ak::import($model);
        foreach($record_details as $record_detail)
        {
            $Element = new $model();                                 
            $Element->setAttributes($record_detail);
            $Element->save();
            
            if($Element->hasErrors())
            {
                echo "<p>There was an error while adding a new ".$Element->getModelName().'</p>';                
                echo "<pre>".print_r($Element->getErrors(),true)."</pre>";
            }
        }
     }
}
?>
