<?php

namespace Nacosvel\OpenHttp;

use ArrayIterator;
use Nacosvel\OpenHttp\Concerns\ChainableTrait;
use Nacosvel\OpenHttp\Contracts\ChainableInterface;
use Nacosvel\OpenHttp\Contracts\ClientDecoratorInterface;

class Chainable extends ArrayIterator implements ChainableInterface
{
    use ChainableTrait;

    /**
     * Compose the chainable `ClientDecorator` instance, most starter with the tree root point
     *
     * @param array                    $array
     * @param ClientDecoratorInterface $client The `ClientDecorator` instance
     * @param array                    $config
     */
    public function __construct(array $array, protected ClientDecoratorInterface $client, protected array $config = [])
    {
        parent::__construct($array, self::STD_PROP_LIST | self::ARRAY_AS_PROPS);
    }

    /**
     * @return ClientDecoratorInterface The `ClientDecorator` instance
     */
    public function getClient(): ClientDecoratorInterface
    {
        return $this->client;
    }

    /**
     * @param ClientDecoratorInterface $client The `ClientDecorator` instance
     *
     * @return ChainableInterface
     */
    public function setClient(ClientDecoratorInterface $client): ChainableInterface
    {
        $this->client = $client;
        return $this;
    }

    /**
     * Get ths `Chainable` config
     *
     * @param string|null $key
     *
     * @return array|mixed|null
     */
    public function getConfig(string $key = null): mixed
    {
        if (is_null($key)) {
            return $this->config;
        }
        if (array_key_exists($key, $this->config)) {
            return $this->config[$key];
        }
        return null;
    }

    /**
     * Set ths `Chainable` config
     *
     * @param array $config The `Chainable` config
     *
     * @return ChainableInterface
     */
    public function setConfig(array $config): ChainableInterface
    {
        $this->config = $config;
        return $this;
    }

    /**
     * Only retrieve a copy array of the URI segments
     *
     * @return array The URI segments array
     */
    protected function allChains(): array
    {
        return array_filter($this->getArrayCopy(), static fn($value) => !($value instanceof ChainableInterface || $value instanceof ClientDecoratorInterface));
    }

    /**
     * Normalize the $subject by the rules:
     *```
     * PascalCase -> camelCase & camelCase -> kebab-case & _placeholder_ -> {placeholder}
     *```
     *
     * @param string $subject The string waiting for normalization
     *
     * @return string
     */
    protected function normalize(string $subject = ''): string
    {
        return preg_replace_callback_array([
            '#^[A-Z]#'   => static fn(array $match) => strtolower($match[0]),
            '#[A-Z]#'    => static fn(array $match) => strtolower("-{$match[0]}"),
            '#^_(.*)_$#' => static fn(array $match) => "{{$match[1]}}",
        ], $subject) ?? $subject;
    }

    /**
     * Get value for an offset
     *
     * @param mixed $key The offset to get the value from.
     *
     * @return ChainableInterface|ClientDecoratorInterface
     */
    public function offsetGet(mixed $key): ChainableInterface|ClientDecoratorInterface
    {
        if (!$this->offsetExists($key)) {
            $this->offsetSet($key, new self([...$this->allChains(), $this->normalize($key)], $this->getClient(), $this->getConfig()));
        }

        return parent::offsetGet($key);
    }

    /**
     * Chainable the given $segments with the ChainableInterface instance
     *
     * @param string $segments  The segments or `URI`
     * @param string $separator The URI separator, default is slash(`/`) character
     *
     * @return ChainableInterface
     */
    public function chain(string $segments, string $separator = '/'): ChainableInterface
    {
        return array_reduce(explode($separator, $segments), static fn($carry, $item) => $carry->offsetGet($item), $this);
    }

    /**
     * URI pathname
     *
     * @param string $separator The URI separator, default is slash(`/`) character
     *
     * @return string The URI string
     */
    public function pathname(string $separator = '/'): string
    {
        return implode($separator, $this->allChains());
    }

}
