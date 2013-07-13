<?php

if ( $args[1]=='' ) {
	$dAmn->say( '"irpg [user]" Gets iRPG scores for [user]', $c );
} else {

$irpguser = strtolower($args[1]);
$startsearch 	= microtime( true );
$userpage = file_get_contents("http://irpg.lonelypker.net/users.php?user=".$irpguser);

$scontent = explode('</div>', $userpage);

if (substr_count($scontent[5], "It seems this user does not play iRPG")==0)
	{
 		//$dAmn->say(htmlspecialchars($scontent[5])." $|$|$ ".htmlspecialchars($scontent[6]), $c);
 		//$dAmn->say(, $c);
 		$y=0;
 		$message = $scontent[5].$scontent[6];
 		
 		$message = str_replace('<div class="post"><h2 class="title"><a href="#">Items</a></h2>', "<b>{$args[1]}'s Items</b>", $message);
 		$message = str_replace('<!-- end ads -->', "", $message);
 		$message = str_replace('<!-- start content -->', "", $message);
 		$message = str_replace('<!-- end ads -->', "", $message);
 		$message = str_replace('<div id="content">', "", $message);
 		$message = str_replace("<h2 class='title'><a href='#'>{$irpguser}'s Stats</a></h2>", "<b>:icon{$irpguser}: :dev{$args[1]}:'s Stats</b>
		 ", $message);
 		$message = str_replace('<div class="post">', "", $message);
		$message = str_replace("<img src=\"images/irpg-bot.png\" alt=\"{$irpguser}'s icon\" />", "", $message);
  		$top100 = file_get_contents("http://irpg.lonelypker.net/");
if (substr_count($top100, $args[1] ))
	{
		$message.= "
		<i>{$args[1]} is among TOP 100 idlers!</i>"; 
	}
  		$topp100 = file_get_contents("http://irpg.lonelypker.net/penalties.php");
if (substr_count($topp100, $args[1] ))
	{
		$message.= "
		<i>{$args[1]} is among 100 most penalized idlers!</i>"; 
	}
$tiems = microtime( true ) - $startsearch;
$tiems = round( $tiems, 3 );
		$message.= "
		<i>Search took {$tiems} seconds</i>";

		$dAmn->say($message, $c);
	}
else
	{
		$dAmn->say("It seems ".$args[1]." does not play iRPG", $c);
	}
	}
?>