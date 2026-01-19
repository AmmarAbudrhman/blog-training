<?php

namespace App\Helpers;

use Illuminate\Pagination\LengthAwarePaginator;

class PaginationHelper
{
    public static function format(LengthAwarePaginator $paginator, $resourceClass = null)
    {
        $items = $paginator->items();

        if ($resourceClass) {
            $items = $resourceClass::collection($items);
        }

        return [
            'items' => $items,
            'pagination' => [
                'total' => $paginator->total(),
                'count' => $paginator->count(),
                'per_page' => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
                'total_pages' => $paginator->lastPage(),
                'next_page_url' => $paginator->nextPageUrl(),
                'prev_page_url' => $paginator->previousPageUrl(),
            ],
        ];
    }
}
