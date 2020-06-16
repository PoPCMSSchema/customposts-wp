<?php

declare(strict_types=1);

namespace PoP\CustomPostsWP\TypeAPIs;

use PoP\CustomPostsWP\TypeAPIs\PostTypeAPI;
use PoP\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class CustomPostTypeAPI implements CustomPostTypeAPIInterface
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
    public function getCustomPostType($objectOrID): string
    {
        return $this->getPostTypeAPI()->getCustomPostType($objectOrID);
    }
}
