<?php

/** @var \App\Model\Gun $gun */
/** @var \App\Service\Router $router */

$title = "Edit Gun {$gun->getName()} ({$gun->getId()})";
$bodyClass = "edit";

ob_start(); ?>
    <h1><?= $title ?></h1>
    <form action="<?= $router->generatePath('gun-edit') ?>" method="post" class="edit-form">
        <?php require __DIR__ . DIRECTORY_SEPARATOR . '_form.html.php'; ?>
        <input type="hidden" name="action" value="gun-edit">
        <input type="hidden" name="id" value="<?= $gun->getId() ?>">
    </form>

    <ul class="action-list">
        <li>
            <a href="<?= $router->generatePath('gun-57744') ?>">Back to list</a></li>
        <li>
            <form action="<?= $router->generatePath('gun-delete') ?>" method="post">
                <input type="submit" value="Delete" onclick="return confirm('Are you sure?')">
                <input type="hidden" name="action" value="gun-delete">
                <input type="hidden" name="id" value="<?= $gun->getId() ?>">
            </form>
        </li>
    </ul>

<?php $main = ob_get_clean();

include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'base.html.php';