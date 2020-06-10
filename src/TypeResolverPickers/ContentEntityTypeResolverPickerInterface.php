<?php

declare(strict_types=1);

namespace PoP\ContentWP\TypeResolverPickers;

interface ContentEntityTypeResolverPickerInterface
{
    /**
     * Maybe cast the object of type `WP_Post` returned by function `get_posts`, to a different object type
     *
     * @param [type] $post
     * @return void
     */
    public function maybeCast($post);
    /**
     * Get the post type of the Type (eg: Post is "post", Media is "attachment", etc)
     *
     * @return string
     */
    public function getPostType(): string;
}
