<?php declare(strict_types = 1);

namespace NetworkTransport\Http;

use NetworkTransport;

class Transport implements TransportInterface
{
    private $multiCurlResource;

    /**
     * @param Request $request
     * @return Response
     * @throws Exception\MethodNotAllowed
     */
    public function send(Request $request): Response
    {
        $ch = $request->getResource();
        $result = curl_exec($ch);
        if ($result === false) {
            $response = new Response(null, curl_errno($ch), curl_error($ch));
            curl_close($ch);
            return $response;
        }

        curl_close($ch);
        return new Response($result);
    }
}
