<?php
/** @var $router \App\Service\Router */
?>
<ul>
    <li><a href="<?= $router->generatePath('') ?>">Home</a></li>
    <li><a href="<?= $router->generatePath('post-57744') ?>">Posts</a></li>
    <li><a href="<?= $router->generatePath('gun-57744') ?>">Guns</a></li>
</ul>