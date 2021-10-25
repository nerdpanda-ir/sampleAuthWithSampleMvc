<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<?php if (isset($_SESSION['user'])) : ?>
    <nav>
        <img src="<?php echo $_SESSION['user']['thumbnail'] ?? $route::getFullUri(false,true).'media'.DIRECTORY_SEPARATOR.'default.png' ?>" alt="">
        <h3> welcome <?php echo $_SESSION['user']['name']?? '' ;?></h3>
        <a href="<?php echo $route::getFullUri(false,true).'logout.php'?>">log Out</a>
    </nav>
<?php endif ;?>
<h1>user panel</h1>
</body>
</html>