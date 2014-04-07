<?php

/**
 * @file
 * Contains TrelloBoardEventHandler.
 */

namespace Drupal\fluxtrello\Plugin\Rules\Event;

/**
 * Event handler for accounts.
 */
class TrelloBoardEventHandler extends TrelloEventHandlerBase {

  /**
   * Defines the event.
   */
  public static function getInfo() {
    return static::getInfoDefaults() + array(
      'name' => 'fluxtrello_account_event',
      'label' => t('Something happend to this board'),
      'variables' => array(
        'account' => static::getServiceVariableInfo(),
        'trello_board' => array(
          'type' => 'fluxtrello_board',
          'label' => t('Trello: Board'),
          'description' => t('The board that triggered the event.'),
        ),
        'change_type' => array(
          'type' => 'text',
          'options list' => 'change_type_get_options',
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
    return 'Drupal\fluxtrello\TaskHandler\TrelloBoardTaskHandler';
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
