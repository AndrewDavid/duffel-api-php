<?php

declare(strict_types=1);

namespace Duffel\Api;

use Duffel\HttpClient\ResponseParser;

class SeatMaps extends AbstractApi {
  /**
   * @param string $offerId
   *
   * @return mixed
   */
  public function all(string $offerId = '', array $parameters = []) {
	  if ('' !== $offerId) {
		  $parameters['offer_id'] = self::encodePath($offerId);
	  }
	  $response = $this->getAsResponse('/air/seat_maps', $parameters);
	  $this->meta = ResponseParser::getContent($response, 'meta');

	  return ResponseParser::getContent($response);
  }
}
