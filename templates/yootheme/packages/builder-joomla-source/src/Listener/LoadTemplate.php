<?php

namespace YOOtheme\Builder\Joomla\Source\Listener;

use Joomla\CMS\Language\Text;
use YOOtheme\Builder;
use YOOtheme\Builder\Templates\TemplateHelper;
use YOOtheme\Config;
use YOOtheme\Event;

class LoadTemplate
{
    public Config $config;
    public Builder $builder;
    public TemplateHelper $template;

    public function __construct(Config $config, Builder $builder, TemplateHelper $template)
    {
        $this->config = $config;
        $this->builder = $builder;
        $this->template = $template;
    }

    public function handle($event): void
    {
        [$view, $tpl] = $event->getArguments();

        $template = Event::emit('builder.template', $view, $tpl);

        if (empty($template['type'])) {
            return;
        }

        // get template from customizer request?
        $templ = $this->config->get('req.customizer.template');

        if ($this->config->get('app.isCustomizer')) {
            $this->config->set('customizer.view', $template['type']);
        }

        if ($this->config->get('app.isBuilder') && empty($templ)) {
            return;
        }

        // get visible template
        $visible = $this->template->match($template);

        // set template identifier
        if ($this->config->get('app.isCustomizer')) {
            $this->config->add('customizer.template', [
                'id' => $templ['id'] ?? null,
                'visible' => $visible['id'] ?? null,
            ]);
        }

        if ($templ || $visible) {
            $template += ($templ ?? $visible) + ['layout' => [], 'params' => []];

            // get output from builder
            $output = $this->builder->render(
                json_encode($template['layout']),
                $template['params'] + [
                    'prefix' => "template-{$template['id']}",
                    'template' => $template['type'],
                ],
            );

            // append frontend edit button?
            if ($output && isset($template['editUrl']) && !$this->config->get('app.isCustomizer')) {
                $output .=
                    "<a style=\"position: fixed!important\" class=\"uk-position-medium uk-position-bottom-right uk-position-z-index uk-button uk-button-primary\" href=\"{$template['editUrl']}\">" .
                    Text::_('JACTION_EDIT') .
                    '</a>';
            }

            if ($output) {
                $view->set('_output', $output);
                $this->config->set('app.isBuilder', true);
            }
        }
    }
}
