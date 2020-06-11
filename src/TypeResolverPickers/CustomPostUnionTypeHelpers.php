<?php

declare(strict_types=1);

namespace PoP\ContentWP\TypeResolverPickers;

use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ContentWP\TypeResolverPickers\CustomPostTypeResolverPickerInterface;

/**
 * In the context of WordPress, "Custom Posts" are all posts (eg: posts, pages, attachments, events, etc)
 * Hence, this class can simply inherit from the Post dataloader, and add the post-types for all required types
 */
class CustomPostUnionTypeHelpers
{
    /**
     * Obtain the post types from all member typeResolvers
     *
     * @return void
     */
    public static function getTargetTypeResolverPostTypes(string $unionTypeResolverClass)
    {
        $postTypes = [];
        $instanceManager = InstanceManagerFacade::getInstance();
        $unionTypeResolver = $instanceManager->getInstance($unionTypeResolverClass);
        $typeResolverPickers = $unionTypeResolver->getTypeResolverPickers();
        foreach ($typeResolverPickers as $typeResolverPicker) {
            // The picker should implement interface CustomPostTypeResolverPickerInterface
            if ($typeResolverPicker instanceof CustomPostTypeResolverPickerInterface) {
                $postTypes[] = $typeResolverPicker->getPostType();
            }
        }
        return $postTypes;
    }
}
