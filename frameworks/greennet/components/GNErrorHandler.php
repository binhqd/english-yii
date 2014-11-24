<?php
class GNErrorHandler extends CComponent {
	public static function handlingError($err) {
		//Yii::log($err->message, 'error', "error-{$err->code}");
		$err = array(
			'error'		=> 1,
			'message'	=> $err->message,
			'code'		=> $err->code,
			'file'		=> $err->file,
			'line'		=> $err->line
		);
		
		// clean output that read before
// 		$backtrace = debug_backtrace();
		
// 		$cnt = 0;
// 		$traces = array(); 
// 		foreach ($backtrace as $item) {
// 			if ($cnt == 0) {$cnt++;continue;}
// 			if (isset($item['file']) && isset($item['line'])) {
// 				$traces[] = "{$item['file']} ({$item['line']}):";
// 			} else {
// 				$traces[] = "{$item['class']}{$item['type']}{$item['function']}";
// 			}
// 			//$traces[] = $item;
// 		}
		
// 		$msg = $err['message'] . "\n" . implode("\n", $traces);
		
// 		Yii::log($msg, 'warning', 'php error');
		// show message
		/*ob_clean();
		Yii::app()->controller->showMessage("JustLook Error", $err['message'], GNController::MESSAGE_ERROR);
		Yii::app()->end();*/
	}
	
	public static function handlingException($event) {
		if (isset($event->exception)) {
			$ex = $event->exception;
		} else {
			$ex = $event;
		}
		
		//Yii::log($ex->getMessage(), 'error', "error-{$ex->getCode()}");
		$err = array(
			'error'		=> 1,
			'message'	=> $ex->getMessage(),
			'code'		=> $ex->getCode(),
			'file'		=> $ex->getFile(),
			'line'		=> $ex->getLine(),
			'trace'		=> $ex->getTrace()
		);
		
		if ($err['message'] == "Property \"RWebUser.model\" is not defined.") {
			header("Location: /login");
			Yii::app()->end();
		} else if ($ex->getCode() > 10000){
			if (Yii::app()->request->isAjaxRequest) {
				jsonOut(array(
					'error'		=> true,
					'message'	=> $ex->getMessage()
				));
			} else {
				// clean output that read before
				ob_clean();
				// show message
				Yii::app()->controller->showMessage("JustLook Error", $ex->getMessage(), GNController::MESSAGE_ERROR);
				Yii::app()->end();
			}
		} else {
			//Yii::log('test', CLogger::LEVEL_ERROR, 'test');
// 			watch($logger);
			//Yii::log($err['message'], CLogger::LEVEL_ERROR, 'exception.CHttpException.404');
		}
	}
}