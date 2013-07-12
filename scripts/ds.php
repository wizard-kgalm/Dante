<?php
// DANTE SCRIPT ======
// Name: desymbolize
// Description: Converts the messages from the symbols made with a special other command. 
// Author: Wizard-Kgalm
// Version: 1.0
// Priv: 0
// Help: {tr}ds [message]. Converts messages from my symbols to regular letters 
// ====================
$regalph = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w'/*,'x'*/,'y','z',);
$symalph = array('&#x023A;','&#x0243;','&#x20A1;','&#x0189;','&#x0246;','&#x0191;','&#x01E4;','&#x04C7;','&#x1F3F;','&#x0248;','&#x0198;','&#x0141;','&#x04CD;','&#x0220;','&#x01FE;','&#x2C63;','Q','&#x2C64;','&#x015C;','&#x01AE;','&#x0244;','&#x01B2;','&#x20A9;','&#x04FE;','&#x0194;','&#x01B5;','&#x1D8F;','&#x1D6C;','&#x023C;','&#x0256;','&#x0247;','&#x0192;','&#x01E5;','&#x0267;','&#x0268;','&#x0284;','&#x0199;','&#x026B;','&#x0271;','&#x0273;','&#x01FF;','&#x01A5;','q','&#x027D;','&#x0282;','&#x0288;','&#x1D7E;','&#x028B;','&#x2C73;'/*,'&#x04FD;'*/,'&#x0263;','&#x0240;',);
$firstpart = array('<&#x0282;&#x1D7E;&#x01A5;>','</&#x0282;&#x1D7E;&#x01A5;>','<&#x0282;&#x1D7E;&#x1D6C;>','</&#x0282;&#x1D7E;&#x1D6C;>','<&#x1D7E;>','</&#x1D7E;>','<&#x1D6C;>','</&#x1D6C;>','<&#x0268;>','<&#x0268;>','<&#x1D6C;&#x027D;>','<&#x1D6C;&#x027D;/>','<&#x1D8F;&#x1D6C;&#x1D6C;&#x027D; &#x0288;&#x0268;&#x0288;&#x026B;&#x0247;=','</&#x1D8F;&#x1D6C;&#x1D6C;&#x027D;>','&#x03?1;','&#x014?;','&#x025?;',);
$secondpart = array('<sup>','</sup>','<sub>','</sub>','<u>','</u>','<b>','</b>','<i>','</i>','<br>','<br/>','<abbr title=','</abbr>','&#x03B1;','&#x014B;','&#x025B;',);
$argsF = str_replace( $symalph, $regalph, $argsF );
$dAmn->say( $argsF, $c );
?>
