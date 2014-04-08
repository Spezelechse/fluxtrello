<?php

/**
 * @file
 * Contains TrelloListController.
 */

namespace Drupal\fluxtrello;

use Drupal\fluxservice\Entity\FluxEntityInterface;
use Drupal\fluxservice\Entity\RemoteEntityInterface;
use Guzzle\Http\Exception\BadResponseException;

/**
 * Class TrelloListController
 */
class TrelloListController extends TrelloControllerBase {

  /**
   * {@inheritdoc}
   */
  protected function loadFromService($ids, FluxEntityInterface $agent) {
    $output = array();
    $ids=array_values($ids);
    $client = $agent->client();
    
    try{
	    foreach ($ids as $id) {
        //TODO: getList
	  	}
  	}
  	catch(BadResponseException $e){
       if($e->getResponse()->getStatusCode()==404){
         $this->handle404('[404] Host "'.$client->getBaseUrl().'" not found (getList)');
       }
       else{
       }
    }

    return $output;
  }

  /**
   * {@inheritdoc}
   */
  protected function sendToService(RemoteEntityInterface $entity) {
    // @todo Throw exception.
  }
}
