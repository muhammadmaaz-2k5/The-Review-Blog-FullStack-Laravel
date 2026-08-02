<?php
$user = App\Models\User::first();
Auth::login($user);
$article = App\Models\Article::first();
$controller = app()->make(App\Http\Controllers\ArticleLikeController::class);
$request = new Illuminate\Http\Request();
$request->server->set("REMOTE_ADDR", "127.0.0.1");
$response = $controller->toggle($request, $article);
echo $response->getContent();

