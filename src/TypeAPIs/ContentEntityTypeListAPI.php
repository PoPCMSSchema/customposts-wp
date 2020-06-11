<?php

declare(strict_types=1);

namespace PoP\ContentWP\TypeAPIs;

use PoP\ContentWP\TypeAPIs\PostTypeAPI;
use PoP\Content\TypeAPIs\ContentEntityTypeListAPIInterface;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class ContentEntityTypeListAPI implements ContentEntityTypeListAPIInterface
{
    protected function getPostTypeAPI(): PostTypeAPI
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        return $instanceManager->getInstance(PostTypeAPI::class);
    }

    public function getContentEntities($query, array $options = []): array
    {
        return $this->getPostTypeAPI()->getPosts($query, $options);
    }
    public function getContentEntityCount(array $query = [], array $options = []): int
    {
        return $this->getPostTypeAPI()->getPostCount($query, $options);
    }
}
