<?php

namespace YOOtheme\Builder\Joomla\Source\Type;

use function YOOtheme\trans;

class TagItemsQueryType
{
    protected static $view = ['com_tags.tag'];

    /**
     * @return array
     */
    public static function config()
    {
        return [
            'fields' => [
                'tagItemsSingle' => [
                    'type' => 'TagItem',
                    'args' => [
                        'offset' => [
                            'type' => 'Int',
                        ],
                    ],
                    'metadata' => [
                        'label' => trans('Item'),
                        'view' => static::$view,
                        'group' => trans('Page'),
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
                        ],
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveSingle',
                    ],
                ],
                'tagItems' => [
                    'type' => [
                        'listOf' => 'TagItem',
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
            $items = $root['items'];

            if ($args['offset'] || $args['limit']) {
                $items = array_slice($items, (int) $args['offset'], (int) $args['limit'] ?: null);
            }

            return $items;
        }
    }

    public static function resolveSingle($root, array $args)
    {
        if (in_array($root['template'] ?? '', static::$view)) {
            return $root['items'][$args['offset'] ?? 0] ?? null;
        }
    }
}
