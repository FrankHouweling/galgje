<h3>Join a current game:</h3>

<?php

	foreach( $games as $game )
	{
		
		?>
		<a href="<?=site_url("Welcome/room/" . $game["id"])?>" data-role="button"><?=$game["name"]?></a>
		<?
		
	}

?>

<h3>Or create a new one:</h3>
<p>Create a new game and share the link with others to play with you!</p>

<form method="post" action="<?=site_url("Welcome/newroom")?>">
	<label for="basic">Choose a name for your room:</label>
	<input type="text" name="name" id="basic" value=""  />
	<div class="ui-block-b"><button type="submit" data-theme="a">Submit</button></div>
</form>