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
	  	if($this->checkDependencies()){
		  	print_r("<br>list<br>");
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

		$lists=array();

		$client=$this->getAccount()->client();

		try{
			foreach ($board_ids as $board) {
			    $lists = array_merge($lists, $client->getBoardLists(array( 	'remote_id'=>$board->remote_id,
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