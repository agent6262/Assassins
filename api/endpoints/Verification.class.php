<?php

/*
 * All Rights Reserved
 *
 * Copyright (c) 2017 Tyler Bucher
 *
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 */

namespace Assassins\api\endpoints;

use Assassins\DatabaseHelp;
use Underline\ApiEndpoint;

/**
 * Class Verification Verifies a user and makes that user active.
 * @package Assassins\api\endpoints
 */
class Verification implements ApiEndpoint {

    /**
     * @var string The http method requested.
     */
    private $method;

    /**
     * @var array The additional URI components if any.
     */
    private $args;

    /**
     * ApiEndpoint constructor.
     *
     * @param string $method The http method requested.
     * @param array  $args   The additional URI components if any.
     * @param string $file   The file for the put request.
     */
    public function __construct(string $method, array $args, string $file) {
        $this->method = $method;
        $this->args = $args;
    }

    /**
     * @return mixed API endpoint return call.
     */
    public function handle() {
        if ($this->method == "GET") {
            // Validate get request
            if (!is_numeric($this->args[0]) || strlen($this->args[1]) != 32) {
                return array('error' => 'Malformed api request');
            } else {
                // Check if user exists
                $user = \UserQuery::create()->findOneById($this->args[0]);
                if ($user == null) {
                    return array('error' => 'Invalid user id');
                }
                // Verify user
                $response = DatabaseHelp::verifyUser($user, $this->args[1]);
                $returnResponse = array();
                if ($response == -3) {
                    $returnResponse['error'] = 'Internal error.';
                } else if ($response == -2) {
                    $returnResponse['error'] = 'Invalid verification token.';
                } else if ($response == -1) {
                    $returnResponse['error'] = 'Invalid user.';
                } else if ($response == 0) {
                    $returnResponse['success'] = false;
                    $returnResponse['message'] = 'User is already verified.';
                } else if ($response == 1) {
                    $returnResponse['success'] = true;
                    $returnResponse['message'] = 'User has been successful verified.';
                }
                return $returnResponse;
            }
        } else {
            return array('error' => "Unsupported method '$this->method' for api call 'Verification'");
        }
    }
}