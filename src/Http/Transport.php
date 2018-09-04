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

    public function __construct(string $host, int $port)
    {
        $this->host = $host;
        $this->port = $port;
    }

    /**
     * @param Request $request
     * @return Result
     * @throws Exception\MethodNotAllowed
     */
    public function send(Request $request): Result
    {
        $ch = curl_init(sprintf('%s:%d%s', $this->host, $this->port, $request->getUri()));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $request->getHeaders());

        if ($request->getMethod() === self::METHOD_POST) {
            curl_setopt($ch, CURLOPT_POST, true);
        } elseif ($request->getMethod() === self::METHOD_GET) {
        } else {
            throw new Exception\MethodNotAllowed(
                sprintf('Method "%s" not allowed', $request->getMethod()),
                Exception::METHOD_NOT_ALLOWED
            );
        }

        $result = curl_exec($ch);

        // @todo implement me
    }
}
