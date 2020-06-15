<?php

declare(strict_types=1);

namespace PoP\CustomPostsWP\TypeAPIs;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class PostTypeAPIHelpers
{
    public static function getPostObjectAndID($postObjectOrID): array
    {
        if (is_object($postObjectOrID)) {
            $post = $postObjectOrID;
            $postID = $post->ID;
        } else {
            $postID = $postObjectOrID;
            $post = \get_post($postID);
        }
        return [
            $post,
            $postID,
        ];
    }
}
