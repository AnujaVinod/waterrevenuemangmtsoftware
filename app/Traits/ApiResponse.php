<?php

namespace App\Traits;

trait ApiResponse {

    protected $statusCode = 200;

    public function getStatusCode() {
        return $this->statusCode;
    }

    public function setStatusCode($statusCode) {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function respond($data, $headers = []) {
        return response()->json($data, $this->getStatusCode(), $headers);
    }

    public function respondWithError($message = '', $data = []) {
        $this->setStatusCode(500);
        $response = [
            'status' => 'error',
            'message' => $message,
            'statusCode' => $this->getStatusCode()
        ];
        if (!empty($data)) {
            $response['data'] = $data;
        }
        return $this->respond($response);
    }

    public function respondWithSuccess($message = '', $data = []) {       
        $this->setStatusCode(200);
        $response = [
            'status' => 'success',
            'message' => $message,
            'statusCode' => $this->getStatusCode()
        ];
        if (!empty($data)) {
            $response['data'] = isset($data['data']) ? $data['data'] : $data;
        }
        return $this->respond($response);
    }
    
    public function respondTokenError($message = '', $data = []) {
        $this->setStatusCode(300);
        $response = [
            'status' => 'error',
            'message' => $message,
            'statusCode' => $this->getStatusCode()
        ];
        if (!empty($data)) {
            $response['data'] = $data;
        }
        return $this->respond($response);
    }

    public function respondWithSuccessError($message = '', $data = []) {
        $this->setStatusCode(200);
        $response = [
            'status' => 'failure',
            'message' => $message,
            'statusCode' => $this->getStatusCode()
        ];
        if (!empty($data)) {
            $response['data'] = $data;
        }
        return $this->respond($response);
    }
}
