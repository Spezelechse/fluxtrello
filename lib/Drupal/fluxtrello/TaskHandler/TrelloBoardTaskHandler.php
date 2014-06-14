<?php

/**
 * @file
 * Contains TrelloBoardPostedTaskHandler.
 */

namespace Drupal\fluxtrello\TaskHandler;

/**
 * Event dispatcher for changed trello boards.
 */
class TrelloBoardTaskHandler extends TrelloTaskHandlerBase {
	/**
   * {@inheritdoc}
   */
	public function runTask() {
	  	print_r("<br>board<br>");
	    $this->checkAndInvoke();
	}

	protected function getRemoteDatasets(){
		$board_ids=$this->init();
		$boards=array();

		$client=$this->getAccount()->client();

		try{
			foreach ($board_ids as $board_id) {
			    $board = $client->getBoard(array( 'remote_id'=>$board_id['id'],
                                                   'key'=>$client->getConfig('consumer_key'),
                                                   'token'=>$client->getConfig('token'),
                                                   'fields'=>'all'));
			    array_push($boards, $board);
		    }
	    }
	    catch(BadResponseException $e){
	      if($e->getResponse()->getStatusCode()==404){
	        watchdog('Fluxmite','[404] Host "'.$client->getBaseUrl().'" not found (GetBoard)');
	      }
	      else{	
          	watchdog('fluxtrello @ getBoard', $e->getResponse()->getMessage());
	      }
	    }
	
		return $boards;
	}
}