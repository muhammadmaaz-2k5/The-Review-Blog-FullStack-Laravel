<?php
$request = Illuminate\Http\Request::create("/api/profile/drtool/load-more?page=2", "GET");
$request->headers->set("X-Requested-With", "XMLHttpRequest");
$request->headers->set("Accept", "application/json");
$response = app()->handle($request);
echo $response->getStatusCode() . " - " . substr($response->getContent(), 0, 100);

