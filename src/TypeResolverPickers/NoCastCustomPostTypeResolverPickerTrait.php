<?php

declare(strict_types=1);

namespace PoP\CustomPostsWP\TypeResolverPickers;

trait NoCastCustomPostTypeResolverPickerTrait
{
    /**
     * Do not cast the object of type `WP_Post` returned by function `get_posts`, since it already satisfies this Type too (eg: locationPost)
     *
     * @param [type] $post
     * @return void
     */
    public function maybeCast($customPost)
    {
        return $customPost;
    }
}
