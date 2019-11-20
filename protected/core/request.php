<?php
/**
 * Created by PhpStorm.
 * User: novichkov
 * Date: 14.09.17
 * Time: 12:48
 */
class request
{
    public $headers = null;
    public function withMethod($method)
    {
        return $this->getMethod() === $method;
    }

    public function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function getContentType()
    {

    }

    public function getHeaders()
    {
        if(null === $this->headers) {
            $this->headers = getallheaders();
        }
        return $this->headers;
    }

    public function getHeader($header)
    {
        return $this->getHeaders()[$header];
    }

//withUri(UriInterface $uri, $preserveHost = false)
//withCookieParams(array $cookies)
//withQueryParams(array $query)
//withUploadedFiles(array $uploadedFiles)
//withParsedBody($data)
//withAttribute($name, $value)
//withoutAttribute($name)
}