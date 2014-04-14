<?php

/**
 * @file
 * Contains TrelloCardEventHandler.
 */

namespace Drupal\fluxtrello\Plugin\Rules\Event;

/**
 * Event handler for cards.
 */
class TrelloCardEventHandler extends TrelloEventHandlerBase {

  /**
   * Defines the event.
   */
  public static function getInfo() {
    return static::getInfoDefaults() + array(
      'name' => 'fluxtrello_card_event',
      'label' => t('Something happend to a card'),
      'variables' => array(
        'account' => static::getServiceVariableInfo(),
        'trello_card' => array(
          'type' => 'fluxtrello_card',
          'label' => t('Trello: Card'),
          'description' => t('The card that triggered the event.'),
        ),
        'change_type' => array(
          'type' => 'text',
          'options list' => 'fluxtrello_change_type_get_options',
          'label' => t('Change type'),
          'restiction' => 'input',
        ),
        'local_entity_id' => array(
          'type' => 'integer',
          'label' => t('Local entity id'),
          'optional' => TRUE,
        ),
      ),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getTaskHandler() {
    return 'Drupal\fluxtrello\TaskHandler\TrelloCardTaskHandler';
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
