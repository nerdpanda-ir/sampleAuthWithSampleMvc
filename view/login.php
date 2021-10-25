<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
</head>
<body>
    <?php if (isset($_SESSION['errors'])) : ?>
        <ul class="errorBox">
            <?php foreach ($_SESSION['errors'] as $error) : ?>
                <li>
                    <?php echo $error?>
                </li>
            <?php endforeach;?>
        </ul>
    <?php endif;?>
    <h1>welcome !!! </h1>
    <form method="post" action="doLogin.php">
        <section class="fieldItem">
            <label for="userId"> user name :  </label>
            <input type="text" name="userId" id="userId" value="<?php echo $_SESSION['values']['userId']??''?>">
        </section>
        <section class="fieldItem">
            <label for="password"> password :  </label>
            <input type="text" name="password" id="password" value="<?php echo $_SESSION['values']['password']??''?>">
        </section>
        <section class="fieldItem">
            <label for="remember"> remember me </label>
            <input type="checkbox" name="remember" id="remember">
        </section>
        <section class="fieldItem">
            <input type="submit">
        </section>
    </form>
    <?php
    if (!empty($_SESSION))
        session_unset();
    ?>
</body>
</html>