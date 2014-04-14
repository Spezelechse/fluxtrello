<?php

/**
 * @file
 * Contains TrelloCard.
 */

namespace Drupal\fluxtrello\Plugin\Entity;

use Drupal\fluxservice\Entity\RemoteEntity;

/**
 * Entity class for Trello Cards.
 */
class TrelloCard extends TrelloEntityBase implements TrelloCardInterface {

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
      'name' => 'fluxtrello_card',
      'label' => t('Trello (remote): Card'),
      'module' => 'fluxtrello',
      'service' => 'fluxtrello',
      'controller class' => '\Drupal\fluxtrello\TrelloCardController',
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
    $info=parent::getEntityPropertyInfo($entity_type,$entity_info);

    $info['id'] = array(
      'label' => t('Id'),
      'description' => t("Card id."),
      'type' => 'text',
      'setter callback' => 'entity_property_verbatim_set',
    );
    $info['trello_id'] = array(
      'label' => t('Trello id'),
      'description' => t("Trello id."),
      'type' => 'text',
      'setter callback' => 'entity_property_verbatim_set',
    );
    $info['badges'] = array(
      'label' => t('Badges'),
      'description' => t("Badges."),
      'type' => 'struct',
      'setter callback' => 'entity_property_verbatim_set',
      'property info' => array(
        'votes' => array(
          'label' => t('Vote'),
          'description' => t('Vote'),
          'type' => 'integer',
          'setter callback' => 'entity_property_verbatim_set',
        ),
        'viewingMemberVoted' => array(
          'label' => t('Viewing member voted'),
          'description' => t('Viewing member voted'),
          'type' => 'boolean',
          'setter callback' => 'entity_property_verbatim_set',
        ),
        'subscribed' => array(
          'label' => t('Subscribed'),
          'description' => t('Subscribed'),
          'type' => 'boolean',
          'setter callback' => 'entity_property_verbatim_set',
        ),
        'fogbugz' => array(
          'label' => t('Fogbugz'),
          'description' => t('Fogbugz'),
          'type' => 'text',
          'setter callback' => 'entity_property_verbatim_set',
        ),
        'checkItems' => array(
          'label' => t('Check items'),
          'description' => t('Check items'),
          'type' => 'integer',
          'setter callback' => 'entity_property_verbatim_set',
        ),
        'checkItemsChecked' => array(
          'label' => t('Check items checked'),
          'description' => t('Check items checked'),
          'type' => 'integer',
          'setter callback' => 'entity_property_verbatim_set',
        ),
        'comments' => array(
          'label' => t('Comments'),
          'description' => t('Comments'),
          'type' => 'integer',
          'setter callback' => 'entity_property_verbatim_set',
        ),
        'attachments' => array(
          'label' => t('Attachments'),
          'description' => t('Attachments'),
          'type' => 'integer',
          'setter callback' => 'entity_property_verbatim_set',
        ),
        'description' => array(
          'label' => t('Description'),
          'description' => t('Description'),
          'type' => 'boolean',
          'setter callback' => 'entity_property_verbatim_set',
        ),
        'due' => array(
          'label' => t('Due'),
          'description' => t('Due'),
          'type' => 'date',
          'setter callback' => 'entity_property_verbatim_set',
        ),
      )
    );
    $info['checkItemStates'] = array(
      'label' => t('Check item states'),
      'description' => t("Check item states."),
      'type' => 'list<struct>',
      'setter callback' => 'entity_property_verbatim_set',
      'property info' => array(
        'idCheckItem' => array(
          'label' => t('Id check item'),
          'description' => t('Id check item'),
          'type' => 'text',
          'setter callback' => 'entity_property_verbatim_set',
        ),
        'state' => array(
          'label' => t('State'),
          'description' => t('State'),
          'type' => 'text',
          'setter callback' => 'entity_property_verbatim_set',
        ),
      )
    );
    $info['closed'] = array(
      'label' => t('Closed'),
      'description' => t("Card closed."),
      'type' => 'boolean',
      'setter callback' => 'entity_property_verbatim_set',
    );
    $info['dateLastActivity'] = array(
      'label' => t('Date last activity'),
      'description' => t("Date last activity."),
      'type' => 'date',
      'setter callback' => 'entity_property_verbatim_set',
    );
    $info['desc'] = array(
      'label' => t('Description'),
      'description' => t("Description."),
      'type' => 'text',
      'setter callback' => 'entity_property_verbatim_set',
    );
    $info['descData'] = array(
      'label' => t('Description data'),
      'description' => t("Desciption data."),
      'type' => 'struct',
      'setter callback' => 'entity_property_verbatim_set',
      'property info' => array(
        'emoji' => array(
          'label' => t('Emoji'),
          'description' => t('Emoji'),
          'type' => 'text',
          'setter callback' => 'entity_property_verbatim_set',
        ),
      )
    );
    $info['due'] = array(
      'label' => t('Due'),
      'description' => t("Due."),
      'type' => 'date',
      'setter callback' => 'entity_property_verbatim_set',
    );
    $info['idBoard'] = array(
      'label' => t('Board id'),
      'description' => t("Board id."),
      'type' => 'text',
      'setter callback' => 'entity_property_verbatim_set',
    );
    $info['idChecklists'] = array(
      'label' => t('Id checklists'),
      'description' => t("Id checklists."),
      'type' => 'list<text>',
      'setter callback' => 'entity_property_verbatim_set',
    );
    $info['idList'] = array(
      'label' => t('Id list'),
      'description' => t("Id list."),
      'type' => 'text',
      'setter callback' => 'entity_property_verbatim_set',
    );
    $info['idMembersVoted'] = array(
      'label' => t('Id members voted'),
      'description' => t("Id members voted."),
      'type' => 'list<text>',
      'setter callback' => 'entity_property_verbatim_set',
    );
    $info['idMembers'] = array(
      'label' => t('Id members'),
      'description' => t("Id members."),
      'type' => 'list<text>',
      'setter callback' => 'entity_property_verbatim_set',
    );
    $info['idShort'] = array(
      'label' => t('Id short'),
      'description' => t("Id short."),
      'type' => 'integer',
      'setter callback' => 'entity_property_verbatim_set',
    );
    $info['idAttachmentCover'] = array(
      'label' => t('Id attachment cover'),
      'description' => t("Id attachment cover."),
      'type' => 'text',
      'setter callback' => 'entity_property_verbatim_set',
    );
    $info['manualCoverAttachment'] = array(
      'label' => t('Manual cover attachment'),
      'description' => t("Manual cover attachment."),
      'type' => 'boolean',
      'setter callback' => 'entity_property_verbatim_set',
    );
    $info['labels'] = array(
      'label' => t('Labels'),
      'description' => t("Labels."),
      'type' => 'list<struct>',
      'setter callback' => 'entity_property_verbatim_set',
      'property info' => array(
        'color' => array(
          'label' => t('Color'),
          'description' => t('Color'),
          'type' => 'text',
          'setter callback' => 'entity_property_verbatim_set',
        ),
        'name' => array(
          'label' => t('Name'),
          'description' => t('Name'),
          'type' => 'text',
          'setter callback' => 'entity_property_verbatim_set',
        ),
      )
    );
    $info['name'] = array(
      'label' => t('Name'),
      'description' => t("Card name."),
      'type' => 'text',
      'required' => TRUE,
      'setter callback' => 'entity_property_verbatim_set',
    );
    $info['pos'] = array(
      'label' => t('Card position'),
      'description' => t("Card position."),
      'type' => 'integer',
      'setter callback' => 'entity_property_verbatim_set',
    );
    $info['shortLink'] = array(
      'label' => t('Short link'),
      'description' => t("Short link."),
      'type' => 'text',
      'setter callback' => 'entity_property_verbatim_set',
    );
    $info['shortUrl'] = array(
      'label' => t('Short url'),
      'description' => t("Short url."),
      'type' => 'text',
      'setter callback' => 'entity_property_verbatim_set',
    );
    $info['subscribed'] = array(
      'label' => t('Subscribed'),
      'description' => t('Subscribed'),
      'type' => 'boolean',
      'setter callback' => 'entity_property_verbatim_set',
    );
    $info['url'] = array(
      'label' => t('Url'),
      'description' => t("Url."),
      'type' => 'text',
      'setter callback' => 'entity_property_verbatim_set',
    );
    return $info;
  }
}
