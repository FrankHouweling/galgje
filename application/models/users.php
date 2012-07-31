<?php

	class Users extends CI_Model {
	
		function __construct()
	    {
	        // Call the Model constructor
	        parent::__construct();
	    }
		
		public function adduser( )
		{
			
			$this->db->query( "INSERT INTO users( last_action ) VALUES( " . time() . " )" );
				
			return $this->db->insert_id();
			
		}
		
		public function cleanup( $userid, $currentroom )
		{
			
			$this->db->query( "UPDATE users SET last_action = '" . time() . "', current_room = '" . $currentroom . "' WHERE id = " . $userid );
			
			$get	=	$this->db->query( "SELECT id FROM users WHERE last_action < " . ( time() - 180 ) );	//	3 minuten inactief... (teveel lag voor onze zin, normaal heb je 1x in de 10 sec. min. contact
			
			foreach( $get->result_array as $res )
			{
				
				$this->db->query( "DELETE FROM games WHERE admin_id = " . $res["id"] );
				$this->db->query( "DELETE FROM users WHERE id = " . $res["id"] );
				
			}
			
		}
		
		public function setCurrentRoom( $userid, $roomid )
		{
			
			$this->db->query( "UPDATE users SET current_room = " . $this->db->escape($roomid) . " WHERE id = " . $this->db->escape( $userid ) );
			
			return true;
			
		}
		
	
	}

?>