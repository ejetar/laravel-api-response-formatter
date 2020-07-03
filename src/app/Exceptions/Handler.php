<?php

namespace Ejetar\ApiResponseFormatter\Exceptions;

use Ejetar\ApiResponseFormatter\Responses\CsvResponse;
use Ejetar\ApiResponseFormatter\Responses\XmlResponse;
use Ejetar\ApiResponseFormatter\Responses\YamlResponse;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Routing\Router;
use Illuminate\Validation\ValidationException;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler {
    /**
     * Prepare a CSV response for the given exception.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Throwable $e
     * @return CsvResponse
     */
    protected function prepareCsvResponse($request, Throwable $e) {
        return new CsvResponse(
            $this->convertExceptionToArray($e),
            $this->isHttpException($e) ? $e->getStatusCode() : 500,
            $this->isHttpException($e) ? $e->getHeaders() : []
        );
    }

    /**
     * Prepare a YAML response for the given exception.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Throwable $e
     * @return YamlResponse
     */
    protected function prepareYamlResponse($request, Throwable $e) {
        return new YamlResponse(
            $this->convertExceptionToArray($e),
            $this->isHttpException($e) ? $e->getStatusCode() : 500,
            $this->isHttpException($e) ? $e->getHeaders() : []
        );
    }

    /**
     * Prepare a XML response for the given exception.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Throwable $e
     * @return XmlResponse
     */
    protected function prepareXmlResponse($request, Throwable $e) {
        return new XmlResponse(
            $this->convertExceptionToArray($e),
            $this->isHttpException($e) ? $e->getStatusCode() : 500,
            $this->isHttpException($e) ? $e->getHeaders() : []
        );
    }

    /**
     * Convert a validation exception into a XML response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Validation\ValidationException  $exception
     * @return XmlResponse
     */
    protected function invalidXml($request, ValidationException $exception) {
        return new XmlResponse([
            'message' => $exception->getMessage(),
            'errors' => $exception->errors(),
        ], $exception->status);
    }

    /**
     * Convert a validation exception into a CSV response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Validation\ValidationException  $exception
     * @return CsvResponse
     */
    protected function invalidCsv($request, ValidationException $exception) {
        return new CsvResponse([
            'message' => $exception->getMessage(),
            'errors' => $exception->errors(),
        ], $exception->status);
    }

    /**
     * Convert a validation exception into a YAML response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Validation\ValidationException  $exception
     * @return YamlResponse
     */
    protected function invalidYaml($request, ValidationException $exception) {
        return new YamlResponse([
            'message' => $exception->getMessage(),
            'errors' => $exception->errors(),
        ], $exception->status);
    }

    /**
     * Create a response object from the given validation exception.
     *
     * @param  \Illuminate\Validation\ValidationException  $e
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function convertValidationExceptionToResponse(ValidationException $e, $request) {
        if ($e->response)
           return $e->response;

        if ($request->expectsJson()) {
            return $this->invalidJson($request, $e);

        } elseif ($request->expectsXml()) {
            return $this->invalidXml($request, $e);

        } elseif ($request->expectsCsv()) {
            return $this->invalidCsv($request, $e);

        } elseif ($request->expectsYaml()) {
            return $this->invalidYaml($request, $e);

        } else {
            $this->invalid($request, $e);
        }
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param Throwable $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $e) {
        if (method_exists($e, 'render') && $response = $e->render($request)) {
            return Router::toResponse($request, $response);
        } elseif ($e instanceof Responsable) {
            return $e->toResponse($request);
        }

        $e = $this->prepareException($e);

        if ($e instanceof HttpResponseException) {
            return $e->getResponse();
        } elseif ($e instanceof AuthenticationException) {
            return $this->unauthenticated($request, $e);
        } elseif ($e instanceof ValidationException) {
            return $this->convertValidationExceptionToResponse($e, $request);
        }

        if ($request->expectsJson()) {
            return $this->prepareJsonResponse($request, $e);

        } elseif ($request->expectsXml()) {
            return $this->prepareXmlResponse($request, $e);

        } elseif ($request->expectsCsv()) {
            return $this->prepareCsvResponse($request, $e);

        } elseif ($request->expectsYaml()) {
            return $this->prepareYamlResponse($request, $e);

        } else {
			if (\Request::is('api/*'))
				return $this->prepareJsonResponse($request, $e);
			else
				return $this->prepareResponse($request, $e);
        }
    }
}
