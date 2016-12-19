<?php

namespace Features\Bootstrap;

use Behat\WebApiExtension\Context\WebApiContext;

class ApiContext extends WebApiContext
{
    /**
     * ApiContext constructor.
     * Set base url placeholder.
     */
    public function __construct()
    {
        $this->setPlaceHolder(':baseUrl', 'http://localhost:8080');
    }
}
