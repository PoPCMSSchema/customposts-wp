<?php

declare(strict_types=1);

namespace PoP\CustomPostsWP\TypeAPIs;

use PoP\CustomPosts\Types\Status;

class PostTypeAPIUtils
{
    protected static $cmsToPoPPostStatusConversion = [
        'publish' => Status::PUBLISHED,
        'pending' => Status::PENDING,
        'draft' => Status::DRAFT,
        'trash' => Status::TRASH,
    ];
    protected static $popToCMSPostStatusConversion;

    public static function init()
    {
        if (is_null(self::$popToCMSPostStatusConversion)) {
            self::$popToCMSPostStatusConversion = array_flip(self::$cmsToPoPPostStatusConversion);
        }
    }

    public static function convertPostStatusFromCMSToPoP($status)
    {
        // Convert from the CMS status to PoP's one
        return self::$cmsToPoPPostStatusConversion[$status];
    }
    public static function convertPostStatusFromPoPToCMS($status)
    {
        // Convert from the CMS status to PoP's one
        self::init();
        return self::$popToCMSPostStatusConversion[$status];
    }
    public static function getCMSPostStatuses()
    {
        return array_keys(self::$cmsToPoPPostStatusConversion);
    }
}
