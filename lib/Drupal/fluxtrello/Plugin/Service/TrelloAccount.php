<?php

/**
 * @file
 * Contains TrelloAccount.
 */

namespace Drupal\fluxtrello\Plugin\Service;

use Drupal\fluxtrello\TrelloClient;
use Drupal\fluxservice\Service\OAuthAccountBase;
use Guzzle\Http\Client;
use Guzzle\Http\Url;
use Guzzle\Service\Builder\ServiceBuilder;

/**
 * Account implementation for Trello.
 */
class TrelloAccount extends OAuthAccountBase implements TrelloAccountInterface {

  /**
   * Defines the plugin.
   */
  public static function getInfo() {
    return array(
      'name' => 'fluxtrello',
      'label' => t('Trello account'),
      'description' => t('Provides Trello integration for fluxkraft.'),
      'class' => '\Drupal\fluxtrello\Plugin\Service\TrelloAccount',
      'service' => 'fluxtrello',
    );
  }

    /**
   * {@inheritdoc}
   */
  public function getDefaultSettings() {
    return parent::getDefaultSettings() + array(
      'username' => '',
    );
  }

  /**
   * The service base url.
   *
   * @var string
   */
  protected $serviceUrl = 'https://api.trello.com';


  /**
   * {@inheritdoc}
   */
  public static function getPropertyDefinitions() {
    $properties['screen_name'] = array(
      'label' => t('Screen name'),
      'description' => t('The user name.'),
      'getter callback' => 'fluxservice_entity_metadata_get_account_detail',
      'type' => 'text',
      'entity views field' => TRUE,
    );

    $properties['location'] = array(
      'label' => t('Location'),
      'description' => t('The location.'),
      'getter callback' => 'fluxservice_entity_metadata_get_account_detail',
      'type' => 'text',
      'entity views field' => TRUE,
    );

    $properties['description'] = array(
      'label' => t('Description'),
      'description' => t('The description.'),
      'getter callback' => 'fluxservice_entity_metadata_get_account_detail',
      'type' => 'text',
      'entity views field' => TRUE,
    );

    return $properties;
  }

   /**
   * {@inheritdoc}
   */
  public function settingsForm(array &$form_state) {
    $form=parent::settingsForm($form_state);

    $form['username'] = array(
      '#type' => 'textfield',
      '#title' => t('Username'),
      '#default_value' => $this->getUsername(),
      '#description' => t('The username of this account'),
    );

    return $form;
  }

  /**
    * 
    */
  public function client(){
    $service = $this->getService();
    return TrelloClient::factory(array(
      'base_url' => $this->serviceUrl.'/1',
      'consumer_key' => $service->getConsumerKey(),
      'consumer_secret' => $service->getConsumerSecret(),
      'token' => $this->getOauthToken(),
      'token_secret' => $this->getOauthTokenSecret(),
    ));
  }

  protected function getUsername(){
    return $this->data->get('username');
  }

  /**
   * {@inheritdoc}
   */
  protected function processAuthorizedAccount(array $response) {
    parent::processAuthorizedAccount($response);
    // Build the label and remote id from the response data.
    $this->setRemoteIdentifier($response['user_id'])->setLabel($response['screen_name']);
  }


  /**
   * {@inheritdoc}
   */
  protected function getAuthorizeUrl() {
    $siteName=check_plain(variable_get('site_name'));

    return "https://trello.com/1/OAuthAuthorizeToken?name=".$siteName."&expiration=never&scope=read,write";
  }

  /**
   * {@inheritdoc}
   */
  protected function getRequestTokenUrl() {
    return "https://trello.com/1/OAuthGetRequestToken";
  }

  /**
   * {@inheritdoc}
   */
  protected function getAccessTokenUrl() {
    return "https://trello.com/1/OAuthGetAccessToken";
  }
}