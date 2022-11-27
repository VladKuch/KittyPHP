<?php
namespace Test\Http;
use \Core\Http\Uri;
use PHPUnit\Framework\TestCase;

class UriTest extends TestCase
{
    public function testGetUriScheme()
    {
        $uri = new Uri("https://test.ru/admin/fetch?order_desc=name");

        $this->assertSame('https', $uri->getScheme());
    }

    public function testGetUriHost()
    {
        $uri = new Uri("https://test.ru/admin/fetch?order_desc=name");

        $this->assertSame('test.ru', $uri->getHost());
    }

    public function testGetUriPortEmpty()
    {
        $uri = new Uri("https://test.ru/admin/fetch?order_desc=name");

        $this->assertEmpty($uri->getPort());
    }

    public function testGetUriPortNotEmpty()
    {
        $uri = new Uri("https://test.ru:8015/admin/fetch?order_desc=name");

        $this->assertSame(8015, $uri->getPort());
    }

    public function testGetUriPath()
    {
        $uri = new Uri("https://test.ru/admin/fetch?order_desc=name");

        $this->assertSame('/admin/fetch', $uri->getPath());
    }

    public function testGetUriQuery()
    {
        $uri = new Uri("https://test.ru/admin/fetch?order_desc=name");

        $this->assertSame('order_desc=name', $uri->getQuery());
    }

    public function testWithShemeWrong()
    {
        $uri = new Uri("https://test.ru/admin/fetch?order_desc=name");
        $this->expectException(\InvalidArgumentException::class);
        $uri = $uri->withScheme(80);
    }

    public function testWithWrongSheme()
    {
        $uri = new Uri("https://test.ru/admin/fetch?order_desc=name");
        $this->expectException(\InvalidArgumentException::class);
        $uri = $uri->withScheme('ftp');
    }

    public function testWithShemeRight()
    {
        $uri = new Uri("https://test.ru/admin/fetch?order_desc=name");

        $uri = $uri->withScheme('http');
        $this->assertSame('http', $uri->getScheme());
    }
}