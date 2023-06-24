<?php

declare(strict_types=1);

namespace Duffel\Api;

use Duffel\Exception\RuntimeException;
use Duffel\HttpClient\ResponseParser;

class OfferRequests extends AbstractApi {
  /**
   * @param array $parameters
   *
   * @return mixed
   */
  public function all(array $parameters = []) {
	  $response = $this->getAsResponse('/air/offer_requests', $parameters);
	  $this->meta = ResponseParser::getContent($response, 'meta');

	  return ResponseParser::getContent($response);
  }

  /**
   * @param string $id
   *
   * @return mixed
   */
  public function show(string $id) {
    return $this->get('/air/offer_requests/'.self::encodePath($id));
  }

  /**
   * @param string $cabin_class
   * @param array  $passengers
   * @param array  $slices
   *
   * @return mixed
   */
  public function create(string $cabin_class = "economy", array $passengers = array(), array $slices = array()) {
    if ([] === $passengers) {
      throw new RuntimeException("You must provide at least one passenger");
    }

    if ([] === $slices) {
      throw new RuntimeException("You must provide at least one slice");
    }

    $resolver = $this->createOptionsResolver();
    $resolver->setRequired(['departure_date', 'destination', 'origin']);

    $parsedSlices = [];

    foreach($slices as $slice) {
      $parsedSlices[] = $resolver->resolve($slice);
    }

    $params = [
      'cabin_class' => $cabin_class,
      'passengers' => $passengers,
      'slices' => $parsedSlices,
    ];

    return $this->post('/air/offer_requests', $params);
  }
}
