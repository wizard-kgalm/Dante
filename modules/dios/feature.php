<?php
switch($args[1]){
	case 'total':
	    $total=count($config['feature']['devs']);
	    $dAmn->say("<b>Total Deviations Featured</b>: {$total}",$c);
        break;
    case 'add':
        $args[2]=str_replace(":thumb","",$args[2]);
        $args[2]=str_replace(":","",$args[2]);       
        if(is_numeric($args[2])){
	        $id=$args[2];
	        if(!$config['feature']['devs'][$id]){
		        $data=@file_get_contents("http://www.deviantart.com/view/$id/");
		        if(preg_match("/<title>(.*)<\/title>/Ums",$data,$matches)){
			        $haha=strip_tags($matches[0]);
			        $datas=explode(" by ",$haha);
			        $config['feature']['devs'][$id]['title']=$datas[0];
			        $title=$datas[0];
			        $name=explode(" on ",$datas[1]);
			        $config['feature']['devs'][$id]['author']=substr($name[0],1);
			        $author=substr($name[0],1);
			        if(!$args[3]){
				        $config['feature']['devs'][$id]['by']=$from;
			        } else {
				        $config['feature']['devs'][$id]['by']=$args[3];
			        }
			        save_config('feature');
		            $dAmn->say("Added <a href=\"http://www.deviantart.com/view/$id\"><b>$title</b></a> by <b>:dev$author:</b> with thumb ID of <b>$id</b>. (Featured by :dev{$config['feature']['devs'][$id]['by']}:)", $c); 
	            } else {
		            $dAmn->say("{$from}: Invalid thumb number.", $c); 
	            }
            } else {
	            $dAmn->say("{$from}: That deviation has already been featured.", $c); 
            }
        } else {
	        $dAmn->say("$from: Please enter a thumb or thumb number.", $c); 
        }
       break;
	case 'del':
	   $id=$args[2];
	   if($config['feature']['devs'][$id]){
		   unset($config['feature']['devs'][$id]);
	       save_config('feature');
	       $dAmn->say("{$from}: Removed feature for <b>$id</b>.", $c); 
       } else {
	       $dAmn->say("$from: Feature doesnt exist.", $c); 
       }
       break;
    case 'list':
	   $text=":gallery: <b>Featured deviations</b> :gallery:<sup><br>";
	   $text.="<i>Features will be showed every <b>{$config['feature']['interval']}</b> seconds.</i><br><br>";
	   foreach($config['feature']['devs'] as $tid => $feature){
		  $title=$feature['title'];
		  $author=$feature['author'];
		  $by=$feature['by'];
		  $text.=":<b>$tid</b>:  <a href=\"http://www.deviantart.com/view/$id\"><b>$title</b></a> by <b>:dev$author:</b>, featured by <b>:dev$by:</b>.<br>"; 
	   }
	   $dAmn->say($text, $c); 
	   break;
	case 'timer':
	   $id=$args[2];
	   if($id<5){
		   $dAmn->say(":police: Cannot set timer interval below <b>5</b> for your bot's safety. :police:", $c); 
	   } else {
		   $config['feature']['interval']=$id;
		   global $feature, $Timer;
		   $feature=$id;
		   $Timer->register($feature,$data,"modules/dios/timer.php",null);
	       save_config('feature');
	       $dAmn->say("Feature interval set to <b>[</b>$id<b>]</b>.", $c);  
       }
       break;
    case 'chan':
        $chan=str_replace("#", "chat:", $args[3]);
        switch($args[2]){
	        case 'add':
	            $config['feature']['channels'][$chan]=true;
		        save_config('feature');
		        $dAmn->say("Now showing features in <b>$chan</b>.", $c);
		        break;
		     case 'del':
		        if($config['feature']['channels'][$chan]){
			        unset($config['feature']['channels'][$chan]);
		            save_config('feature');
		            $dAmn->say("No longer showing features in <b>#".substr($chan,5)."</b>.", $c);
	            } else {
		            $dAmn->say("Invalid channel, wasn't not showing features there anyways.", $c);
	            }
	            break;
	         case 'list':
	            $text="<b>Showing features in</b>";
	            if($config['feature']['channels']){
		            foreach($config['feature']['channels'] as $chan => $t){
			            if($t) $e=":bulletgreen:"; else $e=":bulletred:";
		        	   $text.="<li>$e #".substr($chan,5)."</li>";
	        	    }
	        	    $dAmn->say($text, $c);
        	    } else {
	        	    $dAmn->say("{$from}: No channels to feature added.", $c);
             	}
		        break;
		     default:
		       $chan=str_replace("#", "chat:", $args[2]);
		       if($config['feature']['channels'][$chan] || !$config['feature']['channels'][$chan]){
			       if($args[3]=="on"){
				       $config['feature']['channels'][$chan]=true;
				       save_config('feature');
				       $dAmn->say("Features for <b>#".substr($chan,5)."</b> turned ON.", $c);
			       } else {
				       $config['feature']['channels'][$chan]=false;
				       save_config('feature');
				       $dAmn->say("Features for <b>#".substr($chan,5)."</b> turned OFF.", $c);
			       }
		       }		         
	     }
	     break;
     default:
       $ran=rand(1,count($config['feature']['devs']));
       $getit=1;
       foreach($config['feature']['devs'] as $tid => $f){
	       if($getit==$ran){
		       $author=$config['feature']['devs'][$tid]['author'];
	           $title=$config['feature']['devs'][$tid]['title'];
	           $by=$config['feature']['devs'][$tid]['by'];
	           $txt=str_replace("%id", $tid, $config['feature']['format']);
	           $txt=str_replace("%a", $author, $txt);
	           $txt=str_replace("%t", $title, $txt);
               $txt=str_replace("%b", $by, $txt);
	           $dAmn->say($txt, $c); 
           }
           $getit++;
       }
}
?>