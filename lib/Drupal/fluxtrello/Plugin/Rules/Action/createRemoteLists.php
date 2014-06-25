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
      'parameter' => static::getServiceParameterInfo('fluxtrello')+array(
        'board_id' => array(
          'type' => 'text',
          'label' => t('Trello: board id'),
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
  public function execute(TrelloAccountInterface $account, $board_id, $vocab_list) {
//    dpm('create remote lists');
  //  print_r('create remote lists<br>');

    $controller = entity_get_controller('fluxtrello_list');
    $client = $account->client();

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
        $controller->updateRemote($vocab->getIdentifier(), $vocab->type(), $account, $list);
      }
      else{
        $list=entity_create('fluxtrello_list',array('name'=>$vocab->name->value(),
                                                    'idBoard'=>$board_id));
        $controller->createRemote($vocab->getIdentifier(), $vocab->type(), $account, $list);
      }
    } 
  }
}
