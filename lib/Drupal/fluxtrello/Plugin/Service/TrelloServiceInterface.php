<?php

/**
 * @file
 * Contains TrelloServiceInterface
 */

namespace Drupal\fluxtrello\Plugin\Service;

use Drupal\fluxservice\Service\OAuthServiceInterface;

/**
 * Interface for Trello services.
 */
interface TrelloServiceInterface extends OAuthServiceInterface  {

  /**
   * Gets the update interval.
   *
   * @return int
   *   The update interval.
   */
  public function getPollingInterval();

  /**
   * Sets the update interval.
   *
   * @param int $interval
   *   The update interval.
   *
   * @return TrelloServiceInterface
   *   The called object for chaining.
   */
  public function setPollingInterval($interval);

}
