    <h1>Content Controller Index</h1>
    <p>One controller to manage the actions for content, mainly list, create, edit, delete, view.</p>

    <h2>All content</h2>
    <?php if($contents != null):?>
      <ul>
      <?php foreach($contents as $val):?>
        <li><?=$val["id"]?>, <a href="<?=create_uri("page/view/{$val["id"]}")?>"><?=$val["title"]?></a> by <?=$val["owner"]?>. <a href='<?=create_uri("content/edit/{$val["id"]}")?>'>Edit</a>
      <?php endforeach; ?>
      </ul>
    <?php else:?>
      <p>No content exists.</p>
    <?php endif;?>

    <h2>Actions</h2>
    <ul>
      <li><a href='<?=create_uri('content/create')?>'>Create new content</a>
    </ul>
