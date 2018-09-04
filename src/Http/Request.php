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

    /**
     * @var mixed
     */
    protected $data;

    public function __construct(string $uri, string $method, array $headers)
    {
        $this->uri = $uri;
        $this->method = $method;
        $this->headers = $headers;
    }

    /**
     * @param mixed $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
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
        $headers = [];
        foreach ($this->headers as $header => $headerContent) {
            $headers[] = sprintf('%s: %s', $header, $headerContent);
        }
        return $headers;
    }

    /**
     * @return mixed
     */
    public function serialize()
    {
        if (isset($this->headers['Content-Type'])) {
            if ($this->headers['Content-Type'] == 'application/json') {
                return json_encode($this->data);
            }
        }

        return $this->data;
    }
}
