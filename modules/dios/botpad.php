<?php
switch($args[1]){
	case 'about':
       $dAmn->say("<b>Botpad v2.0</b> by :devMagaman: ( Credits :devExtreme-Reality: and :devaritra:)", $c);
    break;
    case 'create':
       $filename=$args[2];
       if(empty($filename)){
	       return $dAmn->say($f. "Please give the file a name",$c);
       }
       if(!$config['botpad'][$from][$filename]){
	       $msg=$argsE[3];
	       $config['botpad'][$from][$filename]=$msg;
	       save_config('botpad');
	       $dAmn->say($f. "File was sucessfully created.",$c);
       } else {
	       $dAmn->say($f." File with that name is already created.",$c);
       }
    break;
    case 'del':
        if(is_numeric($args[2])){
	        if($config['botpad'][$from]){
		        $key=0; $del=false;
		        foreach($config['botpad'][$from] as $fn => $contents){
			        if($key==$args[2]){
				        $del=true;
				        unset($config['botpad'][$from][$fn]);
				        save_config('botpad');
				        $dAmn->say($f." The file has been deleted sucessfully.",$c);
				        break;
			        }
			        $key++;
		        }
		        if(!$del){
			        $dAmn->say($f." File does not exist",$c);
		        }
	        }
        } else 
        if($argsE[2]){
	        if($config['botpad'][$from][$argsE[2]]){
		        unset($config['botpad'][$from][$argsE[2]]);
				save_config('botpad');
				$dAmn->say($f." The file has been deleted sucessfully.",$c);
			} else {
				$dAmn->say($f." File does not exist",$c);
			}
		} else {
			$dAmn->say($f."{$tr}botpad del [#/filename]",$c);
		}
	break;
	case 'open':
        if(is_numeric($args[2])){
	        if($config['botpad'][$from]){
		        $key=0; $op=false;
		        foreach($config['botpad'][$from] as $fn => $contents){
			        if($key==$args[2]){
				        $op=true;
				        $dAmn->say("<abbr title='{$f}'></abbr><b>File <i>\"{$fn}\"</i> Contents</b><br>".$contents,$c);
				        break;
			        }
			        $key++;
		        }
		        if(!$op){
			        $dAmn->say($f." File does not exist",$c);
		        }
	        }
        } else 
        if($argsE[2]){
	        if($config['botpad'][$from][$argsE[2]]){
		        $dAmn->say("<abbr title='{$f}'></abbr><b>File <i>\"{$argsE[2]}\"</i> Contents</b><br>".$config['botpad'][$from][$argsE[2]],$c);
			} else {
				$dAmn->say($f." File does not exist",$c);
			}
		} else {
			$dAmn->say($f."{$tr}botpad open [#/filename]",$c);
		}	          
	 break;       
	 case 'check':
	   if($config['botpad'][$from]){
		   $key=0;
		   foreach($config['botpad'][$from] as $fn => $contents){
			   $say.="[#{$key}] &middot; {$fn} <br>";
		   } 
		   $dAmn->say("<abbr title='{$f}'></abbr><b>Your Files List</b><br><sub>{$say}</sub>",$c);
	   } else {
		   $dAmn->say($f." Sorry, you have no files.",$c);
	   }
	 break;     
	 case 'commands':
     default:
       $dAmn->say("<abbr title='{$f}'></abbr><b>Botpad Commands</b><br>{$tr}botpad create [filename] [contents] <br> {$tr}botpad del [#/filename] <br>{$tr}botpad open [#/filename] <br>{$tr}botpad check",$c);
}
?>