<?php
/**
 * Auto-generated code below aims at helping you parse
 * the standard input according to the problem statement.
 **/

class Dwarf {
  /** @var  Dwarf[] */
  private $myDwarfs;

  /** @var  Dwarf[] */
  private $myGiants;

  /** @var int */
  private $longestInfluences;

  function __construct()
  {
    $this->myDwarfs = array();
    $this->myGiants = array();
    $this->longestInfluences = 1;
  }

  function addAsGiant($dwarf) {
    $this->myDwarfs[] = $dwarf;
    foreach($this->myDwarfs as $dwarf) {
      $dwarf->scanLongest();
    }
  }

  function addAsDwarf($dwarf) {
    $this->myGiants[] = $dwarf;
    self::scanLongest();
    foreach($this->myDwarfs as $dwarf) {
      $dwarf->scanLongest();
    }
  }

  function scanLongest() {
    foreach($this->myGiants as $giant) {
      $giant->scanLongest();
      if ($giant->getLongestInfluences() >= $this->longestInfluences) {
        $this->longestInfluences = $giant->getLongestInfluences() + 1;
      }
    }
  }

  public function getLongestInfluences()
  {
    return $this->longestInfluences;
  }
}

$dwarfs = array();
fscanf(STDIN, "%d",
    $n // the number of relationships of influence
);
for ($i = 0; $i < $n; $i++)
{
    fscanf(STDIN, "%d %d",
        $x, // a relationship of influence between two people (x influences y)
        $y
    );
  if (!isset($dwarfs["$x"])) {
    $dwarfs["$x"] = new Dwarf();
  }
  if (!isset($dwarfs["$y"])) {
    $dwarfs["$y"] = new Dwarf();
  }
  $dwarfs["$x"]->addAsGiant($dwarfs["$y"]);
  $dwarfs["$y"]->addAsDwarf($dwarfs["$x"]);
}

$max = 0;
foreach($dwarfs as $id => $dwarf) {
  $max = max($max, $dwarf->getLongestInfluences());
}

// Write an action using echo(). DON'T FORGET THE TRAILING \n
// To debug (equivalent to var_dump): error_log(var_export($var, true));

echo("$max\n"); // The number of people involved in the longest succession of influences
?>