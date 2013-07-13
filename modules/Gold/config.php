<?php
/* Module Header */
// DANTE MODULE ======
// Name: Gold
// Description: The Gold Module
// Author: goldenskl
// Version: 1.0
// ====================
$modules[$m] = new Module($m,1); 
$modules[$m]->setInfo(__FILE__);

$modules[$m]->addCmd("yousearch","yousearch",'default');
$modules[$m]->addHelp("yousearch",'"yousearch [query]" Searches the arguments you sent in youtube and displays 5.');

$modules[$m]->addCmd("dvid","dvid",'default');
$modules[$m]->addHelp("dvid","'dvid [youtube video's url]' Return link from which you can download a video from youtube.");

$modules[$m]->addCmd("coin","coin",'default');
$modules[$m]->addHelp("coin",'"coin [heads\tails]" Just a little game i did while i was bored.');

$modules[$m]->addCmd("scrm","scrm",'default');
$modules[$m]->addHelp("scrm",'"scrm [message]" its just like the command say but it scrambles the message it sends.');

$modules[$m]->addCmd("count","count",'default');
$modules[$m]->addHelp("count",'"count [message]" Returns the number of characters in the message');

$modules[$m]->addCmd("damnit","damnit",'default');
$modules[$m]->addHelp("damnit",'Returns a funny quote from the dAmnquotes database here on dA.');

//$modules[$m]->addCmd("damnquotes","damnquotes",'default');
//$modules[$m]->addHelp("damnquotes",'Returns a funny quote from damnquotes.com');

$modules[$m]->addCmd("jokeplz","jokeplz",'default');
$modules[$m]->addHelp("jokeplz",'Returns a funny joke from one of the internets biggest joke database. Very funny too.');

$modules[$m]->addCmd("kr","kr",'default');
$modules[$m]->addHelp("kr",'Kick random. Randomly kicks one person in the room.');

$modules[$m]->addCmd("rev","rev",'default');
$modules[$m]->addHelp("rev",'"rev [message]" Just like say, but returns the message backwards');

$modules[$m]->addCmd("stb","stb",'default');
$modules[$m]->addHelp("stb",'The game of spin the bottle.');
?>

