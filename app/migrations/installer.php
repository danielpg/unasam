<?php

class SystemInstaller extends Installer{
 
 	function test1(){

	  	$this->createTable('u_personas', 
	            'id,
				nombre(200),
				tipo I,
				dni_ruc(20),
				direccion(200),
				cod_estudiante(100),
				entidad_id I,
				created datetime,	
				modified datetime'
	    );	
 
	  	$this->createTable('u_roles', 
	            'id,
				nombre(200),
				codigo(20),
				created datetime,	
				modified datetime'
	    );	 

	  	$this->createTable('u_rubros', 
	            'id,
				nombre(200),
				codigo(50),
				estado I,
				created datetime,	
				modified datetime'
	    );

	  	$this->createTable('u_precios', 
	            'id,
				rubro_id,
				nombre(200),
				codigo(50),
				estado I,
				monto(20),
				created datetime,	
				modified datetime'
	    );

	  	$this->createTable('u_oficinas', 
	            'id,
				nombre(200),
				estado I,
				created datetime,	
				modified datetime'
	    );

	  	$this->createTable('u_entidades', 
	            'id,
				nombre(200),
				codigo(50),
				estado I,
				created datetime,	
				modified datetime'
	    );

	  	$this->createTable('u_logins', 
               'id,
				oficina_id,
				role_id,
				username(100),
				password(100),
				nombres(200),
				estado I,
				cantidad I,
				created datetime,	
				modified datetime'
	    );	 
       $this->createTable('u_recibos', 
	            'id,
				persona_id,
				precio_id,
				login_id,
				oficina_id,
				entidad_id,
				nombres(150),
				monto(100),
				extorno I,
				codigo(50),
				fecha_externo datetime,
				created datetime,	
				modified datetime'
	    );	
	}

} 
?>
