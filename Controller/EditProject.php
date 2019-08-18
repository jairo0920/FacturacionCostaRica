<?php

namespace FacturaScripts\Plugins\FacturacionCostaRica\Controller;

use FacturaScripts\Core\Lib\ExtendedController\EditController;

use FacturaScripts\Core\Base\DataBase\DataBaseWhere;
/**
 * Description of EditProject
 *
 * @author jcruz
 */
class EditProject extends EditController {
    //put your code here
    public function getModelClassName() {
        return 'Project';
    }
    
    protected function createViews() {
        parent::createViews();
        $this->addListView('ListProject', 'Project', 'projects');
        $this->addListView('ListProject-2', 'Project', 'projects');
        //$this->setTabsPosition('top');
        $this->setTabsPosition('bottom');
    }
    
    public function getPageData() {
        $page = parent::getPageData();
        return $page;
    }
    
    protected function loadData($viewName, $view) {
        switch ($viewName) {
            case 'ListProject':
                $codcliente = $this->getViewModelValue('EditProject', 'codcliente');
                $where = [new DataBaseWhere('codcliente', $codcliente)];
                $view->loadData('', $where);
                break;
            case 'EditProject':
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
