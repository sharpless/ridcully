    <?php if($content['id']):?>
      <h1><?=htmlent($content['title'])?></h1>
      <p><?=$content->GetFilteredData()?></p>
      <p class='smaller-text silent'><a href='<?=create_uri("content/edit/{$content['id']}")?>'>edit</a> <a href='<?=create_uri("content")?>'>view all</a></p>
    <?php else:?>
      <p>404: No such page exists.</p>
    <?php endif;?>