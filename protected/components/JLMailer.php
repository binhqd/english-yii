<?php
Yii::import('application.extensions.yii-mail.*');
class JLMailer extends YiiMail {
	public function sendMailWithTemplate($email = NULL, $subject = NULL, $view = NULL, $data = NULL,$from=NULL) { // add argument {from} , author: thinhpq
		if (isset($email) && isset($view)) {
			/**
			 * @todo Change message email
			 */
			$subject = isset($subject) ? $subject : "(No Subject)";

			try {
				$message = new YiiMailMessage;
				$message->view = $view;
				$message->setSubject($subject);
				$message->setBody(
					array(
						'data'	=> isset($data) ? $data : array()
					),
					'text/html'
				);

				$message->addTo($email);
				if(empty($from)) $from = Yii::app()->params->mailer['username'];
				$message->from = $from;

				$this->send($message);
			} catch (Exception $ex) {
				// TODO : Can't send mail
				return array(
					'error'			=> true,
					'msg'			=> $ex->getMessage()
				);
			}
		}
	}
}