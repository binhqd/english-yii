<?php
/**
 * Class that handling error, logging them with mongoDB
 * @author BinhQD
 *
 */
Yii::import('greennet.modules.error_handling.models.GNMySQLErrorLog');
class CustomErrorHandler extends GNMySQLErrorLog {
	
}