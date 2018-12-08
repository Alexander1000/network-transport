<?php declare(strict_types = 1);

namespace NetworkTransport\Http;

interface TransportInterface
{
    /**
     * @param resource $request
     * @return Response
     * @throws Exception\MethodNotAllowed
     */
    public function send(resource $request): Response;
}
