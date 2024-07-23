<?php

namespace YOOtheme\Builder\Joomla;

use Joomla\CMS\Factory;

class ArticleHelper
{
    public static function isArticleView()
    {
        $input = Factory::getApplication()->input;

        return $input->getCmd('option') === 'com_content' &&
            $input->getCmd('view') === 'article' &&
            $input->getCmd('task', '') === '';
    }

    public static function getCollision($article)
    {
        $user = Factory::getUser($article->modified_by);

        return [
            'contentHash' => md5($article->fulltext . $article->introtext),
            'modifiedBy' => $user->username ?: '',
        ];
    }
}
