<?php

/**
 * @file
 * Contains fetchListIdByTaxonomyId.
 */

namespace Drupal\fluxtrello\Plugin\Rules\Action;

use Drupal\fluxtrello\Plugin\Service\TrelloAccountInterface;
use Drupal\fluxtrello\Rules\RulesPluginHandlerBase;

/**
 * fetch list id by taxonomy id.
 */
class fetchListIdByTaxonomyId extends RulesPluginHandlerBase implements \RulesActionHandlerInterface {

  /**
   * Defines the action.
   */
  public static function getInfo() {

    return static::getInfoDefaults() + array(
      'name' => 'fluxtrello_fetch_list_id_by_taxonomy_id',
      'label' => t('Fetch list id by taxonomy id'),
      'parameter' => array(
        'taxonomy_id' => array(
          'type' => 'text',
          'label' => t('Taxonomy id'),
          'required' => TRUE,
        ),
        'board_id' => array(
          'type' => 'text',
          'label' => t('Board id'),
          'required' => TRUE,
        ),
      ),
      'provides' => array(
        'list_id' => array(
          'type'=>'text',
          'label' => t('List id')),
      )
    );
  }

  /**
   * Executes the action.
   */
  public function execute($taxonomy_id, $board_id) {
    dpm("fetch list id: ".$taxonomy_id);
    print_r("<br>fetch list id: ".$taxonomy_id."<br>");

    $res=db_select('fluxtrello','fm')
          ->fields('fm',array('remote_id'))
          ->condition('fm.id',$taxonomy_id,'=')
          ->condition('fm.board_id', $board_id, '=')
          ->condition('fm.type','taxonomy_term','=')
          ->execute()
          ->fetchAssoc();
    
    if($res){
      $id=$res['remote_id'];  
    }
    else{
      $id=0;
      watchdog('fluxtrello','List not found');
    }

    return array('list_id' => $id);
  }
}
