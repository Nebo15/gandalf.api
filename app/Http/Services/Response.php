<?php
namespace App\Http\Services;

use Illuminate\Http\Response as LumenResponse;

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
}
