<?php

use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriFactoryInterface;
use QuillStack\DI\Container;
use QuillStack\Http\Request\Factory\ServerRequest\RequestFromGlobalsFactory;
use QuillStack\Http\Stream\InputStream;
use QuillStack\Http\Uri\Factory\UriFactory;

require __DIR__ . '/../vendor/autoload.php';


$container = new Container([
    UriFactoryInterface::class => UriFactory::class,
    StreamInterface::class => InputStream::class,
]);

$factory = $container->get(RequestFromGlobalsFactory::class);
$request = $factory->createServerRequest();
$requestWithHeader = $request->withHeader('Test', 'test');
$requestWithoutHeader = $request->withoutHeader('aCCept');
$requestMethod = $request->withMethod('post');

$header = 'Host';
dump($request);
dump($request->getHeader($header));
dump($request->getHeaderLine($header));
dump($requestWithHeader);
dump($requestWithoutHeader);
dump((string) $requestMethod->getUri());
