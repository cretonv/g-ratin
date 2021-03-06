<?php

namespace App\Context;

use App\Entity\Website;
use App\Repository\WebsiteRepository;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\RequestStack;

class WebsiteContext {
    private Website $_website;

    public function __construct(
        private RequestStack $requestStack,
        private WebsiteRepository $websiteRepository,
    ) {
    }

    public function getWebsite(): Website {
        if (empty($this->_website)) {
            $apiKey = $this->requestStack->getCurrentRequest()->headers->get('authorization');
            if (empty($apiKey)) {
                throw new BadRequestException('The API Key must be specified');
            }
            $website = $this->websiteRepository->findOneBy(['token' => $apiKey]);
            if (empty($website)) {
                throw new BadRequestException('The API Key is not valid');
            }
            $this->_website = $website;
        }
        return $this->_website;
    }
}
