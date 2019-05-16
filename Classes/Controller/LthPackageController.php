<?php

namespace LTH\LthPackage\Controller;

/**
 * This file is part of the "lth_package" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

//use MyVendor\StoreInventory\Domain\Repository\ProductRepository;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * Class StoreInventoryController
 *
 * @package MyVendor\StoreInventory\Controller
 */
class LthPackageController extends ActionController
{
    /**
     * @var ProductRepository
     */
    private $productRepository;

    
    /**
     * List Action
     *
     * @return void
     */
    public function listAction()
    {
        //$GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_devlog', array('msg' => '???', 'crdate' => time()));
        $this->view->assign('products', $products);
    }
}