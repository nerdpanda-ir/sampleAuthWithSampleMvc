<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> Register Form </title>
    <link rel="stylesheet" href="http://localhost/php/assets/css/library/core/core.css">
    <style>
        body>h1
        {
            font-size: 30px;
            margin: 15px 0 ;
            text-align: center;
        }
        .fieldItem
        {
            width: 60%;
            margin: 25px auto;
        }
    </style>
</head>
<body>
<?php $haveSuccessfullyRegister = isset($_SESSION['successfullyRegister']);?>
    <?php if ($haveSuccessfullyRegister and $_SESSION['successfullyRegister']['status']) : ?>
        <section class="successBox">
            <?php echo $_SESSION['successfullyRegister']['name']?>
            عزیز
            ثبت نام شما با موفقیت انجام شد
        </section>
    <?php elseif ($haveSuccessfullyRegister and !$_SESSION['successfullyRegister']['status']) : ?>
    <section class="errorBox">
        <?php echo $_SESSION['successfullyRegister']['name']?>
        عزیز
        ثبت نام شما با موفق امیز نبود !!!
    </section>
<?php endif;?>
<?php if (isset($_SESSION['errors']) and !empty($_SESSION['errors'])) : ?>
    <ul class="errorBox">
        <?php foreach ($_SESSION['errors'] as $error) : ?>
             <li>
                 <?php echo $error;?>
             </li>
        <?php endforeach;?>
    </ul>
<?php endif;?>
<h1>Register : </h1>
<form action="register.php" method="post" enctype="multipart/form-data" autocomplete="on">
    <section class="fieldItem">
        <label for="name"> name : </label>
        <input type="text" name="name" id="name" value="<?php echo $_SESSION['values']['name']??'';?>">
    </section>
    <section class="fieldItem">
        <label for="family"> family : </label>
        <input type="text" name="family" id="family" value="<?php echo $_SESSION['values']['family']??'';?>">
    </section>
    <section class="fieldItem">
        <label for="userId"> user name  : </label>
        <input type="text" name="userId" id="userId" value="<?php echo $_SESSION['values']['userId']??'';?>">
    </section>
    <section class="fieldItem">
        <label for="email"> email : </label>
        <input type="text" name="email" id="email" value="<?php echo $_SESSION['values']['email']??'';?>">
    </section>
    <section class="fieldItem">
        <label for="phone"> phone : </label>
        <input type="text" name="phone" id="phone" value="<?php echo $_SESSION['values']['phone']??'';?>">
    </section>
    <section class="fieldItem">
        <label for="password"> password : </label>
        <input type="text" name="password" id="password" value="<?php echo $_SESSION['values']['password']??'';?>">
    </section>
    <section class="fieldItem">
        <label for="thumbnail"> thumbnail : </label>
        <input type="file" name="thumbnail" id="thumbnail">
    </section>
    <section class="fieldItem">
        <input type="submit">
    </section>
</form>
<?php session_unset() ?>
</body>
</html>