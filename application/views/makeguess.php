<h3>Please guess a letter...</h3>

<?php

	foreach( $unguessed as $ung )
	{
		
		?>
		<a style="border:1px solid #000;width:40px;height:35px;padding-top:15px;margin:3px;font-size:20px;text-align:center;float:left;background-color:#fff;text-decoration:none;" href="<?=site_url("Welcome/guessletter/" . $roomid . "/" . $ung)?>">
			<?=$ung?>
		</a>
		<?
		
	}

?>
<!--
<h3 style="clear:both;padding-top:30px;">... or take a chance by guessing the word!</h3>
<a style="border:1px solid #000;padding-left:10px;padding-right:10px;height:35px;padding-top:15px;margin:3px;font-size:20px;text-align:center;float:left;background-color:#fff;text-decoration:none;" href="<?=site_url("Welcome/guessword/" . $roomid . "/")?>">
			I know the word!
		</a>
-->