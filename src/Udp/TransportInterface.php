<?php declare(strict_types = 1);

namespace NetworkTransport\Udp;

interface TransportInterface
{
    /**
     * @param RequestInterface $request
     * @return bool
     * @throws Exception
     */
    public function send(RequestInterface $request): bool;
}
