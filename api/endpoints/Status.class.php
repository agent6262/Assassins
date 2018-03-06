<?php

/*
 * All Rights Reserved
 *
 * Copyright (c) 2017 Tyler Bucher
 *
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 */

namespace Assassins\api\endpoints;

use Underline\ApiEndpoint;

/**
 * Class Status Simply returns true or false if the api is active.
 * @package Assassins\api\endpoints
 */
class Status implements ApiEndpoint {

    /**
     * @var string The http method requested.
     */
    private $method;

    /**
     * ApiEndpoint constructor.
     *
     * @param string $method The http method requested.
     * @param array  $args   The additional URI components if any.
     * @param string $file   The file for the put request.
     */
    public function __construct(string $method, array $args, string $file) {
        $this->method = $method;
    }

    /**
     * @return mixed API endpoint return call.
     */
    public function handle() {
        if($this->method == "GET") {
            return array('status' => true, 'message' => 'Api is active');
        } else {
            return array('error' => "Unsupported method '$this->method' for api call 'Status'");
        }
    }
}