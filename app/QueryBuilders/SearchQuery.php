<?php

declare(strict_types=1);

namespace App\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;

class SearchQuery extends Builder
{
    public function search(string $column): self
    {
        if (request()->has("search")) {
            return $this->where($column, "ilike", request("search") . "%");
        }

        return $this;
    }
}
