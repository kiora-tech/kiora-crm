<?php

declare(strict_types=1);

namespace App\Menu;

use App\Entity\LegalPerson;
use App\Entity\User;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Impersonate\ImpersonateUrlGenerator;

use function Symfony\Component\Translation\t;

final readonly class Builder
{
    public function __construct(
        private FactoryInterface        $factory,
        private Security                $security,
        #[Autowire(service: 'security.impersonate_url_generator', lazy: true)]
        private ImpersonateUrlGenerator $impersonateUrlGenerator,
        #[Autowire(lazy: true)]
        private UrlGeneratorInterface   $urlGenerator,
    )
    {
    }

    private function getCompany(): LegalPerson
    {
        $user = $this->security->getUser();
        if (!$user instanceof User) {
            throw new \LogicException('User is not valid.');
        }

        return $user->getCompany()
            ?? throw new \LogicException('Company is not valid.');
    }

    public function createMainMenu(): ItemInterface
    {
        $menu = $this->factory->createItem('root');
        
        // Dashboard
        $menu->addChild('menu.home', ['route' => 'homepage'])
            ->setLabel((string)t('menu.home'))
            ->setExtra('icon', 'bi bi-grid')
            ->setExtra('safe_label', true);
        
        // Clients
        $clients = $menu->addChild('menu.clients', ['uri' => '#'])
            ->setLabel((string)t('menu.clients'))
            ->setExtra('icon', 'bi bi-people')
            ->setExtra('safe_label', true);
            
        $clients->addChild('menu.client_dashboard', ['route' => 'client_dashboard_index'])
            ->setLabel((string)t('menu.client_dashboard'))
            ->setExtra('icon', 'bi bi-speedometer2')
            ->setExtra('safe_label', true);
            
        $clients->addChild('menu.physical_persons', ['route' => 'physical_person_index'])
            ->setLabel((string)t('menu.physical_persons'))
            ->setExtra('icon', 'bi bi-person')
            ->setExtra('safe_label', true);
            
        $clients->addChild('menu.legal_persons', ['route' => 'legal_person_index'])
            ->setLabel((string)t('menu.legal_persons'))
            ->setExtra('icon', 'bi bi-building')
            ->setExtra('safe_label', true);
        
        // Projects
        $menu->addChild('menu.projects', ['route' => 'project_index'])
            ->setLabel((string)t('menu.projects'))
            ->setExtra('icon', 'bi bi-kanban')
            ->setExtra('safe_label', true);
            
        // Tasks
        $menu->addChild('menu.tasks', ['route' => 'task_index'])
            ->setLabel((string)t('menu.tasks'))
            ->setExtra('icon', 'bi bi-check2-square')
            ->setExtra('safe_label', true);
            
        // Interactions
        // TODO: Uncomment this when InteractionController is implemented
        // $menu->addChild('menu.interactions', ['route' => 'interaction_index'])
        //     ->setLabel((string)t('menu.interactions'))
        //     ->setExtra('icon', 'bi bi-chat-dots')
        //     ->setExtra('safe_label', true);
        
        // Administration
        if ($this->security->isGranted('ROLE_ADMIN')) {
            $admin = $menu->addChild('menu.admin', ['uri' => '#'])
                ->setLabel((string)t('menu.admin'))
                ->setExtra('icon', 'bi bi-gear')
                ->setExtra('safe_label', true);
                
            $admin->addChild('menu.company', ['route' => 'legal_person_index'])
                ->setLabel((string)t('menu.company'))
                ->setExtra('icon', 'bi bi-building-gear')
                ->setExtra('safe_label', true);
                
            $admin->addChild('menu.user', ['route' => 'app_user_index'])
                ->setLabel((string)t('menu.user'))
                ->setExtra('icon', 'bi bi-people-fill')
                ->setExtra('safe_label', true);
        }

        return $menu;
    }
}
