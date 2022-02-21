<?php


namespace GuzzleRetry;


class GuzzleRetry
{
    public $max_retries;
    public $http_status;
    public $client;

    public function __construct($max_retries = 5, $http_status = 400)
    {
        $this->max_retries = $max_retries;
        $this->http_status = $http_status;
        $handlerStack      = \GuzzleHttp\HandlerStack::create(new  \GuzzleHttp\Handler\CurlHandler());
        $handlerStack->push(\GuzzleHttp\Middleware::retry($this->retryDecider(), $this->retryDelay()));
        $this->client = new \GuzzleHttp\Client(['handler' => $handlerStack]);
    }

    public function retryDecider()
    {
        return function (
            $retries,
            \GuzzleHttp\Psr7\Request $request,
            \GuzzleHttp\Psr7\Response $response = null,
            \GuzzleHttp\Exception\RequestException $exception = null
        ) {
            if ($retries >= $this->max_retries) {
                return false;
            }
            if ($exception instanceof \GuzzleHttp\Exception\ConnectException) {
                return true;
            }

            if ($response) {
                // 如果请求有响应，但是状态码大于等于自定义的，继续重试(这里根据自己的业务而定)
                if ($response->getStatusCode() >= $this->http_status) {
                    return true;
                }
            }

            return false;
        };
    }

    protected function retryDelay()
    {
        return function ($numberOfRetries) {
            return 1000 * $numberOfRetries;
        };
    }
}