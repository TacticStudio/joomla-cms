<?php

namespace YOOtheme\Theme\Joomla;

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Document\DocumentRenderer;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\User\User;
use YOOtheme\Config;
use YOOtheme\View;
use function YOOtheme\app;

class ModulesRenderer extends DocumentRenderer
{
    public function render($position, $params = [], $content = null)
    {
        [$config, $view, $user, $joomla] = app(
            Config::class,
            View::class,
            User::class,
            CMSApplication::class,
        );

        $modules = ModuleHelper::getModules($position);
        $renderer = $this->_doc->loadRenderer('module');
        $frontEdit = $joomla->isClient('site') && $joomla->get('frontediting', 1) && !$user->guest;
        $menusEdit =
            $joomla->get('frontediting', 1) == 2 && $user->authorise('core.edit', 'com_menus');

        // Reset section transparent header
        if ($modules && $position === 'top') {
            $config->set(
                'header.section.transparent',
                ($modules[0]->type ?? '') === 'yootheme_builder'
                    ? null
                    : (bool) $config('~theme.top.header_transparent'),
            );
        }

        foreach ($modules as $module) {
            $moduleHtml = $renderer->render($module, $params, $content);

            if (!isset($module->attrs)) {
                $module->attrs = [];
            }

            if (
                $frontEdit &&
                trim($moduleHtml) != '' &&
                $user->authorise('module.edit.frontend', "com_modules.module.{$module->id}")
            ) {
                $displayData = [
                    'moduleHtml' => &$moduleHtml,
                    'module' => $module,
                    'position' => $position,
                    'menusediting' => $menusEdit,
                ];
                LayoutHelper::render('joomla.edit.frontediting_modules', $displayData);
            }

            $module->content = $moduleHtml;
        }

        return $view(
            '~theme/templates/position',
            ['name' => $position, 'items' => $modules] + $params,
        );
    }
}
