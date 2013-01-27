    <div class='box'>
    <h4>All modules</h4>
    <p>All Ridcully modules.</p>
    <ul>
    <?php foreach($modules as $module): ?>
      <li><a href='<?=create_uri("modules/view/{$module['name']}")?>'><?=$module['name']?></a></li>
    <?php endforeach; ?>
    </ul>
    </div>


    <div class='box'>
    <h4>Ridcully core</h4>
    <p>Ridcully core modules.</p>
    <ul>
    <?php foreach($modules as $module): ?>
      <?php if($module['isRidcullyCore']): ?>
      <li><a href='<?=create_uri("modules/view/{$module['name']}")?>'><?=$module['name']?></a></li>
      <?php endif; ?>
    <?php endforeach; ?>
    </ul>
    </div>


    <div class='box'>
    <h4>Ridcully CMF</h4>
    <p>Ridcully Content Management Framework (CMF) modules.</p>
    <ul>
    <?php foreach($modules as $module): ?>
      <?php if($module['isRidcullyCMF']): ?>
      <li><a href='<?=create_uri("modules/view/{$module['name']}")?>'><?=$module['name']?></a></li>
      <?php endif; ?>
    <?php endforeach; ?>
    </ul>
    </div>


    <div class='box'>
    <h4>Models</h4>
    <p>A class is considered a model if its name starts with CM.</p>
    <ul>
    <?php foreach($modules as $module): ?>
      <?php if($module['isModel']): ?>
      <li><a href='<?=create_uri("modules/view/{$module['name']}")?>'><?=$module['name']?></a></li>
      <?php endif; ?>
    <?php endforeach; ?>
    </ul>
    </div>


    <div class='box'>
    <h4>Controllers</h4>
    <p>Implements interface <span class="code">IController</span>.</p>
    <ul>
    <?php foreach($modules as $module): ?>
      <?php if($module['isController']): ?>
      <li><a href='<?=create_uri("modules/view/{$module['name']}")?>'><?=$module['name']?></a></li>
      <?php endif; ?>
    <?php endforeach; ?>
    </ul>
    </div>


    <div class='box'>
    <h4>Contains SQL</h4>
    <p>Implements interface <span class="code">IHasSQL</span>.</p>
    <ul>
    <?php foreach($modules as $module): ?>
      <?php if($module['hasSQL']): ?>
      <li><a href='<?=create_uri("modules/view/{$module['name']}")?>'><?=$module['name']?></a></li>
      <?php endif; ?>
    <?php endforeach; ?>
    </ul>
    </div>


    <div class='box'>
    <h4>More modules</h4>
    <p>Modules that does not implement any specific Ridcully interface.</p>
    <ul>
    <?php foreach($modules as $module): ?>
      <?php if(!($module['isController'] || $module['isRidcullyCore'] || $module['isRidcullyCMF'])): ?>
      <li><a href='<?=create_uri("modules/view/{$module['name']}")?>'><?=$module['name']?></a></li>
      <?php endif; ?>
    <?php endforeach; ?>
    </ul>
    </div>