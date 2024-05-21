<?php

namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\HttpFoundation\RequestStack;

final readonly class Builder
{
    public function __construct(private FactoryInterface $factory)
    {
    }

    public function createMainMenu(): ItemInterface
    {
        $menu = $this->factory->createItem('root');

        $menu->addChild('menu.home', ['route' => 'homepage']);

        $menu->addChild('menu.user', ['route' => 'app_user_index',]);

        $menu->addChild('menu.company', ['route' => 'app_company_index',]);
        $menu->addChild('menu.logout', ['route' => 'app_logout',]);

        return $menu;
    }
}