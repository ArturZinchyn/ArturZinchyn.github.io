<?php
namespace App\Controller;

use App\Exception\NotFoundException;
use App\Model\Gun;
use App\Service\Router;
use App\Service\Templating;

class GunController
{
    public function indexAction(Templating $templating, Router $router): ?string
    {
        $guns = Gun::findAll();

        $html = $templating->render('gun/index.html.php', [
            'guns' => $guns,
            'router' => $router,
        ]);

        return $html;
    }

    public function createAction(?array $requestPost, Templating $templating, Router $router): ?string
    {
        if ($requestPost) {
            $gun = Gun::fromArray($requestPost);
            // @todo missing validation
            $gun->save();

            $path = $router->generatePath('gun-57744');
            $router->redirect($path);

            return null;
        } else {
            $gun = new Gun();
        }

        $html = $templating->render('gun/create.html.php', [
            'gun' => $gun,
            'router' => $router,
        ]);

        return $html;
    }

    public function editAction(int $gunId, ?array $requestPost, Templating $templating, Router $router): ?string
    {
        $gun = Gun::find($gunId);
        if (! $gun) {
            throw new NotFoundException("Missing gun with id $gunId");
        }

        if ($requestPost) {
            $gun->fill($requestPost);
            // @todo missing validation
            $gun->save();

            $path = $router->generatePath('gun-57744');
            $router->redirect($path);

            return null;
        }

        $html = $templating->render('gun/edit.html.php', [
            'gun' => $gun,
            'router' => $router,
        ]);

        return $html;
    }

    public function showAction(int $gunId, Templating $templating, Router $router): ?string
    {
        $gun = Gun::find($gunId);
        if (! $gun) {
            throw new NotFoundException("Missing gun with id $gunId");
        }

        $html = $templating->render('gun/show.html.php', [
            'gun' => $gun,
            'router' => $router,
        ]);

        return $html;
    }

    public function deleteAction(int $gunId, Router $router): ?string
    {
        $gun = Gun::find($gunId);
        if (! $gun) {
            throw new NotFoundException("Missing gun with id $gunId");
        }

        $gun->delete();

        $path = $router->generatePath('gun-57744');
        $router->redirect($path);

        return null;
    }
}