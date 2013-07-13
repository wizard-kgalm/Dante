<?php
switch($args[1]){
	case 'scandir':
	   $opndir=$argsE[2];
	   $opndir=str_replace("/","\\",$opndir);
	   if(is_dir("$opndir")){
		   $num=0; $files=0; $dirs=0;
		   foreach(scandir($opndir) as $scan){
			   if(is_dir("$opndir\\$scan")){
				   $dirs++;
				   $result.="- <b>$opndir\\$scan</b><br>";
			   } else {
				   $files++;
				   $result.="-- $opndir\\$scan<br>";
			   }
			   $num++;
           }
           $dAmn->say("<abbr title='{$f}'></abbr><b>Directory <sub>\"{$opndir}\"</sub> Results</b><br><br><sub>{$result}<br><b>{$num}</b> results found in directory.<br>{$dirs} dirs and {$files} files.", $c);
       } else {
	       $dAmn->say($f."Directory does not exist.", $c);
       }
    break;
    case 'openfile':
       $opnfile=$argsE[2];
       if(file_exists("$opnfile")){
	       if(is_readable("$opnfile")){
		       $results=@file_get_contents("$opnfile");
		       $dAmn->say("<b>File <sub>{$opnfile}</sub> Contents:</b>", $c);
               $dAmn->say("<bcode>{$results}", $c);
               $dAmn->say("<b></End File <sub>{$opnfile}</sub> Output></b>", $c);
            } else {
	            $dAmn->say($f." File cannot be read.", $c);
            }
       } else {
	       $dAmn->say("$from: File does not exist.", $c);
       }
    break;
    default:
      $dAmn->say($f." {$tr}sys [scandir/openfile] directoryname/filename ", $c);
}    
?>