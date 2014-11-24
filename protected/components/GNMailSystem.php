<?php
Yii::import('application.extensions.yii-mail.*');
/**
 * GNMailSystem - This component is used to process mail of system
 *
 * @author Thanh Huy
 * @version 1.0
 * @created 24-Jan-2013 3:44:20 PM
 * @modified 30-Jan-2013 4:56:49 PM
 */
class GNMailSystem extends YiiMail
{
	/**
	 * This method is used to send mail with template
	 */
	public function sendMailWithTemplate($email = NULL, $subject = NULL, $view = NULL, $data = NULL,$from=NULL)
	{
		if (isset($email) && isset($view)) {
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

				return $this->send($message);
			} catch (Exception $ex) {
				throw $ex;
			}
		}
	}
}