<?php
$a = $dAmn->room[$cc]->getUserList(); 
$d = ($a[array_rand($a)]);
		if(!empty($args[1])){
					$hehe = $args[1];
		}else{
			$hehe = "Owned";
				}
		$dAmn->say(":gun: <B>:dev$d:",$c);
$dAmn->kick($d, $c, $hehe);
?>