<?php

// $b = [2,10,20,2,10];
// $e = [5,30,40,5,30];

// $b = [2,1,3,2,5];
// $e = [5,3,4,4,6];

// $b = [3,1,4,6,9];
// $e = [10,6,8,13,12];

// $b = [71, 17, 45, 50, 19, 83, 71, 9, 37, 65];
// $e = [86, 37, 85, 81, 25, 84, 78, 49, 72, 91];

$b = [10, 12, 11, 13, 14, 12,  9, 14, 12, 10];
$e = [15, 14, 17, 15, 15, 16, 13, 15, 17, 18];

$tuples = [];

for ($i=0, $l='A'; $l<'Z', $i < count($b); $i++, $l++) {
	$tuples[] = [$b[$i], -1, $l];
	$tuples[] = [$e[$i], 1, $l];
}

sort($tuples);

// If two tuples with the same offset but opposite types exist, jednym z rozwiązań jest umieszczenie par z typem +1 przed parami z typem -1 (robi się tak w celu uniknięcia problemu z przedziałami nakładającymi się tylko swoimi końcami).

// sortCorrection($tuples);

$current = 0;
$best = 0;
$beststart = $bestend = 0;
$cTuples = count($tuples);

for ($i=0; $i < $cTuples; $i++) {
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
20 30 3
2 3 3
4 6 3
71 72 6
14 14 9
*/

function sortCorrection(&$tuples) {
	$cTuples = count($tuples);

	// szukanie najczęściej występujących
	$track = array_fill(0, 100, 0);
	for ($i=0;$i<$cTuples;$i++) {
		$track[$tuples[$i][0]] += 1;
	}

	for ($i=0; $i < count($track); $i++) {
		if ($track[$i] > 1) {
			$trackFreq[$i] = $track[$i];
		}
	}

	// rozdzielenie najczęściej występujących na start -1 i koniec 1
	$cTrackFreq = count($trackFreq);
	$start = $end = [];
	foreach ($trackFreq as $k => $f) {
		for ($i=0; $i < $cTuples; $i++) {
			if ($tuples[$i][0] == $k) {
				if ($tuples[$i][1] == -1) {
					$start[$k][] = $tuples[$i];
				}
				if ($tuples[$i][1] == 1) {
					$end[$k][] = $tuples[$i];
				}
			}
		}
	}

	// szukanie części wspólnych
	$kEnd = array_keys($end);
	$kStart = array_keys($start);
	$kIntersect = array_values(array_intersect($kStart, $kEnd));
	$freqById = array_flip(array_intersect($kStart, $kEnd));
	$plusTuples = $minusTuples = [];
	for ($i=0;$i<$cTuples;$i++) {
		if (in_array($tuples[$i][0], $kIntersect)) {
			if ($tuples[$i][1] == 1) {
				$plusTuples[] = $tuples[$i];
			} else {
				$minusTuples[] = $tuples[$i];
			}
		}
	}

	// odwracanie kolejności: najpierw koniec 1 a póżniej start -1
	$rSorted = [];
	$cPlus = count($plusTuples);
	$cMinus = count($minusTuples);
	$cSeparated = ($cPlus>$cMinus) ? $cPlus : $cMinus;
	for ($i=0; $i < $cSeparated; $i++) {
		if (isset($plusTuples[$i]) && isset($minusTuples[$i]) && $minusTuples[$i][0] == $plusTuples[$i][0]) {
			$rSorted[] = $plusTuples[$i];
			$rSorted[] = $minusTuples[$i];
		} elseif (empty($minusTuples[$i][0])) { // gdy $plusTuples lub $minusTuples są niesymetryczne
			$rSorted[] = $plusTuples[$i];
		} elseif (empty($plusTuples[$i][0])) {
			$rSorted[] = $minusTuples[$i];
		}
	}

	$tt = [];
	for ($i=0; $i < $cTuples; $i++) {
		if (in_array($tuples[$i][0], $kIntersect)) {
			$tt[$i] = $tuples[$i];
		}
	}

	$kTT = array_keys($tt);
	$rSorTuples = array_combine($kTT, $rSorted);

	for ($i=0; $i < $cTuples; $i++) {
		if (in_array($tuples[$i][0], $kIntersect)) {
			$tuples[$i] = $rSorTuples[$i];
		}
	}
}

?>