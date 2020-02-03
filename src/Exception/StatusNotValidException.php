<?php

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class StatusNotValidException extends \RuntimeException implements HttpExceptionInterface
{

    /**
     * Returns the status code.
     *
     * @return int An HTTP response status code
     */
    public function getStatusCode() : int
    {
        return Response::HTTP_BAD_REQUEST;
    }

    /**
     * Returns response headers.
     *
     * @return array Response headers
     */
    public function getHeaders() : array
    {
        return [];
    }
}