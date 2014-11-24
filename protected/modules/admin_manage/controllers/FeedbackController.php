<?php
class FeedbackController extends JLController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/admin';
	private $outputDir = "";
	public $defaultAction = "index";
	//List data type of jl attributes

	public function allowedActions() {
		return '*';
	}
	
	public function init() {
		parent::init();
		Yii::import('application.modules.feedback.models.JLFeedback');
		$this->outputDir = Yii::getPathOfAlias('feedback.data');
		//Yii::import('feedback.models');
	}
	
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionIndex()
	{
		$model = new JLFeedback();
		
		$this->render('index',array(
			'model'	=> $model
		));
	}
	
	public function actionMassDelete() {
		$ids = $_POST['cid'];
		$ids = IDHelper::uuidToBinary($ids);
		
		JLFeedback::model()->deleteAllByAttributes(array(
			'id'	=> $ids
		));
		
		$this->redirect('/admin_manage/feedback');
	}
	/**
	 * This action is used to read a feedback content
	 */
	public function actionView() {
		$id = $_GET['id'];
 		//$feedback = JLFeedback::model()->findByPk(0x4F867DAD6E30421C80221728C0A801BE);
		$feedback = JLFeedback::model()->findByPk(IDHelper::uuidToBinary($id));
		
		if (!empty($feedback)) {
			$this->render('view', array(
				'feedback'	=> $feedback
			));
		} else {
			$this->showMessage("JustLook Notice", "This feedback doesn't exist in our database. Please check feedback ID");
		}
	}
	
	public function actionLoadSnapshot() {
		$_id = $_GET['id'];
		if (is_file($this->outputDir . "/{$_id}.html")) {
			echo file_get_contents($this->outputDir . "/{$_id}.html");
		} else {
			$feedback = JLFeedback::model()->findByPk(IDHelper::uuidToBinary($_id));
				
			if (!empty($feedback)) {
				$content = $feedback->snapshot;
				//file_put_contents($this->outputDir . "/{$_id}.html", $feedback->snapshot);
				echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n";
				echo $content;
			}
		}
		exit;
		//4F842515676049238F081024C0A801BE0000
	}
	
	public function actionViewSnapshot() {
		$_id = $_GET['id'];
	
		$feedback = JLFeedback::model()->findByPk(IDHelper::uuidToBinary($_id));
		$screen = CJSON::decode($feedback->screen);
		// 		debug($screen);
		$regions = CJSON::decode($feedback->regions);
		//if (!file_exists("{$this->outputDir}/{$_id}.jpg")) {
			$command = "wkhtmltoimage  --quality 90 --width {$screen['width']} http://{$_SERVER['HTTP_HOST']}/feedback/feedback/loadSnapshot?id={$_id} \"{$this->outputDir}/{$_id}.jpg\"";
			//debug($command);
			$output = shell_exec($command);
				
			// Draw regions
			$img = imagecreatefromjpeg("{$this->outputDir}/{$_id}.jpg");
				
			$red = imagecolorallocate($img, 0xFF, 0x00, 0x00);
			imagesetthickness($img, 3);
			foreach ($regions as $region) {
				imagerectangle($img, $region['x'], $region['y'], $region['x'] + $region['width'], $region['y'] + $region['height'], $red );
			}
				
			imagejpeg($img, "{$this->outputDir}/{$_id}.jpg", 100);
			imagedestroy($img);
		//}
	
		header("Content-type: image/jpeg");
		echo file_get_contents("{$this->outputDir}/{$_id}.jpg");
		exit;
	}
	
	public function actionViewCanvas() {
		$_id = $_GET['id'];
	
		$feedback = JLFeedback::model()->findByPk(IDHelper::uuidToBinary($_id));
		$screen = CJSON::decode($feedback->screen);
		// 		debug($screen);
		$regions = CJSON::decode($feedback->regions);
		//if (!file_exists("{$this->outputDir}/{$_id}.jpg")) {
		
		$canvas = substr($feedback->canvas, strlen("data:image/png;base64,"));
		
		$filePath = $this->outputDir . "/canvas_{$_id}.png";
		file_put_contents($filePath, base64_decode($canvas));
	
		// Draw regions
		$img = imagecreatefrompng($filePath);
	
		$red = imagecolorallocate($img, 0xFF, 0x00, 0x00);
		imagesetthickness($img, 3);
		foreach ($regions as $region) {
			imagerectangle($img, $region['x'], $region['y'], $region['x'] + $region['width'], $region['y'] + $region['height'], $red );
		}
	
		imagepng($img, $filePath);
		
		//}
	
		header("Content-type: image/jpeg");
		imagepng($img);
		imagedestroy($img);
		//echo file_get_contents("{$this->outputDir}/{$_id}.jpg");
		exit;
	}
	
	public function actionDelete() {
		$id = $_GET['fid'];
		$feedback = JLFeedback::model()->findByPk(IDHelper::uuidToBinary($id));
		
		if (!empty($feedback)) {
			if (file_exists($this->outputDir . "/{$id}.html")) {
				@unlink($this->outputDir . "/{$id}.html");
			}
			
			if (file_exists($this->outputDir . "/{$id}.jpg")) {
				@unlink($this->outputDir . "/{$id}.jpg");
			}
			
			if (file_exists($this->outputDir . "/canvas_{$id}.png")) {
				@unlink($this->outputDir . "/canvas_{$id}.png");
			}
			
			$feedback->delete();
		}
		
		/*$this->setRedirectOptions(array(
			"title"		=> "Justlook message",
			"message"	=> "Feedback has been delete successful"
		));*/
		
		$this->redirect('/admin_manage/feedback');
	}
}