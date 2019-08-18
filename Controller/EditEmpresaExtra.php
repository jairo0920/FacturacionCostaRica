<?php

namespace FacturaScripts\Plugins\FacturacionCostaRica\Controller;

use FacturaScripts\Core\Lib\ExtendedController\EditController;

use FacturaScripts\Core\Base\DataBase\DataBaseWhere;
/**
 * Description of EditProject
 *
 * @author jcruz
 */
class EditEmpresaExtra extends EditController {
    //put your code here
    public function getModelClassName() {
        return 'EmpresaExtra';
    }
    
    /*
    protected function createViews() {
        parent::createViews();
        $this->addListView('ListEmpresaExtra', 'EmpresaExtra', 'empresasextras');
        //$this->setTabsPosition('top');
        //$this->setTabsPosition('bottom');
    }*/
    
    public function getPageData() {
        $page = parent::getPageData();
        $page['showonmenu'] = false;
        return $page;
    }
    
    protected function loadData($viewName, $view) {
        switch ($viewName) {
            case 'ListEmpresaExtra':
                $idempresa = $this->getViewModelValue('EditEmpresaExtra', 'idempresa');
                $where = [new DataBaseWhere('idempresa', $idempresa)];
                $view->loadData('', $where);
                break;
            case 'EditEmpresaExtra':
                parent::loadData($viewName, $view);
                if (!$this->views[$this->active]->model->exists()) {
                   $this->views[$this->active]->model->user = $this->user->nick;
                   $this->views[$this->active]->model->ip = $this->request->getClientIp();
                }
                break;
            default:
                parent::loadData($viewName, $view);
                break;
        } 
    }
}
