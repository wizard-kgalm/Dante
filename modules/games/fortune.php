<?php
$responses=array(
	'You will meet someone special in your next life.',
	'Your future would have been sucessful had you gotten a different cookie.',
	'EMPTY COOKIE.',
	'If at first you do not succeed, sky diving is not for you.',
	'It is true, your days are numbered.',
	'Your car will run out of gas soon, but your significant other will be full.',
	'Success is just around the corner.  To bad your here.',
	'Tomorrow does not look good, but it is probably better then today.',
  	'You should not have flushed the toilet.',
	'Your fly is open but do not worry, the barn is empty.',
	'EMPTY COOKIE.',
	'You are realy the orphan of a great king who is searching for you.',
	'05  12  22  24  31  40.',
	'Time could have been better spent by not reading this fortune cookie.',
	'And the winning lotto numbers are 6 6 6.',
	'Sell a man one fish, and he can eat for one day.  Teach the man to fish, and you can go broke.',
	'Corn dogs are not grown... they are nurtured.',
	'Life teaches many lessons and yes there is a test in the end.',
	'The collective IQ of the world dropped 10 points because you read this.',
	'Love will come to you when you least expect it, now that your expecting to not expect it, love probably is not coming.',
	'HELP ME!!! I have been kidnapped!',
	'If you are looking for sexual satisfaction, then you will need to keep looking.',
	'There is a BMW parked outside with someone else\'s name on it.',
	'I am all out of fortune cookies.'
);
$response = $responses[array_rand( $responses )];
$dAmn->say( "$from, Confucious says: \n<b>$response</b>", $c );
?>