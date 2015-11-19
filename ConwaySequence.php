<?php
/**
 * Auto-generated code below aims at helping you parse
 * the standard input according to the problem statement.
 **/

fscanf(STDIN, "%d",
    $R
);
fscanf(STDIN, "%d",
    $L
);

// Write an action using echo(). DON'T FORGET THE TRAILING \n
// To debug (equivalent to var_dump): error_log(var_export($var, true));

$sequence[] = array('number' => $R, 'count' => 1);
for ($i = 1; $i < $L; $i++) {
  $newSequence = array();
  for($j = 0; $j < sizeof($sequence); $j++) {
    if (empty($newSequence) || $newSequence[sizeof($newSequence) - 1]['number'] != $sequence[$j]['number']) {
      $newSequence[] = array('number' => $sequence[$j]['number'], 'count' => 1);
    } else {
      $newSequence[sizeof($newSequence) - 1]['count']++;
    }

    if ($sequence[$j]['count'] == $newSequence[sizeof($newSequence) - 1]['number']) {
      $newSequence[sizeof($newSequence) - 1]['count']++;
    } else {
      $newSequence[] = array('number' => $sequence[$j]['count'], 'count' => 1);
    }
  }
  $sequence = $newSequence;
}
for ($i = 0; $i < sizeof($sequence); $i++) {
  for ($j = 0; $j < $sequence[$i]['count']; $j++) {
    $string[] = $sequence[$i]['number'];
  }
}
echo(implode(" " , array_reverse($string)) . "\n");
?>