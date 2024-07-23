<?php

namespace YOOtheme\Builder\Joomla\Source\Listener;

use YOOtheme\Builder\Joomla\Source\Type;
use YOOtheme\Builder\Source\Type\SiteType;

class LoadSourceTypes
{
    public function handle($source): void
    {
        $query = [
            Type\SiteQueryType::config(),
            Type\ArticleQueryType::config(),
            Type\CategoryQueryType::config(),
            Type\ContactQueryType::config(),
            Type\ArticlesQueryType::config(),
            Type\SmartSearchQueryType::config(),
            Type\SmartSearchItemsQueryType::config(),
            Type\TagsQueryType::config(),
            Type\TagItemsQueryType::config(),
            Type\CustomArticleQueryType::config(),
            Type\CustomArticlesQueryType::config(),
            Type\CustomCategoryQueryType::config(),
            Type\CustomCategoriesQueryType::config(),
            Type\CustomTagQueryType::config(),
            Type\CustomTagsQueryType::config(),
            Type\CustomMenuItemQueryType::config(),
            Type\CustomMenuItemsQueryType::config(),
            Type\CustomUserQueryType::config(),
            Type\CustomUsersQueryType::config(),
        ];

        $types = [
            ['Article', Type\ArticleType::config()],
            ['ArticleEvent', Type\ArticleEventType::config()],
            ['ArticleImages', Type\ArticleImagesType::config()],
            ['ArticleUrls', Type\ArticleUrlsType::config()],
            ['Category', Type\CategoryType::config()],
            ['CategoryParams', Type\CategoryParamsType::config()],
            ['Contact', Type\ContactType::config()],
            ['Event', Type\EventType::config()],
            ['Images', Type\ImagesType::config()],
            ['MenuItem', Type\MenuItemType::config()],
            ['SmartSearch', Type\SmartSearchType::config()],
            ['SmartSearchItem', Type\SmartSearchItemType::config()],
            ['Site', SiteType::config()],
            ['Tag', Type\TagType::config()],
            ['TagItem', Type\TagItemType::config()],
            ['User', Type\UserType::config()],
        ];

        foreach ($query as $args) {
            $source->queryType($args);
        }

        foreach ($types as $args) {
            $source->objectType(...$args);
        }
    }
}
