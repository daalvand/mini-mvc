<?php

namespace Core\Contracts\Http;

interface Response
{
    /**
     * Set status code
     *
     * @param int $status
     *
     * @return Response
     */
    public function statusCode(int $status): static;

    /**
     * Redirect to another url
     *
     * @param $url
     *
     * @return void
     */
    public function redirect($url): void;

    /**
     * Render view
     *
     * @param string $view
     * @param array  $data
     *
     * @return string
     */
    public function render(string $view, array $data = []): string;
}
