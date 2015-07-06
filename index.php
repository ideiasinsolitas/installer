<?php

use Deck\Http\Enviroment;
use Deck\Http\Request;
use Deck\Http\Response;

$env = new Enviroment();

$req = new Request($env);

$body = "Your ip is: " . $req->getIp() . "\n";

$res = new Response($body);

$res->send();