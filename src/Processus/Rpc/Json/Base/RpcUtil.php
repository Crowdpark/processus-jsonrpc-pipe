<?php
/**
 * Created by JetBrains PhpStorm.
 * User: seb
 * Date: 10/17/12
 * Time: 11:53 AM
 * To change this template use File | Settings | File Templates.
 */
namespace Processus\Rpc\Json\Base;

class RpcUtil
{
    /**
     * @var array
     */
    private static $_namespaceNameCache = array();

    /**
     * @var array
     */
    private static $_reflectionClassCache = array();


    /**
     * @var array
     */
    private static $_rpcMethodParsedCache = array();


    /**
     * @param string $text
     * @param bool $assoc
     * @param bool $marshallExceptions
     * @return mixed|null
     * @throws \Exception
     */
    public static function jsonDecode(
        $text,
        $assoc,
        $marshallExceptions
    ) {
        $assoc              = ($assoc === true);
        $marshallExceptions = ($marshallExceptions === true);

        $result = null;

        if (!$marshallExceptions) {
            if (!is_string($text)) {
                return $result;
            }
            try {
                $result = json_decode($text, $assoc);
            } catch (\Exception $e) {
                // NOP
            }

            return $result;
        }

        try {
            $result = json_decode($text, $assoc);
        } catch (\Exception $e) {
            $result = null;
            // delegate exception
            throw $e;
        }

        return $result;
    }

    /**
     * @param mixed $value
     * @param bool $marshallExceptions
     * @return null|string
     * @throws \Exception
     */
    public static function jsonEncode(
        $value,
        $marshallExceptions
    ) {
        $marshallExceptions = ($marshallExceptions === true);

        $result = null;
        try {
            $result = json_encode($value);
        } catch (\Exception $e) {
            $result = null;
            if ($marshallExceptions) {

                // delegate exception
                throw $e;
            }
        }

        if (!is_string($result)) {
            $result = null;
        }

        return $result;

    }

    /**
     * @param mixed $value
     * @return bool
     */
    public static function isAssocArray($value)
    {
        $result = false;

        if (!is_array($value)) {

            return $result;
        }

        $isAssocArray = (array_keys($value)
            !== range(0, count($value) - 1)
        );

        return $isAssocArray;
    }


    /**
     * @param object|string $instance
     * @return string
     */
    public static function getClassnameNice($instance)
    {
        $result = 'null';

        $className = null;
        if (is_string($instance)) {
            $className = $instance;
        }

        if (is_object($instance)) {

            try {
                $className = get_class($instance);
            } catch (\Exception $e) {
                //NOP
            }
        }

        if (!is_string($className)) {

            return $result;
        }

        if (empty($className)) {

            return $result;
        }

        $classNameNice = str_replace(
            array('_', '\\'),
            '.',
            $className
        );

        return $classNameNice;
    }


    /**
     * @param object|string $instance
     * @param bool $reflectionEnabled
     * @return string
     */
    public static function getNamespaceName($instance, $reflectionEnabled)
    {
        $result = '';

        $reflectionEnabled = ($reflectionEnabled === true);

        $className = null;
        if (is_string($instance)) {
            $className = $instance;
        }

        if (is_object($instance)) {
            try {
                $className = get_class($instance);
            } catch (\Exception $e) {
                //NOP
            }
        }

        if (!is_string($className)) {

            return $result;
        }

        if (empty($className)) {

            return $result;
        }

        $namespaceName = null;

        if (array_key_exists($className, self::$_namespaceNameCache)) {
            $namespaceName = self::$_namespaceNameCache[$className];
        }
        if ((is_string($namespaceName)) && (!empty($namespaceName))) {

            return $namespaceName;
        } else {
            $namespaceName = null;
        }

        if ($reflectionEnabled) {
            try {
                $reflectionClass = new \ReflectionClass($className);
                $namespaceName   = $reflectionClass->getNamespaceName();

                if (
                    (is_string($namespaceName))
                    && (!empty($namespaceName))
                ) {

                    return $namespaceName;
                }

            } catch (\Exception $e) {
                //NOP
            }
        }

        $parts = (array)explode('\\', $className);
        array_pop($parts);
        $namespaceName = implode('\\', (array)$parts);

        if (!is_string($namespaceName)) {

            return $result;
        }

        if (empty($namespaceName)) {

            return $result;
        }

        self::$_namespaceNameCache[$className] = $namespaceName;

        return $namespaceName;
    }


