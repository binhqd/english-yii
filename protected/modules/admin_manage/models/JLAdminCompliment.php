<?php
Yii::import('application.modules.reviews.models.JLCompliment');
/**
 * The model used to compliment. This model is mapping with table compliments.
 *
 * @author Thanh Huy
 * @version 1.0
 * @created 2012-09-10 16:04
 */
class JLAdminCompliment extends JLCompliment
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return JLCompliment the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * This method is used to get compliments has badwords
	 */
	public function getComplimentsHasBadwords()
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
	 * This method is used to get all compliments
	 */
	public function getCompliments()
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
	 * This method is used to delete a compliment
	 */
	public function deleteCompliment($cpmliment_id)
	{
		$model = self::model()->findByPk($compliment_id);
		return $model->delete();
	}
}
