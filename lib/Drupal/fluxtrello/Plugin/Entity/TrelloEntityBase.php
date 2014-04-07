<?php

/**
 * @file
 * Contains TrelloEntityBase.
 */

namespace Drupal\fluxmite\Plugin\Entity;

use Drupal\fluxservice\Entity\RemoteEntity;

/**
 * Entity class for Trello Customers.
 */
class TrelloEntityBase extends RemoteEntity implements TrelloEntityBaseInterface {
	public function __construct(array $values = array(), $entity_type = NULL) {
		parent::__construct($values, $entity_type);
   	}
}