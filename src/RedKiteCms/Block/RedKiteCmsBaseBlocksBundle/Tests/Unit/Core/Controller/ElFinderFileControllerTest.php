<?php
/**
 * This file is part of the RedKiteCmsBaseBlocksBundle and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

namespace RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Controller;

use RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Controller\ElFinderFileController;
use RedKiteLabs\RedKiteCmsBundle\Tests\Unit\Controller\AlCmsElFinderControllerTest;

/**
 * ElFinderControllerTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class ElFinderControllerTest extends AlCmsElFinderControllerTest
{
    public function testConnectFileAction()
    {
        $container = $this->initContainer(
            'el_finder.file_connector',
            'RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\ElFinder\File\ElFinderFileConnector'
        );
        
        $controller = new ElFinderFileController();
        $controller->setContainer($container);
        $controller->connectFileAction();
    }
}
