<?php
$data=@file_get_contents("http://www.deviantart.com/random/deviant");
preg_match("/<title>(.*) on deviantART<\/title>/Ums", $data, $u);
$u=$u['1'];
$dAmn->say("<b>[<u>Random Deviant</u>]</b><br>:icon{$u}:<br><b>:dev{$u}:</b> <br> <a href=\"http://{$u}.deviantart.com/gallery\"> {$u}'s :gallery:</a>", $c);
?>