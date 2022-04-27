<?php

declare(strict_types=1);

namespace Duffel;

use Duffel\Exception\InvalidAccessTokenException;
use Duffel\HttpClient\Builder;
use Http\Client\Common\HttpMethodsClientInterface;
use Http\Client\Common\Plugin\AddHostPlugin;
use Http\Client\Common\Plugin\AuthenticationPlugin;
use Http\Client\Common\Plugin\HeaderDefaultsPlugin;
use Http\Client\Common\Plugin\HistoryPlugin;
use Http\Client\Common\Plugin\RedirectPlugin;
use Http\Client\Common\Plugin\RetryPlugin;
use Http\Message\Authentication\Bearer;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;

class Client {
  private const DEFAULT_API_URL = 'https://api.duffel.com/';
  private const DEFAULT_API_VERSION = 'beta';
  private const VERSION = '0.0.0-alpha';

  private $accessToken;
  private $apiUrl;
  private $apiVersion;
  private $httpClientBuilder;

  public function __construct(Builder $httpClientBuilder = null) {
    $this->httpClientBuilder = $builder = $httpClientBuilder ?? new Builder();
    $this->apiUrl = self::DEFAULT_API_URL;
    $this->apiVersion = self::DEFAULT_API_VERSION;

    $builder->addPlugin(new HeaderDefaultsPlugin($this->getDefaultHeaders()));

    $this->setUrl($this->apiUrl);
  }

  public function getAccessToken() {
    return $this->accessToken;
  }

  public function setAccessToken(string $token) {
    if ('' !== trim($token) && strlen(trim($token)) > 0) {
      $this->accessToken = $token;
    } else {
      throw new InvalidAccessTokenException("You need to set a token");
    }

    $this->httpClientBuilder->addPlugin(new AuthenticationPlugin(new Bearer($this->getAccessToken())));
  }

  public function getApiVersion(): string {
    return $this->apiVersion;
  }

  public function setApiVersion(string $apiVersion): void {
    $this->apiVersion = $apiVersion;
  }

  public function setUrl(string $url): void {
    $uri = $this->getHttpClientBuilder()->getUriFactory()->createUri($url);

    $this->getHttpClientBuilder()->removePlugin(AddHostPlugin::class);
    $this->getHttpClientBuilder()->addPlugin(new AddHostPlugin($uri));
  }

  public function getHttpClient(): HttpMethodsClientInterface {
    return $this->getHttpClientBuilder()->getHttpClient();
  }

  protected function getHttpClientBuilder(): Builder {
    return $this->httpClientBuilder;
  }
  private function getDefaultHeaders(): array {
    return array(
      "Duffel-Version" => $this->apiVersion,
      "Content-Type" => "application/json",
      "User-Agent" => $this->getUserAgent(),
    );
  }

  private function getUserAgent(): string {
    return "Duffel/" . $this->apiVersion . " " . "duffel_api_php/" . self::VERSION;
  }
}
