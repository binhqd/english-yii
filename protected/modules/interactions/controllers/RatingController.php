<?php

/**
 * @author MinhNC
 * @version 1.0
 * @created 12-Mar-2013 3:30:26 PM
 */
class RatingController extends GNController
{
	/**
	 * Import action
	 * @return multitype:string
	 */
	public function actions()
	{
		return array(
			'rate'					=> 'greennet.modules.ratings.actions.GNRateAction',
			'getRatingForObject'	=> 'greennet.modules.ratings.actions.GNRatingStatisticAction'
		);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see RController::allowedActions()
	 */
	public function allowedActions() {
		return '*';
	}
}
