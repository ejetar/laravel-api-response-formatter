<?php

namespace Ejetar\ApiResponseFormatter\Requests;

use Illuminate\Http\Request as BaseRequest;

class Request extends BaseRequest {
    /**
     * Determine if the current request is asking for XML.
     *
     * @return bool
     */
    public function wantsXml() {
        $acceptable = $this->getAcceptableContentTypes();

        return isset($acceptable[0]) && in_array($acceptable[0], ['application/xml']);
    }

    /**
     * Determine if the current request probably expects a JSON response.
     *
     * @return bool
     */
    public function expectsXml() {
        return $this->wantsXml();
    }

    /**
     * Determine if the request is sending XML.
     *
     * @return bool
     */
    public function isXml() {
        return in_array($this->header('CONTENT_TYPE'), ['application/xml']);
    }

    /**
     * Determines whether a request accepts XML.
     *
     * @return bool
     */
    public function acceptsXml() {
        return $this->accepts('application/xml');
    }



    /**
     * Determine if the current request is asking for CSV.
     *
     * @return bool
     */
    public function wantsCsv() {
        $acceptable = $this->getAcceptableContentTypes();

        return isset($acceptable[0]) && in_array($acceptable[0], ['text/csv']);
    }

    /**
     * Determine if the current request probably expects a CSV response.
     *
     * @return bool
     */
    public function expectsCsv() {
        return $this->wantsCsv();
    }

    /**
     * Determine if the request is sending CSV.
     *
     * @return bool
     */
    public function isCsv() {
        return in_array($this->header('CONTENT_TYPE'), ['text/csv']);
    }

    /**
     * Determines whether a request accepts CSV.
     *
     * @return bool
     */
    public function acceptsCsv() {
        return $this->accepts('text/csv');
    }



    /**
     * Determine if the current request is asking for YAML.
     *
     * @return bool
     */
    public function wantsYaml() {
        $acceptable = $this->getAcceptableContentTypes();

        return isset($acceptable[0]) && in_array($acceptable[0], ['application/x-yaml', 'text/yaml']);
    }

    /**
     * Determine if the current request probably expects a YAML response.
     *
     * @return bool
     */
    public function expectsYaml() {
        return $this->wantsYaml();
    }

    /**
     * Determine if the request is sending YAML.
     *
     * @return bool
     */
    public function isYaml() {
        return in_array($this->header('CONTENT_TYPE'), ['application/x-yaml', 'text/yaml']);
    }

    /**
     * Determines whether a request accepts YAML.
     *
     * @return bool
     */
    public function acceptsYaml() {
        return $this->accepts(['application/x-yaml', 'text/yaml']);
    }
}
