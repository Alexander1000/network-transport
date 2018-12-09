<?php declare(strict_types = 1);

namespace NetworkTransport\Http;

use NetworkTransport;

class Transport implements TransportInterface
{
    /**
     * @var int
     */
    private $parallelTaskId = 0;

    /**
     * @var resource[]
     */
    private $multiCurlResource = [];

    private $requestCollector = [];

    private $responseCollector = [];

    /**
     * @param Request $request
     * @return $this
     */
    public function add(Request $request)
    {
        if (empty($this->multiCurlResource[$this->parallelTaskId])) {
            $this->multiCurlResource[$this->parallelTaskId] = curl_multi_init();
        }

        curl_multi_add_handle($this->multiCurlResource[$this->parallelTaskId], $request->getResource());
        $this->requestCollector[$this->parallelTaskId][$request->getHash()] = $request;
        return $this;
    }

    /**
     * @param Request $request
     * @return Response
     * @throws Exception\MethodNotAllowed
     */
    public function send(Request $request): Response
    {
        $active = null;
        $mh = $this->multiCurlResource[$this->parallelTaskId];
        do {
            $mrc = curl_multi_exec($mh, $active);
        } while($mrc == CURLM_CALL_MULTI_PERFORM);

        while ($active && $mrc == CURLM_OK) {
            if (curl_multi_select($mh) != -1) {
                do {
                    $mrc = curl_multi_exec($mh, $active);
                } while ($mrc == CURLM_CALL_MULTI_PERFORM);
            }
        }

        foreach ($this->requestCollector[$this->parallelTaskId] as $hash => $tRequest) {
            /** @var Request $tRequest */
            $this->responseCollector[$this->parallelTaskId][$hash] = curl_multi_getcontent($tRequest->getResource());
            curl_multi_remove_handle($mh, $tRequest->getResource());
        }

        curl_multi_close($mh);
        $this->parallelTaskId++;

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
