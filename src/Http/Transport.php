<?php declare(strict_types = 1);

namespace NetworkTransport\Http;

use NetworkTransport;

class Transport implements TransportInterface
{
    public const METHOD_GET = 'GET';
    public const METHOD_POST = 'POST';

    public const HEADER_CONTENT_TYPE = 'Content-Type';

    /**
     * @var string
     */
    protected $host;

    /**
     * @var array
     */
    protected $headers;

    /**
     * @var array
     */
    protected $options;

    public function __construct(string $host, array $headers = [], array $options = [])
    {
        $this->host = $host;
        $this->headers = $headers;
        $this->options = $options;
    }

    /**
     * @param Request $request
     * @return Response
     * @throws Exception\MethodNotAllowed
     */
    public function send(Request $request): Response
    {
        $ch = curl_init(sprintf('%s%s', $this->host, $request->getUri()));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array_merge($this->getHeaders(), $request->getHeaders())
        );

        if (!$request->hasHeader(self::HEADER_CONTENT_TYPE) && isset($this->headers[self::HEADER_CONTENT_TYPE])) {
            $request->setHeader(self::HEADER_CONTENT_TYPE, $this->headers[self::HEADER_CONTENT_TYPE]);
        }

        if ($request->getOption('timeoutMs') !== null) {
            curl_setopt($ch, CURLOPT_TIMEOUT_MS, $request->getOption('timeoutMs'));
        } elseif ($request->getOption('timeout') !== null) {
            curl_setopt($ch, CURLOPT_TIMEOUT, $request->getOption('timeout'));
        } elseif (isset($this->options['timeoutMs'])) {
            curl_setopt($ch, CURLOPT_TIMEOUT_MS, $this->options['timeoutMs']);
        } elseif (isset($this->options['timeout'])) {
            curl_setopt($ch, CURLOPT_TIMEOUT, $this->options['timeout']);
        }

        if ($request->getMethod() === self::METHOD_POST) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $request->serialize());
        } elseif ($request->getMethod() === self::METHOD_GET) {
        } else {
            throw new Exception\MethodNotAllowed(
                sprintf('Method "%s" not allowed', $request->getMethod()),
                Exception::METHOD_NOT_ALLOWED
            );
        }

        $result = curl_exec($ch);
        if ($result === false) {
            $response = new Response(null, curl_errno($ch), curl_error($ch));
            curl_close($ch);
            return $response;
        }

        curl_close($ch);
        return new Response($result);
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
