<?php
// DANTE MODULE ======
// Name: Core Commands
// Description: This holds the basic commands any bot needs
// Author: SubjectX52873M
// Version: 1.1
// Notes: Added back up and restore capabilities. Modified a few commands.
// ====================
//Activated on startup 
$modules[$m] = new Module( $m, 1 ); 
//Deactivated on startop
//$modules[$m] = new Module( $m, 0 ); //Uncomment to use this option and then comment out the one above.
$modules[$m]->setInfo( __FILE__ ); //NO TOUCHY or file will not load properly
/* Commands and Events below here */

//EVENTS
$modules[$m]->addEvt( 'kicked', 'rejoin' ); //Autorejoin stuff. Modulized because I can.
$modules[$m]->addEvt( 'whois', 'whois' ); //Whois
//Symbols
$modules[$m]->addCmd( 'help', 'syscom', 0, 1 );

//A
$modules[$m]->addCmd( 'about', 'syscom', 0, 1 );
$modules[$m]->addHelp( 'about', '"about (all/system/uptime)" Shows infomation about the bot' );

$modules[$m]->addCmd( 'access', 'syscom', 99, 1 );
$modules[$m]->addHelp( 'access', 'access [command] [level]' );

$modules[$m]->addCmd( 'admin', 'syscom', 99, 1 );
$modules[$m]->addHelp( 'admin', 'See http://help.deviantart.com/540/ ([FAQ 540]) It works just like the dAmn command.' );

$modules[$m]->addCmd( 'autojoin', 'chan', 75, 1 );
$modules[$m]->addHelp( 'autojoin', '"autojoin [add/del/list] [room]" Manage the auto-join list.' );

//B
$modules[$m]->addCmd( 'ban', 'syscom', 75, 1 );
$modules[$m]->addHelp( 'ban', '"ban (#room) [user]"' );

$modules[$m]->addCmd( 'backup', 'syscom', 99, 1 );
$modules[$m]->addHelp( 'backup', "{$tr}backup [file/all] <i>yes</i>. Backs up [file] to the back up from the original file. If [all] instead of [file], all of the config will be backed up." );

//C
$modules[$m]->addCmd( 'chat', 'syscom', 0, 1 );
$modules[$m]->addHelp( 'chat', '"chat [user]" Starts a pchat conversion with [user] (Requires dAx or a third party client with pchat ablity)' );

$modules[$m]->addCmd( 'commands', 'more', 0, 1 );
$modules[$m]->addHelp( 'commands', '"commands (all/details)" List commands on the bot.' );

$modules[$m]->addCmd( 'credits', 'syscom', 0, 1 );
$modules[$m]->addHelp( 'credits', 'Shows infomation about the bot' );

$modules[$m]->addCmd( 'reset', 'syscom', 99, 1 );

$modules[$m]->addCmd( 'ctrig', 'ctrig', 99, 1 );
$modules[$m]->addHelp( 'ctrig', '"ctrig [trigger] confirm" Allow you to change the bot\'s trigger.<br/> You need to type "confirm" after the new trigger to set it.' );
//D
$modules[$m]->addCmd( 'demote', 'syscom', 75, 1 );
$modules[$m]->addHelp( 'demote', '"demote (#room) [user] (privclass)"' );

$modules[$m]->addCmd( 'do', 'syscom', 99, 1 );
$modules[$m]->addHelp( 'do', '"do (#room) [user] [message]" sends message to the message parser. It does not cause an event but the AI works<br>' .
'#room is optional and must have # in front of the room or is fails to work<br>' .
'To do a command just use the bot\'s trigger and the command in the message as normal' );

//E
//F
$modules[$m]->addCmd( 'flood', 'syscom', 75, 1 );
$modules[$m]->addHelp( 'flood', 'Manages the bot\'s defensive flood protection. (This is designed to defend the bot from spamming and using it as a spam tool. It does not protect a room. )');
//G
//H
$modules[$m]->addCmd( 'help' ,'syscom', 0, 1 );
$modules[$m]->addHelp( 'help', '"help [command]" for help on command' );
//I
$modules[$m]->addCmd( 'ignore', 'syscom', 75, 1 );
$modules[$m]->addHelp( 'ignore', '"ignore [user]" adds the user to the ignore list.<br>' .
'"ignore !list" displays the ignore list<br>' .
'"ignore !clearall" clears the ignore list' );

