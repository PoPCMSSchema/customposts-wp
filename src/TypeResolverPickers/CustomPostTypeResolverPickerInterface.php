<?php

declare(strict_types=1);

namespace PoP\CustomPostsWP\TypeResolverPickers;

interface CustomPostTypeResolverPickerInterface
{
    /**
     * Maybe cast the object of type `WP_Post` returned by function `get_posts`, to a different object type
     *
     * @param array $customPosts An array with "key" the ID, "value" the object
     * @return array
     */
    public function maybeCastCustomPosts(array $customPosts): array;
    /**
     * Get the post type of the Type (eg: Post is "post", Media is "attachment", etc)
     *
     * @return string
     */
    public function getCustomPostType(): string;
}
