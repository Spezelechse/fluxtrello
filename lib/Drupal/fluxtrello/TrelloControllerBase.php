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
 * Creates a new database entry
 */
  public function createLocal(RemoteEntityInterface $remote_entity, $local_entity_id, $local_entity_type, $isNode){
    $fields=array('id', 'type', 'isNode', 'remote_id', 'remote_type', 'trello_id', 'touched_last', 'checksum');
    $values=array($local_entity_id, 
                  $local_entity_type,
                  $isNode,
                  $remote_entity->id, 
                  $remote_entity->entityType(), 
                  $remote_entity->trello_id,
                  time(),
                  $remote_entity->checksum);

    $nid=db_insert('fluxtrello')
      ->fields($fields)
      ->values($values)
      ->execute();
  }

  public function createRemote($local_entity_id, $local_entity_type, $isNode, $account, $remote_entity, $request="", $remote_type=""){
    $client=$account->client();

    if($remote_entity!=null){
      $req=$this->createRequest($client, $remote_entity);
      $remote_type=$remote_entity->entityType();
    }
    else if($request!=""){
      $req=$request;
    }
    else{
      //TODO: throw error missing argument
    }

    //extract trello type
    $type=$remote_type;
    $type_split=explode("_",$type);
    $type=$type_split[1];


    //build operation name
    $operation='post'.ucfirst($type);
    
    //try to send the post request
    try{
      $response=$client->$operation($req);
    }
    catch(BadResponseException $e){
      if($e->getResponse()->getStatusCode()==404){
        $this->handle404( '[404] Host "'.$client->getBaseUrl().'" not found ('.$operation.')',
                          array(
                            'callback'=>'post',
                            'task_priority'=>2,
                            'local_id'=>$local_entity_id,
                            'local_type'=>$local_entity_type,
                            'isNode'=>$isNode,
                            'request'=>$req,
                            'remote_type'=>$remote_type),
                            $e->getResponse()->getMessage());
      }
      else{
        watchdog('fluxtrello @ '.$operation, $e->getResponse()->getMessage());
      }
    }

    if(isset($response)){
      $response['checksum']=md5(json_encode($response));
      
      $remoteEntity = fluxservice_entify($response, $remote_type, $account);

      //create local database entry
      $this->createLocal($remoteEntity, $local_entity_id, $local_entity_type, $isNode);
      return $remoteEntity;
    }
  }

  public function createRequest($client, $remote_entity=null, $remote_id=0){
    $req=array();

    if(isset($remote_entity)){
      $properties=$remote_entity->getEntityPropertyInfo("","");

      foreach ($properties as $key => $value) {
        if($key=='id'||$key=='trello_id'||$key=='descData'){
          continue;
        }

        if(isset($remote_entity->$key)){
          $value=$remote_entity->$key;

          if(gettype($value)=='array'){
            foreach ($value as $inKey => $inValue) {
              if(gettype($inValue)=='boolean'){
                $req[$key.'/'.$inKey]=$inValue ? 'true' : 'false';
              }
              else{
                $req[$key.'/'.$inKey]=''.$inValue;
              }
            }
          }
          else if(gettype($value)=='boolean'){
            $req[$key] = $value ? 'true' : 'false';
          }
          else{
            $req[$key] = ''.$value;
          }
        }
      }
    
      if(isset($remote_entity->trello_id)){
        $req['remote_id']=$remote_entity->trello_id;
      }
    }
    else if(isset($remote_id)){
      $req['remote_id']=$remote_id;
    }
    else{
      //TODO: throw missing argument exception
    }

    $req['key']=$client->getConfig('consumer_key');
    $req['token']=$client->getConfig('token');

    return $req;
  }


  public function deleteRemote($local_entity_id, $local_entity_type, $isNode, $account, $remote_type, $trello_id){
    $client=$account->client();

    $req=$this->createRequest($client, null, $trello_id);
    
    $type_split=explode("_",$remote_type);
    $type=$type_split[1];
      
    //build operation name
    $operation='delete'.ucfirst($type);
    $continue=false;

    //try to send delete request
    try{
      $response=$client->$operation($req);
    }
    catch(BadResponseException $e){
      if($e->getResponse()->getStatusCode()==404){
        $continue=$this->handle404( '[404] Host "'.$account->client()->getBaseUrl().'" not found ('.$operation.')',
                          array(
                            'callback'=>'delete',
                            'task_priority'=>0,
                            'local_id'=>$local_entity_id,
                            'local_type'=>$local_entity_type,
                            'isNode'=>$isNode,
                            'remote_type'=>$remote_type,
                            'trello_id'=>$trello_id),
                            $e->getResponse()->getMessage());
      }
      else{
        watchdog('fluxtrello @ '.$operation, $e->getResponse()->getMessage());
      }
    }
  }
  /**
   *  Updates the local fluxtrello table
   */
  public function updateLocal(RemoteEntityInterface $remote_entity, $local_entity_id, $local_entity_type, $isNode){
    $fields=array('checksum'=>$remote_entity->checksum);

    db_update('fluxtrello')
      ->fields($fields)
      ->condition('id', $local_entity_id, '=')
      ->condition('type', $local_entity_type, '=')
      ->condition('isNode', $isNode)
      ->execute();
  }


  /**
  *   Sends a put request to update a mite data set and if successful updates the local table
  */
  public function updateRemote($local_entity_id, $local_entity_type, $isNode, $account, $remote_entity){
    $client=$account->client();

    $req=$this->createRequest($client, $remote_entity);
    unset($req['idBoard']);

    //extract mite type
    $type=$remote_entity->entityType();
    $type_split=explode("_",$type);
    $type=$type_split[1];

    //build operation name
    $operation='put'.ucfirst($type);

    //try to send the update request
    try{
      $response=$client->$operation($req);
    }
    catch(BadResponseException $e){
      if($e->getResponse()->getStatusCode()==404){
        $this->handle404( '[404] Host "'.$account->client()->getBaseUrl().'" not found ('.$operation.')', 
                          array(
                            'callback'=>'put',
                            'task_priority'=>1,
                            'local_id'=>$local_entity_id,
                            'local_type'=>$local_entity_type,
                            'isNode'=>$isNode,
                            'request'=>$req,
                            'remote_type'=>$remote_entity->entityType()),
                            $e->getResponse()->getMessage());
      }
      else{
        watchdog('fluxtrello @ '.$operation, $e->getResponse()->getMessage());
      }
    }

    //check if successful
    if(isset($response)){
      //get the new updated-at timestamp
      $operation='get'.ucfirst($type);
      $response=$client->$operation(array(  'remote_id'=>$remote_entity->trello_id,
                                            'key'=>$client->getConfig('consumer_key'),
                                            'token'=>$client->getConfig('token'),
                                            'fields'=>'all'));

      unset($response['dateLastActivity']);
      unset($response['dateLastView']);

      $response['checksum']=md5(json_encode($response));
      echo "<br>";
      print_r(json_encode($response));
      echo "<br>";
      $remoteEntity = fluxservice_entify($response, $remote_entity->entityType(), $account);

      //update local database entry
      $this->updateLocal($remoteEntity, $local_entity_id, $local_entity_type, $isNode);
      return $remoteEntity;
    }
  }

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
