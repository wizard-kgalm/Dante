<?php
$data=@file_get_contents("http://www.deviantart.com/random/deviation");

preg_match("/<title>(.*) on deviantART<\/title>/Ums", $data, $by);
preg_match("/value=\":thumb(.*):\"/Ums", $data, $id);
$dAmn->say("<b>[<u>Random Deviation</u>]</b><br> ".$by['1']." <br>:thumb".$id['1'].":", $c);
?>