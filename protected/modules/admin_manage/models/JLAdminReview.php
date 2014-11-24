<?php
Yii::import('application.modules.reviews.models.JLReview');
/**
 * The model used to review. This model is mapping with table reviews.
 *
 * @author Thanh Huy
 * @version 1.0
 * @created 2012-08-28 9:01 AM
 */

/**
 * This is the model class for table "reviews".
 *
 * The followings are the available columns in table 'reviews':
 * @property string $id
 * @property string $business_id
 * @property string $user_id
 * @property string $content
 * @property string $rate
 * @property string $created
 * @property integer $helpfuls
 * @property integer $unhelpfuls
 * @property integer $has_badwords
 * @property integer $is_similar_biz
 * @property integer $status
 * @property integer $is_draft
 */
class JLAdminReview extends JLReview
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return JLReview the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * This method is used to get reviews has badwords
	 */
	public function getReviewsHasBadwords()
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
	 * This method is used to get all reviews
	 */
	public function getReviews()
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
	 * This method is used to delete a review
	 */
	public function deleteReview($review_id)
	{
		$model = self::model()->findByPk($review_id);
		if (empty($model)) throw new Exception('Review does not exists');
		$modelTransaction = $model->dbConnection->beginTransaction(); // Transaction for model begin
		try {
			if (!$model->delete())
				throw new Exception('Cannot delete review.');
			
			if ($model->is_draft==self::NO_DRAFT && $model->status==self::TYPE_APPROVED) {
				// Delete all comment & compliment of review
				Yii::import('application.modules.reviews.models.JLComment');
				JLComment::model()->deleteAllByAttributes(array(
					'object_type' => JLComment::TYPE_REVIEW,
					'object_id' => $model->id,
				));
				Yii::import('application.modules.reviews.models.JLCompliment');
				JLCompliment::model()->deleteAllByAttributes(array(
					'object_type' => JLCompliment::TYPE_REVIEW,
					'object_id' => $model->id,
				));
				
				// TODO: Delete top review in search page
			}
			
			$modelTransaction->commit();
			
			if ($model->is_draft==self::NO_DRAFT && $model->status==self::TYPE_APPROVED) {
				/***** Remove point of user *****/
				// Attach behavior for user
				$user = JLUser::getUserInfo($model->user_id);
				Yii::import('application.modules.pointSystem.models.JLPointSystem');
				$user->attachBehavior('UserPoint', 'application.modules.pointSystem.components.behaviors.JLUserPointBehavior');
				$user->retrieveAction(JLPointSystem::WRITE_REVIEW, null, $model->id);
				if ($model->is_first)
					$user->retrieveAction(JLPointSystem::FIRST_REVIEW, null, $model->id);
				$user->detachBehavior('UserPoint');
				
				// Decrease reviews in business
				Yii::import('application.modules.businesses.models.JLBusiness');
				$modelBiz = JLBusiness::model()->findByPk($model->business_id);
				if ($modelBiz) $modelBiz->decReviews();
			}
			return array(
				'error' => false,
				'model' => $model,
			);
		} catch (Exception $e) {
			$modelTransaction->rollBack();
			return array(
				'error' => true,
				'msg' => $e->getMessage(),
			);
		}
	}
}
