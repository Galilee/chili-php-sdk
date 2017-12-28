<?php

namespace Galilee\PPM\SDK\Chili\Config;

use Galilee\PPM\SDK\Chili\Exception\InvalidConfigurationException;
use Galilee\PPM\SDK\Chili\Exception\InvalidJsonException;
use Galilee\PPM\SDK\Chili\Exception\InvalidXmlException;
use Galilee\PPM\SDK\Chili\Exception\InvalidYamlException;
use Symfony\Component\Yaml\Yaml;

class ConfigService
{
    const TYPE_XML = 'xml';
    const TYPE_JSON = 'json';
    const TYPE_YAML = 'yaml';
    const TYPE_PHP_ARRAY = 'php_array';

    protected static $allowedTypes = array(
        self::TYPE_XML,
        self::TYPE_YAML,
        self::TYPE_JSON,
        self::TYPE_PHP_ARRAY
    );


    public static function fromYaml($yaml)
    {
        $result = self::parseYaml($yaml);
        $config = new Config();
        if (self::checkConfiguration($result)) {
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
     * @return array
     * @throws InvalidXmlException
     */
    protected static function parseXml($xml)
    {
        libxml_use_internal_errors(true);
        $resultObject = simplexml_load_string($xml, 'SimpleXMLElement');

        if ($resultObject === false) {
            $errors = libxml_get_errors();
            $messages = array();
            foreach ($errors as $error) {
                $messages[] = $error->message;
            }
            throw new InvalidXmlException('Invalid XML : ' . implode("\t", $messages));
        }
        $result = array();
        if ($resultObject instanceof \SimpleXMLElement) {
            foreach ($resultObject as $key => $value) {
                $result[$key] = (string)$value;
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
     * @return array
     */
    protected static function parseYaml($yaml)
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
    protected static function parseJson($json)
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
     * @throws InvalidConfigurationException
     */
    protected static function parseArray($arr)
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
    protected static function checkConfiguration($config)
    {
        if (!is_array($config)) {
            throw new InvalidConfigurationException('Unknown configuration format.');
        }

        $missingProp = array();
        foreach (Config::$mandatoryFields as $property) {
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
