<?php declare(strict_types = 1);

namespace NetworkTransport\Http;

interface TransportInterface
{
    /**
     * @param Request $request
     * @return Response
     * @throws Exception\MethodNotAllowed
     */
    public function send(Request $request): Response;
}
