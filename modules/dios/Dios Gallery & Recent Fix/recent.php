<?php
  if($args[1]==""){
     $args[1]=$from;
  }
  $found=false;
  $dAmn->say("<i>Retrieving info from deviant's page:thumb6430580:",$c);
 $page=@file_get_contents("http://{$args[1]}.deviantart.com/");
 $ha=explode("<div class=\"boxtop\"><h2 class=\"c\"><i class=\"icon i2\"></i> Recent Deviations</h2></div>",$page);
 $haha=explode("<a class=\"super\" href=\"/gallery/\">Browse Gallery</a>",$ha[1]);
 $dem=strip_tags($haha[0],"<a>");
 $dem=explode("<a href=\"http://{$args[1]}.deviantart.com/art/",$dem);
 $a=0;
 $b=1;
 foreach($dem as $n => $ooo){
		if($a==$b){
		   $b+=2;
	       $get=explode("/",$dem[$a]);
		   $get=explode(" ",$get[0]);
		   $get=explode("-",$get[0]);
		   $t = end($get);
		   $thumbs.=":thumb".str_replace("\"","",$t).": ";
		   $get="";
		   $found=true;
		}
		$a++;
 }		
$txt="<abbr title='{$f}'></abbr><i>:gallery:<b>:dev{$args[1]}:'s RECENT DEVIATIONS (<a href=\"http://{$args[1]}.deviantart.com/gallery/\" title=\"Click to view {$args[1]}'s Gallery!\">more...</a>)</b></i><br>";	 
$txt.=$thumbs;	
if($found){ 
  $dAmn->say($txt,$c);
} else {
    $dAmn->say("{$from}: <b><i>Deviant not found or no deviations was submitted.",$c);
}
?>


