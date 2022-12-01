<?php
/**
 * Model genrated using LaraAdmin
 * Help: http://laraadmin.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use SoftDeletes;
	
	protected $table = 'countries';
	
	protected $hidden = [
        
    ];

	protected $guarded = [];

	protected $dates = ['deleted_at'];

	const CACHE_KEY = 'cache_all_countries';
	const CACHE_COUNTRIES_KEY = 'cache_countries';
	const CACHE_COUNTRIES_WITH_ISO_KEY = 'cache_countries_with_iso';

    const PHONE_CODE_LIST =[
        '93' => 'AF',
        '355' => 'AL',
        '213' => 'DZ',
        '1684' => 'AS',
        '376' => 'AD',
        '244' => 'AO',
        '1264' => 'AI',
        '1268' => 'AG',
        '54' => 'AR',
        '374' => 'AM',
        '297' => 'AW',
        '61' => 'AU',
        '43' => 'AT',
        '994' => 'AZ',
        '1242' => 'BS',
        '973' => 'BH',
        '880' => 'BD',
        '1246' => 'BB',
        '375' => 'BY',
        '32' => 'BE',
        '501' => 'BZ',
        '229' => 'BJ',
        '1441' => 'BM',
        '975' => 'BT',
        '591' => 'BO',
        '387' => 'BA',
        '267' => 'BW',
        '55' => 'BR',
        '246' => 'IO',
        '673' => 'BN',
        '359' => 'BG',
        '226' => 'BF',
        '257' => 'BI',
        '855' => 'KH',
        '237' => 'CM',
        '238' => 'CV',
        '1345' => 'KY',
        '236' => 'CF',
        '235' => 'TD',
        '56' => 'CL',
        '86' => 'CN',
        '57' => 'CO',
        '269' => 'KM',
        '242' => 'CG',
        '682' => 'CK',
        '506' => 'CR',
        '225' => 'CI',
        '385' => 'HR',
        '53' => 'CU',
        '357' => 'CY',
        '420' => 'CZ',
        '45' => 'DK',
        '253' => 'DJ',
        '1767' => 'DM',
        '1809' => 'DO',
        '593' => 'EC',
        '20' => 'EG',
        '503' => 'SV',
        '240' => 'GQ',
        '291' => 'ER',
        '372' => 'EE',
        '251' => 'ET',
        '298' => 'FO',
        '679' => 'FJ',
        '358' => 'FI',
        '33' => 'FR',
        '594' => 'GF',
        '689' => 'PF',
        '241' => 'GA',
        '220' => 'GM',
        '995' => 'GE',
        '49' => 'DE',
        '233' => 'GH',
        '350' => 'GI',
        '30' => 'GR',
        '299' => 'GL',
        '1473' => 'GD',
        '1671' => 'GU',
        '502' => 'GT',
        '224' => 'GN',
        '245' => 'GW',
        '592' => 'GY',
        '509' => 'HT',
        '0' => 'HM',
        '504' => 'HN',
        '852' => 'HK',
        '36' => 'HU',
        '354' => 'IS',
        '91' => 'IN',
        '62' => 'ID',
        '98' => 'IR',
        '964' => 'IQ',
        '353' => 'IE',
        '972' => 'IL',
        '39' => 'IT',
        '1876' => 'JM',
        '81' => 'JP',
        '962' => 'JO',
        '7' => 'KZ',
        '254' => 'KE',
        '686' => 'KI',
        '850' => 'KP',
        '82' => 'KR',
        '965' => 'KW',
        '996' => 'KG',
        '856' => 'LA',
        '371' => 'LV',
        '961' => 'LB',
        '266' => 'LS',
        '231' => 'LR',
        '218' => 'LY',
        '423' => 'LI',
        '370' => 'LT',
        '352' => 'LU',
        '853' => 'MO',
        '389' => 'MK',
        '261' => 'MG',
        '265' => 'MW',
        '60' => 'MY',
        '960' => 'MV',
        '223' => 'ML',
        '356' => 'MT',
        '692' => 'MH',
        '596' => 'MQ',
        '222' => 'MR',
        '230' => 'MU',
        '52' => 'MX',
        '691' => 'FM',
        '373' => 'MD',
        '377' => 'MC',
        '976' => 'MN',
        '382' => 'ME',
        '1664' => 'MS',
        '212' => 'MA',
        '258' => 'MZ',
        '95' => 'MM',
        '264' => 'NA',
        '674' => 'NR',
        '977' => 'NP',
        '31' => 'NL',
        '599' => 'AN',
        '687' => 'NC',
        '64' => 'NZ',
        '505' => 'NI',
        '227' => 'NE',
        '234' => 'NG',
        '683' => 'NU',
        '672' => 'NF',
        '1670' => 'MP',
        '47' => 'NO',
        '968' => 'OM',
        '92' => 'PK',
        '680' => 'PW',
        '970' => 'PS',
        '507' => 'PA',
        '675' => 'PG',
        '595' => 'PY',
        '51' => 'PE',
        '63' => 'PH',
        '48' => 'PL',
        '351' => 'PT',
        '1787' => 'PR',
        '974' => 'QA',
        '262' => 'RE',
        '40' => 'RO',
        '70' => 'RU',
        '250' => 'RW',
        '290' => 'SH',
        '1869' => 'KN',
        '1758' => 'LC',
        '508' => 'PM',
        '1784' => 'VC',
        '684' => 'WS',
        '378' => 'SM',
        '239' => 'ST',
        '966' => 'SA',
        '221' => 'SN',
        '381' => 'RS',
        '248' => 'SC',
        '232' => 'SL',
        '65' => 'SG',
        '421' => 'SK',
        '386' => 'SI',
        '677' => 'SB',
        '252' => 'SO',
        '27' => 'ZA',
        '500' => 'GS',
        '211' => 'SS',
        '34' => 'ES',
        '94' => 'LK',
        '249' => 'SD',
        '597' => 'SR',
        '268' => 'SZ',
        '46' => 'SE',
        '41' => 'CH',
        '963' => 'SY',
        '886' => 'TW',
        '992' => 'TJ',
        '255' => 'TZ',
        '66' => 'TH',
        '670' => 'TL',
        '228' => 'TG',
        '690' => 'TK',
        '676' => 'TO',
        '1868' => 'TT',
        '216' => 'TN',
        '90' => 'TR',
        '7370' => 'TM',
        '1649' => 'TC',
        '688' => 'TV',
        '256' => 'UG',
        '380' => 'UA',
        '971' => 'AE',
        '44' => 'GB',
        '1' => 'US',
        '598' => 'UY',
        '998' => 'UZ',
        '678' => 'VU',
        '58' => 'VE',
        '84' => 'VN',
        '1284' => 'VG',
        '1340' => 'VI',
        '681' => 'WF',
        '967' => 'YE',
        '260' => 'ZM',
        '263' => 'ZW',
    ];

	public static function getAllCountries() {
        return \Cache::get(self::CACHE_KEY, function() {
            return self::select('iso', 'name', 'flag')->get()->toArray();
        });
    }

    public static function getCountries(){
        return \Cache::get(self::CACHE_COUNTRIES_KEY, function() {
            return self::select('name')->pluck('name', 'name');
        });
    }

    public static function getCountriesWithIso() {
        return \Cache::get(self::CACHE_COUNTRIES_WITH_ISO_KEY, function() {
            return self::select('iso','id')->pluck('id', 'iso')->toArray();
        });
    }
}
