<?php

/**
 * @file
 * Contains getAccountEmail.
 */

namespace Drupal\fluxtrello\Plugin\Rules\Action;

use Drupal\fluxtrello\Plugin\Service\TrelloAccountInterface;
use Drupal\fluxtrello\Rules\RulesPluginHandlerBase;

/**
 * get account email.
 */
class getAccountEmail extends RulesPluginHandlerBase implements \RulesActionHandlerInterface {

  /**
   * Defines the action.
   */
  public static function getInfo() {

    return static::getInfoDefaults() + array(
      'name' => 'fluxtrello_get_account_email',
      'label' => t('Get account email'),
      'parameter' => array(
        'account' => static::getServiceParameterInfo()
      ),
      'provides' => array(
        'email' => array(
          'type'=>'text',
          'label' => t('Account email')),
      )
    );
  }

  /**
   * Executes the action.
   */
  public function execute(TrelloAccountInterface $account) {
    dpm('get account email');
    print_r('get account email<br>');

    $controller = entity_get_controller('fluxtrello_list');
    $client = $account->client();
    
    $email=$client->getMyEmail(array( 'key'=>$client->getConfig('consumer_key'),
                                      'token'=>$client->getConfig('token')));

    return array('email'=>$email['_value']);
  }
}
