<?php

/**
 * @file
 * Contains TrelloUserClient.
 */

namespace Drupal\fluxtrello;

use Drupal\fluxservice\ServiceClientInterface;
use Guzzle\Common\Collection;
use Guzzle\Service\Client;
use Guzzle\Service\Description\ServiceDescription;

/**
 * Guzzle driven service client for the Trello API.
 */
class TrelloClient extends Client {

  /**
   * {@inheritdoc}
   */
  public static function factory($config = array()) {
    $required = array('base_url', 'access_token');

    $config = Collection::fromConfig($config, array(), $required);
    $client = new static($config->get('base_url'), $config);

    // Attach a service description to the client
    $description = ServiceDescription::factory(__DIR__ . '/Trello.json');
    $client->setDescription($description);

    return $client;
  }

}
