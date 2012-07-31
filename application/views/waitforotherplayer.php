<script type="text/javascript">
		
		var snd = new Audio('<?=site_url( "../datafiles/sound.wav" )?>'); // buffers automatically when created

		
		function update()
		{
			
			$.ajax({
			  url: "<?=site_url( "Welcome/ajaxWaitingForGuess/" . $roomid )?>"
			}).done(function ( data ) {
			
				if( data == "stop" )
				{
					
					window.open( '<?=site_url( "Welcome/room/" . $roomid )?>', '_self' );
					
				}
				else
				{
				
					if( data !== $("#mainword").html() )
					{
					
						snd.play();
						
					}
					
					 $("#mainword").html( data );
					 
				}
			});
			
			setTimeout( "update();", 2000 );
			
		}
		
		setTimeout( "update();", 2000 );
		
</script>
<p style="text-align:center;">
	We're waiting for an other player to guess... don't worry, we'll tell you when it's your turn!
	<br /><br />
	<img src="<?=site_url("../img/ajax-loader.gif")?>" alt="Loading..." />
</p>