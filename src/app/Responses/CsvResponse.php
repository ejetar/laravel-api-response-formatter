<?php

namespace Ejetar\ApiResponseFormatter\Responses;

use Illuminate\Http\ResponseTrait;
use Illuminate\Support\Traits\Macroable;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\CsvEncoder;

class CsvResponse extends Response {
    use ResponseTrait, Macroable {
        Macroable::__call as macroCall;
    }

    protected $data;

    /**
     * @param mixed $data The response data
     * @param int $status The response status code
     * @param array $headers An array of response headers
     * @param bool $csv If the data is already a CSV string
     */
    public function __construct($data = null, int $status = 200, array $headers = [], bool $csv = false) {
        parent::__construct('', $status, $headers);

        if (null === $data) {
            $data = new \ArrayObject();
        }

        $csv ? $this->setCsv($data) : $this->setData($data);
    }

    /**
     * Factory method for chainability.
     *
     * Example:
     *
     *     return CsvResponse::fromCsvString('{"key": "value"}')
     *         ->setSharedMaxAge(300);
     *
     * @param string|null $data The CSV response string
     * @param int $status The response status code
     * @param array $headers An array of response headers
     *
     * @return static
     */
    public static function fromCsvString(string $data = null, int $status = 200, array $headers = []) {
        return new static($data, $status, $headers, true);
    }

    /**
     * Sets a raw string containing a CSV document to be sent.
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function setCsv(string $csv) {
        $this->data = $csv;

        return $this->update();
    }

    /**
     * Sets the data to be sent as CSV.
     *
     * @param mixed $data
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function setData($data = []) {
        $encoder = new CsvEncoder();
        return $this->setCsv($encoder->encode($data, 'csv'));
    }

    /**
     * Updates the content and headers according to the CSV data.
     *
     * @return $this
     */
    protected function update() {
        $this->headers->set('Content-Type', 'text/csv');

        return $this->setContent($this->data);
    }
}
