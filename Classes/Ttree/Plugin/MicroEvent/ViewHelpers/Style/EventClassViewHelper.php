<?php
namespace Ttree\Plugin\MicroEvent\ViewHelpers\Style;

/*
 * This file is part of the Ttree.Plugin.MicroEvent package.
 *
 * (c) ttree ltd - www.ttree.ch
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;

/**
 * @api
 */
class EventClassViewHelper extends AbstractViewHelper
{
    /**
     * @param NodeInterface $event
     * @param boolean $archived
     * @return string
     */
    public function render(NodeInterface $event, $archived = false)
    {
        if ($event->getNodeType()->getName() !== 'Ttree.Plugin.MicroEvent:Event') {
            return '';
        }
        $classes = array('event-type-default');
        switch ($event->getProperty('eventType')) {
            case '':
                $classes[] = 'event-type-basic';
                break;
            case 'event':
                $classes[] = 'event-type-event';
                break;
            case 'talk':
                $classes[] = 'event-type-talk';
                break;
            case 'reminder':
                $classes[] = 'event-type-reminder';
                break;
        }
        if ($archived === true) {
            $classes[] = 'event-type-archived';
        }

        return trim(implode(' ', $classes));
    }
}
