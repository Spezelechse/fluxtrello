<?php

/**
 * @file
 * Contains TrelloListEventHandler.
 */

namespace Drupal\fluxtrello\Plugin\Rules\Event;

/**
 * Event handler for lists.
 */
class TrelloListEventHandler extends TrelloEventHandlerBase {

  /**
   * Defines the event.
   */
  public static function getInfo() {
    return static::getInfoDefaults() + array(
      'name' => 'fluxtrello_list_event',
      'label' => t('Something happend to a list'),
      'variables' => static::getServiceVariableInfo()+array(
        'trello_list' => array(
          'type' => 'fluxtrello_list',
          'label' => t('Trello: List'),
          'description' => t('The list that triggered the event.'),
        ),
      ),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getTaskHandler() {
    return 'Drupal\fluxtrello\TaskHandler\TrelloListTaskHandler';
  }

  /**
   * {@inheritdoc}
   */
  public function summary() {
    $settings = $this->getSettings();
    if ($settings['account'] && $account = entity_load_single('fluxservice_account', $settings['account'])) {
      return $this->eventInfo['label'] . ' ' . t('of %account', array('%account' => "@{$account->label()}"));
    }
    return $this->eventInfo['label'];
  }

}
