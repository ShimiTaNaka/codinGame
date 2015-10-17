<?php
/**
 * Auto-generated code below aims at helping you parse
 * the standard input according to the problem statement.
 **/

fscanf(STDIN, "%d",
    $surfaceN // the number of points used to draw the surface of Mars.
);
$surface = array();
for ($i = 0; $i < $surfaceN; $i++)
{
    fscanf(STDIN, "%d %d",
        $landX, // X coordinate of a surface point. (0 to 6999)
        $landY // Y coordinate of a surface point. By linking all the points together in a sequential fashion, you form the surface of Mars.
    );
  $surface[] = array($landX, $landY);
}

function getLandingArea() {
  global $surface;

  for($i = 1; $i < sizeof($surface); $i++) {
    if ($surface[$i-1][1] ==  $surface[$i-1][1]) {
      return array($surface[$i-1], $surface[$i]);
    }
  }
}

function getHighestPointInTheWay($posX, $posY, $landingArea) {
  global $surface;

  $middleOfLandingArea = array(($landingArea[0][0] + $landingArea[1][0])/2, ($landingArea[0][1] + $landingArea[1][1])/2);

  $a = ($middleOfLandingArea[1] - $posY)/($middleOfLandingArea[0] - $posX);
  $b = ($posX * $a) + $posY;

  $highest = false;
  for($i = 0; $i < sizeof($surface); $i++) {
    if (($surface[$i][0]<= $posX) || ($surface[$i][0] > $middleOfLandingArea[0])) {
      continue;
    }
    $calculatedY = ($a * $surface[$i][0]) + $b;
    if (($calculatedY <= $surface[$i][1]) && (!$highest || $highest[1] <= $surface[$i][1])) {
      $highest = $surface[$i];
    }
  }

  return $highest;
}

$landingArea = getLandingArea();
// game loop
while (TRUE)
{
    fscanf(STDIN, "%d %d %d %d %d %d %d",
        $X,
        $Y,
        $hSpeed, // the horizontal speed (in m/s), can be negative.
        $vSpeed, // the vertical speed (in m/s), can be negative.
        $fuel, // the quantity of remaining fuel in liters.
        $rotate, // the rotation angle in degrees (-90 to 90).
        $power // the thrust power (0 to 4).
    );

    // Write an action using echo(). DON'T FORGET THE TRAILING \n
    // To debug (equivalent to var_dump): error_log(var_export($var, true));

    echo("-20 3\n"); // rotate power. rotate is the desired rotation angle. power is the desired thrust power.
}
?>