<?php
namespace Ttree\Plugin\MicroEvent\ViewHelpers\Style;

/*                                                                        *
 * This script belongs to the FLOW3 package "MicroEvent".                 *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License as published by the *
 * Free Software Foundation, either version 3 of the License, or (at your *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
 * General Public License for more details.                               *
 *                                                                        *
 * You should have received a copy of the GNU Lesser General Public       *
 * License along with the script.                                         *
 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;

/**
 * @api
 */
class EventClassViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * @param \TYPO3\TYPO3CR\Domain\Model\NodeInterface $event
	 * @return string
	 */
	public function render(NodeInterface $event) {
		if ($event->getNodeType()->getName() !== 'Ttree.Plugin.MicroEvent:Event') {
			return;
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
		if ($event->getProperty('eventTbc')) {
			$classes[] = 'event-type-tbc';
		}

		return trim(implode(' ', $classes));
	}
}


?>