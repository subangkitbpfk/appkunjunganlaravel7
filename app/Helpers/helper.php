<?php
namespace App\Helpers;
use Illuminate\Support\Facades\DB;

class Helper
{
    public static function shortName($name)
    {
        return "" . explode(' ', $name)[0] . " " . (count(explode(' ', $name)) > 1 ? substr(explode(' ', $name)[1], 0, 1) : '');
    }

    public static function romanNumeral($input_arabic_numeral = '')
    {

        if ($input_arabic_numeral == '') {
            $input_arabic_numeral = date("Y");
        } // DEFAULT OUTPUT: THIS YEAR

        if (!ereg('[0-9]', $arabic_numeral_text)) {
            return false;
        }

        if ($arabic_numeral > 4999) {
            return false;
        }

        if ($arabic_numeral < 1) {
            return false;
        }

        if ($arabic_numeral_length > 4) {
            return false;
        }

        $roman_numeral_units = $roman_numeral_tens = $roman_numeral_hundreds = $roman_numeral_thousands = array();
        $roman_numeral_units[0] = $roman_numeral_tens[0] = $roman_numeral_hundreds[0] = $roman_numeral_thousands[0] = ''; // NO ZEROS IN ROMAN NUMERALS

        $roman_numeral_units[1] = 'I';
        $roman_numeral_units[2] = 'II';
        $roman_numeral_units[3] = 'III';
        $roman_numeral_units[4] = 'IV';
        $roman_numeral_units[5] = 'V';
        $roman_numeral_units[6] = 'VI';
        $roman_numeral_units[7] = 'VII';
        $roman_numeral_units[8] = 'VIII';
        $roman_numeral_units[9] = 'IX';

        $roman_numeral_tens[1] = 'X';
        $roman_numeral_tens[2] = 'XX';
        $roman_numeral_tens[3] = 'XXX';
        $roman_numeral_tens[4] = 'XL';
        $roman_numeral_tens[5] = 'L';
        $roman_numeral_tens[6] = 'LX';
        $roman_numeral_tens[7] = 'LXX';
        $roman_numeral_tens[8] = 'LXXX';
        $roman_numeral_tens[9] = 'XC';

        $roman_numeral_hundreds[1] = 'C';
        $roman_numeral_hundreds[2] = 'CC';
        $roman_numeral_hundreds[3] = 'CCC';
        $roman_numeral_hundreds[4] = 'CD';
        $roman_numeral_hundreds[5] = 'D';
        $roman_numeral_hundreds[6] = 'DC';
        $roman_numeral_hundreds[7] = 'DCC';
        $roman_numeral_hundreds[8] = 'DCCC';
        $roman_numeral_hundreds[9] = 'CM';

        $roman_numeral_thousands[1] = 'M';
        $roman_numeral_thousands[2] = 'MM';
        $roman_numeral_thousands[3] = 'MMM';
        $roman_numeral_thousands[4] = 'MMMM';

        if ($arabic_numeral_length == 3) {
            $arabic_numeral_text = "0" . $arabic_numeral_text;
        }
        if ($arabic_numeral_length == 2) {
            $arabic_numeral_text = "00" . $arabic_numeral_text;
        }
        if ($arabic_numeral_length == 1) {
            $arabic_numeral_text = "000" . $arabic_numeral_text;
        }

        $anu = substr($arabic_numeral_text, 3, 1);
        $anx = substr($arabic_numeral_text, 2, 1);
        $anc = substr($arabic_numeral_text, 1, 1);
        $anm = substr($arabic_numeral_text, 0, 1);

        $roman_numeral_text = $roman_numeral_thousands[$anm] . $roman_numeral_hundreds[$anc] . $roman_numeral_tens[$anx] . $roman_numeral_units[$anu];
        return ($roman_numeral_text);
    }

    public static function shorten($fullname)
    {
        $full = $fullname;
        $fixedName = '';

        $nameArr = explode(',', $fullname);
        $name = $nameArr[0];
        $title = '';
        if (count($nameArr) > 1) {
            $title = $nameArr[1];
        }

        $nameArr = explode(' ', $name);
        if (count($nameArr) > 2) {
            foreach ($nameArr as $i => $val) {
                if ($i > 1) {
                    $nameArr[$i] = strtoupper(substr($nameArr[$i], 0, 1)) . '.';
                }
            }
        }

        $name = '';
        foreach ($nameArr as $val) {
            $name = $name . ' ' . $val;
        }

        if (strlen($title) > 0) {
            $name = $name . ',' . $title;
        }
        $fixedName = $name;
        //return $fixedName;
        return $full;
    }
}

if (!function_exists('romanNumeral')) {
    function romanNumeral($integer, $upcase = true)
    {
        $table = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
        $return = '';
        while ($integer > 0) {
            foreach ($table as $rom => $arb) {
                if ($integer >= $arb) {
                    $integer -= $arb;
                    $return .= $rom;
                    break;
                }
            }
        }

        return $return;
    }
}

function monthIdn($month)
{

    switch ($month) {
        case '1':
            return "Januari";
        case '2':
            return "Februari";
        case '3':
            return "Maret";
        case '4':
            return "April";
        case '5':
            return "Mei";
        case '6':
            return "Juni";
        case '7':
            return "Juli";
        case '8':
            return "Agustus";
        case '9':
            return "September";
        case '10':
            return "Oktober";
        case '11':
            return "November";
        case '12':
            return "Desember";
    }
}

function dateIdn($date)
{
    $date = explode(' ', $date);
    if (count($date) < 3) {
        $date = explode('-', $date[0]);
        if (count($date < 3)) {
            $date = explode('-', '2016-01-01');
        } else {

        }
    }
    $month = monthIdn($date[1]);
    $final = $date[2] . " " . $month . " " . $date[0];
    return $final;
}

function dayIdn($date) /*date('d-m-Y')*/
{
    $hari = array("Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jum'at", "Sabtu");
    $return = "";
    $return = date('w', strtotime($date));
    return @$hari[$return];
}

function dateIdnFromTimestamp($timestamp)
{
    $date = date('Y m d', strtotime($timestamp));
    $date = explode(' ', $date);
    if (count($date) < 3) {
        $date = explode('-', $date[0]);
        if (count($date < 3)) {
            $date = explode('-', '2016-01-01');
        } else {

        }
    }
    $month = monthIdn($date[1]);
    $final = $date[2] . " " . $month . " " . $date[0];
    return $final;
}

function reverseDateIdnToDB($dateIdn)
{

}

function oldOrDbData($old, $db)
{
    return $old != null ? $old : $db;
}

function Terbilang($nominal){
        $abil = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
        if ($nominal < 12) {
            return " " . $abil[$nominal];
        } else if ($nominal < 20) {
            return Terbilang($nominal - 10) . "belas";
        } else if ($nominal < 100) {
            return Terbilang($nominal / 10) . " puluh" . Terbilang($nominal % 10);
        } else if ($nominal < 200) {
            return " seratus" . Terbilang($nominal - 100);
        } else if ($nominal < 1000) {
            return Terbilang($nominal / 100) . " ratus" . Terbilang($nominal % 100);
        } else if ($nominal < 2000) {
            return " seribu" . Terbilang($nominal - 1000);
        } else if ($nominal < 1000000) {
            return Terbilang($nominal / 1000) . " ribu" . Terbilang($nominal % 1000);
        } else if ($nominal < 1000000000) {
            return Terbilang($nominal / 1000000) . " juta" . Terbilang($nominal % 1000000);
        }
    }