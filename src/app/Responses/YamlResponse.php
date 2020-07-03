<?php

namespace Ejetar\ApiResponseFormatter\Responses;

use Illuminate\Http\ResponseTrait;
use Illuminate\Support\Traits\Macroable;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Yaml\Yaml;

class YamlResponse extends Response {
    use ResponseTrait, Macroable {
        Macroable::__call as macroCall;
    }

    protected $data;

    /**
     * @param mixed $data The response data
     * @param int $status The response status code
     * @param array $headers An array of response headers
     * @param bool $yaml If the data is already a YAML string
     */
    public function __construct($data = null, int $status = 200, array $headers = [], bool $yaml = false) {
        parent::__construct('', $status, $headers);

        if (null === $data) {
            $data = new \ArrayObject();
        }

        $yaml ? $this->setYaml($data) : $this->setData($data);
    }

    /**
     * Factory method for chainability.
     *
     * Example:
     *
     *     return YamlResponse::fromYamlString('{"key": "value"}')
     *         ->setSharedMaxAge(300);
     *
     * @param string|null $data The YAML response string
     * @param int $status The response status code
     * @param array $headers An array of response headers
     *
     * @return static
     */
    public static function fromYamlString(string $data = null, int $status = 200, array $headers = []) {
        return new static($data, $status, $headers, true);
    }

    /**
     * Sets a raw string containing a YAML document to be sent.
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function setYaml(string $yaml) {
        $this->data = $yaml;

        return $this->update();
    }

    /**
     * Sets the data to be sent as YAML.
     *
     * @param mixed $data
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function setData($data = []) {
        return $this->setYaml(Yaml::dump($data));
    }

    /**
     * Updates the content and headers according to the YAML data.
     *
     * @return $this
     */
    protected function update() {
        $this->headers->set('Content-Type', 'application/x-yaml');

        return $this->setContent($this->data);
    }
}
