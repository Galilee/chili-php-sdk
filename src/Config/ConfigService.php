<?php

namespace Galilee\PPM\SDK\Chili\Config;

use Galilee\PPM\SDK\Chili\Exception\InvalidConfigurationException;
use Galilee\PPM\SDK\Chili\Exception\InvalidJsonException;
use Galilee\PPM\SDK\Chili\Exception\InvalidXmlException;
use Galilee\PPM\SDK\Chili\Exception\InvalidYamlException;
use Symfony\Component\Yaml\Yaml;

class ConfigService
{
    private $config = null;

    const TYPE_XML = 'xml';
    const TYPE_JSON = 'json';
    const TYPE_YAML = 'yaml';
    const TYPE_PHP_ARRAY = 'php_array';

    protected $allowedTypes = [
        self::TYPE_XML,
        self::TYPE_YAML,
        self::TYPE_JSON,
        self::TYPE_PHP_ARRAY
    ];

    protected $allowedConfigProperties = [
        'login',
        'password',
        'wsdlUrl',
        'environment',
        'privateUrl',
        'publicUrl',
    ];

    /**
     * init configuration object
     *
     * @param string $type
     * @param string|array $conf - should array (key-value pairs), json, yaml or xml format
     *
     * @return Config
     */
    public function __construct($type, $conf)
    {
        $this->config = $this->createConfig($type, $conf);
    }

    /**
     * @return Config|null
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Creates Config object from the given $conf parameter
     *
     * @param string $type
     * @param string|array $conf
     * @throws InvalidConfigurationException
     * @throws InvalidJsonException
     * @throws InvalidYamlException
     *
     * @return Config $config
     */
    private function createConfig($type, $conf)
    {
        $result = null;
        switch ($type) {
            case self::TYPE_PHP_ARRAY :
                $result = $this->parseArray($conf);
                break;
            case self::TYPE_XML:
                $result = $this->parseXml($conf);
                break;
            case self::TYPE_JSON:
                $result = $this->parseJson($conf);
                break;
            case self::TYPE_YAML:
                $result = $this->parseYaml($conf);
                break;
            default:
                throw new InvalidConfigurationException('Invalid configuration type. Expected one of: '. implode(', ', $this->allowedTypes));
        }

        $config = new Config();
        if ($this->checkConfiguration($result)) {
            // set Config object values
            foreach ($result as $key => $value) {
                $method = 'set' . ucfirst($key);
                if (method_exists($config, $method)) {
                    $config->{$method}($value);
                }
            }
        }

        return $config;
    }

    /**
     * Transforms xml to array
     *
     * @param string $xml
     *
     * @return array
     */
    protected function parseXml($xml)
    {
        libxml_use_internal_errors(true);
        $resultObject = simplexml_load_string($xml, 'SimpleXMLElement');

        if ($resultObject === false) {
            $errors = libxml_get_errors();
            $messages = [];
            foreach ($errors as $error) {
                $messages[] = $error->message;
            }
            throw new InvalidXmlException('Invalid XML : ' . implode("\t", $messages));
        }
        $result = [];
        if ($resultObject instanceof \SimpleXMLElement) {
            foreach ($resultObject as $key => $value) {
                $result[$key] = (string) $value;
            }
        }

        return $result;
    }

    /**
     * Transforms yaml to array
     * (uses Symfony Yaml component)
     *
     * @param string $yaml
     * @throws InvalidYamlException
     *
     * @return array
     */
    protected function parseYaml($yaml)
    {
        try {
            $decoded = Yaml::parse($yaml);
        } catch (\Exception $e) {
            throw new InvalidYamlException($e->getMessage());
        }
        return $decoded;
    }

    /**
     * Transforms JSON to array
     *
     * @param string $json
     *
     * @return array
     * @throws InvalidJsonException
     */
    protected function parseJson($json)
    {
        $decoded = @json_decode($json, true);

        if ($decoded === null && json_last_error() != JSON_ERROR_NONE) {
            throw new InvalidJsonException('Invalid JSON encoding / decoding. Error code : ' . json_last_error());
        }

        return $decoded;
    }

    /**
     *
     * @param array $arr
     *
     * @return array
     */
    protected function parseArray($arr)
    {
        if (!is_array($arr)) {
            throw new InvalidConfigurationException('Illegal configuration format. The argument $arr should be an array (key => value pairs)');
        }

        return $arr;
    }


    /**
     * Verifies configuration properties
     *
     * @param array $config
     *
     * @return bool
     * @throws InvalidConfigurationException
     */
    protected function checkConfiguration($config)
    {
        if (!is_array($config)) {
            throw new InvalidConfigurationException('Unknown configuration format.');
        }

        $missingProp = [];
        foreach ($this->allowedConfigProperties as $property) {
            if (!isset($config[$property])) {
                $missingProp[] = $property;
            }
        }
        if (!empty($missingProp)) {
            throw new InvalidConfigurationException('Invalid configuration. Missing properties: ' . implode(', ', $missingProp));
        }

        return true;
    }
}
