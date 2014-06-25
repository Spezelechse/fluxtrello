<?php

/**
 * @file
 * Contains TrelloControllerBase.
 */

namespace Drupal\fluxtrello;

use Drupal\fluxservice_extension\RemoteEntityControllerExtended;
use Drupal\fluxservice_extension\FluxserviceTaskQueue;
use Guzzle\Http\Exception\BadResponseException;

/**
 * Class RemoteEntityController
 */
abstract class TrelloControllerBase extends RemoteEntityControllerExtended {
  /**
   * 
   */
  public function createRequest($client, $operation_type='', $remote_entity=null, $remote_id=0){
    $req=array();

    if(isset($remote_entity)){
      $properties=$remote_entity->getEntityPropertyInfo("","");

      foreach ($properties as $key => $value) {
        if($key=='id'||$key=='remote_id'||$key=='descData'){
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
    
      if(isset($remote_entity->remote_id)){
        $req['remote_id']=$remote_entity->remote_id;
      }
    }
    else if(isset($remote_id)){
      $req['remote_id']=$remote_id;
    }
    else{
      //TODO: throw missing argument exception
    }

    if($operation_type=='update'){
      unset($req['idBoard']);
    }
    else if($operation_type=='get'){
      $req['fields']='all';
    }

    $req['key']=$client->getConfig('consumer_key');
    $req['token']=$client->getConfig('token');

    return $req;
  }

  /**
   * 
   */
  public function handle404($log_message, $data=array(), $response_message=""){
    if($data!=array()){
      FluxserviceTaskQueue::addTask($data);
      watchdog('Fluxtrello',$log_message);
    }
    else{
      return true;
    }
  }

  /**
   * 
   */
  public function extractRemoteType($entity_type){
    $type_split=explode("_",$entity_type);
    $type=$type_split[1];

    return $type;
  }

  /**
   * 
   */
  public function prepareResponse($response){
    return $response;
  }


  /**
   * 
   */
  public function addAdditionalFields(&$fields, &$values, $remote_entity){
    array_push($fields, 'board_id');

    if(isset($remote_entity->idBoard)){
      array_push($values, $remote_entity->idBoard);
    }
    else{
      array_push($values, $remote_entity->remote_id);
    }
  }
}
