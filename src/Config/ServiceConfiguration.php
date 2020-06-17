<?php

declare(strict_types=1);

namespace PoP\CustomPostsWP\Config;

use PoP\Root\Component\PHPServiceConfigurationTrait;
use PoP\ComponentModel\Container\ContainerBuilderUtils;

class ServiceConfiguration
{
    use PHPServiceConfigurationTrait;

    protected static function configure(): void
    {
        ContainerBuilderUtils::injectValuesIntoService(
            'instance_manager',
            'overrideClass',
            \PoP\CustomPosts\TypeDataLoaders\CustomPostUnionTypeDataLoader::class,
            \PoP\CustomPostsWP\TypeDataLoaders\Overrides\CustomPostUnionTypeDataLoader::class
        );

        ContainerBuilderUtils::injectValuesIntoService(
            'instance_manager',
            'overrideClass',
            \PoP\CustomPosts\TypeResolvers\CustomPostUnionTypeResolver::class,
            \PoP\CustomPostsWP\TypeResolvers\Overrides\CustomPostUnionTypeResolver::class
        );
    }
}
