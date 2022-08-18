<?php

declare(strict_types=1);

namespace App\Twig;

use App\Service\AudioList;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class Collections extends AbstractExtension
{
    public function __construct(protected AudioList $audioList) { }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('collections', [$this, 'collections']),
        ];
    }

    public function collections(): array
    {
        return $this->audioList->getCollections();
    }
}
