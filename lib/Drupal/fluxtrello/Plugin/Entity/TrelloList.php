<?php

/**
 * @file
 * Contains TrelloList.
 */

namespace Drupal\fluxtrello\Plugin\Entity;

use Drupal\fluxservice\Entity\RemoteEntity;

/**
 * Entity class for Trello Lists.
 */
class TrelloList extends TrelloEntityBase implements TrelloListInterface {

  public function __construct(array $values = array(), $entity_type = NULL) {
    parent::__construct($values, $entity_type);
  }

  /**
   * Defines the entity type.
   *
   * This gets exposed to hook_entity_info() via fluxservice_entity_info().
   */
  public static function getInfo() {
    return array(
      'name' => 'fluxtrello_list',
      'label' => t('Trello (remote): List'),
      'module' => 'fluxtrello',
      'service' => 'fluxtrello',
      'controller class' => '\Drupal\fluxtrello\TrelloListController',
      'label callback' => 'entity_class_label',
      'entity keys' => array(
        'id' => 'id',
        'remote id' => 'id',
      ),
    );
  }

  /**
   * Gets the entity property definitions.
   */
  public static function getEntityPropertyInfo($entity_type, $entity_info) {
    $info['id'] = array(
      'label' => t('Id'),
      'description' => t("List id."),
      'type' => 'text',
      'setter callback' => 'entity_property_verbatim_set',
    );
    $info['trello_id'] = array(
      'label' => t('Trello id'),
      'description' => t("Trello id."),
      'type' => 'text',
      'setter callback' => 'entity_property_verbatim_set',
    );
    $info['name'] = array(
      'label' => t('Name'),
      'description' => t("List name."),
      'type' => 'text',
      'required' => TRUE,
      'setter callback' => 'entity_property_verbatim_set',
    );
    $info['closed'] = array(
      'label' => t('Closed'),
      'description' => t("List closed."),
      'type' => 'boolean',
      'setter callback' => 'entity_property_verbatim_set',
    );
    $info['idBoard'] = array(
      'label' => t('Board id'),
      'description' => t("Board id."),
      'type' => 'text',
      'setter callback' => 'entity_property_verbatim_set',
    );
    $info['pos'] = array(
      'label' => t('List position'),
      'description' => t("List position."),
      'type' => 'text',
      'setter callback' => 'entity_property_verbatim_set',
    );
    $info['subscribed'] = array(
      'label' => t('Subscribed'),
      'description' => t("Subscribed."),
      'type' => 'boolean',
      'setter callback' => 'entity_property_verbatim_set',
    );
    return $info;
  }
}