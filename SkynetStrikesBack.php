<?php
/**
 * Auto-generated code below aims at helping you parse
 * the standard input according to the problem statement.
 **/

$distanceMatrix = false;
$gateways = array();
$fakeGateways = array();
$connections = array();
function initiateDistanceMatrix($size) {
  global $distanceMatrix;
  global $fakeGateways;

  $fakeGateways = array();
  for ($i = 0; $i < $size; $i++) {
    for ($j = 0; $j < $size; $j++) {
      $distanceMatrix[$i][$j] = array();
    }
  }
}

function addConnection($source, $destination, $size) {
  global $distanceMatrix;
  global $fakeGateways;
  global $gateways;

  $distanceMatrix[$source][$destination] = array($source, $destination);
  $distanceMatrix[$destination][$source] = array($destination, $source);

//  Check routes that the new link is their beginning/end
  for ($i = 0; $i < $size; $i++) {
    if ($i != $source && $i != $destination) {
      if (sizeof($distanceMatrix[$i][$destination]) == 0) {
//      Creating a brand new link
        if (sizeof($distanceMatrix[$i][$source]) > 0) {
          $distanceMatrix[$i][$destination] = array_merge($distanceMatrix[$i][$source], array($destination));
          $distanceMatrix[$destination][$i] = array_merge(array($destination), $distanceMatrix[$source][$i]);
        }
      } else {
//        Shortening an existing connection
        if ((sizeof($distanceMatrix[$i][$source]) > 0) && (sizeof($distanceMatrix[$i][$destination]) > sizeof($distanceMatrix[$i][$source]) + 1)) {
          $distanceMatrix[$i][$destination] = array_merge($distanceMatrix[$i][$source], array($destination));
          $distanceMatrix[$destination][$i] = array_merge(array($destination), $distanceMatrix[$source][$i]);
        }
      }
      if (sizeof($distanceMatrix[$i][$source]) == 0) {
//        Creating a brand new link
        if (sizeof($distanceMatrix[$i][$destination]) > 0) {
          $distanceMatrix[$i][$source] = array_merge($distanceMatrix[$i][$destination],array($source));
          $distanceMatrix[$source][$i] = array_merge(array($source), $distanceMatrix[$destination][$i]);
        }
      } else {
        if ((sizeof($distanceMatrix[$i][$destination]) > 0) && (sizeof($distanceMatrix[$i][$source]) > sizeof($distanceMatrix[$i][$destination]) + 1)) {
          $distanceMatrix[$i][$source] = array_merge($distanceMatrix[$i][$destination],array($source));
          $distanceMatrix[$source][$i] = array_merge(array($source), $distanceMatrix[$destination][$i]);
        }
      }
    }
  }

//  If the target or the source are a gateway, then we don't want to amend it in an in-between connection
  if (in_array($source, $gateways) || in_array($destination, $gateways)) {
    return;
  }

  for ($i = 0; $i < $size; $i++) {
    if ($i == $source || $i == $destination) {
      continue;
    }
    for ($j = $i + 1; $j < $size; $j++) {
      if ($j == $source || $j == $destination) {
        continue;
      }
      if (sizeof($distanceMatrix[$i][$source]) > 0 && sizeof($distanceMatrix[$destination][$j]) > 0) {
        if (sizeof($distanceMatrix[$i][$j]) == 0 || sizeof($distanceMatrix[$i][$j]) > sizeof($distanceMatrix[$i][$source]) + sizeof($distanceMatrix[$destination][$j])) {
          $distanceMatrix[$i][$j] = array_merge($distanceMatrix[$i][$source], $distanceMatrix[$destination][$j]);
          $distanceMatrix[$j][$i] = array_merge($distanceMatrix[$j][$destination], $distanceMatrix[$source][$i]);
        }
      }
      if (sizeof($distanceMatrix[$i][$destination]) > 0 && sizeof($distanceMatrix[$source][$j]) > 0) {
        if (sizeof($distanceMatrix[$i][$j]) == 0 || sizeof($distanceMatrix[$i][$j]) > sizeof($distanceMatrix[$i][$destination]) + sizeof($distanceMatrix[$source][$j])) {
          $distanceMatrix[$i][$j] = array_merge($distanceMatrix[$i][$destination], $distanceMatrix[$source][$j]);
          $distanceMatrix[$j][$i] = array_merge($distanceMatrix[$j][$source], $distanceMatrix[$destination][$i]);
        }
      }
    }
  }
}

