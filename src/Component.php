<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostsWP;

use PoP\Root\Component\AbstractComponent;
use PoP\Root\Component\YAMLServicesTrait;
use PoPSchema\CustomPostsWP\Config\ServiceConfiguration;

/**
 * Initialize component
 */
class Component extends AbstractComponent
{
    use YAMLServicesTrait;
    // const VERSION = '0.1.0';

    /**
     * Classes from PoP components that must be initialized before this component
     *
     * @return string[]
     */
    public static function getDependedComponentClasses(): array
    {
        return [
            \PoPSchema\CustomPosts\Component::class,
            \PoPSchema\QueriedObjectWP\Component::class,
        ];
    }

    public static function getDependedMigrationPlugins(): array
    {
        return [
            'pop-schema/migrate-customposts-wp',
        ];
    }

    /**
     * Initialize services
     *
     * @param mixed[] $configuration
     * @param string[] $skipSchemaComponentClasses
     */
    protected static function doInitialize(
        array $configuration = [],
        bool $skipSchema = false,
        array $skipSchemaComponentClasses = []
    ): void {
        parent::doInitialize($configuration, $skipSchema, $skipSchemaComponentClasses);
        self::initYAMLServices(dirname(__DIR__));
        ServiceConfiguration::initialize();
    }
}
