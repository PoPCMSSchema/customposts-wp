<?php

declare(strict_types=1);

namespace PoP\CustomPostsWP\TypeDataLoaders\Overrides;

use PoP\CustomPosts\TypeDataLoaders\CustomPostTypeDataLoader;
use PoP\CustomPosts\TypeResolvers\CustomPostUnionTypeResolver;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\CustomPostsWP\TypeResolverPickers\CustomPostUnionTypeHelpers;
use PoP\CustomPostsWP\TypeResolverPickers\CustomPostTypeResolverPickerInterface;

/**
 * In the context of WordPress, "Custom Posts" are all posts (eg: posts, pages, attachments, events, etc)
 * Hence, this class can simply inherit from the Post dataloader, and add the post-types for all required types
 */
class CustomPostUnionTypeDataLoader extends CustomPostTypeDataLoader
{
    public function getObjectQuery(array $ids): array
    {
        $query = parent::getObjectQuery($ids);

        // From all post types from the member typeResolvers
        $query['post-types'] = CustomPostUnionTypeHelpers::getTargetTypeResolverPostTypes(CustomPostUnionTypeResolver::class);

        return $query;
    }

    public function getDataFromIdsQuery(array $ids): array
    {
        $query = parent::getDataFromIdsQuery($ids);

        // From all post types from the member typeResolvers
        $query['post-types'] = CustomPostUnionTypeHelpers::getTargetTypeResolverPostTypes(CustomPostUnionTypeResolver::class);

        return $query;
    }

    public function getObjects(array $ids): array
    {
        $customPosts = parent::getObjects($ids);

        // After executing `get_posts` it returns a list of custom posts of class WP_Post,
        // without converting the object to its own post type (eg: WP_Event for an "event" custom post type)
        // Cast the custom posts to their own classes
        $instanceManager = InstanceManagerFacade::getInstance();
        $customPostUnionTypeResolver =  $instanceManager->getInstance(CustomPostUnionTypeResolver::class);
        $customPosts = array_map(
            function ($customPost) use ($customPostUnionTypeResolver) {
                $targetTypeResolverPicker = $customPostUnionTypeResolver->getTargetTypeResolverPicker($customPost);
                if (is_null($targetTypeResolverPicker)) {
                    return $customPost;
                }
                if ($targetTypeResolverPicker instanceof CustomPostTypeResolverPickerInterface) {
                    // Cast object, eg: from customPost to event
                    return $targetTypeResolverPicker->maybeCast($customPost);
                }
                return $customPost;
            },
            $customPosts
        );
        return $customPosts;
    }
}
