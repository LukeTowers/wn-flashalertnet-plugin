<?php namespace LukeTowers\FlashAlertNet\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Organizations Back-end Controller
 */
class Organizations extends Controller
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

        BackendMenu::setContext('LukeTowers.FlashAlertNet', 'main-flashalert', 'side-organizations');
    }
}
