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
      "page_limit" => 5,
      "order_by" => '',
    ];

    // Get Posts with Pagination settings
    $response = $postObj->findAll([], [], $paginate);

    // Sending Data to views
    $data = [
      'posts' => $response['data'],
      'pagination' => $response['pagination_info'],
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


      // $data = array(
      //   'title' => 'Title updated', 
      //   'category' => "Category updated",
      //   'body' => "Body updated",
      //   'id' => '3',
      // );


      $result = $post->save($req->body());
      // $result = $post->update($data, ['id']);

      // echo "<pre>";
      // var_dump($result);
      // echo "</pre>";

      if (!empty($post->getErrors())) {
        return $res->render("create-product", $result);
      }

      // Redirect to another page
      echo 'Create successfully';
      // return $res->render("create-product", $result);
    }
    $res->render("create-product");
    // echo "Single Posts";
  }
}
