<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use SoapVar;
use SoapClient;
use SoapHeader;
use SoapFault;

/**
 * App\WebService
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WebService newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WebService newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WebService query()
 * @mixin \Eloquent
 * @property int $id
 * @property string|null $url
 * @property string|null $key
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WebService whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WebService whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WebService whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WebService whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WebService whereUrl($value)
 */
class WebService extends Model
{
    protected $table = 'el_webservice';
    protected $primaryKey = 'id';
    protected $fillable = [
        'url',
        'key',
    ];

    public static function getAttributeName() {
        return [
            'url' => 'URL',
            'key' => 'Key',
        ];
    }

    public static function loginWebservice($username, $password)
    {
        $webservice = WebService::orderBy('id', 'DESC')->first();
        try {
            $wsdl = $webservice ? $webservice->url : 'http://192.168.200.20:8015/AuthenticatePortal/AuthenPortal.asmx?wsdl';
            $soapClient = new SoapClient($wsdl);
            $key = $webservice ? $webservice->key : 'eijaE0ePClM34UnXLxFikrk21fXJ4iKLOVmz+pO9rxw=';
            $data = 'Authenticate';
            $elementName = 'PortalSecurityHeader';
            $namespace = "http://tempuri.org/";
            $authenticationHeader = array
            (
                'AccessKey' => $key,
                'Action' => $data,
                'Signature' => base64_encode(hash_hmac('sha1', $key, $data, true)),
                'Timestamp' => null
            );
            $authvalues = new SoapVar($authenticationHeader, SOAP_ENC_OBJECT, $elementName, $namespace);
            $soapHeader = new SoapHeader($namespace, $elementName, $authvalues, false);
            $soapClient->__setSoapHeaders(array($soapHeader));

            $users = array (
                'userName' => $username,
                'password' => $password,
            );
            $result = $soapClient->Authenticate($users)->AuthenticateResult;
            if($result=='true')
            {
                $usernameinfo = array('username'=>$username);
                $resultuser = $soapClient->GetUserByUserName_V2($usernameinfo)->GetUserByUserName_V2Result;
                $code = (explode('^',$resultuser)[20]);
                return $code;
            }

        } catch (SoapFault $fault) {
            if ($soapClient != null) {
                $soapClient = null;
            }

        }

        return false;
    }

    public static function decrypt($data, $key)
    {
        $key = hash('md5', mb_convert_encoding($key,'UTF-32LE'),true);
        $key .= substr($key, 0, 8);
        $data = base64_decode($data);
        $data = mcrypt_decrypt('tripledes', $key, $data, 'ecb');
        $block = mcrypt_get_block_size('tripledes', 'ecb');
        $len = strlen($data);
        $pad = ord($data[$len-1]);
        return trim(substr($data, 0, strlen($data) - $pad));
    }

    public static function getUserName($username, $key)
    {
        $username = self::decrypt($username, $key);
        $u_k=str_split($username);
        $u='';
        for($i=0;$i<count($u_k);$i++)
        {
            if(ord($u_k[$i])!=0)
            {
                $u.=$u_k[$i];
            }
        }
        $u = trim(substr($u,1,strlen($u)));
        return $u;
    }

    public static function getPassword($password, $key)
    {
        $password = self::decrypt($password, $key);
        $p_k = str_split($password);
        $p = '';
        for($i=0;$i<count($p_k);$i++)
        {
            if(ord($p_k[$i])!=0)
            {
                $p.=$p_k[$i];
            }
        }
        $p= trim($p);
        return $p;
    }

    public static function maHoa($tzttt)
    {
        $key='KLBAS';
        $tzttt = self::decrypt($tzttt, $key);
        $p_k = str_split($tzttt);
        $p='';
        for($i=0;$i<count($p_k);$i++)
        {
            if(ord($p_k[$i])!=0)
            {
                $p.=$p_k[$i];
            }
        }
        $p= trim($p);
        return $p;
    }
}
