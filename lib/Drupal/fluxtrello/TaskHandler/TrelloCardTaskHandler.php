<?php

/**
 * @file
 * Contains TrelloCardPostedTaskHandler.
 */

namespace Drupal\fluxtrello\TaskHandler;

/**
 * Event dispatcher for changed trello cards.
 */
class TrelloCardTaskHandler extends TrelloTaskHandlerBase {
  	protected $needed_types=array('list');

 /**
   * {@inheritdoc}
   */
	public function runTask() {
	    if($this->checkRequirements()){
		  	print_r("<br>card<br>");
		  	$this->processQueue();
		    $this->checkAndInvoke();
		}
    	$this->afterTaskComplete();
	}

	protected function getRemoteDatasets(){
		$board_ids=$this->init();
		$cards=array();

		$client=$this->getAccount()->client();

		try{
			foreach ($board_ids as $board_id) {
			    $cards=array_merge($cards, $client->getBoardCards(array( 	'remote_id'=>$board_id['id'],
		                                                          	     	'key'=>$client->getConfig('consumer_key'),
		                                                               		'token'=>$client->getConfig('token'))));
		    }
	    }
	    catch(BadResponseException $e){
	      if($e->getResponse()->getStatusCode()==404){
	        watchdog('Fluxmite','[404] Host "'.$client->getBaseUrl().'" not found (GetBoard)');
	      }
	      else{	
          	watchdog('fluxtrello @ getCard', $e->getResponse()->getMessage());
	      }
	    }
	
		return $cards;
	}
}