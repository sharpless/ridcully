<h1>Module Manager</h1>


<h2>About</h2>
<p><strong>Module Manager</strong> displays information on modules and enable managing of all Lydia modules.
Lydia is made up of modules. Each module has its own subdirectory in the <span class="code">src</span>-directory.</p>

<h2>Manage modules</h2>
<p>A module can implement the interface <span class="code">IModule</span>, which makes it manageable. Ridcully can
administrate these modules through the interface. You can use the following actions:</p>

<ul>
  <li><a href="<?=create_uri('modules/install')?>">Install</a></li>
  <li>Upgrade</li>
  <li>Uninstall</li>
</ul>

<h2>Enabled controllers</h2>
<p>The controllers make up the public API of this website. Here is a list of the enabled
controllers and their methods. You enable and disable controllers in
<span class="code">site/config.php</span>.</p>

<ul>
<?php foreach($controllers as $key => $val): ?>
  <li><a href='<?=create_uri($key)?>'><?=$key?></a></li>

  <?php if(!empty($val)): ?>
  <ul>
  <?php foreach($val as $method): ?>
    <li><a href='<?=create_uri($key, $method)?>'><?=$method?></a></li>
  <?php endforeach; ?>   
  </ul>
  <?php endif; ?>
 
<?php endforeach; ?>   
</ul>