<?php

/**
 * @file
 * Contains createRemoteLists.
 */

namespace Drupal\fluxtrello\Plugin\Rules\Action;

use Drupal\fluxtrello\Plugin\Service\TrelloAccountInterface;
use Drupal\fluxtrello\Rules\RulesPluginHandlerBase;

/**
 * create remote lists.
 */
class createRemoteLists extends RulesPluginHandlerBase implements \RulesActionHandlerInterface {

  /**
   * Defines the action.
   */
  public static function getInfo() {

    return static::getInfoDefaults() + array(
      'name' => 'fluxtrello_create_remote_lists',
      'label' => t('Create remote lists'),
      'parameter' => array(
        'account' => static::getServiceParameterInfo('fluxtrello'),
        'board_id' => array(
          'type' => 'text',
          'label' => t('Trello: board id'),
          'required' => TRUE,
        ),
        'local_entity' => array(
          'type' => 'entity',
          'label' => t('Local: Entity'),
          'wrapped' => TRUE,
          'required' => TRUE,
        ),
        'vocab_list' => array(
          'type' => 'list',
          'label' => t('Vocabulary list'),
          'required' => TRUE,
        )
      )
    );
  }

  /**
   * Executes the action.
   */
  public function execute(TrelloAccountInterface $account, $board_id, $local_entity, $vocab_list) {
    dpm('create remote lists');
    print_r('create remote lists<br>');

    $controller = entity_get_controller('fluxtrello_list');
    $client = $account->client();
    
    //$created = $controller->createRemote($local_id, $local_type, $isNode, $account, $remote_entity);

    $lists=$client->getBoardLists(array(  'remote_id'=>$board_id,
                                          'key'=>$client->getConfig('consumer_key'),
                                          'token'=>$client->getConfig('token')));

    foreach($lists as  $key => $list){
      $lists[$key]['checksum']=md5(json_encode($list));
    }

    $lists = fluxservice_entify_multiple($lists, 'fluxtrello_list', $account);

    foreach ($vocab_list as $vocab) {
      $list=array_shift($lists);
      $vocab=entity_metadata_wrapper('taxonomy_term',$vocab);

      if(isset($list)){
        $controller->createLocal($list, $vocab->getIdentifier(), $vocab->type(),1);
        $list->name=$vocab->name->value();
        $controller->updateRemote($vocab->getIdentifier(), $vocab->type(), 1, $account, $list);
      }
      else{
        $list=entity_create('fluxtrello_list',array('name'=>$vocab->name->value(),
                                                    'idBoard'=>$board_id));
        $controller->createRemote($vocab->getIdentifier(), $vocab->type(), 1, $account, $list);
      }
    } 
  }
}
