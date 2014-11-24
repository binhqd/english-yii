<?php
class Scoreboard {
  public $home = 0;
  public $away = 0;
  private $home_counter;	
  private $away_counter;	
  
  public function __construct ($home, $away) {
    $this->home_counter = $home;
    $this->away_counter = $away;
  }
 
  public function score_home () {
    $this->home = $this->home_counter->increment();
    return $this->home;
  }
  public function score_away () {
    $this->away = $this->away_counter->increment();
    return $this->away;
  }
  
}
