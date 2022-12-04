<?php
namespace Core\Http\Request;

use \Psr\Http\Message\ServerRequestInterface;
use \Core\Http\Uri;
use \Core\Http\Header;
use \Core\Http\Body;
use \Core\Http\Request;

class RequestFactory 
{
    public static function createRequest(): ServerRequestInterface
    {
        $method = mb_strtolower($_SERVER['REQUEST_METHOD']) ?? 'get';
        $uri = new Uri($_SERVER['REQUEST_URI']);
        $headers = Header::createFromGlobals();
        $resource = fopen('php://input', 'r');
        $body = new Body($resource);

        $request = new Request($method, $uri, $headers, $body);

        $content_types = $request->getHeader('Content-Type');

        $parsed_content_type = '';
        foreach ($content_types as $content_type) {
            $fragments = explode(';', $content_type);
            $parsed_content_type = current($fragments);
        }

        $content_types_with_parsed_bodies = ['application/x-www-form-urlencoded', 'multipart/form-data'];
        if ($method === 'POST' && in_array($parsed_content_type, $content_types_with_parsed_bodies)) {
            return $request->withParsedBody($_POST);
        }

        return $request;
    }
}