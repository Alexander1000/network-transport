<?php declare(strict_types = 1);

namespace NetworkTransport\Http;

class Request
{
    /**
     * @var string
     */
    protected $uri;

    /**
     * @var string
     */
    protected $method;

    public function __construct(string $uri, string $method)
    {
        $this->uri = $uri;
        $this->method = $method;
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }
}