function getConnectionToDelete($agent, $gateways, $size) {
  global $distanceMatrix;
  $closestTarget = -1;

  $nearGateNodes = array();

  for($i = 0; $i < sizeof($gateways); $i++) {
    if (sizeof($distanceMatrix[$agent][$gateways[$i]]) == 2) {
      return array($agent, $gateways[$i]);
    }
  }

  for($i = 0; $i < $size; $i++) {
    for($j = 0; $j < sizeof($gateways); $j++) {
      if (sizeof($distanceMatrix[$i][$gateways[$j]]) == 2) {
        $nearGateNodes[] = $i;
      }
    }
  }
  $nearGateNodes = array_unique($nearGateNodes);

  $nearGatewaysClusters = array();
  $i=0;
  $temp = $nearGateNodes;
  while (!empty($temp)) {
    $clusterMember = array_pop($temp);
    $nearGatewaysClusters[$i] = array();
    array_push($nearGatewaysClusters[$i], $clusterMember);
    $queue = array($clusterMember);
    while (!empty($queue)) {
      $process = array_pop($queue);
      foreach ($temp as $k => $gate) {
        if (sizeof($distanceMatrix[$gate][$process]) == 2) {
          unset($temp[$k]);
          array_push($queue, $gate);
          array_push($nearGatewaysClusters[$i], $gate);
        }
      }
    }
    $i++;
  }


  $minDistance = array();
  for($i = 0; $i < sizeof($nearGatewaysClusters); $i++) {
    for($j = 0; $j < sizeof($nearGatewaysClusters[$i]); $j++) {
      if (!isset($minDistance[$i]) || sizeof($distanceMatrix[$nearGatewaysClusters[$i][$j]][$agent]) - 1 < $minDistance[$i]) {
        $minDistance[$i] = sizeof($distanceMatrix[$nearGatewaysClusters[$i][$j]][$agent]) - 1;
        $closest[$i] = $nearGatewaysClusters[$i][$j];
      }
    }
  }
  error_log(var_export($minDistance, true));
  for($i = 0; $i < sizeof($nearGatewaysClusters); $i++) {
    for ($j = 0; $j < sizeof($nearGatewaysClusters[$i]); $j++) {
      $minDistance[$i]++;
      for($l = 0; $l < sizeof($gateways); $l++) {
        if (sizeof($distanceMatrix[$gateways[$l]][$nearGatewaysClusters[$i][$j]]) == 2) {
          $minDistance[$i]--;
        }
      }
    }
  }

  asort($minDistance);

  $distanceKeys = array_keys($minDistance);
  $dangeredCluster = $distanceKeys[0];
  for($i = 0; $i < sizeof($nearGatewaysClusters[$dangeredCluster]); $i++) {
    $count = 0;
    for ($j = 0; $j < sizeof($gateways); $j++) {
      if (sizeof($distanceMatrix[$gateways[$j]][$nearGatewaysClusters[$dangeredCluster][$i]]) == 2) {
        $count++;
        $gateway = $gateways[$j];
      }
    }
    if ($count > 1) {
      return array($gateway, $nearGatewaysClusters[$dangeredCluster][$i]);
    }
  }

  $closestTarget = $closest[$dangeredCluster];
  for($i = 0; $i < sizeof($gateways); $i++) {
    if (sizeof($distanceMatrix[$closestTarget][$gateways[$i]]) == 2) {
      return array($closestTarget, $gateways[$i]);
    }
  }

}

function deleteConnection($source, $destination, $size) {
  global $distanceMatrix, $connections;

  initiateDistanceMatrix($size);

  $tempConnections = array();
  for ($i = 0; $i < sizeof($connections); $i++) {
    $item = $connections[$i];
    if (!(($item[0] == $source && $item[1] == $destination) || ($item[1] == $source && $item[0] == $destination))) {
      $tempConnections[] = $item;
    }
  }

  $connections = $tempConnections;
  error_log("Number of connections: ". sizeof($connections));
  for ($i = 0; $i < sizeof($connections); $i++) {
    $item = $connections[$i];
    addConnection($item[0], $item[1], $size);
  }
}

fscanf(STDIN, "%d %d %d",
    $N, // the total number of nodes in the level, including the gateways
    $L, // the number of links
    $E // the number of exit gateways
);

initiateDistanceMatrix($N);

for ($i = 0; $i < $L; $i++)
{
  fscanf(STDIN, "%d %d",
      $N1, // N1 and N2 defines a link between these nodes
      $N2
  );
  addConnection($N1, $N2, $N);
  $connections[] = array($N1, $N2);
}
for ($i = 0; $i < $E; $i++)
{
  fscanf(STDIN, "%d",
      $EI // the index of a gateway node
  );
  $gateways[] = $EI;
}
//error_log(var_export($connections, true));

// game loop
while (TRUE)
{
  fscanf(STDIN, "%d",
      $SI // The index of the node on which the Skynet agent is positioned this turn
  );

  $remove = getConnectionToDelete($SI, $gateways, $N);
  deleteConnection($remove[0], $remove[1], $N);
  // Write an action using echo(). DON'T FORGET THE TRAILING \n
  // To debug (equivalent to var_dump): error_log(var_export($var, true));

  echo($remove[0] . " " . $remove[1] . "\n"); // Example: 0 1 are the indices of the nodes you wish to sever the link between
}
?>