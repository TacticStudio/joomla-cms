<?php

namespace YOOtheme\Builder\Joomla\Search\Type;

use function YOOtheme\trans;

class SearchItemsQueryType
{
    protected static $view = ['com_search.search'];

    /**
     * @return array
     */
    public static function config()
    {
        return [
            'fields' => [
                'searchItems' => [
                    'type' => [
                        'listOf' => 'SearchItem',
                    ],
                    'args' => [
                        'offset' => [
                            'type' => 'Int',
                        ],
                        'limit' => [
                            'type' => 'Int',
                        ],
                    ],
                    'metadata' => [
                        'label' => trans('Items'),
                        'view' => static::$view,
                        'group' => trans('Page'),
                        'fields' => [
                            '_offset' => [
                                'description' => trans(
                                    'Set the starting point and limit the number of items.',
                                ),
                                'type' => 'grid',
                                'width' => '1-2',
                                'fields' => [
                                    'offset' => [
                                        'label' => trans('Start'),
                                        'type' => 'number',
                                        'default' => 0,
                                        'modifier' => 1,
                                        'attrs' => [
                                            'min' => 1,
                                            'required' => true,
                                        ],
                                    ],
                                    'limit' => [
                                        'label' => trans('Quantity'),
                                        'type' => 'limit',
                                        'attrs' => [
                                            'placeholder' => trans('No limit'),
                                            'min' => 0,
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolve',
                    ],
                ],
            ],
        ];
    }

    public static function resolve($root, array $args)
    {
        $args += [
            'offset' => 0,
            'limit' => null,
        ];

        if (in_array($root['template'] ?? '', static::$view)) {
            $items = $root['items'] ?? [];

            if ($args['offset'] || $args['limit']) {
                $items = array_slice($items, (int) $args['offset'], (int) $args['limit'] ?: null);
            }

            return $items;
        }
    }
}