    /**
     * @param $class
     * @return null|\ReflectionClass
     */
    public static function getReflectionClass($class)
    {
        $result = null;

        $className = null;
        if (is_object($class)) {
            try {
                $className = get_class($class);
            } catch (\Exception $e) {
                // NOP
            }
        }

        if (!is_string($className)) {

            return $result;
        }

        if (empty($className)) {

            return $result;
        }

        if (array_key_exists($className, self::$_reflectionClassCache)) {
            $reflectionClass = self::$_reflectionClassCache[$className];
            if ($reflectionClass instanceof \ReflectionClass) {

                return $reflectionClass;
            }
        }

        $reflectionClass                         = new \ReflectionClass($className);
        self::$_reflectionClassCache[$className] = $reflectionClass;

        return $reflectionClass;

    }

    /**
     * @param string $rpcMethod
     * @return array
     */
    public static function parseRpcMethod($rpcMethod)
    {
        $rpcMethod = '' . strtolower(trim('' . $rpcMethod));

        if (array_key_exists($rpcMethod, self::$_rpcMethodParsedCache)) {
            $rpcMethodParsed = self::$_rpcMethodParsedCache[$rpcMethod];

            if (is_array($rpcMethodParsed)) {

                return $rpcMethodParsed;
            }
        }

        $parts  = (array)explode('.', $rpcMethod);
        $_parts = array();
        foreach ($parts as $part) {
            $part     = '' . ucfirst(trim('' . $part));
            $_parts[] = $part;
        }
        $parts = $_parts;

        $rpcMethodName         = '' . strtolower('' . array_pop($parts));
        $rpcClassName          = '' . array_pop($parts);
        $rpcPackageName        = '' . implode('.', $parts);
        $rpcQualifiedClassName = '' . implode(
            '.',
            array(
                $rpcPackageName,
                $rpcClassName,
            )
        );

        $result = array(
            'rpcMethod'             => $rpcMethod,
            'rpcPackageName'        => $rpcPackageName,
            'rpcClassName'          => $rpcClassName,
            'rpcMethodName'         => $rpcMethodName,
            'rpcQualifiedClassName' => $rpcQualifiedClassName,
        );

        self::$_rpcMethodParsedCache[$rpcMethod] = $result;

        return $result;

    }

    /**
     * @param string|object $class
     * @param bool $marshallExceptions
     * @return \ReflectionClass
     * @throws \Exception
     */
    public static function newReflectionClass($class, $marshallExceptions)
    {
        $result = null;

        $marshallExceptions = ($marshallExceptions === true);

        try {

            if (
                ((is_string($class)) && (!empty($class)))
                || (is_object($class))
            ) {
                $reflectionClass = new \ReflectionClass($class);

                return $reflectionClass;
            }

            if ($marshallExceptions) {

                throw new \Exception(
                    'Invalid parameter class at ' . __METHOD__
                );
            }

            return $result;

        } catch (\Exception $e) {

            if ($marshallExceptions) {

                throw $e;
            }
        }

        return $result;
    }


    /**
     * @param array $data
     * @param array $signKeys
     * @param string $appSecret
     * @param string $signedRequestAlgorithm
     * @param int|string $issuedAt
     * @return string
     */
    public static function createRequestSignature(

        $data = array(),
        $signKeys = array(),
        $appSecret = '',
        $signedRequestAlgorithm = 'HMAC-SHA256',
        $issuedAt = 0
    ) {

        if (!is_int($issuedAt)) {
            $issuedAt = 0;
        }

        if (!is_array($data)) {
            $data = array();
        }
        if (!is_array($signKeys)) {
            $signKeys = array();
        }

        $signedRequestAlgorithm = strtoupper($signedRequestAlgorithm);

        $_data = array();
        foreach ($signKeys as $key) {
            $value = null;
            if (array_key_exists($key, $data)) {
                $value = $data[$key];
            }
            $_data[$key] = $value;
        }
        $data = $_data;

        // sort keys
        uksort($data, 'strcmp');

        $json = (string)self::jsonEncode($data, false);

        $signatureParts =
            array(
                (string)strtoupper($signedRequestAlgorithm),
                (string)$issuedAt,
                (string)self::base64UrlEncodeUrlSafe($json),
            );

        $b64Data = self::base64UrlEncodeUrlSafe(
            implode(
                '.',
                $signatureParts
            )
        );

        $rawSig = hash_hmac(
            $signedRequestAlgorithm,
            $b64Data,
            $appSecret,
            $raw = true
        );

        $sig = (string)implode(
            '.',
            array(
                $signatureParts[0],
                $signatureParts[1],
                $rawSig,
            )
        );

        $sig = (string)self::base64UrlEncodeUrlSafe($sig);

        return $sig;

    }

