<?php
class Counter {
  public $num = 0;
  public function increment ($num = 1) {
    $this->num = $this->num + $num;
    return $this->num;
  }
}
