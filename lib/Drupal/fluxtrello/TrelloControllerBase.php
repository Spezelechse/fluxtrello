<?php

/**
 * @file
 * Contains TrelloControllerBase.
 */

namespace Drupal\fluxtrello;

use Drupal\fluxservice\Entity\FluxEntityInterface;
use Drupal\fluxservice\RemoteEntityController;
use Drupal\fluxservice\Entity\RemoteEntityInterface;
use Guzzle\Http\Exception\BadResponseException;

/**
 * Class RemoteEntityController
 */
abstract class TrelloControllerBase extends RemoteEntityController {

  /**
   * 
   */
  public function handle404($log_message, $data=array(), $response_message=""){
    $document_not_found_de="Der Datensatz ist nicht vorhanden";
    $document_not_found_eng="The record does not exist";

    if($data!=array()&&!strpos($response_message,$document_not_found_eng)&&!strpos($response_message,$document_not_found_de)){
      TrelloTaskQueue::addTask($data);
      watchdog('Fluxtrello',$log_message);
    }
    else{
      return true;
    }
  }
}
