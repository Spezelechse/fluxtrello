<?php

/**
 * @file
 * Contains TrelloTaskHandlerBase.
 */

namespace Drupal\fluxtrello\TaskHandler;

use Drupal\fluxservice_extension\TaskHandler\RepetitiveTaskHandlerBaseExtended;
use Guzzle\Http\Exception\BadResponseException;

/**
 * Base class for Trello task handlers that dispatch Rules events.
 */
abstract class TrelloTaskHandlerBase extends RepetitiveTaskHandlerBaseExtended {

/**
 * 
 */
  protected function init(){
    $board_ids=array();
    $client = $this->getAccount()->client();

    try{
      $board_ids = $client->getMemberBoards(array( 'username'=>$client->getConfig('username'),
                                                    'key'=>$client->getConfig('consumer_key'),
                                                    'token'=>$client->getConfig('token'),
                                                    'fields'=>''));
    }
    catch(BadResponseException $e){
      if($e->getResponse()->getStatusCode()==404){
        watchdog('Fluxtrello','[404] Host "'.$client->getBaseUrl().'" not found ('.$operation.')');
      }
      else{ 
          watchdog('fluxtrello @ getMemberBoards', $e->getResponse()->getMessage());
      }
    }


    return $board_ids;
  }

/**
 * 
 */
  protected function getCheckvalue($data_set){
    return md5(json_encode($data_set));
  }
}