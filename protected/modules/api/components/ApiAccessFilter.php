<?php

/**
 * Api Access Filter
 *
 * @author TienVV
 * @version 1.0
 */
class ApiAccessFilter extends CFilter {

	/**
	 * Performs the pre-action filtering.
	 * @param CFilterChain $filterChain the filter chain that the filter is on.
	 * @return boolean whether the filtering process should continue and the action
	 * should be executed.
	 */
	protected function preFilter($filterChain) {
		$controller = $filterChain->controller;
		// Check if the action should be allowed
		if (currentUser()->isGuest && !$this->isAllowedAction($filterChain)) {
			$controller->accessDenied();
			return false;
		}
		return true;
	}

	/**
	 * Sets the allowed actions.
	 * @param string $allowedActions the actions that are always allowed separated by commas,
	 * you may also use star (*) to represent all actions.
	 */
	public function isAllowedAction($filterChain) {
		$allowedActions = $filterChain->controller->allowedActions();
		if ($allowedActions === '*') {
			return true;
		} else {
			$allowedActions = preg_split('/[\s,]+/', $allowedActions, -1, PREG_SPLIT_NO_EMPTY);
			if (in_array($filterChain->action->id, $allowedActions) !== false) {
				return true;
			}
		}
		return false;
	}

}