<?php

declare(strict_types=1);

namespace Duffel\Api;

use Duffel\HttpClient\ResponseParser;

class Airports extends AbstractApi {
  /**
   * @param array $parameters
   *
   * @return mixed
   */
  public function all(array $parameters = []) {
	  $response = $this->getAsResponse('/air/airports', $parameters);
	  $this->meta = ResponseParser::getContent($response, 'meta');

	  return ResponseParser::getContent($response);
  }

  /**
   * @param string $id
   *
   * @return mixed
   */
  public function show(string $id) {
    return $this->get('/air/airports/'.self::encodePath($id));
  }
}
