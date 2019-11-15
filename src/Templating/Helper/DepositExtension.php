<?php

declare(strict_types=1);

namespace Gweb\SyliusProductDepositPlugin\Templating\Helper;

use Symfony\Component\Templating\Helper\Helper;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Template extension to show product deposit price
 *
 * @author Gerd Weitenberg <gweitenb@gmail.com>
 */
class DepositExtension extends AbstractExtension
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
            new TwigFilter('gweb_calculate_deposit', [$this->helper, 'getPrice']),
        ];
    }
}
