<?php

declare(strict_types=1);

namespace Duffel\Api;

use Duffel\HttpClient\ResponseParser;

class OrderChangeOffers extends AbstractApi {
  /**
   * @param string $orderChangeRequestId
   *
   * @return mixed
   */
  public function all(string $orderChangeRequestId = '', array $parameters = []) {
    if ('' !== $orderChangeRequestId) {
	    $parameters['order_change_request_id'] = self::encodePath($orderChangeRequestId);
    }

	  $response = $this->getAsResponse('/air/order_change_offers', $parameters);
	  $this->meta = ResponseParser::getContent($response, 'meta');

	  return ResponseParser::getContent($response);
  }

  /**
   * @param string $id
   *
   * @return mixed
   */
  public function show(string $id) {
    return $this->get('/air/order_change_offers/'.self::encodePath($id));
  }
}
