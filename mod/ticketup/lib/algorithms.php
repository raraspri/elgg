<?php

// From DB-----
$user_tickets=5;
$product='leche';
$buy_int = array(3, 5, 5, 4, 3, 2, 3, 3, 4, 4, 7, 7, 7, 8);// purchase interval (in days).

//$buy_f=fopen('distribution.txt', 'r');
//$buy_int=array_map('intval', explode(",", fread($buy_f, filesize('distribution.txt'))));

//-------
$times_bought=count($buy_int);
// Params -------
$gauss_int=20;
$min_tickets=3;
$min_product=3;
$D_rep=1;
//--------

if ($user_tickets<$min_tickets or $times_bought<$min_product) {
	exit('Not enough data');
}

$distr = array_count_values($buy_int); 
$distr[0]=0;
$distr[max(array_keys($distr))+3]=0;
ksort($distr);

$x0=0;
$y0=0;
$sign=0;

foreach ($distr as $x => $y) {
	if ($sign!==gmp_sign($y-$y0) and gmp_sign($y-$y0)==1) {
		$mode_day[]=$x;
		$mode_rep[]=$y;
	}
	$sign=gmp_sign($y-$y0);
	$y0=$y;
	$x0=$x;
}

$y_max=max($mode_rep);

foreach ($mode_rep as $x => $y) {

	if ($y<$y_max-$D_rep) {

		unset($mode_day[$x]);
		unset($mode_rep[$x]);
		continue;
	}
}


/*
|----------------------------------------------------------|
|EMPEZAR CON ESTO SI SE DECIDE EVALUAR                     |
|EL COMPORTAMIENTO DE COMPRA CUANDO HAY MÁS DE UN PICO     |
|----------------------------------------------------------|

if (count($mode_day)>1) {
	function filt($value)
	{
		global $mode_day;
		return(in_array($value, $mode_day));
	}

	$seq=array_filter($buy_int, 'filt');


} elseif (count($mode_day)==1) {
	$next_day=$mode_day[0];
}
*/

$next_day=min($mode_day);

echo $next_day;


?>