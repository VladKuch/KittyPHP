<?php
namespace Core\Mvc;

use \Core\Http\Uri;
use \Core\Http\Request;

class Router 
{
    private array $get_array = [];
    private array $post_array = [];

    public function __construct()
    {

    }

    public function handle(Request $request, Uri $uri)
    {

    }

    public function get(string $pattern, $path): void
    {
        $this->get_array[$pattern] = $path;
    }

    public function post(string $pattern, $path): void
    {
        $this->post_array[$pattern] = $path;
    }

}