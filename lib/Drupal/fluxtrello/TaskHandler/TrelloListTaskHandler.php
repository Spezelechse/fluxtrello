<?php

/**
 * @file
 * Contains TrelloListPostedTaskHandler.
 */

namespace Drupal\fluxtrello\TaskHandler;

/**
 * Event dispatcher for changed trello lists.
 */
class TrelloListTaskHandler extends TrelloTaskHandlerBase {
  	protected $needed_types=array('board');
 /**
   * {@inheritdoc}
   */
	public function runTask() {
	  	if($this->checkRequirements()){
		  	print_r("<br>list<br>");
		  	$this->processQueue();
		    $this->checkAndInvoke();
		}
    	$this->afterTaskComplete();
	}

	protected function getRemoteDatasets(){
		$board_ids=$this->init();
		$lists=array();

		$client=$this->getAccount()->client();

		try{
			foreach ($board_ids as $board_id) {
			    $lists = array_merge($lists, $client->getBoardLists(array( 	'remote_id'=>$board_id['id'],
						                                           		    'key'=>$client->getConfig('consumer_key'),
						                                               		'token'=>$client->getConfig('token'))));
		    }
	    }
	    catch(BadResponseException $e){
	      if($e->getResponse()->getStatusCode()==404){
	        watchdog('Fluxmite','[404] Host "'.$client->getBaseUrl().'" not found (GetBoard)');
	      }
	      else{	
          	watchdog('fluxtrello @ getList', $e->getResponse()->getMessage());
	      }
	    }

		return $lists;
	}
}