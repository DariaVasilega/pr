<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('upper', [$this, 'firstLetterToUpperCase']),
        ];
    }

    public function firstLetterToUpperCase($string)
    {
        $string = ucwords(strtolower($string));

        return $string;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('checkInFriends', [$this, 'friendList']),
        ];
    }

    public function friendList($someUser)
    {
        $friendsArray = [];
        if ($someUser->getFriends()->count()) {
            foreach ($someUser->getFriends() as $item) {
                $friendsArray[] = $item->getFriend();
            }
        }

        return $friendsArray;
    }
}
