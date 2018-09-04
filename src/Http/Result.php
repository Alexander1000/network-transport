<?php declare(strict_types = 1);

namespace NetworkTransport\Http;

class Result
{
    /**
     * @var null|string
     */
    protected $response;

    /**
     * @var int|null
     */
    protected $errorCode;

    /**
     * @var null|string
     */
    protected $errorMsg;

    public function __construct(?string $response, ?int $errorCode = null, ?string $errorMsg = null)
    {
        $this->response = $response;
        $this->errorCode = $errorCode;
        $this->errorMsg = $errorMsg;
    }
}
