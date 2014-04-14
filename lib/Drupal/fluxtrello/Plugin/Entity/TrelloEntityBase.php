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

		if(isset($values['id'])){
	   		$id=explode(':', $values['id']);
	   		$this->trello_id=$id[2];
   		}
   	}

	/**
   	  * Gets the entity property definitions.
   	  */
  	public static function getEntityPropertyInfo($entity_type, $entity_info) {
	   	$info['checksum'] = array(
	      'label' => t('Checksum'),
	      'description' => t("Entity checksum."),
	      'type' => 'text',
	      'setter callback' => 'entity_property_verbatim_set',
	    );

	    return $info;
   	}
}