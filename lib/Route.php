<?php require_once dirname(__DIR__) . DIRECTORY_SEPARATOR.'interfaces'.DIRECTORY_SEPARATOR.'route'.DIRECTORY_SEPARATOR.'Route.php'?>
<?php require_once dirname(__DIR__).DIRECTORY_SEPARATOR.'traits'.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'route'.DIRECTORY_SEPARATOR.'Route.php' ?>
<?php
    use interfaces\route\Route as RouteInterface ;
    use traits\lib\route\Route as RouteTrait;
?>
<?php
class Route implements RouteInterface
{
    use RouteTrait;
}