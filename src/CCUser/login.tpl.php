<h1>Login</h1>
<p>Login using your acronym or email.</p>
<?=$login_form?>
<?php if($allow_create_user) : ?>
<p class='form-action-link'><a href='<?=$create_user_url?>' title='Create a new user account'>Create user</a></p>
<?php endif; ?>