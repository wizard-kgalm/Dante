<?php
// DANTE SCRIPT ======
// Name: Steps to getting a bot
// Description: Just because n00bs don't understand
// Author: SubjectX52873M
// Version: 0.4
// Priv: 0
// Help: "botstep [#/bots/define/disclaimer]"
// ====================
$step = $args[1];

if ( empty( $args[2] ) ) $person = $from;
else $person = $args[2];

switch ( $step ) {
  case '#':
		$say = "$f I think you are taking the help message too literally, # by itself usually stands for a number, like 1 or 2. Try {$tr}botstep 1";
		break;
	case '1':
		$say = "<abbr title = ' $person '></abbr> <b><u>Getting a PHP Bot: Step One</u></b><br>" .
		" Read disclaimer first if you haven't ({$tr}botstep disclaimer)<br><br>" .
		'Then see if you can follow the instructions here http://botdom.com/wiki/NoodleBot/Readme but do not try to install anything yet.<br><br>' .
		'If you think you understand all of that you can continue to step 2. ' . "({$tr}botstep 2)";
		break;
	case '2':
		$say = "<abbr title = ' $person '></abbr> <b><u>Getting a PHP Bot: Step Two</u></b><br>" .
		'Get the <b><a href="http://www.php.net/get/php-5.2.5-Win32.zip/from/this/mirror" title="PHP 5.2.5">latest version of PHP for Windows</a></b> here. If you need other versions of PHP, those are present at <a href="http://www.php.net/downloads.php" title="">PHP.net Downloads</a>.<br><br>' .
		'Extract the zip archive to <i>C:\php</i><br><br>' .
		'Once you have the files extracted move to step 3. ' . "({$tr}botstep 3)";
		break;
	case '3':
		$say = "<abbr title = ' $person '></abbr> <b><u>Getting a PHP Bot: Step Three</u></b><br>".
		'Find the file <i>php.ini-recommended</i> in <i>C:\php</i> and open it with Windows Notepad.<br><br>' .
		'Find the line <code>;extension=php_sockets.dll</code> and change it to <code>extension=php_sockets.dll</code> (i.e. remove the semicolon)<br>' .
		'Find the line with <code>extension_dir = </code> and change it to <code>extension_dir = "C:\php\ext"</code> (<i>C:\php</i> being the location you installed PHP)<br><br>' .
		'Save the file as <i>php.ini</i> and move to step 4.' . "({$tr}botstep 4)";
		break;
	case '4':
		$say = "<abbr title = ' $person '></abbr> <b><u>Getting a PHP Bot: Step Four</u></b><br>" .
		'Grab a bot of your choice and extract to a subfolder of <i>C:\php</i> (Or a different location if you wish.)<br>' .
		'Bots are listed on the front page of the <a href="http://botdom.com/wiki/" title="">Botdom Wiki</a>.<br><br>' .
		'Most bots have a readme or setup file. Look for a text file called <code>readme</code> or <code>install</code><br><br>' .
		'You will need to read that file to setup your bot.<br><br>' . 
		'If your bot is now working, you\'ll have to register it for dAmn by clicking the link below:<br>' . 
		'<b>FAQ #795:</b> <a href="http://help.deviantart.com/795/" title="">How do I register my bot on the chat network?</a>';
		break;
	case "define":
		$say = "<abbr title = ' $person '></abbr><sub><b>What is a bot?</b><br><br>".
		"A bot is a program that sits in a channel around the clock. It looks just like a normal person on the user list, but is usually idle until it's called upon to perform a particular function. A bot can perform many useful functions, such as logging channel events, providing frequently requested information, hosting trivia games, and so on.<br/><br/>" .
		"Code to run your own dAmn bot can be found through :devdAmnhack: or #Botdom. However, please do not ask either teams for assistance in setting up your bot. The #dAmnhack & #Botdom channels are unofficial channels.<br/><br/>" .
		"Also note that deviantART does not officially support bots, and as such, we cannot help you with setting up or running your bot.</sub>";
		break;
	case "disclaimer":
		$say = "<abbr title = ' $person '></abbr><sub><b>This is the disclaimer for the #Botdom chatroom:</b><br>".
		"<ul><li>Installing, using, and running a bot requires a certain level of knowledge which cannot be given effectively via a chatroom, such as this.</li>" .
		"<li>Therefore, before you even think about getting a bot, we suggest you at least become familiar with filesystems, paths, and learn the basics of the language the bot is written in.</li>" . 
		"<li><b>Rule of thumb:</b> If you cannot understand the instructions supplied with the bot (this applies for any software), then it is either too advanced for you or it has not been designed to be used by others.</li>" .
		"</ul> We will not give any bot-related help if you do not satisfy the above requirements.</sub>";
		break;
	case "bots":
		$say = 'For the list of bots, check the <a href="http://botdom.com/wiki/Main_Page">Botdom Wiki Main Page</a> or the #Botdom title & topic.';
		break;
	default:
		$say = "So you want to get a bot. Type {$tr}botstep [#] for the different steps. Start with " . "{$tr}botstep 1";
		break;
}
$dAmn->say( $say, $c );
?>
