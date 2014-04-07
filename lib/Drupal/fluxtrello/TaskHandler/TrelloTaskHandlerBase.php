<?php

/**
 * @file
 * Contains TrelloTaskHandlerBase.
 */

namespace Drupal\fluxtrello\TaskHandler;

use Drupal\fluxservice\Rules\TaskHandler\RepetitiveTaskHandlerBase;
use Guzzle\Http\Exception\BadResponseException;
use Drupal\fluxtrello\TrelloTaskQueue;

/**
 * Base class for Trello task handlers that dispatch Rules events.
 */
class TrelloTaskHandlerBase extends RepetitiveTaskHandlerBase {

  public function __construct(array $task) {
    parent::__construct($task);
    
    //extract the entity type from the event type
    $type_split=explode("_",$this->task['identifier']);
    $type=$type_split[1];
    $remote_type=$type;

    //workaround for time_entries
    if($type=='time'){
      $type.='_'.$type_split[2];
      $remote_type.='-'.$type_split[2];
    }

    $this->task['entity_type']=$type;
    $this->task['remote_type']=$remote_type;
  }
  /**
   * Gets the configured event name to dispatch.
   */
  public function getEvent() {
    return $this->task['identifier'];
  }

  /**
   * 
   */
  public function getEntityType(){
    return $this->task['entity_type'];
  }

  /**
   * 
   */
  public function getRemoteType(){
    return $this->task['remote_type'];
  }

  /**
   * Gets the configured Trello account.
   *
   * @throws \RulesEvaluationException
   *   If the account cannot be loaded.
   *
   * @return \Drupal\fluxtrello\Plugin\Service\TrelloAccount
   */
  public function getAccount() {
    $account = entity_load_single('fluxservice_account', $this->task['data']['account']);
    if (!$account) {
      throw new \RulesEvaluationException('The specified trello account cannot be loaded.', array(), NULL, \RulesLog::ERROR);
    }
    return $account;
  }

  /**
   * {@inheritdoc}
   */
  public function afterTaskQueued() {
    try {
      $service = $this->getAccount()->getService();

      // Continuously reschedule the task.
      db_update('rules_scheduler')
        ->condition('tid', $this->task['tid'])
        ->fields(array('date' => $this->task['date'] + $service->getPollingInterval()))
        ->execute();
    }
    catch(\RulesEvaluationException $e) {
      rules_log($e->msg, $e->args, $e->severity);
    }
  }

/**
 * invoke events for all given entities
 * 
 * @param string $entity_type
 * A string defining the entity type
 * 
 * @param array $entities
 * An array of arrays defining the entities
 * 
 * @param TrelloAccount (service account) $account
 * The account used to connect to trello
 * 
 * @param string $change_type
 * Event type that happend to the entity (create, delete, update)
 * 
 * @param array $local_entity_ids
 * if needed the local entity ids which refer to the remote entities
 */
  public function invokeEvent($entity_type, $entities, $account, $change_type, $local_entity_ids=array()){
    if(!empty($entities)){
      $entities = fluxservice_entify_multiple($entities, $entity_type, $account);

      $i=0;
      if($entities){
        foreach ($entities as $entity) {
          if(!empty($local_entity_ids)){
            $local_entity_id=$local_entity_ids[$i++];
            rules_invoke_event($this->getEvent(), $account, $entity, $change_type, $local_entity_id);
          }
          else{
            rules_invoke_event($this->getEvent(), $account, $entity, $change_type); 
          }
        }
      }
    }
  }

/**
 * Checks for trello "updates" and invoke the appropriate events
 */
  public function checkAndInvoke(){
    $account = $this->getAccount();

    
      /*
      $this->invokeEvent('fluxtrello_'.$type, $create, $account, 'create');
      $this->invokeEvent('fluxtrello_'.$type, $update, $account, 'update', $update_local_ids);
      $this->invokeEvent('fluxtrello_'.$type, $delete, $account, 'delete', $delete_local_ids); */  
  }

/**
 * checks which event is needed for the given trello data_set
 */
  private function checkSingleResponseSet($data_set, &$create, &$update, &$update_local_ids){
    $res=db_select('fluxtrello','fm')
          ->fields('fm',array('updated_at','id'))
          ->condition('trello_id',$data_set['id'])
          ->execute()
          ->fetchAssoc();


    if($res){
      //check for updates
      if($res['updated_at']<strtotime($data_set['updated-at'])){
        array_push($update, $data_set);
        array_push($update_local_ids, $res['id']);
      }

      db_update('fluxtrello')
        ->fields(array('touched_last'=>time()))
        ->condition('id',$res['id'],'=')
        ->execute();
    }
    else{
      array_push($create, $data_set);
    }
  }

  /**
   * 
   */

  protected function processQueue(){
    $queue=new TrelloTaskQueue($this->getEntityType(),$this->getAccount());

    $queue->process();
  }
}