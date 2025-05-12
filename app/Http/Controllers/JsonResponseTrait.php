<?php

namespace App\Http\Controllers;

trait JsonResponseTrait
{
    // Success response
    protected function success($data, $title = 'Success', $status = 200, $meta = null)
    {
        return response()->json([
            'status' => 'success',
            'title' => $title,
            'data' => $data,
            'error' => null,
            'code' => $status,
            'meta' => $meta,
        ], $status);
    }

    // Error response
    protected function error($title, $message, $status = 400, $meta = null)
    {
        return response()->json([
            'status' => 'error',
            'title' => $title,
            'data' => null,
            'error' => [
                'message' => $message,
                'code' => $status,
            ],
            'code' => $status,
            'meta' => $meta,
        ], $status);
    }

    // Specific error responses
    protected function serverError($message = 'Server Error', $meta = null)
    {
        return $this->error('Server Error', $message, 500, $meta);
    }

    protected function badRequest($message = 'Bad Request', $meta = null)
    {
        return $this->error('Bad Request', $message, 400, $meta);
    }

    protected function unauthorized($message = 'Unauthorized', $meta = null)
    {
        return $this->error('Unauthorized', $message, 401, $meta);
    }

    protected function forbidden($message = 'Forbidden', $meta = null)
    {
        return $this->error('Forbidden', $message, 403, $meta);
    }

    protected function notFound($message = 'Not Found', $meta = null)
    {
        return $this->error('Not Found', $message, 404, $meta);
    }

    protected function timeout($message = 'Request Timeout', $meta = null)
    {
        return $this->error('Timeout', $message, 408, $meta);
    }

    // New method for Service Unavailable
    protected function serviceUnavailable($message = 'Service Unavailable', $meta = null)
    {
        return $this->error('Service Unavailable', $message, 503, $meta);
    }
}
