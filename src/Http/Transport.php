<?php declare(strict_types = 1);

namespace NetworkTransport\Http;

class Transport implements \NetworkTransport\TransportInterface
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
     */
    public function send(Request $request): Result
    {
        $ch = curl_init(sprintf('%s:%d%s', $this->host, $this->port, $request->getUri()));
        // @todo implement me
    }
}
