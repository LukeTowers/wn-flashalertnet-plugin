<?php namespace LukeTowers\FlashAlertNet;

use Backend;
use System\Classes\PluginBase;

/**
 * FlashAlert.net Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'FlashAlert.Net',
            'description' => 'Pulls data from FlashAlert.net',
            'author'      => 'Luke Towers',
            'icon'        => 'icon-code',
        ];
    }

    /**
     * Register method, called when the plugin is registered
     *
     * @return array
     */
    public function register()
    {
        $this->registerConsoleCommand('flashalertnet.sync', 'LukeTowers\FlashAlertNet\Console\Sync');
    }

    /**
     * Registers any custom scheduled tasks for the plugin.
     *
     * @return void
     */
    public function registerSchedule($schedule)
    {
        $schedule->command('flashalertnet:sync')->daily();
    }

    /**
     * Register the navigation items provided by this plugin
     *
     * @return array
     */
    public function registerNavigation()
    {
        return [
            'main-flashalert' => [
                'label'       => 'Alerts',
                'url'         => Backend::url('luketowers/flashalertnet/alerts'),
                'icon'        => 'icon-exclamation-triangle',
                'permissions' => ['luketowers.flashalertnet.*'],
                'order'       => 500,

                'sideMenu' => [
                    'side-alerts' => [
                        'label'       => 'Alerts',
                        'icon'        => 'icon-exclamation-triangle',
                        'url'         => Backend::url('luketowers/flashalertnet/alerts'),
                        'permissions' => ['luketowers.flashalertnet.*'],
                    ],
                    'side-organizations' => [
                        'label'       => 'Organizations',
                        'icon'        => 'icon-list',
                        'url'         => Backend::url('luketowers/flashalertnet/organizations'),
                        'permissions' => ['luketowers.flashalertnet.*'],
                    ],
                ]
            ]
        ];
    }

    /**
     * Register the permissions utilized by this plugin
     *
     * @return array
     */
    public function registerPermissions()
    {
        return [
            'luketowers.flashalertnet.alerts.manage' => ['tab' => 'FlashAlert.net', 'label' => 'Manage alerts'],
            'luketowers.flashalertnet.organizations.manage' => ['tab' => 'FlashAlert.net', 'label' => 'Manage organizations']
        ];
    }

    /**
     * @return array
     */
    public function registerComponents()
    {
        return [
            \LukeTowers\FlashAlertNet\Components\Alerts::class => 'alerts',
        ];
    }

    /**
     * @return array
     */
    public function registerPageSnippets()
    {
        return [
            \LukeTowers\FlashAlertNet\Components\Alerts::class => 'alerts',
        ];
    }
}