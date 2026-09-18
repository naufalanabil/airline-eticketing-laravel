<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Airline extends Model
{
    protected $fillable = ['code', 'name', 'logo'];

    public function logoUrl(): string
    {
        $domain = $this->logoDomain();

        return 'https://www.google.com/s2/favicons?domain='.$domain.'&sz=128';
    }

    public function fallbackLogoUrl(): string
    {
        return 'https://icon.horse/icon/'.$this->logoDomain();
    }

    private function logoDomain(): string
    {
        $domains = [
            'GA' => 'garuda-indonesia.com',
            'SQ' => 'singaporeair.com',
            'AK' => 'airasia.com',
            'QZ' => 'airasia.com',
            'JL' => 'jal.co.jp',
            'MH' => 'malaysiaairlines.com',
            'EK' => 'emirates.com',
            'QR' => 'qatarairways.com',
            'KE' => 'koreanair.com',
            'QF' => 'qantas.com',
            'TG' => 'thaiairways.com',
            'VN' => 'vietnamairlines.com',
            'PR' => 'philippineairlines.com',
            'SL' => 'lionairthai.com',
            'TR' => 'flyscoot.com',
            'NH' => 'ana.co.jp',
            'OZ' => 'flyasiana.com',
            'CI' => 'china-airlines.com',
            'BR' => 'evaair.com',
            'CX' => 'cathaypacific.com',
            'CZ' => 'csair.com',
            'MU' => 'ceair.com',
            'CA' => 'airchina.com.cn',
            'EY' => 'etihad.com',
            'SV' => 'saudia.com',
            'WY' => 'omanair.com',
            'TK' => 'turkishairlines.com',
            'LH' => 'lufthansa.com',
            'AF' => 'airfrance.com',
            'BA' => 'britishairways.com',
            'KL' => 'klm.com',
            'VA' => 'virginaustralia.com',
            'JQ' => 'jetstar.com',
            'NZ' => 'airnewzealand.com',
            'UA' => 'united.com',
            'DL' => 'delta.com',
            'AA' => 'aa.com',
            'AC' => 'aircanada.com',
        ];

        return $domains[$this->code] ?? 'google.com';
    }
}
