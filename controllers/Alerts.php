<?php namespace LukeTowers\FlashAlertNet\Controllers;

use Artisan;
use BackendMenu;
use Backend\Classes\Controller;

/**
 * Alerts Back-end Controller
 */
class Alerts extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('LukeTowers.FlashAlertNet', 'main-flashalert', 'side-alerts');
    }

    public function index_onSync()
    {
        Artisan::call('flashalertnet:sync');
        return $this->listRefresh();
    }
}
