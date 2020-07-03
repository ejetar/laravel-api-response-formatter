<?php

namespace Ejetar\ApiResponseFormatter\Responses;

use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Illuminate\Http\ResponseTrait;
use Illuminate\Support\Traits\Macroable;
use Symfony\Component\HttpFoundation\Response;

class XmlResponse extends Response {
    use ResponseTrait, Macroable {
        Macroable::__call as macroCall;
    }

    protected $data;

    /**
     * @param mixed $data The response data
     * @param int $status The response status code
     * @param array $headers An array of response headers
     * @param bool $xml If the data is already a XML string
     */
    public function __construct($data = null, int $status = 200, array $headers = [], bool $xml = false) {
        parent::__construct('', $status, $headers);

        if (null === $data) {
            $data = new \ArrayObject();
        }

        $xml ? $this->setXml($data) : $this->setData($data);
    }

    /**
     * Factory method for chainability.
     *
     * Example:
     *
     *     return XmlResponse::fromXmlString('{"key": "value"}')
     *         ->setSharedMaxAge(300);
     *
     * @param string|null $data The XML response string
     * @param int $status The response status code
     * @param array $headers An array of response headers
     *
     * @return static
     */
    public static function fromXmlString(string $data = null, int $status = 200, array $headers = []) {
        return new static($data, $status, $headers, true);
    }

    /**
     * Sets a raw string containing a XML document to be sent.
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function setXml(string $xml) {
        $this->data = $xml;

        return $this->update();
    }

    /**
     * Sets the data to be sent as XML.
     *
     * @param mixed $data
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function setData($data = []) {
        $encoder = new XmlEncoder();
        return $this->setXml($encoder->encode($data, 'xml'));
    }

    /**
     * Updates the content and headers according to the XML data.
     *
     * @return $this
     */
    protected function update() {
        $this->headers->set('Content-Type', 'application/xml');

        return $this->setContent($this->data);
    }
}
