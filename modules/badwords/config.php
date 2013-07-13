<?php
/* Module Header */
// DANTE MODULE ======
// Name: badwords
// Description: Autokicks users who use certain words
// Author: tharglet
// Version: 1.5
// ====================
//Activated on startup 
$modules[$m] = new Module($m,1); 
$modules[$m]->setInfo(__FILE__); //NO TOUCHY or file will not load properly
/* Commands and Events below here */

$modules[$m]->addCmd('badwords','bad',75,1);
$modules[$m]->addHelp('badwords',"Allows managing of a list of words that gets users kicked (and optionally banned) from a channel. N.b. the bot has to have +kick privs for this module to work (plus demote=1, if banning is required, and promote = [max user rank used] if unbanning); also note that channel owners CANNOT be kicked from their channel, along with anything in a higher privclass than the bot. 
Any options in <i>italics</i> are optional. For the commands with a channel parameter - if none is specified, changes will be applied to all channels. <br>
<sub>badwords add <i>[channel]</i> [word] - adds [word] to the banned words list (case-insensitive).
badwords addcase <i>[channel]</i> [word] - adds [word] to the banned words list (case sensitive)
badwords del <i>[channel]</i> [word] - deletes [word] from the case-insensitive list
badwords delcase <i>[channel]</i> [word] - deletes [word] from case-sensitive the list
badwords list - lists currently set banned words
badwords message <message> - sets the message the user gets on /kick. Use {word} to insert the used word(s) into the kick message
badwords clearwords - clears all banned words
badwords clearmessage - clears the /kick message
badwords listonword [true/false] - if {word} is used by the kick message, this sets if just the first bad word is displayed (false), or all the bad words the user used (true), if they use more than one bad word in a message
badwords ban [true/false/[time in seconds]] - this sets if a user is banned from a channel, when kicked. true bans a user on using a banned word, false does not ban, and a time (in seconds) will ban the user for the set amount of time, before promoting to their original rank. N.b. the bot must be able to ban/demote (and promote if unbanning) to banned for this to work
badwords addpunc - adds in a letter that is considered to be punctuation (i.e. ignored)
badwords delpunc - deletes a letter that is considered to be punctuation (i.e. ignored)
badwords listpunc - lists the letters that are considered to be punctuation (i.e. ignored)</sub>");
$modules[$m]->addEvt('recv-msg','badparse');
$modules[$m]->addEvt('recv-action','badparse');
?>