//J
$modules[$m]->addCmd( 'join','syscom', 75, 1 );
$modules[$m]->addHelp( 'join', '"join [room]" make the bot join room' );

//K
$modules[$m]->addCmd( 'kick', 'syscom', 75, 1 );
$modules[$m]->addHelp( 'kick', '"kick ( #room ) [user]"' );

//L
$modules[$m]->addCmd( 'list', 'syscom', 75, 1 );
$modules[$m]->addHelp( 'list', '"list [#room]" listing the users in #room' );

//M
$modules[$m]->addCmd( 'modules', 'more', 75, 1 );
$modules[$m]->addHelp( 'modules', '"modules" Manage the modules on the bot' );

//N
//O
//P
$modules[$m]->addCmd( 'part', 'syscom', 75, 1 );
$modules[$m]->addHelp( 'part', '"part ( room )" If room is absent, bot leaves current room.' );

$modules[$m]->addCmd( 'ping', 'more', 25, 1 );
$modules[$m]->addHelp( 'ping', '"ping" Ping the bot to see how much it lags. (More than 500 ms is bad)' );

$modules[$m]->addCmd( 'priv', 'syscom', 99, 1 );
$modules[$m]->addHelp( 'priv', '"priv [commands/errors/guests/trigcheck] [0-99]" Set the default privs for things.' );

$modules[$m]->addCmd( 'promote', 'syscom', 75, 1 );
$modules[$m]->addHelp( 'promote', '"promote (#room) [user] (privclass)"' );

//Q
$modules[$m]->addCmd( 'quit', 'more', 99, 1 );
$modules[$m]->addHelp( 'quit', "{$tr}quit <i>yes</i>. Shuts down the bot. If yes, will also perform a config back up before quitting." );

//R
$modules[$m]->addCmd( 'reload', 'syscom', 99, 1 );
$modules[$m]->addHelp( 'reload', '"reload [all/config/modules/scritps]"' );

$modules[$m]->addCmd( 'restore', 'syscom', 99, 1 );
$modules[$m]->addHelp( 'restore', "{$tr}restore [file] <i>yes</i>. Restores [file] from the back up to the original file. Beware, the file may be out of date. It is recommended that you back up the config regularly." );

$modules[$m]->addCmd( 'restart', 'more', 99, 1 );
$modules[$m]->addHelp( 'restart', "{$tr}restart <i>yes</i>. Restarts the bot. If yes, will also perform a config back up before restarting." );
//S
$modules[$m]->addCmd( 'say', 'syscom', 75, 1 );
$modules[$m]->addHelp( 'say', '"say ( #room ) [text]"' );

$modules[$m]->addCmd( 'scripts', 'syscom', 50, 1 );
$modules[$m]->addHelp( 'scripts', '"scripts [reload/list]"' . "\n" .
'"scripts [scriptname] info"' );
//T
$modules[$m]->addCmd( 'title', 'syscom', 75, 1 );
$modules[$m]->addHelp( 'title', '"title (#room) [addend, addfro, del, set]" Leave options blank to print the title of the room.' );

$modules[$m]->addCmd( 'topic', 'syscom', 75, 1 );
$modules[$m]->addHelp( 'topic', '"topic (#room) [addend, addfro, del, set]" Leave options blank to print the topic of the room.' );


//U
$modules[$m]->addCmd( 'unban', 'syscom', 75, 1 );
$modules[$m]->addHelp( 'unban', '"unban (#room) [user]"' );

$modules[$m]->addCmd( 'unignore', 'syscom', 75, 1 );
$modules[$m]->addHelp( 'unignore', '"unignore [user]" removes the user from the ignore list.' );

$modules[$m]->addCmd( 'user', 'syscom', 99, 1 );
$modules[$m]->addHelp( 'user', '"user add [user] [pc name/id]" adds a user to the bot\'s user list<br>' .
'"user del [user]" removes the user from the bot\'s user list<br>' .
'"user list" diplays the bot\'s user list<br>' .
'"user addprivclass [name] [level]" creates a new privclass<br>' .
'"user renprivclass [level] [new name]" renames a privclass<br>' .
'"user delprivclass [name]" deletes a privclass' );

//V
//W
$modules[$m]->addCmd( 'whois', 'syscom', 0, 1 );
$modules[$m]->addHelp( 'whois', '"whois [user]"' );

//X
//Y
//Z
?>