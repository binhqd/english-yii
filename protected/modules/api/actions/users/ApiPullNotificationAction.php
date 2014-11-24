<?php

/**
 * @author tienvv <tienvv.it@gmail.com>
 * @version 1.0
 * 
 */
class ApiPullNotificationAction extends GNAction {

	/**
	 * This method is used to run action
	 */
	public function run() {
		Yii::import('application.components.notification.JLNotificationReader');
		Yii::import('application.components.notification.JLNotificationDocument');
		Yii::import('application.components.notification.JLNotificationWriter');

		$strUserID = currentUser()->hexID;
		$Paginate = $this->controller->paginate(0);

		$notifications = JLNotificationReader::getNotifications($strUserID
						, $Paginate->limit, $Paginate->offset);
		$result = array();
		foreach ($notifications as $item) {
			$className = $item->type;
			$class = Yii::import($className);
			$renderer = new $class;

			try {
				$data = CMap::mergeArray($item->data, array(
							'created' => date(DATE_ISO8601, $item->created),
							'defaultLink' => null,
				));
				$message = $renderer->render($data);
				if (empty($message)) {
					continue;
				}
				//$message = preg_replace('/<img[^>]+\>/i', '', $message);
			} catch (Exception $ex) {
				continue;
			}
			$notifier = null;
			if (!empty($item->notifier_id)) {
				$notifier = GNUser::model()->get($item->notifier_id);
				if (!$notifier || $notifier['hexID'] == '2d31') {
					continue;
				}
			}
			$ret = array(
				'receiver_id' => $strUserID,
				'message' => preg_replace('/<img[^>]+\>/i', '', $message),
				'read' => $item->read,
				'is_clicked' => $item->isClicked,
				'id' => $item->_id->{'$id'},
				'created' => date(DATE_ISO8601, $item->created),
				'timestamp' => intval($item->created)
			);

			if (!empty($item->notifier_id)) {
				if (!empty($notifier['hexID'])) {
					$ret['notifier'] = array(
						'type' => 'no',
						'id' => $notifier['id'],
						'username' => $notifier['username'],
						'displayname' => $notifier['displayname']
					);
					$ret['avatar'] = @$notifier['profile']['image'];
				} else {
					$ret['notifier'] = array(
						'type' => 'bm',
						'id' => $notifier['bizid'],
						'bizname' => $notifier['bizname'],
						'owner_id' => $notifier['owner_id'],
					);
					$ret['filepath'] = $notifier['avatar'];
				}
			}

			if (!empty($item->data['defaultLink'])) {
				$ret['defaultLink'] = $item->data['defaultLink'];
			}
			$ret['type'] = $item->action;

			if ($item->action == 'postArticle') {
				$article = ZoneArticle::model()->find('id=:id', array(
					':id' => IDHelper::uuidToBinary($item->data['article_id'])
				));
				$nodeInfo = $article->namespace;
				$strNodeId = IDHelper::uuidFromBinary($nodeInfo->holder_id, true);
				$image = ZoneResourceImage::getNamespaceImage($strNodeId);
				$ret['otherInfo'] = array(
					'node' => array(
						'id' => $strNodeId,
						'image' => $image ? $image : null
					)
				);
			}

			$result[] = $ret;
		}
		$this->controller->out(200, array(
			'data' => $result,
			'cdn' => ZoneRouter::CDNUrl("/"),
			//'unread' => JLNotificationReader::countNotifications($strUserID),
			'page' => $Paginate->currentPage + 1,
			'limit' => $Paginate->limit
				), false);

		if(!isset($_GET['markRead']) || !empty($_GET['markRead'])){
			JLNotificationDocument::markRead($strUserID);
		}
		$data = array(
			"receiver_id" => $strUserID,
			"command" => "reset",
			"data" => "",
			"notifier_id" => $strUserID
		);
		JLNotificationWriter::sendRFC($strUserID, $data);
	}

}