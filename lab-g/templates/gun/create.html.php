<?php

/** @var \App\Model\Gun $gun */
/** @var \App\Service\Router $router */

$title = 'Create Gun';
$bodyClass = "edit";

ob_start(); ?>
    <h1>Create Gun</h1>
    <form action="<?= $router->generatePath('gun-create') ?>" method="post" class="edit-form">
        <?php require __DIR__ . DIRECTORY_SEPARATOR . '_form.html.php'; ?>
        <input type="hidden" name="action" value="gun-create">
    </form>

    <a href="<?= $router->generatePath('gun-57744') ?>">Back to list</a>
<?php $main = ob_get_clean();

include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'base.html.php';