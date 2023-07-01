<?php

namespace app\controllers;

use app\models\Post;
use app\router\Request;
use app\router\Response;
use app\router\Router;

class MainController
{
  public function __construct()
  {
    Router::$router->setLayout('layouts/main');
  }
  public function index(Request $req, Response $res)
  {
    $res->render("home");
  }

  public function contact(Request $req, Response $res)
  {
    $res->render("contact");
  }

  public function SinglePost(Request $req, Response $res)
  {
    $param = $req->params('post_id');

    $res->render("single-post", ['post_id' => $param]);
  }
  public function createPost(Request $req, Response $res)
  {
    if ($req->isPost()) {
      $post = new Post();

      $result = $post->create($req->body());

      if (!empty($result['errors'])) {
        return $res->render("create-product", $result);
      }

      echo 'Create successfully';
    }
    $res->render("create-product");
    // echo "Single Posts";
  }
}
