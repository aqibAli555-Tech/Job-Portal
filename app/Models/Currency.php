<?php

namespace App\Models;


use App\Observers\CurrencyObserver;
use Larapen\Admin\app\Models\Traits\Crud;

class Currency extends BaseModel
{
    use Crud;

    public $incrementing = false;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'currencies';
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'code';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    //public $timestamps = false;
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'name',
        'symbol',
        'html_entities',
        'in_left',
        'decimal_places',
        'decimal_separator',
        'thousand_separator',
    ];

    /**
     * The attributes that should be hidden for arrays
     *
     * @var array
     */
    // protected $hidden = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'created_at'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public static function get_Currency($request)
    {
        $data = Currency::orderBy('id', 'DESC');
        if (!empty($request->get('search'))) {
            $data = $data->where(function ($query) use ($request) {
                $search = $request->get('search');
                $query->Where('name', 'LIKE', "%{$search}%")
                    ->orWhere('code', 'LIKE', "%{$search}%");
            });
        }
        return $data->paginate(15);
    }

    protected static function boot()
    {
        parent::boot();

        Currency::observe(CurrencyObserver::class);
    }

    public function getNameHtml()
    {
        $currentUrl = preg_replace('#/(search)$#', '', url()->current());
        $url = $currentUrl . '/' . $this->getKey() . '/edit';

        $out = '<a href="' . $url . '">' . $this->name . '</a>';

        return $out;
    }

    public function getSymbolHtml()
    {
        $out = html_entity_decode($this->symbol);

        return $out;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function getPositionHtml()
    {
        if ($this->in_left == 1) {
            return '<i class="fa fa-toggle-on" aria-hidden="true"></i>';
        } else {
            return '<i class="fa fa-toggle-off" aria-hidden="true"></i>';
        }
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function countries()
    {
        return $this->hasMany(Country::class, 'currency_code', 'code');
    }

    public function getIdAttribute($value)
    {
        return isset($this->attributes['code']) ? $this->attributes['code'] : $value;
    }

    public function getSymbolAttribute($value)
    {
        if (trim($value ?? '') == '') {
            if (isset($this->attributes['symbol'])) {
                $value = $this->attributes['symbol'];
            }
        }
        if (trim($value ?? '') == '') {
            if (isset($this->attributes['html_entities'])) {
                $value = $this->attributes['html_entities'];
            }
        }
        if (trim($value ?? '') == '') {
            if (isset($this->attributes['code'])) {
                $value = $this->attributes['code'];
            }
        }

        return $value;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
