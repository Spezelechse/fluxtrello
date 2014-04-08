<?php

/**
 * @file
 * Contains TrelloCardPostedTaskHandler.
 */

namespace Drupal\fluxtrello\TaskHandler;

/**
 * Event dispatcher for changed trello cards.
 */
class TrelloCardTaskHandler extends TrelloTaskHandlerBase {
 /**
   * {@inheritdoc}
   */
	public function runTask() {
	  	print_r("<br>card<br>");
	}
}