<?php declare(strict_types = 1);

namespace NetworkTransport\Udp;

interface RequestInterface
{
    /**
     * @return string
     */
    public function serialize(): string;
}
