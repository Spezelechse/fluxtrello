<?php

/**
 * @file
 * Contains TrelloEventHandlerBase.
 */

namespace Drupal\fluxtrello\Plugin\Rules\Event;

use Drupal\fluxservice\Rules\DataUI\AccountEntity;
use Drupal\fluxservice\Rules\DataUI\ServiceEntity;
use Drupal\fluxservice\Rules\EventHandler\CronEventHandlerBase;
use Drupal\fluxtrello\Rules\RulesPluginHandlerBase;

/**
 * Cron-based base class for Trello event handlers.
 */
abstract class TrelloEventHandlerBase extends CronEventHandlerBase {

  /**
   * Returns info-defaults for trello plugin handlers.
   */
  public static function getInfoDefaults() {
    return RulesPluginHandlerBase::getInfoDefaults();
  }

  /**
   * Rules trello integration access callback.
   */
  public static function integrationAccess($type, $name) {
    return fluxservice_access_by_plugin('fluxtrello');
  }

  /**
   * Returns info for the provided trello service account variable.
   */
  public static function getServiceVariableInfo() {
    return array(
      'type' => 'fluxservice_account',
      'bundle' => 'fluxtrello',
      'label' => t('Trello account'),
      'description' => t('The account used for authenticating with the Trello API.'),
    );
  }
 
  /**
   * {@inheritdoc}
   */
  public function getDefaults() {
    return array(
      'account' => '',
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array &$form_state) {
    $settings = $this->getSettings();

    $form['account'] = array(
      '#type' => 'select',
      '#title' => t('Account'),
      '#description' => t('The service account used for authenticating with the Trello API.'),
      '#options' => AccountEntity::getOptions('fluxtrello', $form_state['rules_config']),
      '#default_value' => $settings['account'],
      '#required' => TRUE,
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function getEventNameSuffix() {
    return drupal_hash_base64(serialize($this->getSettings()));
  }
}
