<h3>Create a new game please!</h3>
				
<p>There are no current games, so you can create one and get some people to play with you!</p>

<form method="post" action="<?=site_url("Welcome/newroom")?>">
	<label for="basic">Choose a name for your room:</label>
	<input type="text" name="name" id="basic" value=""  />
	<div class="ui-block-b"><button type="submit" data-theme="a">Submit</button></div>
</form>