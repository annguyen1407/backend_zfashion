<?php
class Response
{
    private $success;
    private $httpStatusCode;
    private $message = array();
    private $data;
    private $toCache = false;
    private $responseData;

    public function setSuccess($success)
    {
        $this->success = $success;
    }
    public function setHttpStatusCode($httpStatusCode)
    {
        $this->httpStatusCode = $httpStatusCode;
    }
    public function addMessage($message)
    {
        $this->message[] = $message;
    }
    public function setData($data)
    {
        $this->data = $data;
    }
    public function toCache($toCache)
    {
        $this->toCache = $toCache;
    }
    
    public function send()
    {
        header('Content-Type: application/json;charset=utf-8');
        if ($this->toCache == true) {
            header('Cache-Control: max-age=60');
        } else {
            header('Cache-Control: no-cache, no-store');
        }
        if ($this->success !== false && $this->success !== true || !is_numeric($this->httpStatusCode)) {
            http_response_code(500);
            //* show status code 
            $this->responseData['statusCode'] = 500;
            $this->responseData['success'] = false;
            $this->addMessage("Response creation error");
            $this->responseData['message'] = $this->message;
        } else {
            //* successful response
            http_response_code($this->httpStatusCode);
            $this->responseData['success'] = $this->success;
            $this->responseData['statusCode'] = $this->httpStatusCode;
            $this->responseData['message'] = $this->message;
            $this->responseData['data'] = $this->data;
        }
        echo json_encode($this->responseData);
    }
}
