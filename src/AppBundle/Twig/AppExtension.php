<?php
namespace AppBundle\Twig;

use AppBundle\Twig\AppRuntime;

class AppExtension extends \Twig_Extension
{   
    public function getFilters()
    {
        return array(new \Twig_SimpleFilter('avatar', array(AppRuntime::class, 'avatarFilter')));
    }
}
