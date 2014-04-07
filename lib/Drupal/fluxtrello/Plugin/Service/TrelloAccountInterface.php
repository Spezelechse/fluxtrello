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
   * Gets the account's access token.
   *
   * @return string
   *   The access token of the account.
   */
  public function getAccessToken();

  /**
   * Gets the Trello Graph API client.
   *
   * @return \Guzzle\Service\Client
   *   The web service client for the Graph API.
   */
  public function client();

}
