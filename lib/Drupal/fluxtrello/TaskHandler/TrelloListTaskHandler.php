<?php

/**
 * @file
 * Contains TrelloListPostedTaskHandler.
 */

namespace Drupal\fluxtrello\TaskHandler;

/**
 * Event dispatcher for changed trello lists.
 */
class TrelloListTaskHandler extends TrelloTaskHandlerBase {
 /**
   * {@inheritdoc}
   */
	public function runTask() {
	  	print_r("<br>list<br>");
	}
}