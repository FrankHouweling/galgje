<?php

	class Games extends CI_Model {
	
		function __construct()
	    {
	        // Call the Model constructor
	        parent::__construct();
	    }
		
		public function getCurrentRooms()
		{
			
			$query = $this->db->query('SELECT * FROM rooms');
			
			if( $query->num_rows() == 0 )
			{
				
				return false;
				
			}
			else
			{
				
				return $query->result_array();
				
			}
			
			
		}
		
		public function addRoom( $name, $userid )
		{
			
			$this->db->query( "INSERT INTO rooms(name, max_players, admin_id, last_action) VALUES(" . $this->db->escape( $name ) . ", '20', '" . $userid . "', '" . time() . "')" );
			
			return $this->db->insert_id();
			
		}
		
		public function cleanup()
		{
			
			$get	=	$this->db->query( "SELECT id FROM rooms WHERE last_action < " . ( time() - 800 ) );
			
			foreach( $get->result_array() as $r )
			{
				
				$this->db->query( "UPDATE users SET current_room = 0 WHERE current_room = " . $r["id"] );
				$this->db->query( "DELETE FROM rooms WHERE id = " . $r["id"] );
				
			}
			
		}
		
		public function getRoom( $roomid )
		{
			
			
			$get	=	$this->db->query( "SELECT * FROM rooms WHERE id = " . $this->db->escape( $roomid ) );
			
			if( $get->num_rows() !== 0 )
			{
				
				return $get->result_array();
				
			}
			else
			{
				
				return false;
				
			}
			
			
		}
		
		public function amountOfPlayers( $roomid )
		{
			
			$get	=	$this->db->query( "SELECT id FROM users WHERE current_room = " . $roomid );
			
			return $get->num_rows();
			
		}
		
		public function isRoomAdmin( $userid, $roomid )
		{
			
			$get	=	$this->db->query( "SELECT id FROM rooms WHERE id = '" . $roomid . "' AND admin_id = '" . $userid . "'" )or die(mysql_error());
			
			if( $get->num_rows() !== 0 )
			{
				
				return true;
				
			}
			else
			{
				
				return false;
				
			}
			
		}
		
		public function setrandomplayerturn( $roomid )
		{
			
			$get	=	$this->db->query( "SELECT id FROM users WHERE current_room = " . $roomid . " ORDER BY RAND() LIMIT 1" );
			
			$fetch	=	$get->result_array();
			
			$this->updateRoom( $roomid, "guest_guessing", $fetch[0]["id"] );
			
		}
		
		public function startGame( $roomid )
		{
		
			$this->updateroom( $roomid, "current_mode", 1 );
		
			$this->setrandomword( $roomid );
			
			$this->setrandomplayerturn( $roomid );
		
			return true;
			
		}
		
		public function setrandomword( $roomid )
		{
			
			$words	=	explode( "\n", file_get_contents( "datafiles/words.txt" ) );
			
			$this->updateRoom( $roomid, "current_word", $words[array_rand($words)] );
			
		}
		
		public function updateRoom( $roomid, $field, $value )
		{
		
			//	Make sure the last action is updated...
			
			if( $field !== "last_action" )
			{
			
				$this->updateRoom( $roomid,  "last_action", time());
				
			}
			
			if( mysql_query( "UPDATE rooms SET " . $field . " = '" . $value . "' WHERE id = '" . $roomid . "'" ) )
			{
			
				return true;
				
			}
			else
			{
			
				die( "TMPFOUT" );
				
				return false;
				
			}
			
		}
		
		public function makeletterguess( $letter, $roomid )
		{
		
			$get	=	mysql_query("SELECT guessed_characters FROM rooms WHERE id = '" . $roomid . "'")or die(mysql_error());
		
			$g 		=	mysql_fetch_assoc( $get );
			
			$guessed_characters	=	$g["guessed_characters"];
			
			$this->updateRoom( $roomid, "guessed_characters", ($guessed_characters . $letter) );
		
			$this->setrandomplayerturn( $roomid );
		
			return true;
			
		}
	
	}

?>