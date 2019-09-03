<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace FacturaScripts\Plugins\FacturacionCostaRica\Controller;

use FacturaScripts\Core\Lib\ExtendedController\ListController;

/**
 * Description of ListBitacoraFacturacion
 *
 * @author jcruz
 */
class ListBitacoraFacturacion extends ListController{
    //put your code here
    public function getPageData(): array {
        $pageData = parent::getPageData();
        $pageData['menu'] = 'admin';
        //$pageData['menu'] = 'Fact. Costa Rica';
        $pageData['submenu'] = 'Fact. Costa Rica';
        $pageData['title'] = 'Bitacora Facturacion';
        $pageData['icon'] = 'fab fa-bitcoin';
        return $pageData;
    }

    protected function createViews() {
        $this->setTemplate('BitacoraFacturacion');;
    }
    
    public function privateCore(&$response, $user, $permissions)
    {
        parent::privateCore($response, $user, $permissions);
        $this->setTemplate('BitacoraFacturacion');
    }
    
}