    /**
     * @param string $signature
     * @param array $data
     * @param array $signKeys
     * @param string $appSecret
     * @param string $signedRequestAlgorithm
     * @return bool
     */
    public static function validateSignedRequest(
        $signature = '',
        $data = array(),
        $signKeys = array(),
        $appSecret = '',
        $signedRequestAlgorithm = 'HMAC-SHA256'
    ) {

        $result = false;

        if (!is_string($signature)) {

            return $result;
        }

        $sigGiven   = $signature;
        $sigDecoded = self::base64UrlDecodeUrlSafe($sigGiven);

        list(
            $algorithmGiven,
            $issuedAtGiven,
            $rawSigGiven
            ) = explode('.', $sigDecoded, 3);

        if (!is_string($algorithmGiven)) {

            return $result;
        }

        if (!is_string($issuedAtGiven)) {

            return $result;
        }

        if (!is_string($rawSigGiven)) {

            return $result;
        }

        $issuedAtGiven          = (int)$issuedAtGiven;
        $signedRequestAlgorithm = strtoupper($signedRequestAlgorithm);
        $algorithmGiven         = strtoupper($signedRequestAlgorithm);

        if ($algorithmGiven !== $signedRequestAlgorithm) {

            return $result;
        }

        if (!is_array($data)) {
            $data = array();
        }
        if (!is_array($signKeys)) {
            $signKeys = array();
        }

        $signatureExpected = self::createRequestSignature(
            $data,
            $signKeys,
            $appSecret,
            $signedRequestAlgorithm,
            $issuedAtGiven
        );

        $result = ($signatureExpected === $sigGiven);

        return $result;
    }


    /**
     * @see: facebook-php-sdk
     * Base64 encoding that doesn't need to be urlencode()ed.
     * Exactly the same as base64_encode except it uses
     *   - instead of +
     *   _ instead of /
     *   No padded =
     *
     * @param string $input base64UrlEncoded string
     * @return string
     */
    public static function base64UrlDecodeUrlSafe($input)
    {

        return base64_decode(strtr($input, '-_', '+/'));
    }

    /**
     * @see: facebook-php-sdk
     * Base64 encoding that doesn't need to be urlencode()ed.
     * Exactly the same as base64_encode except it uses
     *   - instead of +
     *   _ instead of /
     *
     * @param string $input string
     * @return string base64Url encoded string
     */
    public static function base64UrlEncodeUrlSafe($input)
    {
        $str = strtr(base64_encode($input), '+/', '-_');
        $str = str_replace('=', '', $str);

        return $str;
    }


    /**
     * @param array $array
     * @param array $defaultKeyValueMap
     * @return array
     */
    public static function arrayEnsure($array, $defaultKeyValueMap)
    {
        if (!is_array($array)) {
            $array = array();
        }

        if (!is_array($defaultKeyValueMap)) {
            $defaultKeyValueMap = array();
        }

        foreach ($defaultKeyValueMap as $key => $value) {
            if (!array_key_exists($key, $array)) {
                $array[$key] = $value;
            }
        }

        return $array;
    }

    /**
     * @param array|null $sourceArray
     * @param array|null $mixinArray
     * @return array
     */
    public static function arrayMixinOverride($sourceArray, $mixinArray)
    {
        if (!is_array($sourceArray)) {
            $sourceArray = array();
        }

        if (!is_array($mixinArray)) {
            $mixinArray = array();
        }

        $resultArray = (array)$sourceArray;
        foreach ($mixinArray as $key => $value) {
            $resultArray[$key] = $value;
        }

        return $resultArray;
    }

