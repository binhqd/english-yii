<?php
/**
 * MailHelper - Helper chứa các phương thức phục vụ việc gửi email
 * 
 * @ingroup utils
 * @author  huytbt
 * @date    2011-06-07
 * @version 1.0
 */
class MailHelper
{
    /**
     * Phương thức Send($strSubject, $strMsgHTML, $arrAddress = array()) dùng để gửi email từ hệ thống
     *
     * @param string $strSubject Subject của email
     * @param string $strMsgHTML Nội dung của email (định dạng HTML)
     * @param array $arrAddress Danh sách địa chỉ đích cần gửi email đến Ex: array(array('abc1\@host.com', 'ABC1'), array('abc2\@host.com', 'ABC2'))
     * @return
     */
    public static function Send($strSubject, $strMsgHTML, $arrAddress = array())
    {
        Yii::import('application.extensions.phpmailer.JPhpMailer');
        $mail = new JPhpMailer;
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->Host = Yii::app()->params['mailer']['host'];
        $mail->Port = Yii::app()->params['mailer']['port'];
        $mail->SMTPSecure = Yii::app()->params['mailer']['secure'];
        $mail->Username = Yii::app()->params['mailer']['username'];
        $mail->Password = Yii::app()->params['mailer']['password'];		
        $mail->SetFrom(Yii::app()->params['mailer']['username'], Yii::app()->params['mailer']['name']);
        $mail->Subject = $strSubject;
        $mail->MsgHTML($strMsgHTML);
        foreach ($arrAddress as $address)
            $mail->AddAddress($address[0], $address[1]);
        $mail->Send();
    }
	public function resetUrl($url) {
		if(isset($url)) {
			$cloneUrl = substr($url, 0, 8);
			$mainUrl = substr($url, 8, strlen($url));
			
			if(strcmp($cloneUrl, 'https://') == 0 ) {
				$firstUrl = str_replace('https://', '', $cloneUrl);
			}
			else {
				$firstUrl = str_replace('http://', '', $cloneUrl);
			}
			$newUrl = $firstUrl.$mainUrl;
			
			return $newUrl;
		}		
	}
}
