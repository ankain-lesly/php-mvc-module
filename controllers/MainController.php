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
    $postObj = new Post();

    // Pagination settings
    $paginate = [
      "current_page" => 1,
      "page_limit" => 6,
      "order_by" => '',
    ];

    // Get Posts with Pagination settings
    $response = $postObj->findAll('', '', $paginate);

    // Sending Data to views
    $data = [
      'posts' => $response['data'] ?? [],
      'pagination' => $response['pagination_info'] ?? [],
    ];

    // Rendering home view
    $res->render("home", $data);
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

      $post->loadData($req->body());

      if ($post->validate() && $post->save()) {
        echo 'Create successfully';
        $res->render("home");
        exit;
      }
      $errors =  $post->getErrors();
      echo '<pre>';
      print_r($errors);
      echo '<br />';
      echo '</pre>';
      exit;
      $res->render("create-product", $errors);
    }

    $res->render("create-product");
  }
}
