<?php
namespace App\Http\Services;

use Illuminate\Http\Response as LumenResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class Response extends LumenResponse
{
    public function json($content = [], $code = LumenResponse::HTTP_OK, $meta = [], $paginate = [], $dev = [])
    {
        $meta['code'] = $code;
        $respond = [
            'meta' => $meta,
            'data' => $content,
        ];
        if (!empty($paginate)) {
            $respond['paginate'] = $paginate;
        }
        if (!empty($dev)) {
            $respond['dev'] = $dev;
        }
        return $this->setStatusCode($code)->setContent($respond);
    }

    public function jsonPaginator(LengthAwarePaginator $paginator, array $meta = [], callable $map = null)
    {
        $collection = $paginator->getCollection();
        $content = $map ? $collection->map($map) : $collection->toArray();

        return $this->json($content, 200, $meta, [
            'size' => $paginator->perPage(),
            'total' => $paginator->total(),
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
        ]);
    }
}
