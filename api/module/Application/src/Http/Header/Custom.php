<?php

namespace Application\Http\Header;

use Laminas\Http\Header\Exception\InvalidArgumentException;
use Laminas\Http\Header\GenericHeader;
use Laminas\Http\Header\HeaderInterface;
use Laminas\Http\Header\HeaderValue;

/**
 * @throws InvalidArgumentException
 * @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.40
 */
class Custom implements HeaderInterface
{
    /**
     * @var string
     */
    protected $value;

    /**
     * @var string
     */
    protected $name;

    public static function fromString($headerLine): Custom|static
    {
        list($name, $value) = GenericHeader::splitHeaderLine($headerLine);

        // check to ensure proper header type for this factory
        if (strtolower($name) !== 'trailer') {
            throw new InvalidArgumentException('Invalid header line for Trailer string: "' . $name . '"');
        }

        // @todo implementation details
        return new static($value);
    }

    public function __construct($name, $value = null)
    {
        $this->name = $name;

        if ($value !== null) {
            HeaderValue::assertValid($value);
            $this->value = $value;
        }
    }

    public function getFieldName(): string
    {
        return $this->name;
    }

    public function getFieldValue(): string
    {
        return (string) $this->value;
    }

    public function toString()
    {
        return $this->name . ': ' . $this->getFieldValue();
    }
}
