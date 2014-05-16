<?php

/**
 * @file
 * Contains TrelloEventHandlerBase.
 */

namespace Drupal\fluxtrello\Plugin\Rules\Event;

use Drupal\fluxservice_extension\Plugin\Rules\Event\FluxserviceEventHandlerBase;
use Drupal\fluxtrello\Rules\RulesPluginHandlerBase;

/**
 * Cron-based base class for Trello event handlers.
 */
abstract class TrelloEventHandlerBase extends FluxserviceEventHandlerBase {

  /**
   * Returns info-defaults for plugin handlers.
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
}
