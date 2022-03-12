<?php namespace LukeTowers\FlashAlertNet\Models;

use Model;

/**
 * Organization Model
 */
class Organization extends Model
{
    use \Winter\Storm\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'luketowers_flashalertnet_organizations';

    /**
     * @var boolean Toggle the use of timestamps (created_at, updated_at)
     */
    public $timestamps = false;

    /**
     * @var array Validation rules to apply
     */
    public $rules = [
        'name'       => 'required',
        'api_org_id' => 'required',
    ];

    /**
     * @var array Relations
     */
    public $hasMany = [
        'alerts' => [Alert::class],
    ];
}
