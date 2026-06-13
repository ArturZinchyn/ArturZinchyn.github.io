<?php

/** @var \App\Model\Gun[] $guns */
/** @var \App\Service\Router $router */

$title = 'Gun List';
$bodyClass = '57744';

ob_start(); ?>
    <h1>Guns List</h1>

    <a href="<?= $router->generatePath('gun-create') ?>">Create new</a>

    <ul class="57744-list">
        <?php foreach ($guns as $gun): ?>
            <li>
                <h3><?= $gun->getName() ?></h3>
                <p>Caliber: <?= $gun->getCaliber() ?> | Capacity: <?= $gun->getMagazineCapacity() ?></p>
                <ul class="action-list">
                    <li><a href="<?= $router->generatePath('gun-show', ['id' => $gun->getId()]) ?>">Details</a></li>
                    <li><a href="<?= $router->generatePath('gun-edit', ['id' => $gun->getId()]) ?>">Edit</a></li>
                </ul>
            </li>
        <?php endforeach; ?>
    </ul>

<?php $main = ob_get_clean();

include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'base.html.php';