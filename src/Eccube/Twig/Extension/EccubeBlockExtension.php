<?php

/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eccube\Twig\Extension;

use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class EccubeBlockExtension extends AbstractExtension
{
    protected $twig;

    protected $blockTemplates;

    public function __construct(Environment $twig, array $blockTemplates)
    {
        $this->twig = $twig;
        $this->blockTemplates = $blockTemplates;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('eccube_block_*', function ($name, array $parameters = []) {
                $sources = $this->blockTemplates;
                foreach ($sources as $source) {
                    $template = $this->twig->loadTemplate($source);
                    if ($template->hasBlock($name, $parameters)) {
                        echo $template->renderBlock($name, $parameters);

                        return;
                    }
                }
                @trigger_error($name.' block is not found', E_USER_WARNING);
            }, ['pre_escape' => 'html', 'is_safe' => ['html']]),
        ];
    }
}