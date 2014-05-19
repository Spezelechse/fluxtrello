<?php

/**
 * @file
 * Contains TrelloEntityBase.
 */

namespace Drupal\fluxtrello\Plugin\Entity;

use Drupal\fluxservice_extension\Plugin\Entity\RemoteEntityExtended;

/**
 * Entity base class.
 */
class TrelloEntityBase extends RemoteEntityExtended implements TrelloEntityBaseInterface {
	public function __construct(array $values = array(), $entity_type = NULL) {
		unset($values['dateLastActivity']);
      	unset($values['dateLastView']);

		parent::__construct($values, $entity_type);

   		$id=explode(':', $values['id']);
   		$this->remote_id=$id[2];

   		$values['id']=$id[2];

	    $this->checksum=md5(json_encode($values));
   	}

	/**
   	  * Gets the entity property definitions.
   	  */
  	public static function getEntityPropertyInfo($entity_type, $entity_info) {
	    $info=parent::getEntityPropertyInfo($entity_type,$entity_info);

	   	$info['checksum'] = array(
	      'label' => t('Checksum'),
	      'description' => t("Entity checksum."),
	      'type' => 'text',
	      'setter callback' => 'entity_property_verbatim_set',
	    );
	    $info['remote_id'] = array(
	      'label' => t('Trello id'),
	      'description' => t("Trello id."),
	      'type' => 'text',
	      'setter callback' => 'entity_property_verbatim_set',
	    );

	    return $info;
   	}

   	public function getCheckValue(){
   		return $this->checksum;
   	}
}