    /**
     * @param array|null $sourceArray
     * @param array|null $keysList
     * @param bool $ensureKeysEnabled
     * @return array
     */
    public static function arrayKeepKeys(
        $sourceArray,
        $keysList,
        $ensureKeysEnabled
    ) {
        $ensureKeysEnabled = ($ensureKeysEnabled === true);
        if (!is_array($sourceArray)) {
            $sourceArray = array();
        }

        if (!is_array($keysList)) {
            $keysList = array();
        }

        $resultArray = array();
        foreach ($sourceArray as $key => $value) {
            if (in_array($key, $keysList, true)) {
                $resultArray[$key] = $value;

                continue;
            }
            if ($ensureKeysEnabled) {
                $resultArray[$key] = null;
            }
        }

        return $resultArray;
    }


    /**
     * @param \Exception $exception
     * @param bool $isDebugEnabled
     * @return array
     */
    public static function exceptionAsArray(
        \Exception $exception,
        $isDebugEnabled
    ) {
        $recursionLevel    = 0;
        $recursionLevelMax = 5;

        $isDebugEnabled = ($isDebugEnabled === true);

        $error = self::_exceptionAsArrayRecursive(
            $exception,
            $isDebugEnabled,
            $recursionLevel,
            $recursionLevelMax
        );
        if (!is_array($error)) {
            $error = array(
                'message' => '' . __METHOD__ . 'failed!',
            );
        }

        return $error;
    }

    /**
     * @param \Exception $exception
     * @param bool $isDebugEnabled
     * @param int $recursionLevel
     * @param int $recursionLevelMax
     * @return array|null
     */
    private static function _exceptionAsArrayRecursive(
        \Exception $exception,
        $isDebugEnabled,
        $recursionLevel,
        $recursionLevelMax
    ) {
        $result = null;
        if (!is_int($recursionLevel)) {

            return $result;
        }
        if (!is_int($recursionLevelMax)) {

            return $result;
        }

        if (($recursionLevel < 0) || ($recursionLevelMax < 0)) {

            return $result;
        }

        if ($recursionLevel > $recursionLevelMax) {

            return $result;
        }

        $isDebugEnabled = ($isDebugEnabled === true);
        $recursionLevel++;

        $error = array(
            'class'   => self::getClassnameNice($exception),
            'message' => $exception->getMessage(),
            'data'    => null,
            'debug'   => array(
                'code'       => $exception->getCode(),
                'file'       => $exception->getFile(),
                'line'       => $exception->getLine(),
                'stackTrace' => $exception->getTraceAsString(),
            ),
        );
        if (!$isDebugEnabled) {
            $error['debug'] = null;
        }
        if ($exception instanceof JsonRpcException) {
            /**
             * @var JsonRpcException $rpcException
             */
            $rpcException = $exception;
            $errorMixin   = array(
                'data' => $rpcException->getData(),
            );
            $error        = self::arrayMixinOverride($error, $errorMixin);
            if ($isDebugEnabled) {
                $errorDebugMixin = array(
                    'methodInfo' => array(
                        'class'  => self::getClassnameNice(
                            $rpcException->getMethodClass()
                        ),
                        'method' => $rpcException->getMethod(),
                        'line'   => $rpcException->getMethodLine(),
                    ),
                );
                $error['debug']  = self::arrayMixinOverride(
                    $error['debug'],
                    $errorDebugMixin
                );

                $fault = $rpcException->getFault();
                if ($fault instanceof \Exception) {
                    $faultAsArray = self::_exceptionAsArrayRecursive(
                        $fault,
                        $isDebugEnabled,
                        $recursionLevel,
                        $recursionLevelMax
                    );
                    if (
                        (is_array($faultAsArray))
                        && (count(array_keys($faultAsArray)) > 0)
                    ) {
                        $error['debug']['fault'] = $faultAsArray;
                    }
                }

            }

        }

        $_error = array();
        foreach ($error as $key => $value) {
            if (is_string(self::jsonEncode($value, false))) {
                $_error[$key] = $value;
            } else {
                // could not serialize as json
                $_error[$key] = null;
            }
        }
        $error = $_error;

        return $error;
    }


    /**
     * @param $class
     * @param bool $autoLoad
     * @param bool $marshallExceptions
     * @return bool|string
     */
    public static function classExists($class, $autoLoad, $marshallExceptions)
    {
        $result             = false;
        $marshallExceptions = ($marshallExceptions === true);
        $autoLoad           = ($autoLoad === true);
        if ($marshallExceptions) {

            return (class_exists($class, $autoLoad) === true);
        }

        try {

            return (class_exists($class, $autoLoad) === true);
        } catch (\Exception $e) {

        }

        return $result;
    }

}
