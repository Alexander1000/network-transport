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
     * @throws Exception
     */
    public function send(RequestInterface $request): bool
    {
        $timeout = $this->options['timeout'] ?? null;
        $socket = @fsockopen('udp://' . $this->host, $this->port, $errno, $errstr, $timeout);
        if (!$socket) {
            if (!empty($this->options['strict'])) {
                throw new Exception($errstr, $errno);
            }
            return false;
        }
        
        $result = @fwrite($socket, $request->serialize());
        return $result !== false;
    }
}
