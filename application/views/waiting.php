<script type="text/javascript">
		
		function update()
		{
			
			$.ajax({
			  url: "<?=site_url( "Welcome/ajaxAmountOfUsersWaiting/" . $roomid )?>"
			}).done(function ( data ) {
			
				if( data == "stop" )
				{
					
					window.open( '<?=site_url( "Welcome/room/" . $roomid )?>', '_self' );
					
				}
				else
				{
				
					 $("#waitingcnt").html( data );
					 
				}
			});
			
			setTimeout( "update();", 5000 );
			
		}
		
		setTimeout( "update();", 2000 );
		
</script>
<p>There are <span id="waitingcnt"><?=$waiting?></span> users waiting for the game to start...</p>
<br />
<br />
<?php

	if( $admin == true )
	{
		
		?>
		<a href="<?=site_url("Welcome/startroom/" . $roomid)?>" data-role="button">Start the game.</a>
		<?
		
	}
	else
	{
		
		?>
		<p>
			<i>
				Waiting for the admin to start the game...
			</i>
		</p>

		<?
		
	}

	if( $answer == "" )
	{
		
		// New game..
		
	}
	else if( $last_winner == "Player 0" )
	{
		
		?>
		<br />
		<p>
			<h3>The last game was lost. Boo-hoo!</h3>
			<p>The one and only correct answer was: <strong><?=$answer?></strong>.</p>
		</p>
		<?
		
	}
	else
	{
		
		?>
		<br />
		<p>
			<h3>The last winner was: <u><?=$last_winner?></u> Congrats!</h3>
			<p>The one and only correct answer was: <strong><?=$answer?></strong>.</p>
		</p>
		<?
		
	}

?>
<!-- Begin http://chatwing.com chatbox --><iframe src="http://chatwing.com/chatbox/b70c6c532527d6457ff1831fe357eb67" width="100%" height="400" frameborder="0" scrolling="no">Please contact us at support[at]chatwing.com if you cannot embed the code</iframe><!-- End http://chatwing.com chatbox -->