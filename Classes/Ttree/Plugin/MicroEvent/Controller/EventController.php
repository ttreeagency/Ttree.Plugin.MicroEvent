<?php
namespace Ttree\Plugin\MicroEvent\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Ttree.Plugin.MicroEvent".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Mvc\Routing\UriBuilder;
use TYPO3\TYPO3CR\Domain\Model\Node;
use TYPO3\TYPO3CR\Domain\Model\NodeTemplate;

/**
 * Class EventController
 * @package Ttree\Plugin\MicroEvent\Controller
 */
class EventController extends \TYPO3\Flow\Mvc\Controller\ActionController {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\TYPO3CR\Domain\Service\NodeTypeManager
	 */
	protected $nodeTypeManager;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\I18n\Service
	 */
	protected $i18nService;

	/**
	 * @return void
	 */
	public function indexAction() {
		/** @var Node $eventDocumentNode */
		$eventDocumentNode = $this->request->getInternalArgument('__documentNode');
		if ($eventDocumentNode !== NULL) {
			$this->view->assign('eventsNode', $eventDocumentNode);
			$this->view->assign('hasEventNodes', $eventDocumentNode->hasChildNodes('Ttree.Plugin.MicroEvent:Event'));
		} else {
			return 'Error: The Event Plugin cannot determine the current document node. Please make sure to include this plugin only by inserting it into a page / document.';
		}
	}

	/**
	 * @return void
	 */
	public function createAction() {
		/** @var Node $eventDocumentNode */
		$eventDocumentNode = $this->request->getInternalArgument('__documentNode');
		if ($eventDocumentNode === NULL) {
			return 'Error: The Event Plugin cannot determine the current document node. Please make sure to include this plugin only by inserting it into a page / document.';
		}

		$nodeTemplate = new NodeTemplate();
		$nodeTemplate->setNodeType($this->nodeTypeManager->getNodeType('Ttree.Plugin.MicroEvent:Event'));
		$nodeTemplate->setProperty('title', 'A new event ...');

		$slug     = uniqid('event');
		$postNode = $eventDocumentNode->createNodeFromTemplate($nodeTemplate, $slug);

		// @todo order by start date
		$currentlyFirstEventNode = $eventDocumentNode->getPrimaryChildNode();
		if ($currentlyFirstEventNode !== NULL) {
			// FIXME This currently doesn't work, probably due to some TYPO3CR bug / misconception
			$postNode->moveBefore($currentlyFirstEventNode);
		}

		$mainRequest    = $this->request->getMainRequest();
		$mainUriBuilder = new UriBuilder();
		$mainUriBuilder->setRequest($mainRequest);
		$mainUriBuilder->setFormat('html');
		$uri = $mainUriBuilder
			->reset()
			->setCreateAbsoluteUri(TRUE)
			->uriFor('show', array('node' => $postNode));
		$this->redirectToUri($uri);
	}
}

?>