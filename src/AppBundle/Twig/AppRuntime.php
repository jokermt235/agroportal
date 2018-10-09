<?php
namespace AppBundle\Twig;

class AppRuntime
{
    public function __construct(){
        // this simple example doesn't define any dependency, but in your own
        // extensions, you'll need to inject services using this constructor
    }

    public function avatarFilter($hashed_images)
    {
        $images = unserialize(base64_decode($hashed_images));
        $image = '';
        if(!empty($images)){
            $image = $images[0];
        }
        return 'small_'.$image;
    }
}
