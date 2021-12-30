<?php
namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Cache\ItemInterface as CacheItemInterface;

class MenuBuilder
{
    private $factory;
    private $security;

    /**
     * Add any other dependency you need...
     */
    public function __construct(FactoryInterface $factory, Security $security)
    {
        $this->factory = $factory;
        $this->security = $security;
    }

    public function createMainMenu(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('root');

        $menu->addChild('Liste des participants', ['route' => 'app_cat_list']);
        
        return $menu;
    }

    public function createUserMenu(array $option): ItemInterface
    {
        $menu = $this->factory->createItem('root');

    
        if($this->security->isGranted('ROLE_USER')){
            $user = $this->security->getUser();

            $child = $menu->addChild($user->getEmail(), ['uri' => '#']);
            $menu->addChild('Déconnexion', ['route' => 'app_logout']);
        }else{
            $menu->addChild("S'inscrire", [
                'route' => 'app_register',
            'attributes' => [
                'class' => 'menu-register'
            ]]);

            $menu->addChild('Connexion', ['route' => 'app_login']);
        }

        return $menu;
    }
}
?>