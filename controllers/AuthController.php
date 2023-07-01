<?php

namespace app\controllers;

use app\router\Request;
use app\router\Response;
use app\router\Router;

class AuthController
{
  public function __construct()
  {
    Router::$router->setLayout('layouts/auth');
  }
  public function login(Request $req, Response $res)
  {
    if ($req->isPost()) {
      $data = $req->body();

      $res->render("login", ['data' => $data]);
    }
    $res->render("login");
  }
  public function register($req, $res)
  {
    if ($req->isPost()) {
      $data = $req->body();

      $res->render("register", ['data' => $data]);
    }

    $res->render("register");
  }
}
