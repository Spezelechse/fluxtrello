<?php

/**
 * @file
 * Contains TrelloBoardPostedTaskHandler.
 */

namespace Drupal\fluxtrello\TaskHandler;

/**
 * Event dispatcher for changed trello boards.
 */
class TrelloBoardTaskHandler extends TrelloTaskHandlerBase {
 /**
   * {@inheritdoc}
   */
	public function runTask() {
	  	print_r("<br>board<br>");
	}
}