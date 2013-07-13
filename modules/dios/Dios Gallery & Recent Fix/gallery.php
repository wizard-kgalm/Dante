<?php
$who=$args[1];
$kind=$args[2];
$dev=$args[3];
if(is_numeric($kind)){
	$dev=$kind;
	$kind='random';
} else
if($who==""){
	$who=$from;
}
if(strtolower($who)=="about"){
	 return $dAmn->say("Gallery 2.0, coded by :devMagaman:",$c);
 }
if(strtolower($who)=="commands"){
	return $dAmn->say("<abbr title='{$f}'></abbr><b>Gallery Command List</b><br><sub>".
	"&nbsp;&nbsp;&nbsp;&nbsp;- {$tr}gallery random - <i>Shows 5 random deviations from your gallery.</i><br>".
	"&nbsp;&nbsp;&nbsp;&nbsp;- {$tr}gallery [username] - <i>Shows 5 random deviations from [username] gallery.</i><br>".
	"&nbsp;&nbsp;&nbsp;&nbsp;- {$tr}gallery [username] popular [amt] - <i>Shows [amt] popular deviations from [username]'s gallery.</i><br>".
	"&nbsp;&nbsp;&nbsp;&nbsp;- {$tr}gallery [username] browse [page] [amt]- <i>Shows [amt] random deviations from [username]'s gallery on page [page].</i><br>".
	"&nbsp;&nbsp;&nbsp;&nbsp;- {$tr}gallery [username] search [amt] [term]- <i>Shows [amt] results from [username]'s gallery with the search [term]. [amt] is optional.</i><br>".
	"&nbsp;&nbsp;&nbsp;&nbsp;- {$tr}gallery [username] prints [page] [amt]- <i>Shows [amt] random prints from [username]'s prints gallery on page [page].</i><br>",$c);
}
$defaults=array("help" =>1,"services" =>1,"my" =>1,"software" =>1,"forum" =>1,"news" =>1,"today" =>1,"shop" =>1,"browse" =>1);
if($defaults[strtolower($who)]){
	return $dAmn->say("{$from}: <b><i>Invalid deviant name. Please try again.",$c);	
}
// functions
if(!function_exists('splitmeh')){
	function  splitmeh($oh){
	   global $dAmn;
	   foreach($oh as $key => $stuff){
	       $boo=explode("/",$stuff);
		   $boo=explode(" ",$boo[0]);
		   $boo=explode("-",$boo[0]);
		   $k = end($boo);
           $oh[$key]=":thumb".str_replace("\"","",$k).":";
       }
	   unset($oh[0]);
       return $oh;
   }
}
if(!function_exists('split_page')){
	function  split_page($page,$who){
	   $oh=explode("<span class=\"shadow\"><a href=\"http://".$who.".deviantart.com/art/",$page);
       $lo=explode("<span class=\"mild shadow\"><a href=\"http://".$who.".deviantart.com/art/",$page);
       $oh=array_merge($oh,$lo);
       return $oh;
   }
}
$found=false;
switch(strtolower($kind)){
	case '':
	case 'random':
	   $dAmn->say("<i>Retrieving info from deviant's page:thumb6430580:",$c);
	   if(!is_numeric($dev)){
	       $dev=5;
       }
 	   $page=@file_get_contents("http://{$who}.deviantart.com/gallery/?");
	   $oh=split_page($page,$who);
       $oh=splitmeh($oh);
       if($dev>count($oh)){
           $dev=count($oh);
       }
       $e=0;
       while($dev>0){
	       $dAmn->CheckQueues(false);
	       $me=rand(1,count($oh));
	       if(!$done[$me]){
		       if(substr($oh[$me],0,7)==":thumb:"){
			       unset($oh[$me]);
			       $e--;
			       $dev--;	       
		       } else {
	               $thumbs.=$oh[$me]." ";
		           $done[$me]=$me;	
		           $e++;
                   $dev--;		
		           $found=true;
		       }
	       }	  
       }	
       $done="";	
       if($found && $e>0){
	       $tt="<b><abbr title='{$f}'></abbr><i>({$e}) of :dev{$who}:'s Deviations (<a href=\"http://{$who}.deviantart.com/gallery/\" title=\"Click to view {$who}'s Gallery!\">more...</a>)</i></b><br>".$thumbs;
       } else {
	       $tt="{$from}: <b><i>Deviant not found or has not submitted any artwork.";
       }
	   $dAmn->say($tt,$c);
	break;
	case 'popular':
	   $dAmn->say("<i>Retrieving info from deviant's page:thumb6430580:",$c);
	   if(!is_numeric($dev)){
	       $dev=5;
       }
	   $page=@file_get_contents("http://{$who}.deviantart.com/gallery/?order=9");
	   $oh=split_page($page,$who);
       $oh=splitmeh($oh);
       if($dev>count($oh)){
           $dev=count($oh);
       }
       $e=0;       
       foreach($oh as $key => $thumb){
	       if($dev>0){
		       if(substr($thumb,0,7)==":thumb:"){
	           } else {
	               $thumbs.=$thumb." ";
	               $found=true;
		           $dev--;
		           $e++;
		       }
	       }
	   }
	   if($found){
		   $tt="<b><i><abbr title='{$f}'></abbr>({$e}) of :dev{$who}:'s Popular Deviations (<a href=\"http://{$who}.deviantart.com/gallery/?order=9\" title=\"Click to view {$who}'s Gallery!\">more...</a>)</i></b><br>".$thumbs;  
	   } else {
		   $tt="{$from}: <b><i>Deviant not found or has not submitted any artwork.";
       }
	   $dAmn->say($tt,$c);
	break;
	case 'page':
	case 'browse':
	  $dAmn->say("<i>Retrieving info from deviant's page:thumb6430580:",$c);
	  $n=24;
	  $amt=$args[4];
	  if(!is_numeric($amt) || $amt=="" || $amt>24 || $amt<=0){
		  $amt=5;
	  }
      if($dev==1 || $dev=="" || $dev<1 || !is_numeric($dev)){
	       $dev=1;
		   $page=@file_get_contents("http://{$who}.deviantart.com/gallery/");
      } else 
      if($dev>=2){
	       if($dev>=3){
		       $n*=$dev-1;
	       }
	       $page=@file_get_contents("http://{$who}.deviantart.com/gallery/?offset=".$n);
      }
      $oh=split_page($page,$who);    
      foreach($oh as $key => $stuff){
	       $boo=explode("/",$stuff);
	       if(substr($boo[0],0,1)=="<"){
		       unset($oh[$key]);
	       }
      }
      $e=$amt;
      if($oh){
	      while($amt>0){
		      $me=rand(1,count($oh)); 
	          if(!$done[$me]){
		          $boo=explode("/",$oh[$me]);
				  $boo=explode(" ",$boo[0]);
		          $boo=explode("-",$boo[0]);
		          $k = end($boo);
                  $thumbs.=":thumb".str_replace("\"","",$k).":";
                  $done[$me]=$me;
                  $boo="";
                  $amt--;
	          } 	  
          }
          $found=true;
      }  
      if($found){
	      $tt="<b><i><abbr title='{$f}'></abbr>Browsing Page {$dev} of :dev{$who}:'s Gallery (Showing {$e} Deviations)  (<a href=\"http://{$who}.deviantart.com/gallery/?offset={$n}\" title=\"Click to view {$who}'s Page {$dev} Gallery!\">more...</a>)</i></b><br>".$thumbs;
      } else {
	      $tt="{$from}: <b><i>That page does not exist in deviant's gallery.";
       }
	   $dAmn->say($tt,$c);
    break; 
    case 's':
    case 'search':
      if(is_numeric($dev)){
	      $show=$dev;
	      $argsE[3]=$args[4];
      } else {
	      $show=10;
      }
      if($argsE[3]==""){
	      return $dAmn->say("{$from}:<b><i> Please insert search term.",$c);
      } else {
	      $dAmn->say("<i>Retrieving info from deviant's page:thumb6430580:",$c);
	      $page=@file_get_contents("http://{$who}.deviantart.com/gallery/?order=5&countrows=1&search=".urlencode($argsE[3]));
      }
      
      $oh=split_page($page,$who);  
      $count=0;
	  unset($oh[0]);
      foreach($oh as $key => $stuff){
	       $boo=explode("/?",$stuff);	   
		   $tmp=explode(" ",$boo[0]);$tmp=explode("-",$tmp[0]); $t = end($tmp); 
		   $lol = $tmp; $del = array_keys($lol); $delk = end ($del); unset($lol[$delk]);
	       if(substr($boo[0],0,1)=="<"){
		       unset($oh[$key]);
	       } else {
		       $count++;
		       if($count<=4){
		            $thumbs.=":thumb".str_replace("\"","",$t).":"; 
	            } else
		       if($count<=$show){
		            $thumbs.="<li><sub><a href=\"http://{$who}.deviantart.com/art/".str_replace("\"","",implode("-",$tmp))."/\">".implode(" ",$lol)."</a></sub></li>"; 
	           }
	       }
      }  
      if($count>0){   
	      $tt="<b><i><abbr title='{$f}'></abbr>({$count}) Search Results for \"{$argsE[3]}\" in :dev{$who}:'s Gallery (<a href=\"http://{$who}.deviantart.com/gallery/?order=5&countrows=1&search=".urlencode($argsE[3])."\" title=\"Click to view more Results!\">more...</a>)</i></b><br>".$thumbs;
      } else {
	      $tt="{$from}:<b><i> Search term not found.";
      }
      $dAmn->say($tt,$c);
    break;
    case 'p':
    case 'prints':
      $dAmn->say("<i>Retrieving info from deviant's page:thumb6430580:",$c);
      $n=24;
	  $amt=$args[4];
	  if(!is_numeric($amt) || $amt=="" || $amt>24 || $amt<=0){
		  $amt=5;
	  }
      if($dev==1 || $dev=="" || $dev<1 || !is_numeric($dev)){
	       $dev=1;
		   $page=@file_get_contents("http://{$who}.deviantart.com/prints/");
      } else 
      if($dev>=2){
	       if($dev>=3){
		       $n*=$dev-1;
	       }
	       $page=@file_get_contents("http://{$who}.deviantart.com/prints/?offset=".$n);
      }     
      $oh=explode("<span class=\"shadow\"><a href=\"http://www.deviantart.com/print/",$page);
      foreach($oh as $key => $stuff){
	       $boo=explode("/",$stuff);
	       if(substr($boo[0],0,1)=="<"){
		       unset($oh[$key]);
	       }
      }
      if($amt>count($oh)){
           $amt=count($oh);
       }
      $e=$amt;
      if($oh){
	      while($amt>0){
		      $me=rand(1,count($oh)); 
	          if(!$done[$me]){
		          $boo=explode("/",$oh[$me]);
		          $page=@file_get_contents("http://www.deviantart.com/print/{$boo[0]}/");
		          $gah=explode("http://www.deviantart.com/deviation/",$page);
		          $thumb=explode("/",$gah[1]);
		          $thumb[0]=str_replace("\">[link]<","",$thumb[0]);
			      $thumbs.=":thumb".$thumb[0].":";
                  $done[$me]=$me;
                  $boo="";
                  $amt--;
              }	           	  
          }
          $found=true;
      }  
      if($found){
	      $tt="<b><i><abbr title='{$f}'></abbr>Browsing Page {$dev} of :dev{$who}:'s Prints Gallery (Showing {$e} Prints)  (<a href=\"http://{$who}.deviantart.com/prints/?offset={$n}\" title=\"Click to view Page {$dev} of {$who}'s Gallery!\">more...</a>)</i></b><br>".$thumbs;
      } else {
	      $tt="{$from}: <b><i> Deviant does not have a print gallery or page does not exist.";
      }
      $dAmn->say($tt,$c);
    break;
}
?>