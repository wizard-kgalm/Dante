<?php
switch($args[1]){
	case 'cmds':
	case 'commands':
	  $cmds="<abbr title='{$f}'></abbr><b>Dios Command List</b><br>".
	  "&middot; urbandict &middot; rdev &middot; rdeviant &middot; spamfilter &middot; thumb &middot; gallery &middot; spell &middot; feature &middot; recent &middot;<br>".
	  "&middot; sys &middot; last.fm &middot; botpad &middot; devinfo &middot; ";
	  $dAmn->say($cmds,$c);
	break;
	case 'about':
	   switch($args[2]){
		   case 'cmds':
		   case 'commands':
		       $dAmn->say("<abbr title='{$f}'></abbr><b>About Commands (13)</b><sub><br>".
		       "- <b>urbandict</b> - Urbandict is a script that searches for definitions of words, terms, etc. from <a href=\"http://www.urbandictionary.com/\"><b>UrbanDictionary.com</b></a>.<br>".
		       "- <b>rdev</b> - Displays a random deviation.<br>".
		       "- <b>rdeviant</b> - Displays a random deviant.<br>".
		       "- <b>spamfilter</b> - Helps protect chatroom from spamming. It is highly configurable to meet the needs of your chatroom, you can set ban/silence time for users and configure message quantity, repetitions and much more.<br>".
		       "- <b>thumb</b> - Displays information on a deviation using the thumb number as input.<br>".
		       "- <b>gallery</b> - Browse, search and display deviations from a deviant's gallery.<br>".
		       "- <b>sys</b> - Open files and scan directories with this handy script.<br>".
		       "- <b>last.fm</b> - View your last.fm profile and other deviants.<br>".
		       "- <b>botpad</b> - You bot's Notepad.<br>".
		       "- <b>devinfo</b> - View information on a deviant from their dA page.<br>".
		       "- <b>spell</b> - Spell a word to see if its correct or not.<br>".
		       "- <b>feature</b> - Feature deviations of deviants in your chatroom.<br>".
		       "- <b>recent</b> - View a deviant's recently submitted deviations.</sub>",$c);
		   break;
		   default:
		      $dAmn->say("<abbr title='{$f}'></abbr><a href=\"http://botdom.com/wiki/Dios\"><b>Dios</b></a> v2.0 by :devMagaman:",$c);
	   }	  
	break;    
	default:
	 $dAmn->say("<abbr title='{$f}'></abbr><a href=\"http://botdom.com/wiki/Dios\"><b>Dios</b></a> v2.5 by :devMagaman:",$c);
}
?>