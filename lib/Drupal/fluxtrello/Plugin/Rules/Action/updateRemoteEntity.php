<?php

/**
 * @file
 * Contains updateRemoteEntity.
 */

namespace Drupal\fluxtrello\Plugin\Rules\Action;

use Drupal\fluxtrello\Plugin\Service\TrelloAccountInterface;
use Drupal\fluxtrello\Rules\RulesPluginHandlerBase;

/**
 * update remote entities.
 */
class updateRemoteEntity extends RulesPluginHandlerBase implements \RulesActionHandlerInterface {

  /**
   * Defines the action.
   */
  public static function getInfo() {

    return static::getInfoDefaults() + array(
      'name' => 'fluxtrello_update_remote_entity',
      'label' => t('Update remote entity'),
      'parameter' => array(
        'account' => static::getServiceParameterInfo(),
        'remote_entity' => array(
          'type' => 'entity',
          'label' => t('Trello: Entity'),
          'wrapped' => FALSE,
          'required' => TRUE,
        ),
        'local_entity' => array(
          'type' => 'entity',
          'label' => t('Local: Entity'),
          'wrapped' => TRUE,
          'required' => TRUE,
        ),
      ),
      'provides' => array(
        'updated_entity' => array(
          'type'=>'entity',
          'label' => t('Updated entity')),
      )
    );
  }

  /**
   * Executes the action.
   */
  public function execute(TrelloAccountInterface $account, $remote_entity, $local_entity) {
    dpm('update remote');
    print_r('update remote');

    $local_type="";
    $local_id=0;
    $isNode=1;
    if(method_exists($local_entity, 'entityType')){
      $local_type=$local_entity->entityType();
      $local_id=$local_entity->id;
      $isNode=0;
    }
    else{
      $local_type=$local_entity->type();
      $local_id=$local_entity->getIdentifier();
    }

    $controller = entity_get_controller($remote_entity->entityType());
    
    $updated = $controller->updateRemote($local_id, $local_type, $isNode, $account, $remote_entity);

    return array('updated_entity'=>entity_metadata_wrapper($remote_entity->entityType(),$updated));
  }
}
