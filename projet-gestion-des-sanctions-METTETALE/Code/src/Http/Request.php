<?php

class Request
{
    private array $get;
    private array $post;
    private array $server;
    private string $method;
    private string $action;

    public function __construct()
    {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->server = $_SERVER;
        $this->method = $_SERVER["REQUEST_METHOD"];
        $this->action = $_GET["action"] ?? "index";
    }

    public function get($key, $default = null)
    {
        return $this->get[$key] ?? $default;
    }

    public function post($key, $default = null)
    {
        return $this->post[$key] ?? $default;
    }

    public function allPost()
    {
        return $this->post;
    }

    public function isPost(): bool
    {
        return $this->method === "POST";
    }

    public function isGet(): bool
    {
        return $this->method === "GET";
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function has($param): bool
    {
        return array_key_exists($param, $this->get) ?? array_key_exists($param, $this->post);
    }

    public function getUrl(): string
    {
        return strtolower(explode("/", $this->server["SERVER_PROTOCOL"])[0]) . "://" . $this->server["SERVER_NAME"] . $this->server["REQUEST_URI"];
    }

    public function getClientIp()
    {
        return $this->server["REMOTE_ADDR"] ?? "Unknow";
    }
}
