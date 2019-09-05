<?php

namespace FacturaScripts\Plugins\FacturacionCostaRica\Controller;

use FacturaScripts\Core\Base\Controller;

/**
 * Description of ListBitacoraFacturacion
 *
 * @author jcruz
 */
class BitacoraFacturacion extends Controller{
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
        //AssetManager::add('js', FS_ROUTE . '/Plugins/FacturacionCostaRica/Assets/JS/jquery.dataTables.js', 3);
        //AssetManager::add('js', FS_ROUTE . '/Plugins/FacturacionCostaRica/Assets/JS/dataTables.bootstrap4.js', 3);
        $this->setTemplate('BitacoraFacturacion');;
    }

}
