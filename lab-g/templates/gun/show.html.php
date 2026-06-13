<?php

/** @var \App\Model\Gun $gun */
/** @var \App\Service\Router $router */

$title = "{$gun->getName()} ({$gun->getId()})";
$bodyClass = 'show';

ob_start(); ?>
    <h1><?= $gun->getName() ?></h1>
    <article>
        <p><strong>Caliber:</strong> <?= $gun->getCaliber() ?></p>
        <p><strong>Magazine Capacity:</strong> <?= $gun->getMagazineCapacity() ?></p>
    </article>

    <ul class="action-list">
        <li> <a href="<?= $router->generatePath('gun-57744') ?>">Back to list</a></li>
        <li><a href="<?= $router->generatePath('gun-edit', ['id'=> $gun->getId()]) ?>">Edit</a></li>
    </ul>
<?php $main = ob_get_clean();

include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'base.html.php';