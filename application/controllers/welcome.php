<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	
	private $guestid;
	private $roomid;
	private $room;
	
	function __construct()
	{
		
		parent::__construct();
		
		// Load helpers
		
		$this->load->library('session');
		$this->load->helper('url');
		$this->load->model( "games" );
		$this->load->model( "users" );
		// Generate userid...
		
		if( $this->session->userdata('guestid') == false )
		{
			
			$id	=	$this->users->adduser();
			
			$this->session->set_userdata('guestid', $id);
			
			$this->guestid	=	$id;
			
		}
		else
		{
			
			$this->guestid	=	$this->session->userdata('guestid');
			
		}

		$this->games->cleanup();

		// Set current game..
		
		if( $this->uri->segment( 3 ) )
		{
			
			$this->roomid	=	$this->uri->segment(3);
			
			// Get the room..
			
			$room			=	$this->games->getRoom($this->roomid);
			$this->room 	=	$room[0];
			
			$this->users->setCurrentRoom( $this->guestid, $this->roomid );
			
			// Clean-up old rooms.
			$this->users->cleanup( $this->guestid, $this->roomid );
			
		}
		else
		{
			
			// Clean-up old rooms.
			$this->users->cleanup( $this->guestid, 0 );
				
		}
		
	}
	
	public function index()
	{

		$this->load->view( "header", array("pagetitle" => "Home") );
		
		// Different views for when there is already a game busy or not...
		
		if( $this->games->getCurrentRooms() == false )
		{
			
			// No current game running...
			
			$this->load->view( "no-current-games" );
			
		}
		else
		{
			
			// Show current games...
			
			$this->load->view( "display-current-games", array( "games" => $this->games->getCurrentRooms() ) );
			
		}
		
		$this->load->view( "footer" );

	}
	
	public function newroom()
	{
		
		if( $this->input->post( "name" ) == false )
		{
			
			header( "Location: ../" );
			
		}
		else
		{
			
			$id	=	$this->games->addRoom( $this->input->post("name") , $this->guestid );
		
			$this->load->view( "header", array("pagetitle" => $this->input->post( "name" ) . " - My New Room") );
		
			$this->load->view( "share-new-room", array( "id" => $id) );
			
			$this->load->view( "footer" );
			
		}
		
	}
	
	public function room()
	{

		if( !empty($this->room) )
		{
			
			if( $this->room["current_mode"] == "0" )
			{
				
				// Still waiting for players..
				
				header( "Location: ../../Welcome/waitroom/" . $this->roomid );
				
			}
			else if( $this->room["current_mode"] == "1" )
			{

				
				// Small updates...
				
				if( $this->room["guest_guessing"] == 0 )
				{
					
					$this->games->updateRoom( $this->roomid, "guest_guessing", $this->room["admin_id"] );	//	Admin always guesses first...
					
					$this->room["guest_guessing"]	=	$this->room["admin_id"];
					
				}
				
				
				header( "Location: ../../Welcome/guessing/" . $this->roomid );
				
			}
			else
			{
				
				die( "FUUUU!" );
				
			}
			
		}
		else
		{
		
			header( "Location: ../../" );
			
		}
		
	}
	
	public function waitroom()
	{

		if( $this->room !== false )
		{
			
			
			if( $this->room["current_mode"] == "0" )
			{
				
				if( $this->room["last_winner"] == $this->guestid )
				{
					
					$lw	=	"You!";
					
				}
				else
				{
					
					$lw	=	"Player " . $this->room["last_winner"];
					
				}
				
				$this->load->view( "header", array( "pagetitle" => $this->room["name"] . " - Waiting for more players" ) );
				
				$this->load->view( "waiting", array("waiting" => $this->games->amountOfPlayers( $this->roomid ), "roomid" => $this->roomid, "admin" => $this->games->isRoomAdmin( $this->guestid, $this->roomid ), "last_winner" => $lw , "answer" => $this->room["current_word"] ) );
				
				$this->load->view( "footer" );
				
			}
			else
			{
				
				// Not waiting any more...
				
				header( "Location: ../../Welcome/room/" . $this->roomid );
				
			}
			
		}
		else
		{
		
			header( "Location: ../../" );
			
		}
		
	}
	
	public function ajaxAmountOfUsersWaiting()
	{
	
		if( $this->room["current_mode"] !== "0" )
		{
			
			echo "stop";
			
		}
		else
		{
			
			echo $this->games->amountOfPlayers( $this->roomid );
			
		}
		
	}
	
	public function ajaxWaitingForGuess()
	{
		
		if( $this->room["current_mode"] == "0" OR $this->room["guest_guessing"] == $this->guestid )
		{
			
			echo "stop";
			
		}
		else
		{
			
			// Make an array of used characters... which is a LOT easyer for later..
			
			$gc	=	$this->room["guessed_characters"];
			
			$nr	=	0;
			
			$guessed_characters	=	array();
				
			while( isset( $gc{$nr} ) ) 
			{
	
			 	$guessed_characters[$nr] = $gc{$nr};
				    
			 	$nr++;
				    
			}
			
			// Make the word..
			
			$fullword	=	$this->room["current_word"];
			$display	=	"";
			$nr			=	0;
			
			while( isset( $fullword{$nr} ) ) 
			{
			
				if( in_array($fullword{$nr}, $guessed_characters) )
				{
					    
					$display	.=	$fullword{$nr};
	
				}
				else
				{
					    
					$display	.=	".";
					    
				}
				    
				$nr++;
				    
			}

			echo $display;
			
		}
		
	}
	
	public function startroom()
	{
		
		if( $this->games->isRoomAdmin( $this->guestid, $this->roomid ) )
		{
			
			$this->games->startGame( $this->roomid );
			
			header( "Location: ../../Welcome/room/" . $this->roomid );
			
		}
		else
		{
			
			die( "No rights to start room." );
			
		}
		
	}
	
	public function guessletter()
	{
		
		if( $this->room["guest_guessing"] == $this->guestid )
		{
		
			// First look if this letter isnt the last one..
			
			$allletters	=	$this->room["guessed_characters"] . $this->uri->segment(4);
			
			$word		=	$this->room["current_word"];
			
			$cnt	=	0;
			
			for( $i = 0; $i < strlen($word); $i++ )
			{
				
				if( strpos($allletters, $word{ $i }) !== false )	//	This letter is in the guessed characters list..
				{
					
					$cnt ++;
					
				}
				
			}
			
			if( $cnt == strlen($word) )
			{
			
				// THE USER WON THE GAME! YOOHOO!
				$this->games->updateroom( $this->roomid, "current_mode", 0 );
				$this->games->updateroom( $this->roomid, "last_winner", $this->guestid );
				$this->games->updateroom( $this->roomid, "guessed_characters", "");
			
			}
			else
			{
				
				// Process..
				
				$this->games->makeletterguess( $this->uri->segment(4), $this->roomid );
				
				
			}
		
		}
	
		header( "Location: ../../../Welcome/room/" . $this->roomid );
		
	}
	
	public function guessing()
	{
	
		if( empty( $this->room ) )
		{
			
			header( "Location: ../../Welcome/" );
			
			die();
			
		}
	
		$gc	=	$this->room["guessed_characters"];
		
		// Make an array of used characters... which is a LOT easyer for later..
			
		$nr	=	0;
			
		$guessed_characters	=	array();
			
		while( isset( $gc{$nr} ) ) 
		{

		 	$guessed_characters[$nr] = $gc{$nr};
			    
		 	$nr++;
			    
		}
			
		// An array of unused characters...
			
		$unguesed_characters	=	array();
			
		foreach( range("a","z") as $c )
		{
			
			if( !in_array( $c, $guessed_characters ) )
			{
					
				$unguesed_characters[]	=	$c;
					
			}
				
		}
			
		// Create the to be displayed word thingie.. (fr guessed, word is frank will display fr...)
		
		$display	=	"";
		$fullword	=	$this->room["current_word"];
		
		// Count the amount of wrongly guessed characters
		$amntwronglyguessed	=	0;
		
		foreach( $guessed_characters as $ltr )
		{
			
			if( strpos( $fullword, $ltr ) == false )
			{
			
				$amntwronglyguessed++;
				
			}
			
		}
			
		$nr	=	0;
		
		// Stop the game if there are too many wrong guesses...
		
		if( $amntwronglyguessed > 6 )
		{
			
			// THE USER LOST THE GAME :(
			$this->games->updateroom( $this->roomid, "current_mode", 0 );
			$this->games->updateroom( $this->roomid, "last_winner", 0 );	//	No last winner...
			$this->games->updateroom( $this->roomid, "guessed_characters", '');
			
			header( "Location: ../../Welcome/room/" . $this->roomid );
			
		}
		else
		{
			
			while( isset( $fullword{$nr} ) ) 
			{
			
				if( in_array($fullword{$nr}, $guessed_characters) )
				{
					    
					$display	.=	$fullword{$nr};
	
				}
				else
				{
					    
					$display	.=	".";
					    
				}
				    
				$nr++;
				    
			}
			
			if( $this->room["guest_guessing"] == $this->guestid )
			{
				
				$this->load->view( "header", array( "pagetitle" => $this->room["name"] . " - You can take a guess" ) );
				
				$this->load->view( "wordview", array( "word" => $display, "falseguessed" => $amntwronglyguessed ) );
				
				$this->load->view( "makeguess", array("guessed" => $guessed_characters, "unguessed" => $unguesed_characters, "word" => $this->room["current_word"], "roomid" => $this->roomid ) );
				
				$this->load->view( "footer" );
							
			}
			else
			{
				$this->load->view( "header", array( "pagetitle" => $this->room["name"] . " - An other player is guessing.." ) );
						
				$this->load->view( "wordview", array( "word" => $display, "falseguessed" => $amntwronglyguessed ) );
						
				$this->load->view( "waitforotherplayer", array( "roomid" => $this->roomid ) );
						
				$this->load->view( "footer" );
				
			}
			
		}
		
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */