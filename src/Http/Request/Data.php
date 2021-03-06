<?php declare(strict_types = 1);

namespace NetworkTransport\Http\Request;

class Data
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

    /**
     * @var array
     */
    protected $options;

    public function __construct(string $uri, string $method, array $headers = [], array $options = [])
    {
        $this->uri = $uri;
        $this->method = $method;
        $this->headers = $headers;
        $this->options = $options;
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
     * @param string $header
     * @param string $value
     * @return $this
     */
    public function setHeader(string $header, string $value)
    {
        $this->headers[$header] = $value;
        return $this;
    }

    /**
     * @param string $header
     * @return bool
     */
    public function hasHeader(string $header): bool
    {
        return isset($this->headers[$header]);
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

    /**
     * @param string $name
     * @return mixed|null
     */
    public function getOption(string $name)
    {
        return $this->options[$name] ?? null;
    }
}
