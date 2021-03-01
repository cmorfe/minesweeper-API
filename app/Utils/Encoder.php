<?php
/**
 * Opaque ID encoder.
 *
 * Translates between 32-bit integers (such as resource IDs) and obfuscated
 * scrambled values, as a one-to-one mapping. Supports hex and base64 url-safe
 * string representations. Expects a secret integer key in the constructor.
 *
 * (c) 2011 Marek Z. @marekweb
 */

namespace App\Utils;

class Encoder
{
    const ENCODING_INT = 0;
    const ENCODING_HEX = 1;
    const ENCODING_BASE64 = 2;
    const KEY = 0xae89f0b0;
    private $key;
    private $extraChars = '.-';
    private $encoding;


    // 1 = 98898af6

    /**
     * @param int $key Secret key used for lightweight encryption.
     * @param int $encoding
     */
    public function __construct($key = self::KEY, $encoding = self::ENCODING_HEX)
    {
        $this->key = $key;
        $this->encoding = $encoding;
    }

    /**
     * Encode a value according to the encoding mode selected upon instantiation.
     * @param int $i
     * @return int|string
     */
    public function encode(int $i)
    {
        return match ($this->encoding) {
            self::ENCODING_INT => $this->transcode($i),
            self::ENCODING_BASE64 => $this->encodeBase64($i),
            self::ENCODING_HEX => $this->encodeHex($i),
        };
    }

    /**
     * Reversibly transcode a 32-bit integer to a scrambled form, returning a new 32-bit integer.
     * @param int $i
     * @return int
     */
    public function transcode(int $i)
    {
        $r = $i & 0xffff;
        $l = $i >> 16 & 0xffff ^ $this->transform($r);
        return (($r ^ $this->transform($l)) << 16) + $l;
    }

    /**
     * Produce an integer hash of a 16-bit integer, returning a transformed 16-bit integer.
     * @param int $i
     * @return int
     */
    protected function transform(int $i)
    {
        $i = ($this->key ^ $i) * 0x9e3b;
        return $i >> ($i & 0xf) & 0xffff;
    }

    /**
     * Transcode an integer and return it as a 6-character base64 string.
     * @param int $i
     * @return string
     */
    public function encodeBase64(int $i)
    {
        return strtr(substr(base64_encode(pack('N', $this->transcode($i))), 0, 6), '+/', $this->extraChars);
    }

    /**
     * Transcode an integer and return it as an 8-character hex string.
     * @param int $i
     * @return string
     */
    public function encodeHex(int $i)
    {
        return dechex($this->transcode($i));
    }

    /**
     * Decode a value according to the encoding mode selected upon instantiation.
     * @param string $s
     * @return int
     */
    public function decode(string $s)
    {
        return match ($this->encoding) {
            self::ENCODING_INT => $this->transcode($s),
            self::ENCODING_BASE64 => $this->decodeBase64($s),
            self::ENCODING_HEX => $this->decodeHex($s),
        };
    }

    /**
     * Decode a 6-character base64 string, returning the original integer.
     * @param string $s
     * @return int
     */
    public function decodeBase64(string $s)
    {
        $unpacked = unpack('N', base64_decode(strtr($s, $this->extraChars, '+/')));
        return $this->transcode($unpacked[1]);
    }

    /**
     * Decode an 8-character hex string, returning the original integer.
     * @param string $s
     * @return int
     */
    public function decodeHex(string $s)
    {
        return $this->transcode(hexdec($s));
    }
}
