<?php
namespace Core\Http\Header;

interface HeaderInterface
{
    /**
     * Добавить заголовок
     *
     *
     * @param string       $name
     * @param array|string $value
     *
     * @return HeadersInterface
     */
    public function addHeader($name, $value): HeadersInterface;

    /**
     * Удалить заголовок
     *
     * @param string $name
     * @return HeadersInterface
     */
    public function removeHeader(string $name): HeadersInterface;

    /**
     * Получить заголовок.
     *
     * @param string   $name
     * @param string[] $default
     *
     * @return array
     */
    public function getHeader(string $name, $default = []): array;

    /**
     * Заменить новым заголовком.
     *
     * @param string       $name
     * @param array|string $value
     *
     * @return HeadersInterface
     */
    public function setHeader($name, $value): HeadersInterface;

    /**
     * Существует ли заголовок
     *
     * @param string $name
     * @return bool
     */
    public function hasHeader(string $name): bool;

    /**
     * Вернуть все имеющиеся заголовки
     *
     * @return array
     */
    public function getHeaders(): array;
}