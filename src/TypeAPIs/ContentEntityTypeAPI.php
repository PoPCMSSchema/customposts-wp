<?php

declare(strict_types=1);

namespace PoP\ContentWP\TypeAPIs;

use PoP\ContentWP\TypeAPIs\PostTypeAPI;
use PoP\Content\TypeAPIs\ContentEntityTypeAPIInterface;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class ContentEntityTypeAPI implements ContentEntityTypeAPIInterface
{
    protected function getPostTypeAPI(): PostTypeAPI
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        return $instanceManager->getInstance(PostTypeAPI::class);
    }

    public function getID($object)
    {
        return $this->getPostTypeAPI()->getID($object);
    }
    public function getContent($id): ?string
    {
        return $this->getPostTypeAPI()->getContent($id);
    }
    public function getPermalink($objectOrID): ?string
    {
        return $this->getPostTypeAPI()->getPermalink($objectOrID);
    }
    public function getStatus($objectOrID): ?string
    {
        return $this->getPostTypeAPI()->getStatus($objectOrID);
    }
    public function getPublishedDate($objectOrID): ?string
    {
        return $this->getPostTypeAPI()->getPublishedDate($objectOrID);
    }
    public function getModifiedDate($objectOrID): ?string
    {
        return $this->getPostTypeAPI()->getModifiedDate($objectOrID);
    }
    public function getTitle($id): ?string
    {
        return $this->getPostTypeAPI()->getTitle($id);
    }
    public function getExcerpt($objectOrID): ?string
    {
        return $this->getPostTypeAPI()->getExcerpt($objectOrID);
    }

    // public function getContentEntities($query, array $options = []): array
    // {
    //     return $this->getPostTypeAPI()->getPosts($query, $options);
    // }
    // public function getContentEntityCount(array $query = [], array $options = []): int
    // {
    //     return $this->getPostTypeAPI()->getPostCount($query, $options);
    // }
    // public function getContentEntries($query, array $options = []): array
    // {
    //     return $this->getPostTypeAPI()->getPosts($query, $options);
    // }
    // public function getContentEntryCount(array $query = [], array $options = []): int
    // {
    //     return $this->getPostTypeAPI()->getPostCount($query, $options);
    // }
}
