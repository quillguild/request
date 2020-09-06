<?php

use QuillStack\DI\Container;
use QuillStack\Http\Request\Factory\ServerRequest\RequestFromGlobalsFactory;

require __DIR__ . '/../vendor/autoload.php';

$container = new Container();
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
