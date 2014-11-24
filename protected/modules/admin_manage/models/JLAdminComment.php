<?php
Yii::import('application.modules.reviews.models.JLComment');
/**
 * The model used to comment. This model is mapping with table comments.
 *
 * @author Thanh Huy
 * @version 1.0
 * @created 2012-09-10 16:04
 */
class JLAdminComment extends JLComment
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return JLComment the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * This method is used to get comments has badwords
	 */
	public function getCommentsHasBadwords()
	{
		$pages = new CPagination();
		$pages->pageSize = 10;
		
		$criteria = new CDbCriteria;
		$criteria->together = true;
		$criteria->condition = 'has_badwords=:has_badwords';
		$criteria->params = array(
			':has_badwords' => self::HAS_BADWORDS,
		);
		$criteria->limit = $pages->limit;
		$criteria->offset = $pages->offset;
		
		return new CActiveDataProvider(__CLASS__, array(
			'criteria' => $criteria,
			'pagination' => $pages,
		));
	}

	/**
	 * This method is used to get all comments
	 */
	public function getComments()
	{
		$pages = new CPagination();
		$pages->pageSize = 10;
		
		$criteria = new CDbCriteria;
		$criteria->together = true;
		$criteria->limit = $pages->limit;
		$criteria->offset = $pages->offset;
		
		return new CActiveDataProvider(__CLASS__, array(
			'criteria' => $criteria,
			'pagination' => $pages,
		));
	}

	/**
	 * This method is used to delete a comment
	 */
	public function deleteComment($comment_id)
	{
		$model = self::model()->findByPk($comment_id);
		return $model->delete();
	}
}
