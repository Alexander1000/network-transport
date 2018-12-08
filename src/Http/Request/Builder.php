<?php declare(strict_types = 1);

namespace NetworkTransport\Http\Request;

class Builder
{
    public const METHOD_GET = 'GET';
    public const METHOD_POST = 'POST';

    public const HEADER_CONTENT_TYPE = 'Content-Type';

    /**
     * @var string
     */
    private $host;

    /**
     * @var array
     */
    private $headers;

    /**
     * @var array
     */
    private $options;

    public function __construct(string $host, array $headers = [], array $options = [])
    {
        $this->host = $host;
        $this->headers = $headers;
        $this->options = $options;
    }

    /**
     * @param Data $data
     * @return resource
     * @throws \NetworkTransport\Http\Exception\MethodNotAllowed
     */
    public function build(Data $data): resource
    {
        $ch = curl_init(sprintf('%s%s', $this->host, $data->getUri()));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array_merge($this->getHeaders(), $data->getHeaders())
        );

        if (!$data->hasHeader(self::HEADER_CONTENT_TYPE) && isset($this->headers[self::HEADER_CONTENT_TYPE])) {
            $data->setHeader(self::HEADER_CONTENT_TYPE, $this->headers[self::HEADER_CONTENT_TYPE]);
        }

        if ($data->getOption('timeoutMs') !== null) {
            curl_setopt($ch, CURLOPT_TIMEOUT_MS, $data->getOption('timeoutMs'));
        } elseif ($data->getOption('timeout') !== null) {
            curl_setopt($ch, CURLOPT_TIMEOUT, $data->getOption('timeout'));
        } elseif (isset($this->options['timeoutMs'])) {
            curl_setopt($ch, CURLOPT_TIMEOUT_MS, $this->options['timeoutMs']);
        } elseif (isset($this->options['timeout'])) {
            curl_setopt($ch, CURLOPT_TIMEOUT, $this->options['timeout']);
        }

        if ($data->getMethod() === self::METHOD_POST) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data->serialize());
        } elseif ($data->getMethod() === self::METHOD_GET) {
        } else {
            throw new \NetworkTransport\Http\Exception\MethodNotAllowed(
                sprintf('Method "%s" not allowed', $data->getMethod()),
                \NetworkTransport\Http\Exception::METHOD_NOT_ALLOWED
            );
        }

        return $ch;
    }

    /**
     * @return string[]
     */
    private function getHeaders(): array
    {
        $headers = [];
        foreach ($this->headers as $header => $headerContent) {
            $headers[] = sprintf('%s: %s', $header, $headerContent);
        }
        return $headers;
    }
}
