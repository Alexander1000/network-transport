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
        $taskId = $this->getTaskId($request);
        var_dump($taskId);
        if ($taskId === null) {
            // не было задач в multi_curl
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

        if ($taskId !== $this->parallelTaskId) {
            // запросили уже отработанную задачу
            return $this->responseCollector[$taskId][$request->getHash()];
        }

        $active = null;
        $mh = $this->multiCurlResource[$taskId];
        do {
            $mrc = curl_multi_exec($mh, $active);
        } while($mrc == CURLM_CALL_MULTI_PERFORM);

        while ($active && $mrc == CURLM_OK) {
            // Wait for activity on any curl-connection
            if (curl_multi_select($mh) == -1) {
                usleep(1);
            }

            do {
                $mrc = curl_multi_exec($mh, $active);
            } while ($mrc == CURLM_CALL_MULTI_PERFORM);
        }

        $response = null;

        foreach ($this->requestCollector[$taskId] as $hash => $tRequest) {
            /** @var Request $tRequest */
            $result = curl_multi_getcontent($tRequest->getResource());
            $errno = curl_errno($tRequest->getResource());
            if ($errno) {
                $this->responseCollector[$this->parallelTaskId][$hash] = new Response(null, $errno, curl_error($request->getResource()));
            } else {
                $this->responseCollector[$this->parallelTaskId][$hash] = new Response($result);
            }
            curl_multi_remove_handle($mh, $tRequest->getResource());

            if ($hash == $request->getHash()) {
                $response = $this->responseCollector[$this->parallelTaskId][$hash];
            }
        }

        curl_multi_close($mh);
        $this->parallelTaskId++;

        return $response;
    }

    private function getTaskId(Request $request): ?int
    {
        foreach ($this->requestCollector as $taskId => $requestCollection) {
            if (isset($requestCollection[$request->getHash()])) {
                return $taskId;
            }
        }
        
        return null;
    }
}
