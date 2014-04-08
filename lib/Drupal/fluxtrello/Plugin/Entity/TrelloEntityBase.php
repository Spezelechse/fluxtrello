<?php

/**
 * @file
 * Contains TrelloEntityBase.
 */

namespace Drupal\fluxtrello\Plugin\Entity;

use Drupal\fluxservice\Entity\RemoteEntity;

/**
 * Entity base class.
 */
class TrelloEntityBase extends RemoteEntity implements TrelloEntityBaseInterface {
	public function __construct(array $values = array(), $entity_type = NULL) {
		parent::__construct($values, $entity_type);
   	}
}