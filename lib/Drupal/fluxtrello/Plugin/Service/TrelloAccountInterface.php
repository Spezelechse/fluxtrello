<?php

/**
 * @file
 * Contains TrelloAccountInterface
 */

namespace Drupal\fluxtrello\Plugin\Service;

use Drupal\fluxservice\Service\OAuthAccountInterface;

/**
 * Interface for Trello accounts.
 */
interface TrelloAccountInterface extends OAuthAccountInterface {

  /**
   * Gets the Trello API client.
   *
   * @return \Guzzle\Service\Client
   *   The web service client for the API.
   */
  public function client();

}
