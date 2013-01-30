<?php

if ( ! defined('EXT')) exit('Invalid file request');


/**
 * Currencee Class
 * @package   Currencee
 * @author    Michael Cohen (contact@pro-image.co.il)
 * @acknowldgement Based on code created by Sue Crocker & Ryan Irelan
 * @license   http://creativecommons.org/licenses/by-sa/3.0/ Attribution-Share Alike 3.0 Unported
 */

class Mc_currencee extends Fieldframe_Fieldtype {

	var $info = array(
			'name'             => 'MC CurrencEE',
			'version'          => '1.0.0',
			'desc'             => 'Select from the 176 active codes of the official ISO 4217 3 digit currency codes and names.',
			'docs_url'         => 'http://www.pro-image.co.il'
			);


		function display_field($field_name, $field_data, $field_settings)
		{
		 	global $DSP;
			$currencies = array(
			'AED'=>"AED - United Arab Emirates dirham",
			'AFN'=>"AFN - Afghani",
			'ALL'=>"ALL - Lek",
			'AMD'=>"AMD - Armenian dram",
			'ANG'=>"ANG - Netherlands Antillean guilder",
			'AOA'=>"AOA - Kwanza",
			'ARS'=>"ARS - Argentine peso",
			'AUD'=>"AUD - Australian dollar",
			'AWG'=>"AWG - Aruban guilder",
			'AZN'=>"AZN - Azerbaijanian manat",
			'BAM'=>"BAM - Convertible marks",
			'BBD'=>"BBD - Barbados dollar",
			'BDT'=>"BDT - Bangladeshi taka",
			'BGN'=>"BGN - Bulgarian lev",
			'BHD'=>"BHD - Bahraini dinar",
			'BIF'=>"BIF - Burundian franc",
			'BMD'=>"BMD - Bermudian dollar (customarily known as Bermuda dollar)",
			'BND'=>"BND - Brunei dollar",
			'BOB'=>"BOB - Boliviano",
			'BOV'=>"BOV - Bolivian Mvdol (funds code)",
			'BRL'=>"BRL - Brazilian real",
			'BSD'=>"BSD - Bahamian dollar",
			'BTN'=>"BTN - Ngultrum",
			'BWP'=>"BWP - Pula",
			'BYR'=>"BYR - Belarusian ruble",
			'BZD'=>"BZD - Belize dollar",
			'CAD'=>"CAD - Canadian dollar",
			'CDF'=>"CDF - Franc Congolais",
			'CHE'=>"CHE - WIR euro (complementary currency)",
			'CHF'=>"CHF - Swiss franc",
			'CHW'=>"CHW - WIR franc (complementary currency)",
			'CLF'=>"CLF - Unidad de Fomento (funds code)",
			'CLP'=>"CLP - Chilean peso",
			'CNY'=>"CNY - Chinese Yuan",
			'COP'=>"COP - Colombian peso",
			'COU'=>"COU - Unidad de Valor Real",
			'CRC'=>"CRC - Costa Rican colon",
			'CUC'=>"CUC - Cuban convertible peso",
			'CUP'=>"CUP - Cuban peso",
			'CVE'=>"CVE - Cape Verde escudo",
			'CZK'=>"CZK - Czech Koruna",
			'DJF'=>"DJF - Djibouti franc",
			'DKK'=>"DKK - Danish krone",
			'DOP'=>"DOP - Dominican peso",
			'DZD'=>"DZD - Algerian dinar",
			'EEK'=>"EEK - Kroon",
			'EGP'=>"EGP - Egyptian pound",
			'ERN'=>"ERN - Nakfa",
			'ETB'=>"ETB - Ethiopian birr",
			'EUR'=>"EUR - euro",
			'FJD'=>"FJD - Fiji dollar",
			'FKP'=>"FKP - Falkland Islands pound",
			'GBP'=>"GBP - Pound sterling",
			'GEL'=>"GEL - Lari",
			'GHS'=>"GHS - Cedi",
			'GIP'=>"GIP - Gibraltar pound",
			'GMD'=>"GMD - Dalasi",
			'GNF'=>"GNF - Guinea franc",
			'GTQ'=>"GTQ - Quetzal",
			'GYD'=>"GYD - Guyana dollar",
			'HKD'=>"HKD - Hong Kong dollar",
			'HNL'=>"HNL - Lempira",
			'HRK'=>"HRK - Croatian kuna",
			'HTG'=>"HTG - Haiti gourde",
			'HUF'=>"HUF - Forint",
			'IDR'=>"IDR - Rupiah",
			'ILS'=>"ILS - Israeli new sheqel",
			'INR'=>"INR - Indian rupee",
			'IQD'=>"IQD - Iraqi dinar",
			'IRR'=>"IRR - Iranian rial",
			'ISK'=>"ISK - Iceland krona",
			'JMD'=>"JMD - Jamaican dollar",
			'JOD'=>"JOD - Jordanian dinar",
			'JPY'=>"JPY - Japanese yen",
			'KES'=>"KES - Kenyan shilling",
			'KGS'=>"KGS - Som",
			'KHR'=>"KHR - Riel",
			'KMF'=>"KMF - Comoro franc",
			'KPW'=>"KPW - North Korean won",
			'KRW'=>"KRW - South Korean won",
			'KWD'=>"KWD - Kuwaiti dinar",
			'KYD'=>"KYD - Cayman Islands dollar",
			'KZT'=>"KZT - Tenge",
			'LAK'=>"LAK - Kip",
			'LBP'=>"LBP - Lebanese pound",
			'LKR'=>"LKR - Sri Lanka rupee",
			'LRD'=>"LRD - Liberian dollar",
			'LSL'=>"LSL - Lesotho loti",
			'LTL'=>"LTL - Lithuanian litas",
			'LVL'=>"LVL - Latvian lats",
			'LYD'=>"LYD - Libyan dinar",
			'MAD'=>"MAD - Moroccan dirham",
			'MDL'=>"MDL - Moldovan leu",
			'MGA'=>"MGA - Malagasy ariary",
			'MKD'=>"MKD - Denar",
			'MMK'=>"MMK - Kyat",
			'MNT'=>"MNT - Tugrik",
			'MOP'=>"MOP - Pataca",
			'MRO'=>"MRO - Ouguiya",
			'MUR'=>"MUR - Mauritius rupee",
			'MVR'=>"MVR - Rufiyaa",
			'MWK'=>"MWK - Kwacha",
			'MXN'=>"MXN - Mexican peso",
			'MXV'=>"MXV - Mexican Unidad de Inversion (UDI) (funds code)",
			'MYR'=>"MYR - Malaysian ringgit",
			'MZN'=>"MZN - Metical",
			'NAD'=>"NAD - Namibian dollar",
			'NGN'=>"NGN - Naira",
			'NIO'=>"NIO - Cordoba oro",
			'NOK'=>"NOK - Norwegian krone",
			'NPR'=>"NPR - Nepalese rupee",
			'NZD'=>"NZD - New Zealand dollar",
			'OMR'=>"OMR - Rial Omani",
			'PAB'=>"PAB - Balboa",
			'PEN'=>"PEN - Nuevo sol",
			'PGK'=>"PGK - Kina",
			'PHP'=>"PHP - Philippine peso",
			'PKR'=>"PKR - Pakistan rupee",
			'PLN'=>"PLN - Złoty",
			'PYG'=>"PYG - Guarani",
			'QAR'=>"QAR - Qatari rial",
			'RON'=>"RON - Romanian new leu",
			'RSD'=>"RSD - Serbian dinar",
			'RUB'=>"RUB - Russian rouble",
			'RWF'=>"RWF - Rwanda franc",
			'SAR'=>"SAR - Saudi riyal",
			'SBD'=>"SBD - Solomon Islands dollar",
			'SCR'=>"SCR - Seychelles rupee",
			'SDG'=>"SDG - Sudanese pound",
			'SEK'=>"SEK - Swedish krona/kronor",
			'SGD'=>"SGD - Singapore dollar",
			'SHP'=>"SHP - Saint Helena pound",
			'SLL'=>"SLL - Leone",
			'SOS'=>"SOS - Somali shilling",
			'SRD'=>"SRD - Surinam dollar",
			'STD'=>"STD - Dobra",
			'SYP'=>"SYP - Syrian pound",
			'SZL'=>"SZL - Lilangeni",
			'THB'=>"THB - Baht",
			'TJS'=>"TJS - Somoni",
			'TMT'=>"TMT - Manat",
			'TND'=>"TND - Tunisian dinar",
			'TOP'=>"TOP - Pa'anga",
			'TRY'=>"TRY - Turkish lira",
			'TTD'=>"TTD - Trinidad and Tobago dollar",
			'TWD'=>"TWD - New Taiwan dollar",
			'TZS'=>"TZS - Tanzanian shilling",
			'UAH'=>"UAH - Hryvnia",
			'UGX'=>"UGX - Uganda shilling",
			'USD'=>"USD - US dollar",
			'UYU'=>"UYU - Peso Uruguayo",
			'UZS'=>"UZS - Uzbekistan som",
			'VEF'=>"VEF - Venezuelan bolívar fuerte",
			'VND'=>"VND - Vietnamese đồng",
			'VUV'=>"VUV - Vatu",
			'WST'=>"WST - Samoan tala",
			'XAF'=>"XAF - CFA franc BEAC",
			'XAG'=>"XAG - Silver (one troy ounce)",
			'XAU'=>"XAU - Gold (one troy ounce)",
			'XBA'=>"XBA - European Composite Unit (EURCO) (bond market unit)",
			'XBB'=>"XBB - European Monetary Unit (E.M.U.-6) (bond market unit)",
			'XBC'=>"XBC - European Unit of Account 9 (E.U.A.-9) (bond market unit)",
			'XBD'=>"XBD - European Unit of Account 17 (E.U.A.-17) (bond market unit)",
			'XCD'=>"XCD - East Caribbean dollar",
			'XDR'=>"XDR - Special Drawing Rights",
			'XFU'=>"XFU - UIC franc (special settlement currency)",
			'XOF'=>"XOF - CFA Franc BCEAO",
			'XPD'=>"XPD - Palladium (one troy ounce)",
			'XPF'=>"XPF - CFP franc",
			'XPT'=>"XPT - Platinum (one troy ounce)",
			'XTS'=>"XTS - Code reserved for testing purposes",
			'XXX'=>"XXX - No currency",
			'YER'=>"YER - Yemeni rial",
			'ZAR'=>"ZAR - South African rand",
			'ZMK'=>"ZMK - Kwacha",
			'ZWL'=>"ZWL - Zimbabwe dollar");

			$r = $DSP->input_select_header($field_name);
			$r .= $DSP->input_select_option('', '--');
			foreach ($currencies as $key => $value):
				$r .= $DSP->input_select_option($key, $value, $field_data == $key);
			endforeach;
			$r .= $DSP->input_select_footer();
			return $r;
		}

		/**
		 * Display Cell
		 *
		 * @param  string  $cell_name      The cell's name
		 * @param  mixed   $cell_data      The cell's current value
		 * @param  array   $cell_settings  The cell's settings
		 * @return string  The cell's HTML
		 * @author Brandon Kelly <me@brandon-kelly.com>
		 */
		function display_cell($cell_name, $cell_data, $cell_settings)
		{
			return $this->display_field($cell_name, $cell_data, $cell_settings);
		}
}


?>