<?php

/**
 * @file
 * Contains RulesPluginHandlerBase.
 */

namespace Drupal\fluxtrello\Rules;

use Drupal\fluxservice_extension\Rules\FluxRulesPluginHandlerBaseExtended;

/**
 * Base class for trello Rules plugin handler.
 */
abstract class RulesPluginHandlerBase extends FluxRulesPluginHandlerBaseExtended {

  /**
   * Returns info-defaults for trello plugin handlers.
   */
  public static function getInfoDefaults() {
    return array(
      'category' => 'fluxtrello',
      'access callback' => array(get_called_class(), 'integrationAccess'),
    );
  }

  /**
   * Rules trello integration access callback.
   */
  public static function integrationAccess($type, $name) {
    return fluxservice_access_by_plugin('fluxtrello');
  }
}
