<?php

// DANTE MODULE ======
// Name: Games
// Description: Various games,  some from Noodlebot,  some written by SubjectX52873M
// Author: SubjectX52873M
// Version: 0.4
// ====================
//Activated on startup 
$modules[$m] = new Module($m, 1 ); 
//Deactivated on startup
//$modules[$m] = new Module($m, 0 ); //Uncomment to use this option and then comment out the one about.
$modules[$m]->setInfo(__FILE__ ); //NO TOUCHY or file will not load properly
/* Commands and Events below here */
// Various games from various bots,  some are original

// Dante (Idea from my DDbot extension for noodlebot which has disappeared on me. :(  )
$modules[$m]->addCmd( 'roll', 'roll', 'd', 1 );
$modules[$m]->addHelp( 'roll', '"roll (y)d(c)" Roll a die of x number of sides y number of times.' );

//Noodlebot 
$modules[$m]->addCmd( 'word', 'word', 'd', 1 );
$modules[$m]->addHelp( 'word', '"word (Number < 50)" Create a random word.' );

//Tarbot
$modules[$m]->addCmd( 'fnord', 'fnord', 'd', 1 );
$modules[$m]->addHelp( 'fnord', '"fnord" random sentence,  often funny.' );

//Noodlebot 
$modules[$m]->addCmd( 'fortune', 'fortune', 'd', 1 );
$modules[$m]->addHelp( 'fortune', '"fortune" Open a fortune cookie.' );

//Noodlebot 
$modules[$m]->addCmd( '8ball', '8ball', 'd', 1 );
$modules[$m]->addHelp( '8ball', '"8ball [question]" Ask a question of the almighty 8ball.' );

$modules[$m]->addCmd( '8ans', '8ball', 'd', 1 );
$modules[$m]->addHelp( '8ans', $tr.'8ans add [response] adds a response to the list of 8ball responses.<br>'.$tr.'8ans del <i>ID#<i> where ID# is a number such as 1 or 2 and deletes the response stored on that ID#.<br>'.$tr.'8ans list shows a list of the responses along with their ID#.' );

//Noodlebot 
//Commented out because of spam issues.
$modules[$m]->addCmd( 'big', 'big', 'd', 1 );
$modules[$m]->addHelp( 'big', '"big [text]" BIG TEXT' );

//Dante
$modules[$m]->addCmd( 'dsay', 'dsay', 25, 1 ); 
$modules[$m]->addHelp( 'dsay', '"dsay [number] [msg]" Say [msg] in [number] of seconds.' );

//Noodlebot 
$modules[$m]->addCmd( 'vend', 'vend', 'd', 1 ); 
$modules[$m]->addHelp( 'vend', '"vend [something]" Vending machine game'."<br>".
'"vend [add/del/list]" manage the the machine\'s items.'  );
?>