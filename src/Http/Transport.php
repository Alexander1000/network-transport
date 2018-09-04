<?php declare(strict_types = 1);

namespace NetworkTransport\Http;

use NetworkTransport;

class Transport implements NetworkTransport\TransportInterface
{
    public const METHOD_GET = 'GET';
    public const METHOD_POST = 'POST';

    /**
     * @var string
     */
    protected $host;

    /**
     * @var int
     */
    protected $port;

    /**
     * @var array
     */
    protected $options;

    public function __construct(string $host, int $port, array $options = [])
    {
        $this->host = $host;
        $this->port = $port;
        $this->options = $options;
    }

    /**
     * @param Request $request
     * @return Response
     * @throws Exception\MethodNotAllowed
     */
    public function send(Request $request): Response
    {
        $ch = curl_init(sprintf('%s:%d%s', $this->host, $this->port, $request->getUri()));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $request->getHeaders());

        if (isset($this->options['timeoutMs'])) {
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
}
