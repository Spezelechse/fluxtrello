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
		$board_ids=db_select('fluxtrello','ft')
						->fields('ft',array('remote_id'))
						->condition('ft.remote_type','fluxtrello_board','=')
						->execute()
						->fetchAll();

		$cards=array();

		$client=$this->getAccount()->client();

		try{
			foreach ($board_ids as $board) {
			    $board_cards=$client->getBoardCards(array( 	'remote_id'=>$board->remote_id,
                                                  	     	'key'=>$client->getConfig('consumer_key'),
                                                       		'token'=>$client->getConfig('token'),
                                                       		'fields'=>''));
				//array_merge($cards, 
				foreach ($board_cards as $card) {
					array_push($cards, $client->getCard(array('remote_id'=>$card['id'],
                                              	     	'key'=>$client->getConfig('consumer_key'),
                                                   		'token'=>$client->getConfig('token'),
                                                   		'fields'=>'all')));
				}
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