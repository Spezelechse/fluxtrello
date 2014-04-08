<?php

/**
 * @file
 * Contains TrelloBoard.
 */

namespace Drupal\fluxtrello\Plugin\Entity;

use Drupal\fluxservice\Entity\RemoteEntity;

/**
 * Entity class for Trello Boards.
 */
class TrelloBoard extends TrelloEntityBase implements TrelloBoardInterface {

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
      'name' => 'fluxtrello_',
      'label' => t('Trello (remote): Board'),
      'module' => 'fluxtrello',
      'service' => 'fluxtrello',
      'controller class' => '\Drupal\fluxtrello\TrelloBoardController',
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
      'description' => t("Board id."),
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
      'description' => t("Board name."),
      'type' => 'text',
      'required' => TRUE,
      'setter callback' => 'entity_property_verbatim_set',
    );
    $info['desc'] = array(
      'label' => t('Description'),
      'description' => t("Board description."),
      'type' => 'text',
      'setter callback' => 'entity_property_verbatim_set',
    );
    $info['descData'] = array(
      'label' => t('Description data'),
      'description' => t("Board description data."),
      'type' => 'text',
      'setter callback' => 'entity_property_verbatim_set',
    );
    $info['closed'] = array(
      'label' => t('Closed'),
      'description' => t("Board closed."),
      'type' => 'boolean',
      'setter callback' => 'entity_property_verbatim_set',
    );
    $info['idOrganization'] = array(
      'label' => t('Organization id'),
      'description' => t("Organization id."),
      'type' => 'text',
      'setter callback' => 'entity_property_verbatim_set',
    );
    $info['pinned'] = array(
      'label' => t('Pinned'),
      'description' => t("Board pinned."),
      'type' => 'boolean',
      'setter callback' => 'entity_property_verbatim_set',
    );
    $info['url'] = array(
      'label' => t('URL'),
      'description' => t("Board url."),
      'type' => 'text',
      'setter callback' => 'entity_property_verbatim_set',
    );
    $info['shortUrl'] = array(
      'label' => t('shortUrl'),
      'description' => t("Board short url."),
      'type' => 'text',
      'setter callback' => 'entity_property_verbatim_set',
    );
    $info['prefs'] = array(
      'label' => t('Prefs'),
      'description' => t("Board prefs."),
      'type' => 'structure',
      'setter callback' => 'entity_property_verbatim_set',
      'property info' => array(
        'permissionLevel' => array(
          'label' => t('Permission level'),
          'description' => t('Permission level.'),
          'type' => 'text',
          'setter callback' => 'entity_property_verbatim_set',
        ),
        'voting' => array(
          'label' => t('Voting'),
          'description' => t('Voting.'),
          'type' => 'text',
          'setter callback' => 'entity_property_verbatim_set',
        ),
        'comments' => array(
          'label' => t('Comments'),
          'description' => t('Comments.'),
          'type' => 'text',
          'setter callback' => 'entity_property_verbatim_set',
        ),
        'invitations' => array(
          'label' => t('Invitations'),
          'description' => t('Invitations.'),
          'type' => 'text',
          'setter callback' => 'entity_property_verbatim_set',
        ),
        'selfJoin' => array(
          'label' => t('Self join'),
          'description' => t('Self join.'),
          'type' => 'boolean',
          'setter callback' => 'entity_property_verbatim_set',
        ),
        'cardCovers' => array(
          'label' => t('Card covers'),
          'description' => t('Card covers.'),
          'type' => 'boolean',
          'setter callback' => 'entity_property_verbatim_set',
        ),
        'background' => array(
          'label' => t('Backgound'),
          'description' => t('Backgound'),
          'type' => 'text',
          'setter callback' => 'entity_property_verbatim_set',
        ),
        'backgroundColor' => array(
          'label' => t('Background color'),
          'description' => t('Background color'),
          'type' => 'text',
          'setter callback' => 'entity_property_verbatim_set',
        ),
        'backgroundImage' => array(
          'label' => t('Background image'),
          'description' => t('Background image'),
          'type' => 'text',
          'setter callback' => 'entity_property_verbatim_set',
        ),
        'backgroundImageScaled' => array(
          'label' => t('Background image scaled'),
          'description' => t('Background image scaled'),
          'type' => 'text',
          'setter callback' => 'entity_property_verbatim_set',
        ),
        'backgroundTile' => array(
          'label' => t('Background tile'),
          'description' => t('Background tile'),
          'type' => 'boolean',
          'setter callback' => 'entity_property_verbatim_set',
        ),
        'backgroundBrightness' => array(
          'label' => t('Background brightness'),
          'description' => t('Background brightness'),
          'type' => 'text',
          'setter callback' => 'entity_property_verbatim_set',
        ),
        'canBePublic' => array(
          'label' => t('Can be public'),
          'description' => t('Can be public'),
          'type' => 'boolean',
          'setter callback' => 'entity_property_verbatim_set',
        ),
        'canBeOrg' => array(
          'label' => t('Can be org'),
          'description' => t('Can be org'),
          'type' => 'boolean',
          'setter callback' => 'entity_property_verbatim_set',
        ),
        'canBePrivate' => array(
          'label' => t('Can be private'),
          'description' => t('Can be private'),
          'type' => 'boolean',
          'setter callback' => 'entity_property_verbatim_set',
        ),
        'canInvite' => array(
          'label' => t('Can invite'),
          'description' => t('Can invite'),
          'type' => 'boolean',
          'setter callback' => 'entity_property_verbatim_set',
        )
      )
    );
    $info['labelNames'] = array(
      'label' => t('Label names'),
      'description' => t("Label names."),
      'type' => 'structure',
      'setter callback' => 'entity_property_verbatim_set',
      'property info' => array(
        'red' => array(
          'label' => t('Red'),
          'description' => t('Red'),
          'type' => 'text',
          'setter callback' => 'entity_property_verbatim_set',
        ),
        'organge' => array(
          'label' => t('Organge'),
          'description' => t('Organge'),
          'type' => 'text',
          'setter callback' => 'entity_property_verbatim_set',
        ),
        'yellow' => array(
          'label' => t('Yellow'),
          'description' => t('Yellow'),
          'type' => 'text',
          'setter callback' => 'entity_property_verbatim_set',
        ),
        'green' => array(
          'label' => t('Green'),
          'description' => t('Green'),
          'type' => 'text',
          'setter callback' => 'entity_property_verbatim_set',
        ),
        'blue' => array(
          'label' => t('Blue'),
          'description' => t('Blue'),
          'type' => 'text',
          'setter callback' => 'entity_property_verbatim_set',
        ),
        'purple' => array(
          'label' => t('Purple'),
          'description' => t('Purple'),
          'type' => 'text',
          'setter callback' => 'entity_property_verbatim_set',
        )
      )
    );
    return $info;
  }
}
