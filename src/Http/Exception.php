<?php declare(strict_types = 1);

namespace NetworkTransport\Http;

class Exception extends \Exception
{
    public const BAD_REQUEST = 400;
    public const NOT_FOUND = 404;
    public const METHOD_NOT_ALLOWED = 405;
    public const CLIENT_TIMEOUT = 408;
    public const INTERNAL_SERVER_ERROR = 500;
    public const BAD_GATEWAY = 502;
    public const SERVER_TIMEOUT = 504;
}
