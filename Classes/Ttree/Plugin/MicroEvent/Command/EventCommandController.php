<?php
namespace Ttree\Plugin\MicroEvent\Command;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Ttree.Plugin.MicroEvent".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Neos\Domain\Model\Site;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;

/**
 * Class EventCommandController
 * @package Ttree\Plugin\MicroEvent\Command
 */
class EventCommandController extends \TYPO3\Flow\Cli\CommandController {

	/**
	 * @Flow\Inject
	 * @var \Ttree\Starter\Service\NodeSortingService
	 */
	protected $nodeSortingService;

	/**
	 * @param NodeInterface $storageNode
	 */
	public function sortEventCommand(NodeInterface $storageNode) {
		$this->nodeSortingService->orderByNodePathAndType($storageNode, 'Ttree.Plugin.MicroEvent:Event', array(
			'property'           => 'eventStartDate',
			'propertyTargetType' => 'DateTime'
		));
	}
}

?>