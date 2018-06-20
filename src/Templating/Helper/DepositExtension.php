<?php

declare(strict_types=1);

namespace Gweb\SyliusDepositPlugin\Templating\Helper;

use Symfony\Component\Templating\Helper\Helper;

/**
 * Template extension to get deposit price
 *
 * @author Gerd Weitenberg <gweitenb@gmail.com>
 */
class DepositExtension extends \Twig_Extension
{
    /**
     * @var Helper
     */
    private $helper;

    /**
     * @param Helper $helper
     */
    public function __construct(Helper $helper)
    {
        $this->helper = $helper;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters(): array
    {
        return [
          new \Twig_Filter('gweb_calculate_deposit', [$this->helper, 'getPrice']),
        ];
    }
}
