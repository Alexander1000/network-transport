<?php declare(strict_types = 1);

namespace NetworkTransport\Http;

class Response
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

    /**
     * @return null|string
     */
    public function getResponse(): ?string
    {
        return $this->getResponse();
    }

    /**
     * @return bool
     */
    public function isError(): bool
    {
        return $this->errorCode !== null || $this->errorMsg !== null;
    }

    /**
     * @return int|null
     */
    public function getErrorCode(): ?int
    {
        return $this->errorCode;
    }

    /**
     * @return null|string
     */
    public function getErrorMessage(): ?string
    {
        return $this->errorMsg;
    }
}
