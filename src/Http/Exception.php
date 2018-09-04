<?php declare(strict_types = 1);

namespace NetworkTransport\Http;

class Exception extends \Exception
{
    public const BAD_REQUEST = 400;
    public const METHOD_NOT_ALLOWED = 405;
}
