<?php

/**
 * @file
 * Contains TrelloBoardEventHandler.
 */

namespace Drupal\fluxtrello\Plugin\Rules\Event;

/**
 * Event handler for boards.
 */
class TrelloBoardEventHandler extends TrelloEventHandlerBase {

  /**
   * Defines the event.
   */
  public static function getInfo() {
    return static::getInfoDefaults() + array(
      'name' => 'fluxtrello_board_event',
      'label' => t('Something happend to a board'),
      'variables' => static::getServiceVariableInfo()+array(
        'trello_board' => array(
          'type' => 'fluxtrello_board',
          'label' => t('Trello: Board'),
          'description' => t('The board that triggered the event.'),
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
