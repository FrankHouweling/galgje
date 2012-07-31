<h3>Your new room is ready!</h3>

<p>Now share it on social media and go in your room.</p>

<a href="http://www.facebook.com/sharer.php?u=<?=site_url("Welcome/room/" . $id)?>&t=Come play in my 'galgje'-room!" target="_blank"><img src="<?=site_url("../img/facebook.png")?>" /></a>

<a href="http://twitter.com/home?status=Come play in my 'galgje'-room! <?=site_url("Welcome/room/" . $id)?>" target="_blank"><img src="<?=site_url("../img/twitter.png")?>" /></a>

<p>Or share this URL:</p>

<input type="text" name="name" id="basic" value="<?=site_url("Welcome/room/" . $id)?>"  />
		
<a href="<?=site_url("Welcome/room/" . $id)?>" data-role="button">Go into the gameroom.</a>
	