<?php

namespace App;

/**
 * App\Config
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property string $value
 * @method static \Illuminate\Database\Eloquent\Builder|Config newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Config newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Config query()
 * @method static \Illuminate\Database\Eloquent\Builder|Config whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Config whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Config whereValue($value)
 */
class Config extends CacheModel
{
    public $timestamps = false;
    protected $table = 'el_config';
    protected $fillable = [
        'name',
        'value',
        'object',
    ];

    /**
     * Get all config name.
     * @return array
     * */
    public static function configNames()
    {
        return [
            'ldap_host',
            'ldap_dn',
            'ldap_usr_dom',
            'ldap_version',
            'ldap_start_tls',
            'ldap_contexts',
            'email_driver',
            'email_host',
            'email_port',
            'email_user',
            'email_password',
            'email_encryption',
            'email_from_name',
            'email_address',
        ];
    }

    /**
     * Get config value
     * @param $name
     * @param $default
     * @return string
     * */
    public static function getConfig($name, $default = null)
    {
        if ($config = Config::whereName($name)->first(['value'])) {
            return $config->value;
        }

        return $default;
    }

    /**
     * Set config value
     * @param string $name
     * @param string $value
     * @return string
     * */
    public static function setConfig($name, $value)
    {
        return Config::whereName($name)
            ->updateOrCreate([
                'name' => $name
            ], [
                'value' => $value
            ]);
    }

    public static function getAttributeName()
    {
        return [
            'name' => 'Tên',
            'value' => 'Giá trị',
        ];
    }

    public static function getLogo($name = 'logo')
    {
        return Config::getConfig($name);
    }

    public static function getLogoOutside($name = 'logo_outside')
    {
        return Config::getConfig($name);
    }

    public static function getFavicon()
    {
        return Config::getConfig('favicon');
    }

    public static function getConfigEmail()
    {
        $data =  Config::where('name', 'like', 'email%')->get();
        return $data->isNotEmpty() ? $data->pluck('value', 'name') : null;
    }
}
