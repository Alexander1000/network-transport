<?php declare(strict_types = 1);

namespace NetworkTransport\Http;

use NetworkTransport;

class Transport implements TransportInterface
{
    /**
     * @param resource $request
     * @return Response
     * @throws Exception\MethodNotAllowed
     */
    public function send(resource $request): Response
    {
        $result = curl_exec($request);
        if ($result === false) {
            $response = new Response(null, curl_errno($request), curl_error($request));
            curl_close($request);
            return $response;
        }

        curl_close($request);
        return new Response($result);
    }
}
