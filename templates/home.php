<h1>My Blog</h1>

<br>
<h4>Recent posts</h4>
<style>
  .well {
    border: 2px solid #ccc;
    padding: 2rem;
    display: block;
  }
</style>
<div class="row">
  <?php foreach ($posts as $post) { ?>
    <div class="col-sm-3" style="margin: 8px">
      <a href="_/blog/efasdf56561" class="well">
        <h4><?= $post['title'] ?></h4>
        <p><?= $post['category'] ?></p>
        <small><?= $post['created_on'] ?></small>
      </a>
    </div>
  <?php } ?>
</div>


<!-- 
<div class="row" style="gap: 10px">
  <div class="col-sm-3">
    <a href="_/blog/efasdf56561" class="well">
      <h4>Users</h4>
      <p>1 Million</p>
    </a>
  </div>
  <div class="col-sm-3">
    <a href="_/blog/efasdf56561" class="well">
      <h4>Pages</h4>
      <p>100 Million</p>
    </a>
  </div>
  <div class="col-sm-3">
    <a href="_/blog/efasdf56561" class="well">
      <h4>Sessions</h4>
      <p>10 Million</p>
    </a>
  </div>
  <div class="col-sm-3">
    <a href="_/blog/efasdf56561" class="well">
      <h4>Bounce</h4>
      <p>30%</p>
    </a>
  </div>
</div> -->