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
     * @param RequestInterface $request
     * @return bool
     */
    public function send(RequestInterface $request): bool
    {
        $socket = @fsockopen('udp://' . $this->host, $this->port, $errno, $errstr);
        if (!$socket) {
            var_dump($errno, $errstr);
            return false;
        }
        
        $result = fwrite($socket, $request->serialize());
        return $result !== false;
    }
}
