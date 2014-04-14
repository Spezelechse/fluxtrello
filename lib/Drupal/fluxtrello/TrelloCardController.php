<?php

/**
 * @file
 * Contains TrelloCardController.
 */

namespace Drupal\fluxtrello;

use Drupal\fluxservice\Entity\FluxEntityInterface;
use Drupal\fluxservice\Entity\RemoteEntityInterface;
use Guzzle\Http\Exception\BadResponseException;

/**
 * Class TrelloCardController
 */
class TrelloCardController extends TrelloControllerBase {

  /**
   * {@inheritdoc}
   */
  protected function loadFromService($ids, FluxEntityInterface $agent) {
    $output = array();
    $ids=array_values($ids);
    $client = $agent->client();
    
    try{
	    foreach ($ids as $id) {
        if($response=$client->getCard(array(  'remote_id'=>$id,
                                              'key'=>$client->getConfig('consumer_key'),
                                              'token'=>$client->getConfig('token'),
                                              'fields'=>'all')))
        {
          $output[$id]=$response;
        }
	  	}
  	}
  	catch(BadResponseException $e){
       if($e->getResponse()->getStatusCode()==404){
         $this->handle404('[404] Host "'.$client->getBaseUrl().'" not found (getCard)');
       }
       else{
          watchdog('fluxtrello @ getCard', $e->getResponse()->getMessage());
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
