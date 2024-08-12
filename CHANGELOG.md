# Change Log

Please refer to [CHANGELOG](CHANGELOG.md) guide for upgrading to a major version.

## [v1.2.0-beta](../../compare/v1.1.0-alpha...v1.2.0-beta) - 2024-08-12

version 1.2.0 beta
* [feat:URI Template](https://github.com/nacosvel/open-http/commit/2fa24c73fd247dd5fbd51effb4b2fa331e1035f0)
* [chore:guzzlehttp/uri-template](https://github.com/nacosvel/open-http/commit/9020b0aa0e128a4fc4b7114196cf96c15520966c)
* [fix:bug](https://github.com/nacosvel/open-http/commit/22f34d382dd9ccb9381a9d8c360631e0f9e6611c)

## [v1.1.0-alpha](../../compare/v1.0.0-SNAPSHOT...v1.1.0-alpha) - 2024-08-12

### Changed

* \Nacosvel\OpenHttp\Builder
    * public static function factory(array $options = [], array $config = []): ChainableInterface

### Added

* \Nacosvel\OpenHttp\Contracts\ChainableInterface
    * public function getConfig(string $key = null): mixed;
    * public function setConfig(array $config): ChainableInterface;

## [v1.0.0-SNAPSHOT](../../releases/tag/v1.0.0-SNAPSHOT) - 2024-08-12

### Build

* Requires PHP >= 8.0
* Requires guzzlehttp/guzzle^7.9
* \Nacosvel\OpenHttp\Builder
    * public static function factory(array $options = []): ChainableInterface
* \Nacosvel\OpenHttp\Contracts\ChainableInterface
    * public function getClient(): ClientDecoratorInterface;
    * public function setClient(ClientDecoratorInterface $instance): ChainableInterface;
    * public function chain(string $segments, string $separator = '/'): ChainableInterface;
    * public function get(array $options = []): ResponseInterface;
    * public function head(array $options = []): ResponseInterface;
    * public function put(array $options = []): ResponseInterface;
    * public function post(array $options = []): ResponseInterface;
    * public function patch(array $options = []): ResponseInterface;
    * public function delete(array $options = []): ResponseInterface;
    * public function getAsync(array $options = []): PromiseInterface;
    * public function headAsync(array $options = []): PromiseInterface;
    * public function putAsync(array $options = []): PromiseInterface;
    * public function postAsync(array $options = []): PromiseInterface;
    * public function patchAsync(array $options = []): PromiseInterface;
    * public function deleteAsync(array $options = []): PromiseInterface;
* \Nacosvel\OpenHttp\Contracts\ClientDecoratorInterface
    * public function request(string $method, string $uri, array $options = []): ResponseInterface;
    * public function requestAsync(string $method, string $uri, array $options = []): PromiseInterface;
