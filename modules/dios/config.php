<?php
/* Module Header */
// DANTE MODULE ======
// Name: Dios
// Description: Type {tr}dios commands for list of commands.
// Author: Magaman
// Version: 2
// ====================
$modules[$m] = new Module($m,1); 
$modules[$m]->setInfo(__FILE__); 

//!gallery
$modules[$m]->addCmd('gallery','gallery','d',1);
$modules[$m]->addHelp('gallery',"Usage<li><sub><b>gallery</b> Show 5 Random deviations from your page</sub></li><li><sub><b>gallery [deviant]</b> Show 5 Random deviations from a [deviant]'s page.</sub></li><li><sub><b>gallery [deviant] popular [#]</b> Show [#] of [deviant]'s popular deviations. If [#] is not inputted, the default is 5.</sub></li><li><sub><b>gallery [deviant] [page/browse] [page #] [#]</b> Browse or view a page in a [deviant]'s gallery. [page #] is the page you want to view. [#] is the amount of deviations you want shown. If [page #] is not unputted, page 1 is viewed. If [#] is not inputted, the default is 5.</sub></li><li><sub><b>gallery [deviant] search \"[#] [search term]\" or \"[search term]\"</b> Search a [deviant]'s gallery for the [search term]. [#] is the amount of results you want displayed else the default is 10.</sub></li><li><sub><b>gallery [deviant] prints [page #] [#]</b> Browse or view a page in a [deviant]'s print gallery. [page #] is the page you want to view. [#] is the amount of prints you want shown. If [page #] is not unputted, page 1 is viewed. If [#] is not inputted, the default is 5.</sub>");

//!recent
$modules[$m]->addCmd('recent','recent','d',1);
$modules[$m]->addHelp('recent',"recent [deviant]");

//!feature
$modules[$m]->addCmd('feature','feature','d',1);
$modules[$m]->addHelp('feature',"<br><sub><b>feature add [thumb number] [featured by]</b> ([feature by] is Optional if its you doing the feature)<br><b>feature del [thumb number]</b> (Delete a thumb)<br><b>feature total</b> (View total features)<br><b>feature list</b>(View all features with their thumb number)<br><b>feature timer [#]</b> (# must be numeric/ This sets the time for feature to be displayed e.g. every 10 seconds )<br><b>feature chan [add/del] [#chan]</b> (Add and delete feature channels)<br><b>feature chan list</b>(List all feature channels)<br><b>feature [#chan] [on/off]</b> (Turn on/off feature in a channel)<br><b>feature</b> (show a random feature)");

//!rdeviant
$modules[$m]->addCmd('rdeviant','deviant','d',1);
$modules[$m]->addHelp('rdeviant',"Just type {$tr}rdeviant");

//!rdev
$modules[$m]->addCmd('rdev','dev','d',1);
$modules[$m]->addHelp('rdev',"Just type {$tr}rdev");

//!thumb
$modules[$m]->addCmd('thumb','thumb','d',1);
$modules[$m]->addHelp('thumb',"{$tr}thumb \":thumb######:\" or {$tr}devinfo ######");

//!urbandict
$modules[$m]->addCmd("urbandict", "urbandict",'default');
$modules[$m]->addHelp("urbandict","urbandict [#] [term]");

//!sys
$modules[$m]->addCmd("sys", "sys",99,99);
$modules[$m]->addHelp("sys","{tr}sys [scandir/openfile] directoryname/filename");

//!last.fm
$modules[$m]->addCmd('last.fm','lastfm','d',1);
$modules[$m]->addHelp("last.fm","{$tr}last.fm [user]");

//!spell
$modules[$m]->addCmd('spell','spell','d',1);
$modules[$m]->addHelp('spell',"{$tr}spell [word]");

//!botpad
$modules[$m]->addCmd('botpad','botpad','d',1);
$modules[$m]->addHelp('botpad',"{$tr}botpad commands for more info.");

//!devinfo
$modules[$m]->addCmd('devinfo','devinfo','d',1);
$modules[$m]->addHelp('devinfo',"{$tr}devinfo [user]");

//!spamfilter
$modules[$m]->addCmd('spamfilter','spamfilter/commands','d',99);
$modules[$m]->addEvt('recv-msg','spamfilter/spamcontrol',-1,1);
$modules[$m]->addEvt('recv-action','spamfilter/spamcontrol',-1,1);
$modules[$m]->addEvt('recv-join','spamfilter/joinroom',-1,1);
$modules[$m]->addEvt('join-success','spamfilter/joinroom',-1,1);

// main 
$modules[$m]->addCmd('dios','dios','d',1);

//!feature startup
if(!$config['feature']['format']){
	$config['feature']['format']="<b>[:thumb%id: <a href=\"http://www.deviantart.com/view/%id\">%t</a> by :dev%a:]</b><sub> <i>featured by :dev%b:</i></sub>";
	$config['feature']['interval']=25;
	save_config('feature');
}
//!spamfilter startup
if(!$config['spam_control']['KICKED']){
	$config['spam_control']['KICKED']=3;
	save_config('spam_control');
}

?> 