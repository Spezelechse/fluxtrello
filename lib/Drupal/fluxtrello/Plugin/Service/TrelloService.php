<?php

/**
 * @file
 * Contains TrelloService.
 */

namespace Drupal\fluxtrello\Plugin\Service;

use Drupal\fluxservice\Service\OAuthServiceBase;

/**
 * Service plugin implementation for Trello.
 */
class TrelloService extends OAuthServiceBase implements TrelloServiceInterface {

  /**
   * Defines the plugin.
   */
  public static function getInfo() {
    return array(
      'name' => 'fluxtrello',
      'label' => t('Trello'),
      'description' => t('Provides Trello integration for fluxkraft.'),
      'icon' => 'images/fluxicon_trello.png',
      'icon font class' => 'icon-trello',
      'icon background color' => '#ffffff'
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getDefaultSettings() {
    return parent::getDefaultSettings() + array(
      'polling_interval' => 900,
      'use_remote_dependencies'=> 1,
    );
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array &$form_state) {
    $form = parent::settingsForm($form_state);

    $form['help'] = array(
      '#type' => 'markup',
      '#markup' => t(''),
      '#prefix' => '<p class="fluxservice-help">',
      '#suffix' => '</p>',
      '#weight' => -1,
    );

    $form['rules'] = array(
      '#type' => 'fieldset',
      '#title' => t('Event detection'),
      '#description' => t('Settings for detecting Trello related events as configured via <a href="@url">Rules</a>.', array('@url' => url('http://drupal.org/project/rules'))),
      // Avoid the data being nested below 'rules' or falling out of 'data'.
      '#parents' => array('data'),
    );

    $form['rules']['use_remote_dependencies'] = array(
      '#type' => 'select',
      '#title' => t('Use remote entity dependencies'),
      '#default_value' => $this->remoteDependenciesAreUsed(),
      '#options' => array(0 => t('no'), 1=>t('yes')),
      '#description' => t('Some remote entities depend on others. If dependencies are used the taskhandler will not run until the needed entities are handled.'),
    );

    $form['rules']['polling_interval'] = array(
      '#type' => 'select',
      '#title' => t('Polling interval'),
      '#default_value' => $this->getPollingInterval(),
      '#options' => array(0 => t('Every cron run')) + drupal_map_assoc(array(300, 900, 1800, 3600, 10800, 21600, 43200, 86400, 604800), 'format_interval'),
      '#description' => t('The time to wait before checking for updates. Note that the effecitive update interval is litrellod by how often the cron maintenance task runs. Requires a correctly configured <a href="@cron">cron maintenance task</a>.', array('@cron' => url('admin/reports/status'))),
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function getPollingInterval() {
    return $this->data->get('polling_interval');
  }

  /**
   * {@inheritdoc}
   */
  public function setPollingInterval($interval) {
    $this->data->set('polling_interval', $interval);
    return $this;
  }

  /**
   * 
   */
  public function remoteDependenciesAreUsed(){
    return $this->data->get('use_remote_dependencies');
  }
}
