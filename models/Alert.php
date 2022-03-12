<?php namespace LukeTowers\FlashAlertNet\Models;

use Model;
use System\Models\File;

/**
 * Alert Model
 */
class Alert extends Model
{
    use \Winter\Storm\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'luketowers_flashalertnet_alerts';

    protected $fillable = ['*'];

    /**
     * @var array Attributes to be cast to dates
     */
    public $dates = ['created_at', 'updated_at', 'published_at'];

    /**
     * @var array Validation rules to apply
     */
    public $rules = [
        'guid'    => 'required',
        'content' => 'required',
    ];

    /**
     * @var array Relations
     */
    public $belongsTo = [
        'organization' => [Organization::class],
    ];
    public $attachOne = [
        'featured_image' => [File::class]
    ];
}
