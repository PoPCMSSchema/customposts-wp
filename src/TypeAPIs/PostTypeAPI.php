<?php

declare(strict_types=1);

namespace PoP\CustomPostsWP\TypeAPIs;

use function apply_filters;
use function get_post_status;
use PoP\CustomPosts\Types\Status;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\CustomPosts\ComponentConfiguration;
use PoP\QueriedObject\TypeAPIs\TypeAPIUtils;
use PoP\CustomPostsWP\TypeAPIs\CustomPostTypeAPIUtils;
use PoP\CustomPostsWP\TypeAPIs\CustomPostTypeAPIHelpers;
use PoP\ComponentModel\TypeDataResolvers\APITypeDataResolverTrait;
use PoP\CustomPostsWP\TypeResolverPickers\CustomPostUnionTypeHelpers;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class PostTypeAPI
{
    use APITypeDataResolverTrait;
    // public const NON_EXISTING_ID = "non-existing";

    /**
     * Return the post's ID
     *
     * @param object $post
     * @return void
     */
    public function getID($post)
    {
        return $post->ID;
    }

    public function getStatus($postObjectOrID): ?string
    {
        $status = get_post_status($postObjectOrID);
        return CustomPostTypeAPIUtils::convertPostStatusFromCMSToPoP($status);
    }

    public function getCustomPosts($query, array $options = []): array
    {
        $query = $this->convertPostsQuery($query, $options);
        return (array) \get_posts($query);
    }
    public function getCustomPostCount(array $query = [], array $options = []): int
    {
        // Convert parameters
        $options['return-type'] = POP_RETURNTYPE_IDS;
        $query = $this->convertPostsQuery($query, $options);

        // All results, no offset
        $query['posts_per_page'] = -1;
        unset($query['offset']);

        // Execute query and count results
        $posts = \get_posts($query);
        return count($posts);
    }
    protected function convertPostsQuery($query, array $options = []): array
    {
        if ($return_type = $options['return-type']) {
            if ($return_type == POP_RETURNTYPE_IDS) {
                $query['fields'] = 'ids';
            }
        }

        // Accept field atts to filter the API fields
        $this->maybeFilterDataloadQueryArgs($query, $options);

        // Convert the parameters
        if (isset($query['post-status'])) {
            if (is_array($query['post-status'])) {
                // doing get_posts can accept an array of values
                $query['post_status'] = array_map(
                    [CustomPostTypeAPIUtils::class, 'convertPostStatusFromPoPToCMS'],
                    $query['post-status']
                );
            } else {
                // doing wp_insert/update_post accepts a single value
                $query['post_status'] = CustomPostTypeAPIUtils::convertPostStatusFromPoPToCMS($query['post-status']);
            }
            unset($query['post-status']);
        }
        if ($query['include']) {
            // Transform from array to string
            $query['include'] = implode(',', $query['include']);

            // Make sure the post can also be draft or pending
            if (!isset($query['post_status'])) {
                $query['post_status'] = CustomPostTypeAPIUtils::getCMSPostStatuses();
            }
        }
        if (isset($query['post-types'])) {
            $query['post_type'] = $query['post-types'];
            // // Make sure they are public, to avoid an external query requesting forbidden data
            // $postTypes = array_intersect(
            //     $query['post-types'],
            //     $this->getCustomPostTypes(['public' => true])
            // );
            // // If there are no valid postTypes, then return no results
            // // By not adding the post type, WordPress will fetch a "post"
            // // Then, include a non-existing ID
            // if ($postTypes) {
            //     $query['post_type'] = $postTypes;
            // } else {
            //     $query['include'] = self::NON_EXISTING_ID; // Non-existing ID
            // }
            unset($query['post-types']);
        } elseif ($unionTypeResolverClass = $query['types-from-union-resolver-class']) {
            $query['post_type'] = CustomPostUnionTypeHelpers::getTargetTypeResolverPostTypes(
                $unionTypeResolverClass
            );
            unset($query['types-from-union-resolver-class']);
        }
        // else {
        //     // Default value: only get POST, no CPTs
        //     $query['post_type'] = ['post'];
        // }
        if (isset($query['offset'])) {
            // Same param name, so do nothing
        }
        if (isset($query['limit'])) {
            // Maybe restrict the limit, if higher than the max limit
            $limit = TypeAPIUtils::getLimitOrMaxLimit(
                $query['limit'],
                ComponentConfiguration::getCustomPostListMaxLimit()
            );

            // Assign the limit as the required attribute
            $query['posts_per_page'] = $limit;
            unset($query['limit']);
        }
        if (isset($query['order'])) {
            // Same param name, so do nothing
        }
        if (isset($query['orderby'])) {
            // Same param name, so do nothing
            // This param can either be a string or an array. Eg:
            // $query['orderby'] => array('date' => 'DESC', 'title' => 'ASC');
        }
        if (isset($query['post-not-in'])) {
            $query['post__not_in'] = $query['post-not-in'];
            unset($query['post-not-in']);
        }
        if (isset($query['search'])) {
            $query['is_search'] = true;
            $query['s'] = $query['search'];
            unset($query['search']);
        }
        // Filtering by date: Instead of operating on the query, it does it through filter 'posts_where'
        if (isset($query['date-from'])) {
            $query['date_query'][] = [
                'after' => $query['date-from'],
                'inclusive' => false,
            ];
            unset($query['date-from']);
        }
        if (isset($query['date-from-inclusive'])) {
            $query['date_query'][] = [
                'after' => $query['date-from-inclusive'],
                'inclusive' => true,
            ];
            unset($query['date-from-inclusive']);
        }
        if (isset($query['date-to'])) {
            $query['date_query'][] = [
                'before' => $query['date-to'],
                'inclusive' => false,
            ];
            unset($query['date-to']);
        }
        if (isset($query['date-to-inclusive'])) {
            $query['date_query'][] = [
                'before' => $query['date-to-inclusive'],
                'inclusive' => true,
            ];
            unset($query['date-to-inclusive']);
        }

        $query = HooksAPIFacade::getInstance()->applyFilters(
            'CMSAPI:posts:query',
            $query,
            $options
        );
        return $query;
    }
    public function getCustomPostTypes(array $query = array()): array
    {
        // Convert the parameters
        if (isset($query['exclude-from-search'])) {
            $query['exclude_from_search'] = $query['exclude-from-search'];
            unset($query['exclude-from-search']);
        }
        if (isset($query['publicly-queryable'])) {
            $query['publicly_queryable'] = $query['publicly-queryable'];
            unset($query['publicly-queryable']);
        }
        // Same key, so no need to convert
        // if (isset($query['public'])) {
        //     $query['public'] = $query['public'];
        //     unset($query['public']);
        // }
        return \get_post_types($query);
    }

    public function getPermalink($postObjectOrID): ?string
    {
        list(
            $post,
            $postID,
        ) = $this->getCustomPostObjectAndID($postObjectOrID);
        if ($this->getStatus($postObjectOrID) == Status::PUBLISHED) {
            return \get_permalink($postID);
        }

        // Function get_sample_permalink comes from the file below, so it must be included
        // Code below copied from `function get_sample_permalink_html`
        include_once ABSPATH . 'wp-admin/includes/post.php';
        list($permalink, $post_name) = \get_sample_permalink($postID, null, null);
        return str_replace(['%pagename%', '%postname%'], $post_name, $permalink);
    }
    public function getExcerpt($postObjectOrID): ?string
    {
        return \get_the_excerpt($postObjectOrID);
    }
    protected function getCustomPostObjectAndID($postObjectOrID): array
    {
        return CustomPostTypeAPIHelpers::getCustomPostObjectAndID($postObjectOrID);
    }

    public function getTitle($postObjectOrID): ?string
    {
        list(
            $post,
            $postID,
        ) = $this->getCustomPostObjectAndID($postObjectOrID);
        return apply_filters('the_title', $post->post_title, $postID);
    }

    public function getContent($postObjectOrID): ?string
    {
        list(
            $post,
            $postID,
        ) = $this->getCustomPostObjectAndID($postObjectOrID);
        return apply_filters('the_content', $post->post_content);
    }

    public function getPublishedDate($postObjectOrID): ?string
    {
        list(
            $post,
            $postID,
        ) = $this->getCustomPostObjectAndID($postObjectOrID);
        return $post->post_date;
    }

    public function getModifiedDate($postObjectOrID): ?string
    {
        list(
            $post,
            $postID,
        ) = $this->getCustomPostObjectAndID($postObjectOrID);
        return $post->post_modified;
    }
    public function getCustomPostType($postObjectOrID): string
    {
        list(
            $post,
            $postID,
        ) = $this->getCustomPostObjectAndID($postObjectOrID);
        return $post->post_type;
    }

    /**
     * Get the post with provided ID or, if it doesn't exist, null
     *
     * @param int $id
     * @return void
     */
    public function getCustomPost($id): ?object
    {
        return \get_post($id);
    }
}
