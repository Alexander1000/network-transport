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

    /**
     * @var array
     */
    protected $headers;

    public function __construct(string $uri, string $method, array $headers)
    {
        $this->uri = $uri;
        $this->method = $method;
        $this->headers = $headers;
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

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }
}
