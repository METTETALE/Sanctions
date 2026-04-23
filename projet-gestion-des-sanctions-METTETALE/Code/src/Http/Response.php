<?php

class Response
{
    private int $statusCode;
    private array $headers;
    private string $body;
    private string $contentType;
    private string $charset;

    public function __construct()
    {
        $this->statusCode = 200;
        $this->headers = [];
        $this->body = '';
        $this->contentType = 'text/html';
        $this->charset = 'UTF-8';
    }

    public function setStatusCode(int $statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function setBody(string $body)
    {
        $this->body = $body;
        return $this;
    }

    public function setHeader(string $name, string $value)
    {
        $this->headers[$name] = $value;
        return $this;
    }

    public function setContentType(string $contentType)
    {
        $this->contentType = $contentType;
        return $this;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getHeader(string $name)
    {
        return $this->headers[$name] ?? null;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function redirect($url, $statusCode = 302)
    {
        header("Location: index.php?action={$url}", true, $statusCode);
        return $this;
    }

    public function view($templatePath, $data = [], $statusCode = 200)
    {
        // Start output buffering to capture template output
        ob_start();
        // Extract provided data to local variables for the template
        extract((array) $data, EXTR_SKIP);
        // Use require so templates can be included multiple times if needed
        require_once $templatePath;
        // Get buffer contents and clean buffer
        $this->statusCode = $statusCode;
        return $this;
    }

    public function error($message, $statusCode = 500)
    {

        $this->statusCode = $statusCode;
        $this->body = $message;
        return $this;
    }

    public function success($message, $statusCode = 200)
    {
        $this->statusCode = $statusCode;
        $this->body = $message;
        return $this;
    }

    public function send()
    {
        // Send HTTP status code
        if (!headers_sent()) {
            http_response_code($this->statusCode);

            // Prepare Content-Type header
            $contentTypeHeader = $this->contentType ?: 'text/html';
            if ($this->charset) {
                $contentTypeHeader .= "; charset={$this->charset}";
            }
            header("Content-Type: {$contentTypeHeader}");

            // Send other headers set via setHeader()
            foreach ($this->headers as $name => $value) {
                header("{$name}: {$value}", true);
            }
        }

        // Output body
        echo $this->body;
        return $this;
    }

    public static function redirectTo($url, $statusCode = 302)
    {
        header("Location: {$url}", true, $statusCode);
        exit;
    }

    public static function errorResponse($message, $statusCode = 500)
    {
        http_response_code($statusCode);
        header('Content-Type: text/plain; charset=UTF-8');
        echo $message;
        exit;
    }
}
