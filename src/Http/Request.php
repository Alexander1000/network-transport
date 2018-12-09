<?php declare(strict_types = 1);

namespace NetworkTransport\Http;

class Request
{
    /**
     * @var resource
     */
    private $resource;

    /**
     * @var string
     */
    private $hash;

    public function __construct(Request\Builder $builder, Request\Data $data)
    {
        $this->resource = $builder->build($data);
        $this->hash = spl_object_hash($data);
    }

    /**
     * @return resource
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }
}
