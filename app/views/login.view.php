<h1>Login page view</h1>
<form method="post">

    <input value="<?= old_value('userid') ?>" name="userid" placeHolder="userid"><br>
    <div><?= $user->getError('userid') ?></div><br>
    <input value="<?= old_value('password') ?>" name="password" placeHolder="Password"><br>
    <div><?= $user->getError('password') ?></div><br>
    <button>Login</button>
</form>