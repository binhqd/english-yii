<?php
class GNDeleteCommentAction extends GNAction {
	
	public function run () {
		if (!empty($_POST)) {
			$model		= $this->_model;
			$data		= $_POST;
			$delModel	= $model::model()->findByPk(IDHelper::uuidToBinary($data['commentId']));
			
			if (!empty($delModel)) {
				// Check owner
				if ($delModel->user_id != currentUser()->id) {
					$result	= array (
						'error'		=> true,
						'message'	=> Yii::t("greennet", 'You are not the owner of this comment')
					);
					ajaxOut($result);
				}
				
				// Perform deletion
				if ($delModel->delete()) {
					$result	= array (
						'error'		=> false,
						'message'	=> Yii::t("greennet", 'This comment has been deleted successful!')
					);
				} else {
					$result	= array (
							'error'		=> true,
							'message'	=> Yii::t("greennet", 'Error!')
					);
				}
			}
		} else {
			$result	= array (
				'error'		=> true,
				'message'	=> Yii::t("greennet", 'Error!')
			);
		}
		ajaxOut($result);
	} 
}