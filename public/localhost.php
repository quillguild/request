<?php

use QuillStack\DI\Container;
use QuillStack\Http\Request\Factory\ServerRequest\GivenRequestFromGlobalsFactory;
use QuillStack\Http\Request\TestRequest;

require __DIR__ . '/../vendor/autoload.php';

$container = new Container();
$factory = $container->get(GivenRequestFromGlobalsFactory::class);
$request = $factory->createGivenServerRequest(TestRequest::class);
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
