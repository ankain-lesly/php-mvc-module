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

    $where = ['id' => 2];
    $posts = $postObj->findAll();
    // $DataAccess = (new Post())->DataAccess;
    // $posts = $DataAccess->findAll("SELECT * FROM posts");

    // echo '<pre>';
    // var_dump($posts);
    // echo '<br />';
    // echo '</pre>';
    // exit;
    $res->render("home", ['posts' => $posts]);
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
