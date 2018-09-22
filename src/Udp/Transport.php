<?php declare(strict_types = 1);

namespace NetworkTransport\Udp;

class Transport
{
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
     */
    public function send(Request $request)
    {
        $socket = fsockopen($this->host, $this->port, $errno, $errstr);
    }
}
