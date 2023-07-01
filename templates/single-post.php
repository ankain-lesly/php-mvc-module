<h2>Reading Article</h2>

<?php

$post_id = $post_id ?? false;
if ($post_id) : ?>
  <p>Getting Article with ID: <?= $post_id  ?></p>
<?php endif; ?>