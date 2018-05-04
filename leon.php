<?php

/*
tuples [
	offset int	// start or end interval
	type int	// -1 and +1 for start end interval
]
*/

// $b = [2,10,20,2,10];
// $e = [5,30,40,5,30];

// $b = [2,1,3,2,5];
// $e = [5,3,4,4,6];

// $b = [3,1,4,6,9];
// $e = [10,6,8,13,12];

$b = [71, 17, 45, 50, 19, 83, 71, 9, 37, 65];
$e = [86, 37, 85, 81, 25, 84, 78, 49, 72, 91];

$tuples = [];

for ($i=0; $i < count($b); $i++) {
	$tuples[] = [$b[$i], -1];
	$tuples[] = [$e[$i], 1];
}

sort($tuples);

// Jeżeli pewne pary mają ten sam znacznik czasowy, jednym z rozwiązań jest umieszczenie par z typem +1 przed parami z typem -1 (robi się tak w celu uniknięcia problemu z przedziałami nakładającymi się tylko swoimi końcami).

$c = count($tuples);

for ($i=0; $i < $c; $i++) {
	if (isset($tuples[$i+1][0]) && $tuples[$i][0] == $tuples[$i+1][0] && $tuples[$i][1] == -1) {
		$temp = $tuples[$i];
		$tuples[$i] = $tuples[$i+1];
		$tuples[$i+1] = $temp;
	}
}

$current = 0;
$best = 0;
$beststart = $bestend = 0;

for ($i=0; $i < $c; $i++) {
	$offset = $tuples[$i][0];
	$type = $tuples[$i][1];
	$current -= $type;
	if (isset($tuples[$i+1][0]) && $current > $best) {
		$best = $current;
		$beststart = $offset;
		$bestend = $tuples[$i+1][0];
	}
}
$result = [$beststart, $bestend, $best];
echo $result[0], ' ', $result[1], ' ', $result[2];

/*
Output:
2 3 3
4 6 3
71 72 6
20 30 3
*/

?>