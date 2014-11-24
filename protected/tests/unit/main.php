<?php
 
require_once dirname(__FILE__) . '/../boot.php';

Yii::import('ext.EnhancePHP.*');

// Find the tests - '.' is the current folder
EnhancePHPCore::discoverTests('./Review', true, array(''));
// Run the tests
EnhancePHPCore::runTests();
 
?>
