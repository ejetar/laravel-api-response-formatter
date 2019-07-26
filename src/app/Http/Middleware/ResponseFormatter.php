<?php

namespace Ejetar\ApiResponseFormatter\App\Http\Middleware;

use Closure;
use Ejetar\AcceptHeaderInterpreter\AcceptHeaderInterpreter;
use Illuminate\Http\Response;
use SoapBox\Formatter\Formatter;

class ResponseFormatter {

    public function handle($request, Closure $next) {
        $response = $next($request);

        //If the Accept Header was entered
        if ($request->headers->get('Accept')) {
            try {
                $accept_header_interpreter = new AcceptHeaderInterpreter($request->headers->get('Accept'));
                $mime_types = $accept_header_interpreter->toCollection();

            } catch (\Exception $ex) {
                return response()->json([
                    'message' => 'The Accept Header has an invalid value based on RFC 7231, section 5.3.1 and 5.3.2.'
                ],422);
            }

            $prefered_mime_type = $mime_types->first();

            $original_content = $response->getOriginalContent();
            if (!empty($original_content)) {
                $formatter = Formatter::make(is_array($original_content) ? $original_content : $original_content->toArray(), Formatter::ARR);
                $formatted_response = new Response();
                $formatted_response
                    ->withHeaders($response->headers)
                    ->withHeaders(['Content-Type' => "{$prefered_mime_type['type']}/{$prefered_mime_type['subtype']}"]);
                $formatted_response->setStatusCode($response->status());

                if (in_array("{$prefered_mime_type['type']}/{$prefered_mime_type['subtype']}", ['application/json'])) {
                    return $response;

                } elseif (in_array("{$prefered_mime_type['type']}/{$prefered_mime_type['subtype']}", ['application/xml'])) {
                    return $formatted_response->setContent($formatter->toXml());

                } elseif (in_array("{$prefered_mime_type['type']}/{$prefered_mime_type['subtype']}", ['application/x-yaml', 'text/yaml'])) {
                    return $formatted_response->setContent($formatter->toYaml());

                } elseif (in_array("{$prefered_mime_type['type']}/{$prefered_mime_type['subtype']}", ['text/csv'])) {
                    return $formatted_response->setContent($formatter->toCsv());

                } else {
                    return response()->json([
                        'message' => 'Mime-type not accepted.'
                    ],406);
                }
            } else {
                return $original_content;
            }

        } else {
            return $response;
        }
    }

}
