<?php

/**
 * DBTest kiểm tra kết nối cơ sở dữ liệu
 *
 * @author thienhv
 */
class DBTest extends CTestCase {

    /**
     * testConnection
     */
    public function testConnection() {
        $this->assertNotEquals(NULL, Yii::app()->db);
    }

}

?>
