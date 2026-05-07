<?php

namespace App\Support;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;

class StaticPagePaginator extends LengthAwarePaginator
{
    public function url($page)
    {
        if ($page <= 0) {
            $page = 1;
        }

        $path = $this->path();

        $parameters = [];
        if (count($this->query) > 0) {
            $parameters = $this->query;
        }

        $queryString = '';
        if (count($parameters) > 0) {
            $queryString = (str_contains($path, '?') ? '&' : '?') . Arr::query($parameters);
        }

        if ($page === 1) {
            return $path . $queryString;
        }

        return rtrim($path, '/') . '/page_' . $page . '.html' . $queryString;
    }
}
