<?php namespace LukeTowers\FlashAlertNet\Components;

use Cms\Classes\ComponentBase;
use LukeTowers\FlashAlertNet\Models\Alert;
use LukeTowers\FlashAlertNet\Models\Organization;

class Alerts extends ComponentBase
{
    /**
     * @var Collection Collection of alert objects to display
     */
    public $alerts;

    public function componentDetails()
    {
        return [
            'name'        => 'List of Alerts',
            'description' => 'Display a listing of alerts by organization'
        ];
    }

    public function defineProperties()
    {
        return [
            'organization' => [
                'title'       => 'Organization',
                'description' => 'The organization to display alerts for',
                'type'        => 'dropdown'
            ],
            'limit' => [
                'title'             => 'Alert Limit',
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'Must be a number',
                'default'           => '5',
            ],
        ];
    }

    public function getOrganizationOptions()
    {
        return Organization::lists('name', 'id');
    }

    public function onRun()
    {
        $query = Alert::orderBy('published_at', 'desc')->limit($this->property('limit'));

        if ($this->property('organization')) {
            $query->where('organization_id', $this->property('organization'));
        }

        $this->alerts = $query->get();
    }
}
