<?php

namespace Ejetar\ApiResponseFormatter\Http\Middleware;

use Ejetar\ApiResponseFormatter\Responses\CsvResponse;
use Ejetar\ApiResponseFormatter\Responses\XmlResponse;
use Ejetar\ApiResponseFormatter\Responses\YamlResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiResponseFormatter {
    public function handle(Request $request, $next, $scopeRequired = null) {
        $response = $next($request);

        //If the Accept Header was entered
        $original_content = $response->getOriginalContent();
        if (!empty($original_content)) {
            $data 	 = is_array($original_content) ? $original_content : $original_content->toArray();
            $status  = $response->status();
            $headers = $response->headers->all();

            if ($request->expectsJson()) {
                return new JsonResponse($data, $status, $headers);

            } elseif ($request->expectsXml()) {
                return new XmlResponse($data, $status, $headers);

            } elseif ($request->expectsYaml()) {
                return new YamlResponse($data, $status, $headers);

            } elseif ($request->expectsCsv()) {
                return new CsvResponse($data, $status, $headers);

            } else {
                return new JsonResponse([
                    'message' => 'Mime-type not accepted.'
                ], 406);
            }
        } else {
            return $original_content;
        }
    }
}
