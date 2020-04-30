<?php
/**
 * @author    ThemePunch <info@themepunch.com>
 * @link      https://www.themepunch.com/
 * @copyright 2019 ThemePunch
 * @since 	  5.1.0
 * @lastfetch 26.07.2019
 */
 
if(!defined('ABSPATH')) exit();

/**
*** CREATED WITH SCRIPT SNIPPET AND DATA TAKEN FROM https://www.googleapis.com/webfonts/v1/webfonts?sort=popularity&fields=items(family%2Csubsets%2Cvariants%2Ccategory)&key={YOUR_API_KEY}

$list_raw = file_get_contents('https://www.googleapis.com/webfonts/v1/webfonts?sort=popularity&fields=items(family%2Csubsets%2Cvariants%2Ccategory)&key={YOUR_API_KEY}');

$list = json_decode($list_raw, true);
$list = $list['items'];

echo '<pre>';
foreach($list as $l){
	echo "'".$l['family'] ."' => array("."\n";
	echo "'variants' => array(";
	foreach($l['variants'] as $k => $v){
		if($k > 0) echo ", ";
		if($v == 'regular') $v = '400';
		echo "'".$v."'";
	}
	echo "),\n";
	echo "'subsets' => array(";
	foreach($l['subsets'] as $k => $v){
		if($k > 0) echo ", ";
		echo "'".$v."'";
	}
	echo "),\n";
	echo "'category' => '". $l['category'] ."'";
	echo "\n),\n";
}
echo '</pre>';
**/

$googlefonts = array(
'Roboto' => array(
'variants' => array('100', '100italic', '300', '300italic', '400', 'italic', '500', '500italic', '700', '700italic', '900', '900italic'),
'subsets' => array('cyrillic', 'greek-ext', 'vietnamese', 'cyrillic-ext', 'latin-ext', 'greek', 'latin'),
'category' => 'sans-serif'
),
'Open Sans' => array(
'variants' => array('300', '300italic', '400', 'italic', '600', '600italic', '700', '700italic', '800', '800italic'),
'subsets' => array('cyrillic', 'greek-ext', 'vietnamese', 'cyrillic-ext', 'latin-ext', 'greek', 'latin'),
'category' => 'sans-serif'
),
'Lato' => array(
'variants' => array('100', '100italic', '300', '300italic', '400', 'italic', '700', '700italic', '900', '900italic'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Montserrat' => array(
'variants' => array('100', '100italic', '200', '200italic', '300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic', '800', '800italic', '900', '900italic'),
'subsets' => array('cyrillic', 'vietnamese', 'cyrillic-ext', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Roboto Condensed' => array(
'variants' => array('300', '300italic', '400', 'italic', '700', '700italic'),
'subsets' => array('cyrillic', 'greek-ext', 'vietnamese', 'cyrillic-ext', 'latin-ext', 'greek', 'latin'),
'category' => 'sans-serif'
),
'Source Sans Pro' => array(
'variants' => array('200', '200italic', '300', '300italic', '400', 'italic', '600', '600italic', '700', '700italic', '900', '900italic'),
'subsets' => array('cyrillic', 'greek-ext', 'vietnamese', 'cyrillic-ext', 'latin-ext', 'greek', 'latin'),
'category' => 'sans-serif'
),
'Oswald' => array(
'variants' => array('200', '300', '400', '500', '600', '700'),
'subsets' => array('cyrillic', 'vietnamese', 'cyrillic-ext', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Raleway' => array(
'variants' => array('100', '100italic', '200', '200italic', '300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic', '800', '800italic', '900', '900italic'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Merriweather' => array(
'variants' => array('300', '300italic', '400', 'italic', '700', '700italic', '900', '900italic'),
'subsets' => array('cyrillic', 'vietnamese', 'cyrillic-ext', 'latin-ext', 'latin'),
'category' => 'serif'
),
'Roboto Mono' => array(
'variants' => array('100', '100italic', '300', '300italic', '400', 'italic', '500', '500italic', '700', '700italic'),
'subsets' => array('cyrillic', 'greek-ext', 'vietnamese', 'cyrillic-ext', 'latin-ext', 'greek', 'latin'),
'category' => 'monospace'
),
'Roboto Slab' => array(
'variants' => array('100', '300', '400', '700'),
'subsets' => array('cyrillic', 'greek-ext', 'vietnamese', 'cyrillic-ext', 'latin-ext', 'greek', 'latin'),
'category' => 'serif'
),
'PT Sans' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('cyrillic', 'cyrillic-ext', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Poppins' => array(
'variants' => array('100', '100italic', '200', '200italic', '300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic', '800', '800italic', '900', '900italic'),
'subsets' => array('devanagari', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Noto Sans' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('cyrillic', 'devanagari', 'greek-ext', 'vietnamese', 'cyrillic-ext', 'latin-ext', 'greek', 'latin'),
'category' => 'sans-serif'
),
'Ubuntu' => array(
'variants' => array('300', '300italic', '400', 'italic', '500', '500italic', '700', '700italic'),
'subsets' => array('cyrillic', 'greek-ext', 'cyrillic-ext', 'latin-ext', 'greek', 'latin'),
'category' => 'sans-serif'
),
'Slabo 27px' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'serif'
),
'Playfair Display' => array(
'variants' => array('400', 'italic', '700', '700italic', '900', '900italic'),
'subsets' => array('cyrillic', 'vietnamese', 'latin-ext', 'latin'),
'category' => 'serif'
),
'Open Sans Condensed' => array(
'variants' => array('300', '300italic', '700'),
'subsets' => array('cyrillic', 'greek-ext', 'vietnamese', 'cyrillic-ext', 'latin-ext', 'greek', 'latin'),
'category' => 'sans-serif'
),
'Lora' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('cyrillic', 'vietnamese', 'cyrillic-ext', 'latin-ext', 'latin'),
'category' => 'serif'
),
'Muli' => array(
'variants' => array('200', '200italic', '300', '300italic', '400', 'italic', '600', '600italic', '700', '700italic', '800', '800italic', '900', '900italic'),
'subsets' => array('vietnamese', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'PT Serif' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('cyrillic', 'cyrillic-ext', 'latin-ext', 'latin'),
'category' => 'serif'
),
'Titillium Web' => array(
'variants' => array('200', '200italic', '300', '300italic', '400', 'italic', '600', '600italic', '700', '700italic', '900'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Nunito' => array(
'variants' => array('200', '200italic', '300', '300italic', '400', 'italic', '600', '600italic', '700', '700italic', '800', '800italic', '900', '900italic'),
'subsets' => array('vietnamese', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Rubik' => array(
'variants' => array('300', '300italic', '400', 'italic', '500', '500italic', '700', '700italic', '900', '900italic'),
'subsets' => array('cyrillic', 'hebrew', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Fira Sans' => array(
'variants' => array('100', '100italic', '200', '200italic', '300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic', '800', '800italic', '900', '900italic'),
'subsets' => array('cyrillic', 'greek-ext', 'vietnamese', 'cyrillic-ext', 'latin-ext', 'greek', 'latin'),
'category' => 'sans-serif'
),
'Noto Serif' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('cyrillic', 'greek-ext', 'vietnamese', 'cyrillic-ext', 'latin-ext', 'greek', 'latin'),
'category' => 'serif'
),
'Noto Sans JP' => array(
'variants' => array('100', '300', '400', '500', '700', '900'),
'subsets' => array('japanese', 'latin'),
'category' => 'sans-serif'
),
'Arimo' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('cyrillic', 'greek-ext', 'hebrew', 'vietnamese', 'cyrillic-ext', 'latin-ext', 'greek', 'latin'),
'category' => 'sans-serif'
),
'Nanum Gothic' => array(
'variants' => array('400', '700', '800'),
'subsets' => array('latin', 'korean'),
'category' => 'sans-serif'
),
'Work Sans' => array(
'variants' => array('100', '200', '300', '400', '500', '600', '700', '800', '900'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'PT Sans Narrow' => array(
'variants' => array('400', '700'),
'subsets' => array('cyrillic', 'cyrillic-ext', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Quicksand' => array(
'variants' => array('300', '400', '500', '600', '700'),
'subsets' => array('vietnamese', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Inconsolata' => array(
'variants' => array('400', '700'),
'subsets' => array('vietnamese', 'latin-ext', 'latin'),
'category' => 'monospace'
),
'Noto Sans KR' => array(
'variants' => array('100', '300', '400', '500', '700', '900'),
'subsets' => array('latin', 'korean'),
'category' => 'sans-serif'
),
'Dosis' => array(
'variants' => array('200', '300', '400', '500', '600', '700', '800'),
'subsets' => array('vietnamese', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Nunito Sans' => array(
'variants' => array('200', '200italic', '300', '300italic', '400', 'italic', '600', '600italic', '700', '700italic', '800', '800italic', '900', '900italic'),
'subsets' => array('vietnamese', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Crimson Text' => array(
'variants' => array('400', 'italic', '600', '600italic', '700', '700italic'),
'subsets' => array('latin'),
'category' => 'serif'
),
'Oxygen' => array(
'variants' => array('300', '400', '700'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Heebo' => array(
'variants' => array('100', '300', '400', '500', '700', '800', '900'),
'subsets' => array('hebrew', 'latin'),
'category' => 'sans-serif'
),
'Libre Baskerville' => array(
'variants' => array('400', 'italic', '700'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'serif'
),
'Hind' => array(
'variants' => array('300', '400', '500', '600', '700'),
'subsets' => array('devanagari', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Josefin Sans' => array(
'variants' => array('100', '100italic', '300', '300italic', '400', 'italic', '600', '600italic', '700', '700italic'),
'subsets' => array('vietnamese', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Cabin' => array(
'variants' => array('400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic'),
'subsets' => array('vietnamese', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Bitter' => array(
'variants' => array('400', 'italic', '700'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'serif'
),
'Libre Franklin' => array(
'variants' => array('100', '100italic', '200', '200italic', '300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic', '800', '800italic', '900', '900italic'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Fjalla One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Anton' => array(
'variants' => array('400'),
'subsets' => array('vietnamese', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Indie Flower' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'handwriting'
),
'Arvo' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('latin'),
'category' => 'serif'
),
'Noto Sans TC' => array(
'variants' => array('100', '300', '400', '500', '700', '900'),
'subsets' => array('chinese-traditional', 'latin'),
'category' => 'sans-serif'
),
'Karla' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Lobster' => array(
'variants' => array('400'),
'subsets' => array('cyrillic', 'vietnamese', 'cyrillic-ext', 'latin-ext', 'latin'),
'category' => 'display'
),
'Source Code Pro' => array(
'variants' => array('200', '300', '400', '500', '600', '700', '900'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'monospace'
),
'Abel' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'sans-serif'
),
'Varela Round' => array(
'variants' => array('400'),
'subsets' => array('hebrew', 'vietnamese', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Pacifico' => array(
'variants' => array('400'),
'subsets' => array('cyrillic', 'vietnamese', 'cyrillic-ext', 'latin-ext', 'latin'),
'category' => 'handwriting'
),
'Dancing Script' => array(
'variants' => array('400', '700'),
'subsets' => array('vietnamese', 'latin-ext', 'latin'),
'category' => 'handwriting'
),
'Source Serif Pro' => array(
'variants' => array('400', '600', '700'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'serif'
),
'Mukta' => array(
'variants' => array('200', '300', '400', '500', '600', '700', '800'),
'subsets' => array('devanagari', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Merriweather Sans' => array(
'variants' => array('300', '300italic', '400', 'italic', '700', '700italic', '800', '800italic'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Exo 2' => array(
'variants' => array('100', '100italic', '200', '200italic', '300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic', '800', '800italic', '900', '900italic'),
'subsets' => array('cyrillic', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Abril Fatface' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Hind Siliguri' => array(
'variants' => array('300', '400', '500', '600', '700'),
'subsets' => array('latin-ext', 'latin', 'bengali'),
'category' => 'sans-serif'
),
'Barlow' => array(
'variants' => array('100', '100italic', '200', '200italic', '300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic', '800', '800italic', '900', '900italic'),
'subsets' => array('vietnamese', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Cairo' => array(
'variants' => array('200', '300', '400', '600', '700', '900'),
'subsets' => array('arabic', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Asap' => array(
'variants' => array('400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic'),
'subsets' => array('vietnamese', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Shadows Into Light' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'handwriting'
),
'Yanone Kaffeesatz' => array(
'variants' => array('200', '300', '400', '700'),
'subsets' => array('cyrillic', 'vietnamese', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Teko' => array(
'variants' => array('300', '400', '500', '600', '700'),
'subsets' => array('devanagari', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Questrial' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'sans-serif'
),
'Russo One' => array(
'variants' => array('400'),
'subsets' => array('cyrillic', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Acme' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'sans-serif'
),
'Archivo Narrow' => array(
'variants' => array('400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic'),
'subsets' => array('vietnamese', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'EB Garamond' => array(
'variants' => array('400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic', '800', '800italic'),
'subsets' => array('cyrillic', 'greek-ext', 'vietnamese', 'cyrillic-ext', 'latin-ext', 'greek', 'latin'),
'category' => 'serif'
),
'Bree Serif' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'serif'
),
'Righteous' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Kanit' => array(
'variants' => array('100', '100italic', '200', '200italic', '300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic', '800', '800italic', '900', '900italic'),
'subsets' => array('thai', 'vietnamese', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Catamaran' => array(
'variants' => array('100', '200', '300', '400', '500', '600', '700', '800', '900'),
'subsets' => array('latin-ext', 'latin', 'tamil'),
'category' => 'sans-serif'
),
'Barlow Condensed' => array(
'variants' => array('100', '100italic', '200', '200italic', '300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic', '800', '800italic', '900', '900italic'),
'subsets' => array('vietnamese', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Amatic SC' => array(
'variants' => array('400', '700'),
'subsets' => array('cyrillic', 'hebrew', 'vietnamese', 'latin-ext', 'latin'),
'category' => 'handwriting'
),
'Exo' => array(
'variants' => array('100', '100italic', '200', '200italic', '300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic', '800', '800italic', '900', '900italic'),
'subsets' => array('vietnamese', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Comfortaa' => array(
'variants' => array('300', '400', '500', '600', '700'),
'subsets' => array('cyrillic', 'vietnamese', 'cyrillic-ext', 'latin-ext', 'greek', 'latin'),
'category' => 'display'
),
'Ubuntu Condensed' => array(
'variants' => array('400'),
'subsets' => array('cyrillic', 'greek-ext', 'cyrillic-ext', 'latin-ext', 'greek', 'latin'),
'category' => 'sans-serif'
),
'Overpass' => array(
'variants' => array('100', '100italic', '200', '200italic', '300', '300italic', '400', 'italic', '600', '600italic', '700', '700italic', '800', '800italic', '900', '900italic'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Maven Pro' => array(
'variants' => array('400', '500', '700', '900'),
'subsets' => array('vietnamese', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Play' => array(
'variants' => array('400', '700'),
'subsets' => array('cyrillic', 'vietnamese', 'cyrillic-ext', 'latin-ext', 'greek', 'latin'),
'category' => 'sans-serif'
),
'Rajdhani' => array(
'variants' => array('300', '400', '500', '600', '700'),
'subsets' => array('devanagari', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Fira Sans Condensed' => array(
'variants' => array('100', '100italic', '200', '200italic', '300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic', '800', '800italic', '900', '900italic'),
'subsets' => array('cyrillic', 'greek-ext', 'vietnamese', 'cyrillic-ext', 'latin-ext', 'greek', 'latin'),
'category' => 'sans-serif'
),
'Signika' => array(
'variants' => array('300', '400', '600', '700'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Domine' => array(
'variants' => array('400', '700'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'serif'
),
'Assistant' => array(
'variants' => array('200', '300', '400', '600', '700', '800'),
'subsets' => array('hebrew', 'latin'),
'category' => 'sans-serif'
),
'Monda' => array(
'variants' => array('400', '700'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Hind Madurai' => array(
'variants' => array('300', '400', '500', '600', '700'),
'subsets' => array('latin-ext', 'latin', 'tamil'),
'category' => 'sans-serif'
),
'Ropa Sans' => array(
'variants' => array('400', 'italic'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Francois One' => array(
'variants' => array('400'),
'subsets' => array('vietnamese', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Cinzel' => array(
'variants' => array('400', '700', '900'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'serif'
),
'Vollkorn' => array(
'variants' => array('400', 'italic', '600', '600italic', '700', '700italic', '900', '900italic'),
'subsets' => array('cyrillic', 'vietnamese', 'cyrillic-ext', 'latin-ext', 'greek', 'latin'),
'category' => 'serif'
),
'News Cycle' => array(
'variants' => array('400', '700'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Crete Round' => array(
'variants' => array('400', 'italic'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'serif'
),
'Patua One' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Permanent Marker' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'handwriting'
),
'Amiri' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('arabic', 'latin-ext', 'latin'),
'category' => 'serif'
),
'Orbitron' => array(
'variants' => array('400', '500', '700', '900'),
'subsets' => array('latin'),
'category' => 'sans-serif'
),
'Prompt' => array(
'variants' => array('100', '100italic', '200', '200italic', '300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic', '800', '800italic', '900', '900italic'),
'subsets' => array('thai', 'vietnamese', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Noto Sans SC' => array(
'variants' => array('100', '300', '400', '500', '700', '900'),
'subsets' => array('cyrillic', 'chinese-simplified', 'vietnamese', 'latin'),
'category' => 'sans-serif'
),
'IBM Plex Sans' => array(
'variants' => array('100', '100italic', '200', '200italic', '300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic'),
'subsets' => array('cyrillic', 'vietnamese', 'cyrillic-ext', 'latin-ext', 'greek', 'latin'),
'category' => 'sans-serif'
),
'Cuprum' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('cyrillic', 'vietnamese', 'cyrillic-ext', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Rokkitt' => array(
'variants' => array('100', '200', '300', '400', '500', '600', '700', '800', '900'),
'subsets' => array('vietnamese', 'latin-ext', 'latin'),
'category' => 'serif'
),
'Satisfy' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'handwriting'
),
'Noticia Text' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('vietnamese', 'latin-ext', 'latin'),
'category' => 'serif'
),
'Didact Gothic' => array(
'variants' => array('400'),
'subsets' => array('cyrillic', 'greek-ext', 'cyrillic-ext', 'latin-ext', 'greek', 'latin'),
'category' => 'sans-serif'
),
'Courgette' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'handwriting'
),
'PT Sans Caption' => array(
'variants' => array('400', '700'),
'subsets' => array('cyrillic', 'cyrillic-ext', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Cardo' => array(
'variants' => array('400', 'italic', '700'),
'subsets' => array('greek-ext', 'latin-ext', 'greek', 'latin'),
'category' => 'serif'
),
'Alegreya' => array(
'variants' => array('400', 'italic', '500', '500italic', '700', '700italic', '800', '800italic', '900', '900italic'),
'subsets' => array('cyrillic', 'greek-ext', 'vietnamese', 'cyrillic-ext', 'latin-ext', 'greek', 'latin'),
'category' => 'serif'
),
'Caveat' => array(
'variants' => array('400', '700'),
'subsets' => array('cyrillic', 'cyrillic-ext', 'latin-ext', 'latin'),
'category' => 'handwriting'
),
'ABeeZee' => array(
'variants' => array('400', 'italic'),
'subsets' => array('latin'),
'category' => 'sans-serif'
),
'Old Standard TT' => array(
'variants' => array('400', 'italic', '700'),
'subsets' => array('cyrillic', 'vietnamese', 'cyrillic-ext', 'latin-ext', 'latin'),
'category' => 'serif'
),
'Alfa Slab One' => array(
'variants' => array('400'),
'subsets' => array('vietnamese', 'latin-ext', 'latin'),
'category' => 'display'
),
'Alegreya Sans' => array(
'variants' => array('100', '100italic', '300', '300italic', '400', 'italic', '500', '500italic', '700', '700italic', '800', '800italic', '900', '900italic'),
'subsets' => array('cyrillic', 'greek-ext', 'vietnamese', 'cyrillic-ext', 'latin-ext', 'greek', 'latin'),
'category' => 'sans-serif'
),
'Cormorant Garamond' => array(
'variants' => array('300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic'),
'subsets' => array('cyrillic', 'vietnamese', 'cyrillic-ext', 'latin-ext', 'latin'),
'category' => 'serif'
),
'Martel' => array(
'variants' => array('200', '300', '400', '600', '700', '800', '900'),
'subsets' => array('devanagari', 'latin-ext', 'latin'),
'category' => 'serif'
),
'Kalam' => array(
'variants' => array('300', '400', '700'),
'subsets' => array('devanagari', 'latin-ext', 'latin'),
'category' => 'handwriting'
),
'Fira Sans Extra Condensed' => array(
'variants' => array('100', '100italic', '200', '200italic', '300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic', '800', '800italic', '900', '900italic'),
'subsets' => array('cyrillic', 'greek-ext', 'vietnamese', 'cyrillic-ext', 'latin-ext', 'greek', 'latin'),
'category' => 'sans-serif'
),
'Kaushan Script' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'handwriting'
),
'Barlow Semi Condensed' => array(
'variants' => array('100', '100italic', '200', '200italic', '300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic', '800', '800italic', '900', '900italic'),
'subsets' => array('vietnamese', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Great Vibes' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'handwriting'
),
'Zilla Slab' => array(
'variants' => array('300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'serif'
),
'Pathway Gothic One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Changa' => array(
'variants' => array('200', '300', '400', '500', '600', '700', '800'),
'subsets' => array('arabic', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Tinos' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('cyrillic', 'greek-ext', 'hebrew', 'vietnamese', 'cyrillic-ext', 'latin-ext', 'greek', 'latin'),
'category' => 'serif'
),
'Archivo Black' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Concert One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Cantarell' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('latin'),
'category' => 'sans-serif'
),
'Volkhov' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('latin'),
'category' => 'serif'
),
'Quattrocento Sans' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Frank Ruhl Libre' => array(
'variants' => array('300', '400', '500', '700', '900'),
'subsets' => array('hebrew', 'latin-ext', 'latin'),
'category' => 'serif'
),
'Nanum Myeongjo' => array(
'variants' => array('400', '700', '800'),
'subsets' => array('latin', 'korean'),
'category' => 'serif'
),
'Viga' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Tajawal' => array(
'variants' => array('200', '300', '400', '500', '700', '800', '900'),
'subsets' => array('arabic', 'latin'),
'category' => 'sans-serif'
),
'Lobster Two' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('latin'),
'category' => 'display'
),
'Cookie' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'handwriting'
),
'Quattrocento' => array(
'variants' => array('400', '700'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'serif'
),
'Sacramento' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'handwriting'
),
'Chivo' => array(
'variants' => array('300', '300italic', '400', 'italic', '700', '700italic', '900', '900italic'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Special Elite' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Gloria Hallelujah' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'handwriting'
),
'Fredoka One' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'IBM Plex Serif' => array(
'variants' => array('100', '100italic', '200', '200italic', '300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic'),
'subsets' => array('cyrillic', 'vietnamese', 'cyrillic-ext', 'latin-ext', 'latin'),
'category' => 'serif'
),
'Playfair Display SC' => array(
'variants' => array('400', 'italic', '700', '700italic', '900', '900italic'),
'subsets' => array('cyrillic', 'vietnamese', 'latin-ext', 'latin'),
'category' => 'serif'
),
'Patrick Hand' => array(
'variants' => array('400'),
'subsets' => array('vietnamese', 'latin-ext', 'latin'),
'category' => 'handwriting'
),
'Luckiest Guy' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'BenchNine' => array(
'variants' => array('300', '400', '700'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Poiret One' => array(
'variants' => array('400'),
'subsets' => array('cyrillic', 'latin-ext', 'latin'),
'category' => 'display'
),
'Khand' => array(
'variants' => array('300', '400', '500', '600', '700'),
'subsets' => array('devanagari', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Sanchez' => array(
'variants' => array('400', 'italic'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'serif'
),
'Neuton' => array(
'variants' => array('200', '300', '400', 'italic', '700', '800'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'serif'
),
'Prata' => array(
'variants' => array('400'),
'subsets' => array('cyrillic', 'vietnamese', 'cyrillic-ext', 'latin'),
'category' => 'serif'
),
'Hind Guntur' => array(
'variants' => array('300', '400', '500', '600', '700'),
'subsets' => array('telugu', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Cabin Condensed' => array(
'variants' => array('400', '500', '600', '700'),
'subsets' => array('vietnamese', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Istok Web' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('cyrillic', 'cyrillic-ext', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Bangers' => array(
'variants' => array('400'),
'subsets' => array('vietnamese', 'latin-ext', 'latin'),
'category' => 'display'
),
'M PLUS 1p' => array(
'variants' => array('100', '300', '400', '500', '700', '800', '900'),
'subsets' => array('cyrillic', 'greek-ext', 'hebrew', 'japanese', 'vietnamese', 'cyrillic-ext', 'latin-ext', 'greek', 'latin'),
'category' => 'sans-serif'
),
'Hind Vadodara' => array(
'variants' => array('300', '400', '500', '600', '700'),
'subsets' => array('gujarati', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Tangerine' => array(
'variants' => array('400', '700'),
'subsets' => array('latin'),
'category' => 'handwriting'
),
'Neucha' => array(
'variants' => array('400'),
'subsets' => array('cyrillic', 'latin'),
'category' => 'handwriting'
),
'Handlee' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'handwriting'
),
'Gudea' => array(
'variants' => array('400', 'italic', '700'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Josefin Slab' => array(
'variants' => array('100', '100italic', '300', '300italic', '400', 'italic', '600', '600italic', '700', '700italic'),
'subsets' => array('latin'),
'category' => 'serif'
),
'Passion One' => array(
'variants' => array('400', '700', '900'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Monoton' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Ruda' => array(
'variants' => array('400', '700', '900'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Economica' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Yantramanav' => array(
'variants' => array('100', '300', '400', '500', '700', '900'),
'subsets' => array('devanagari', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Arapey' => array(
'variants' => array('400', 'italic'),
'subsets' => array('latin'),
'category' => 'serif'
),
'Philosopher' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('cyrillic', 'vietnamese', 'cyrillic-ext', 'latin'),
'category' => 'sans-serif'
),
'Gothic A1' => array(
'variants' => array('100', '200', '300', '400', '500', '600', '700', '800', '900'),
'subsets' => array('latin', 'korean'),
'category' => 'sans-serif'
),
'Yrsa' => array(
'variants' => array('300', '400', '500', '600', '700'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'serif'
),
'Amaranth' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('latin'),
'category' => 'sans-serif'
),
'Sawarabi Mincho' => array(
'variants' => array('400'),
'subsets' => array('japanese', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Marck Script' => array(
'variants' => array('400'),
'subsets' => array('cyrillic', 'latin-ext', 'latin'),
'category' => 'handwriting'
),
'Saira' => array(
'variants' => array('100', '200', '300', '400', '500', '600', '700', '800', '900'),
'subsets' => array('vietnamese', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Noto Serif JP' => array(
'variants' => array('200', '300', '400', '500', '600', '700', '900'),
'subsets' => array('japanese', 'latin'),
'category' => 'serif'
),
'Vidaloka' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'serif'
),
'Lalezar' => array(
'variants' => array('400'),
'subsets' => array('arabic', 'vietnamese', 'latin-ext', 'latin'),
'category' => 'display'
),
'Pontano Sans' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Gochi Hand' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'handwriting'
),
'Ultra' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'serif'
),
'Unica One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Boogaloo' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Gentium Basic' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'serif'
),
'Armata' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Montserrat Alternates' => array(
'variants' => array('100', '100italic', '200', '200italic', '300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic', '800', '800italic', '900', '900italic'),
'subsets' => array('cyrillic', 'vietnamese', 'cyrillic-ext', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Advent Pro' => array(
'variants' => array('100', '200', '300', '400', '500', '600', '700'),
'subsets' => array('latin-ext', 'greek', 'latin'),
'category' => 'sans-serif'
),
'Alegreya Sans SC' => array(
'variants' => array('100', '100italic', '300', '300italic', '400', 'italic', '500', '500italic', '700', '700italic', '800', '800italic', '900', '900italic'),
'subsets' => array('cyrillic', 'greek-ext', 'vietnamese', 'cyrillic-ext', 'latin-ext', 'greek', 'latin'),
'category' => 'sans-serif'
),
'Hammersmith One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Signika Negative' => array(
'variants' => array('300', '400', '600', '700'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Sigmar One' => array(
'variants' => array('400'),
'subsets' => array('vietnamese', 'latin-ext', 'latin'),
'category' => 'display'
),
'Architects Daughter' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'handwriting'
),
'Varela' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Cousine' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('cyrillic', 'greek-ext', 'hebrew', 'vietnamese', 'cyrillic-ext', 'latin-ext', 'greek', 'latin'),
'category' => 'monospace'
),
'Asap Condensed' => array(
'variants' => array('400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic'),
'subsets' => array('vietnamese', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Bad Script' => array(
'variants' => array('400'),
'subsets' => array('cyrillic', 'latin'),
'category' => 'handwriting'
),
'Spectral' => array(
'variants' => array('200', '200italic', '300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic', '800', '800italic'),
'subsets' => array('cyrillic', 'vietnamese', 'latin-ext', 'latin'),
'category' => 'serif'
),
'Ubuntu Mono' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('cyrillic', 'greek-ext', 'cyrillic-ext', 'latin-ext', 'greek', 'latin'),
'category' => 'monospace'
),
'Gentium Book Basic' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'serif'
),
'Audiowide' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Rock Salt' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'handwriting'
),
'Yellowtail' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'handwriting'
),
'Aldrich' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'sans-serif'
),
'Alice' => array(
'variants' => array('400'),
'subsets' => array('cyrillic', 'cyrillic-ext', 'latin'),
'category' => 'serif'
),
'PT Mono' => array(
'variants' => array('400'),
'subsets' => array('cyrillic', 'cyrillic-ext', 'latin-ext', 'latin'),
'category' => 'monospace'
),
'Glegoo' => array(
'variants' => array('400', '700'),
'subsets' => array('devanagari', 'latin-ext', 'latin'),
'category' => 'serif'
),
'Enriqueta' => array(
'variants' => array('400', '500', '600', '700'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'serif'
),
'Archivo' => array(
'variants' => array('400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic'),
'subsets' => array('vietnamese', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Taviraj' => array(
'variants' => array('100', '100italic', '200', '200italic', '300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic', '800', '800italic', '900', '900italic'),
'subsets' => array('thai', 'vietnamese', 'latin-ext', 'latin'),
'category' => 'serif'
),
'Saira Semi Condensed' => array(
'variants' => array('100', '200', '300', '400', '500', '600', '700', '800', '900'),
'subsets' => array('vietnamese', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Pridi' => array(
'variants' => array('200', '300', '400', '500', '600', '700'),
'subsets' => array('thai', 'vietnamese', 'latin-ext', 'latin'),
'category' => 'serif'
),
'Playball' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Kreon' => array(
'variants' => array('300', '400', '500', '600', '700'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'serif'
),
'Homemade Apple' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'handwriting'
),
'Sorts Mill Goudy' => array(
'variants' => array('400', 'italic'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'serif'
),
'Actor' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'sans-serif'
),
'Baloo' => array(
'variants' => array('400'),
'subsets' => array('devanagari', 'vietnamese', 'latin-ext', 'latin'),
'category' => 'display'
),
'El Messiri' => array(
'variants' => array('400', '500', '600', '700'),
'subsets' => array('cyrillic', 'arabic', 'latin'),
'category' => 'sans-serif'
),
'Staatliches' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Parisienne' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'handwriting'
),
'Days One' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'sans-serif'
),
'Adamina' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'serif'
),
'Press Start 2P' => array(
'variants' => array('400'),
'subsets' => array('cyrillic', 'cyrillic-ext', 'latin-ext', 'greek', 'latin'),
'category' => 'display'
),
'Damion' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'handwriting'
),
'Julius Sans One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Merienda' => array(
'variants' => array('400', '700'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'handwriting'
),
'Carter One' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Schoolbell' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'handwriting'
),
'Radley' => array(
'variants' => array('400', 'italic'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'serif'
),
'Sintony' => array(
'variants' => array('400', '700'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Allura' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'handwriting'
),
'Oleo Script' => array(
'variants' => array('400', '700'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Shadows Into Light Two' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'handwriting'
),
'Rasa' => array(
'variants' => array('300', '400', '500', '600', '700'),
'subsets' => array('gujarati', 'latin-ext', 'latin'),
'category' => 'serif'
),
'Unna' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'serif'
),
'Arbutus Slab' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'serif'
),
'Fugaz One' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Covered By Your Grace' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'handwriting'
),
'Paytone One' => array(
'variants' => array('400'),
'subsets' => array('vietnamese', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Sarala' => array(
'variants' => array('400', '700'),
'subsets' => array('devanagari', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Italianno' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'handwriting'
),
'Molengo' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Mr Dafoe' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'handwriting'
),
'Antic Slab' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'serif'
),
'Jura' => array(
'variants' => array('300', '400', '500', '600', '700'),
'subsets' => array('cyrillic', 'greek-ext', 'vietnamese', 'cyrillic-ext', 'latin-ext', 'greek', 'latin'),
'category' => 'sans-serif'
),
'Saira Extra Condensed' => array(
'variants' => array('100', '200', '300', '400', '500', '600', '700', '800', '900'),
'subsets' => array('vietnamese', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Abhaya Libre' => array(
'variants' => array('400', '500', '600', '700', '800'),
'subsets' => array('sinhala', 'latin-ext', 'latin'),
'category' => 'serif'
),
'Scada' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('cyrillic', 'cyrillic-ext', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Karma' => array(
'variants' => array('300', '400', '500', '600', '700'),
'subsets' => array('devanagari', 'latin-ext', 'latin'),
'category' => 'serif'
),
'Pragati Narrow' => array(
'variants' => array('400', '700'),
'subsets' => array('devanagari', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Nothing You Could Do' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'handwriting'
),
'Cormorant' => array(
'variants' => array('300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic'),
'subsets' => array('cyrillic', 'vietnamese', 'cyrillic-ext', 'latin-ext', 'latin'),
'category' => 'serif'
),
'Rancho' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'handwriting'
),
'Share Tech Mono' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'monospace'
),
'PT Serif Caption' => array(
'variants' => array('400', 'italic'),
'subsets' => array('cyrillic', 'cyrillic-ext', 'latin-ext', 'latin'),
'category' => 'serif'
),
'Sunflower' => array(
'variants' => array('300', '500', '700'),
'subsets' => array('latin', 'korean'),
'category' => 'sans-serif'
),
'Allerta' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'sans-serif'
),
'Rubik Mono One' => array(
'variants' => array('400'),
'subsets' => array('cyrillic', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Nanum Gothic Coding' => array(
'variants' => array('400', '700'),
'subsets' => array('latin', 'korean'),
'category' => 'monospace'
),
'Alex Brush' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'handwriting'
),
'Cantata One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'serif'
),
'Mitr' => array(
'variants' => array('200', '300', '400', '500', '600', '700'),
'subsets' => array('thai', 'vietnamese', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Lusitana' => array(
'variants' => array('400', '700'),
'subsets' => array('latin'),
'category' => 'serif'
),
'Marcellus' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'serif'
),
'M PLUS Rounded 1c' => array(
'variants' => array('100', '300', '400', '500', '700', '800', '900'),
'subsets' => array('cyrillic', 'greek-ext', 'hebrew', 'japanese', 'vietnamese', 'cyrillic-ext', 'latin-ext', 'greek', 'latin'),
'category' => 'sans-serif'
),
'Chewy' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Nanum Pen Script' => array(
'variants' => array('400'),
'subsets' => array('latin', 'korean'),
'category' => 'handwriting'
),
'Khula' => array(
'variants' => array('300', '400', '600', '700', '800'),
'subsets' => array('devanagari', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Scheherazade' => array(
'variants' => array('400', '700'),
'subsets' => array('arabic', 'latin'),
'category' => 'serif'
),
'Bevan' => array(
'variants' => array('400'),
'subsets' => array('vietnamese', 'latin-ext', 'latin'),
'category' => 'display'
),
'Pinyon Script' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'handwriting'
),
'Belleza' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Lustria' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'serif'
),
'Allerta Stencil' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'sans-serif'
),
'Sawarabi Gothic' => array(
'variants' => array('400'),
'subsets' => array('cyrillic', 'japanese', 'vietnamese', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Spinnaker' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Niconne' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'handwriting'
),
'Tenor Sans' => array(
'variants' => array('400'),
'subsets' => array('cyrillic', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Rambla' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Knewave' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Average' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'serif'
),
'Michroma' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'sans-serif'
),
'Basic' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Black Ops One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Love Ya Like A Sister' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Antic' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'sans-serif'
),
'Biryani' => array(
'variants' => array('200', '300', '400', '600', '700', '800', '900'),
'subsets' => array('devanagari', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Nobile' => array(
'variants' => array('400', 'italic', '500', '500italic', '700', '700italic'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'IBM Plex Mono' => array(
'variants' => array('100', '100italic', '200', '200italic', '300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic'),
'subsets' => array('cyrillic', 'vietnamese', 'cyrillic-ext', 'latin-ext', 'latin'),
'category' => 'monospace'
),
'Fredericka the Great' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Cutive Mono' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'monospace'
),
'Coda' => array(
'variants' => array('400', '800'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Forum' => array(
'variants' => array('400'),
'subsets' => array('cyrillic', 'cyrillic-ext', 'latin-ext', 'latin'),
'category' => 'display'
),
'Reenie Beanie' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'handwriting'
),
'Fira Mono' => array(
'variants' => array('400', '500', '700'),
'subsets' => array('cyrillic', 'greek-ext', 'cyrillic-ext', 'latin-ext', 'greek', 'latin'),
'category' => 'monospace'
),
'Hanuman' => array(
'variants' => array('400', '700'),
'subsets' => array('khmer'),
'category' => 'serif'
),
'Magra' => array(
'variants' => array('400', '700'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Mountains of Christmas' => array(
'variants' => array('400', '700'),
'subsets' => array('latin'),
'category' => 'display'
),
'Caveat Brush' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'handwriting'
),
'Bowlby One SC' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Lateef' => array(
'variants' => array('400'),
'subsets' => array('arabic', 'latin'),
'category' => 'handwriting'
),
'Cabin Sketch' => array(
'variants' => array('400', '700'),
'subsets' => array('latin'),
'category' => 'display'
),
'Lemonada' => array(
'variants' => array('300', '400', '600', '700'),
'subsets' => array('arabic', 'vietnamese', 'latin-ext', 'latin'),
'category' => 'display'
),
'Leckerli One' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'handwriting'
),
'Pangolin' => array(
'variants' => array('400'),
'subsets' => array('cyrillic', 'vietnamese', 'cyrillic-ext', 'latin-ext', 'latin'),
'category' => 'handwriting'
),
'Electrolize' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'sans-serif'
),
'Reem Kufi' => array(
'variants' => array('400'),
'subsets' => array('arabic', 'latin'),
'category' => 'sans-serif'
),
'Coda Caption' => array(
'variants' => array('800'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Syncopate' => array(
'variants' => array('400', '700'),
'subsets' => array('latin'),
'category' => 'sans-serif'
),
'Sarabun' => array(
'variants' => array('100', '100italic', '200', '200italic', '300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic', '800', '800italic'),
'subsets' => array('thai', 'vietnamese', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Rochester' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'handwriting'
),
'Just Another Hand' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'handwriting'
),
'Encode Sans' => array(
'variants' => array('100', '200', '300', '400', '500', '600', '700', '800', '900'),
'subsets' => array('vietnamese', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Kameron' => array(
'variants' => array('400', '700'),
'subsets' => array('latin'),
'category' => 'serif'
),
'Cinzel Decorative' => array(
'variants' => array('400', '700', '900'),
'subsets' => array('latin'),
'category' => 'display'
),
'Alef' => array(
'variants' => array('400', '700'),
'subsets' => array('hebrew', 'latin'),
'category' => 'sans-serif'
),
'Candal' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'sans-serif'
),
'Space Mono' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('vietnamese', 'latin-ext', 'latin'),
'category' => 'monospace'
),
'Encode Sans Condensed' => array(
'variants' => array('100', '200', '300', '400', '500', '600', '700', '800', '900'),
'subsets' => array('vietnamese', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Rufina' => array(
'variants' => array('400', '700'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'serif'
),
'Oranienbaum' => array(
'variants' => array('400'),
'subsets' => array('cyrillic', 'cyrillic-ext', 'latin-ext', 'latin'),
'category' => 'serif'
),
'Squada One' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Yesteryear' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'handwriting'
),
'Palanquin' => array(
'variants' => array('100', '200', '300', '400', '500', '600', '700'),
'subsets' => array('devanagari', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Share' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Overlock' => array(
'variants' => array('400', 'italic', '700', '700italic', '900', '900italic'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Berkshire Swash' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'handwriting'
),
'Marcellus SC' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'serif'
),
'Martel Sans' => array(
'variants' => array('200', '300', '400', '600', '700', '800', '900'),
'subsets' => array('devanagari', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Quantico' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('latin'),
'category' => 'sans-serif'
),
'Telex' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Lilita One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Coming Soon' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'handwriting'
),
'Alegreya SC' => array(
'variants' => array('400', 'italic', '500', '500italic', '700', '700italic', '800', '800italic', '900', '900italic'),
'subsets' => array('cyrillic', 'greek-ext', 'vietnamese', 'cyrillic-ext', 'latin-ext', 'greek', 'latin'),
'category' => 'serif'
),
'Saira Condensed' => array(
'variants' => array('100', '200', '300', '400', '500', '600', '700', '800', '900'),
'subsets' => array('vietnamese', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Markazi Text' => array(
'variants' => array('400', '500', '600', '700'),
'subsets' => array('arabic', 'vietnamese', 'latin-ext', 'latin'),
'category' => 'serif'
),
'Changa One' => array(
'variants' => array('400', 'italic'),
'subsets' => array('latin'),
'category' => 'display'
),
'Arizonia' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'handwriting'
),
'Itim' => array(
'variants' => array('400'),
'subsets' => array('thai', 'vietnamese', 'latin-ext', 'latin'),
'category' => 'handwriting'
),
'Nanum Brush Script' => array(
'variants' => array('400'),
'subsets' => array('latin', 'korean'),
'category' => 'handwriting'
),
'Voltaire' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'sans-serif'
),
'Jockey One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Grand Hotel' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'handwriting'
),
'Chakra Petch' => array(
'variants' => array('300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic'),
'subsets' => array('thai', 'vietnamese', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Anonymous Pro' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('cyrillic', 'latin-ext', 'greek', 'latin'),
'category' => 'monospace'
),
'Arima Madurai' => array(
'variants' => array('100', '200', '300', '400', '500', '700', '800', '900'),
'subsets' => array('vietnamese', 'latin-ext', 'latin', 'tamil'),
'category' => 'display'
),
'Gruppo' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Slabo 13px' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'serif'
),
'Shrikhand' => array(
'variants' => array('400'),
'subsets' => array('gujarati', 'latin-ext', 'latin'),
'category' => 'display'
),
'Prosto One' => array(
'variants' => array('400'),
'subsets' => array('cyrillic', 'latin-ext', 'latin'),
'category' => 'display'
),
'Lekton' => array(
'variants' => array('400', 'italic', '700'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Shojumaru' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Fauna One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'serif'
),
'Sniglet' => array(
'variants' => array('400', '800'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Yeseva One' => array(
'variants' => array('400'),
'subsets' => array('cyrillic', 'vietnamese', 'cyrillic-ext', 'latin-ext', 'latin'),
'category' => 'display'
),
'Arsenal' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('cyrillic', 'vietnamese', 'cyrillic-ext', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Petit Formal Script' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'handwriting'
),
'Jaldi' => array(
'variants' => array('400', '700'),
'subsets' => array('devanagari', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Marvel' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('latin'),
'category' => 'sans-serif'
),
'Trirong' => array(
'variants' => array('100', '100italic', '200', '200italic', '300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic', '800', '800italic', '900', '900italic'),
'subsets' => array('thai', 'vietnamese', 'latin-ext', 'latin'),
'category' => 'serif'
),
'Norican' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'handwriting'
),
'Caudex' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('greek-ext', 'latin-ext', 'greek', 'latin'),
'category' => 'serif'
),
'Carrois Gothic' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'sans-serif'
),
'Gilda Display' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'serif'
),
'Carme' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'sans-serif'
),
'Judson' => array(
'variants' => array('400', 'italic', '700'),
'subsets' => array('vietnamese', 'latin-ext', 'latin'),
'category' => 'serif'
),
'Marmelad' => array(
'variants' => array('400'),
'subsets' => array('cyrillic', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Racing Sans One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'GFS Didot' => array(
'variants' => array('400'),
'subsets' => array('greek'),
'category' => 'serif'
),
'Nixie One' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Rosario' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('latin'),
'category' => 'sans-serif'
),
'Buenard' => array(
'variants' => array('400', '700'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'serif'
),
'Copse' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'serif'
),
'Eczar' => array(
'variants' => array('400', '500', '600', '700', '800'),
'subsets' => array('devanagari', 'latin-ext', 'latin'),
'category' => 'serif'
),
'Capriola' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Contrail One' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Halant' => array(
'variants' => array('300', '400', '500', '600', '700'),
'subsets' => array('devanagari', 'latin-ext', 'latin'),
'category' => 'serif'
),
'Bungee' => array(
'variants' => array('400'),
'subsets' => array('vietnamese', 'latin-ext', 'latin'),
'category' => 'display'
),
'Raleway Dots' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Laila' => array(
'variants' => array('300', '400', '500', '600', '700'),
'subsets' => array('devanagari', 'latin-ext', 'latin'),
'category' => 'serif'
),
'Sue Ellen Francisco' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'handwriting'
),
'Ovo' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'serif'
),
'Aclonica' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'sans-serif'
),
'Annie Use Your Telescope' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'handwriting'
),
'Bungee Inline' => array(
'variants' => array('400'),
'subsets' => array('vietnamese', 'latin-ext', 'latin'),
'category' => 'display'
),
'Merienda One' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'handwriting'
),
'Coustard' => array(
'variants' => array('400', '900'),
'subsets' => array('latin'),
'category' => 'serif'
),
'Palanquin Dark' => array(
'variants' => array('400', '500', '600', '700'),
'subsets' => array('devanagari', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Pattaya' => array(
'variants' => array('400'),
'subsets' => array('cyrillic', 'thai', 'vietnamese', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Graduate' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Kosugi Maru' => array(
'variants' => array('400'),
'subsets' => array('cyrillic', 'japanese', 'latin'),
'category' => 'sans-serif'
),
'VT323' => array(
'variants' => array('400'),
'subsets' => array('vietnamese', 'latin-ext', 'latin'),
'category' => 'monospace'
),
'Cambo' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'serif'
),
'Suez One' => array(
'variants' => array('400'),
'subsets' => array('hebrew', 'latin-ext', 'latin'),
'category' => 'serif'
),
'Baloo Bhaina' => array(
'variants' => array('400'),
'subsets' => array('vietnamese', 'latin-ext', 'latin', 'oriya'),
'category' => 'display'
),
'Bubblegum Sans' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Herr Von Muellerhoff' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'handwriting'
),
'Miriam Libre' => array(
'variants' => array('400', '700'),
'subsets' => array('hebrew', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Doppio One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Kristi' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'handwriting'
),
'Maitree' => array(
'variants' => array('200', '300', '400', '500', '600', '700'),
'subsets' => array('thai', 'vietnamese', 'latin-ext', 'latin'),
'category' => 'serif'
),
'Mada' => array(
'variants' => array('200', '300', '400', '500', '600', '700', '900'),
'subsets' => array('arabic', 'latin'),
'category' => 'sans-serif'
),
'Allan' => array(
'variants' => array('400', '700'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Ceviche One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Delius' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'handwriting'
),
'Mr De Haviland' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'handwriting'
),
'Goudy Bookletter 1911' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'serif'
),
'Calligraffitti' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'handwriting'
),
'Average Sans' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Metrophobic' => array(
'variants' => array('400'),
'subsets' => array('vietnamese', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Titan One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Black Han Sans' => array(
'variants' => array('400'),
'subsets' => array('latin', 'korean'),
'category' => 'sans-serif'
),
'Oxygen Mono' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'monospace'
),
'Mali' => array(
'variants' => array('200', '200italic', '300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic'),
'subsets' => array('thai', 'vietnamese', 'latin-ext', 'latin'),
'category' => 'handwriting'
),
'Mukta Malar' => array(
'variants' => array('200', '300', '400', '500', '600', '700', '800'),
'subsets' => array('latin-ext', 'latin', 'tamil'),
'category' => 'sans-serif'
),
'Freckle Face' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Homenaje' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'sans-serif'
),
'Suranna' => array(
'variants' => array('400'),
'subsets' => array('telugu', 'latin'),
'category' => 'serif'
),
'Noto Serif KR' => array(
'variants' => array('200', '300', '400', '500', '600', '700', '900'),
'subsets' => array('latin', 'korean'),
'category' => 'serif'
),
'Londrina Solid' => array(
'variants' => array('100', '300', '400', '900'),
'subsets' => array('latin'),
'category' => 'display'
),
'Kelly Slab' => array(
'variants' => array('400'),
'subsets' => array('cyrillic', 'latin-ext', 'latin'),
'category' => 'display'
),
'Aladin' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'handwriting'
),
'Trocchi' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'serif'
),
'Do Hyeon' => array(
'variants' => array('400'),
'subsets' => array('latin', 'korean'),
'category' => 'sans-serif'
),
'Duru Sans' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Gabriela' => array(
'variants' => array('400'),
'subsets' => array('cyrillic', 'cyrillic-ext', 'latin'),
'category' => 'serif'
),
'Faster One' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Limelight' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Bai Jamjuree' => array(
'variants' => array('200', '200italic', '300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic'),
'subsets' => array('thai', 'vietnamese', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Balthazar' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'serif'
),
'Emilys Candy' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Cutive' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'serif'
),
'Rye' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Fanwood Text' => array(
'variants' => array('400', 'italic'),
'subsets' => array('latin'),
'category' => 'serif'
),
'Give You Glory' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'handwriting'
),
'IM Fell DW Pica' => array(
'variants' => array('400', 'italic'),
'subsets' => array('latin'),
'category' => 'serif'
),
'Noto Serif SC' => array(
'variants' => array('200', '300', '400', '500', '600', '700', '900'),
'subsets' => array('cyrillic', 'chinese-simplified', 'vietnamese', 'latin'),
'category' => 'serif'
),
'Averia Serif Libre' => array(
'variants' => array('300', '300italic', '400', 'italic', '700', '700italic'),
'subsets' => array('latin'),
'category' => 'display'
),
'Unkempt' => array(
'variants' => array('400', '700'),
'subsets' => array('latin'),
'category' => 'display'
),
'Andada' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'serif'
),
'Bentham' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'serif'
),
'Happy Monkey' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Secular One' => array(
'variants' => array('400'),
'subsets' => array('hebrew', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Encode Sans Expanded' => array(
'variants' => array('100', '200', '300', '400', '500', '600', '700', '800', '900'),
'subsets' => array('vietnamese', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Vesper Libre' => array(
'variants' => array('400', '500', '700', '900'),
'subsets' => array('devanagari', 'latin-ext', 'latin'),
'category' => 'serif'
),
'Cambay' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('devanagari', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Wallpoet' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Mate' => array(
'variants' => array('400', 'italic'),
'subsets' => array('latin'),
'category' => 'serif'
),
'Faustina' => array(
'variants' => array('400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic'),
'subsets' => array('vietnamese', 'latin-ext', 'latin'),
'category' => 'serif'
),
'Inder' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Chelsea Market' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Encode Sans Semi Expanded' => array(
'variants' => array('100', '200', '300', '400', '500', '600', '700', '800', '900'),
'subsets' => array('vietnamese', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Mukta Vaani' => array(
'variants' => array('200', '300', '400', '500', '600', '700', '800'),
'subsets' => array('gujarati', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Chonburi' => array(
'variants' => array('400'),
'subsets' => array('thai', 'vietnamese', 'latin-ext', 'latin'),
'category' => 'display'
),
'Cedarville Cursive' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'handwriting'
),
'Cormorant Infant' => array(
'variants' => array('300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic'),
'subsets' => array('cyrillic', 'vietnamese', 'cyrillic-ext', 'latin-ext', 'latin'),
'category' => 'serif'
),
'Poly' => array(
'variants' => array('400', 'italic'),
'subsets' => array('latin'),
'category' => 'serif'
),
'Anaheim' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Athiti' => array(
'variants' => array('200', '300', '400', '500', '600', '700'),
'subsets' => array('thai', 'vietnamese', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Alike' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'serif'
),
'Amethysta' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'serif'
),
'Pompiere' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Puritan' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('latin'),
'category' => 'sans-serif'
),
'Carrois Gothic SC' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'sans-serif'
),
'Gurajada' => array(
'variants' => array('400'),
'subsets' => array('telugu', 'latin'),
'category' => 'serif'
),
'Federo' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'sans-serif'
),
'IM Fell English' => array(
'variants' => array('400', 'italic'),
'subsets' => array('latin'),
'category' => 'serif'
),
'Six Caps' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'sans-serif'
),
'Amiko' => array(
'variants' => array('400', '600', '700'),
'subsets' => array('devanagari', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Gravitas One' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Niramit' => array(
'variants' => array('200', '200italic', '300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic'),
'subsets' => array('thai', 'vietnamese', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Baloo Bhaijaan' => array(
'variants' => array('400'),
'subsets' => array('arabic', 'vietnamese', 'latin-ext', 'latin'),
'category' => 'display'
),
'Seaweed Script' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Rouge Script' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'handwriting'
),
'Corben' => array(
'variants' => array('400', '700'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'IM Fell Double Pica' => array(
'variants' => array('400', 'italic'),
'subsets' => array('latin'),
'category' => 'serif'
),
'Montez' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'handwriting'
),
'Patrick Hand SC' => array(
'variants' => array('400'),
'subsets' => array('vietnamese', 'latin-ext', 'latin'),
'category' => 'handwriting'
),
'Sriracha' => array(
'variants' => array('400'),
'subsets' => array('thai', 'vietnamese', 'latin-ext', 'latin'),
'category' => 'handwriting'
),
'Wendy One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Vast Shadow' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Oregano' => array(
'variants' => array('400', 'italic'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Krona One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'IBM Plex Sans Condensed' => array(
'variants' => array('100', '100italic', '200', '200italic', '300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic'),
'subsets' => array('vietnamese', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Battambang' => array(
'variants' => array('400', '700'),
'subsets' => array('khmer'),
'category' => 'display'
),
'Tulpen One' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Convergence' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'sans-serif'
),
'Noto Serif TC' => array(
'variants' => array('200', '300', '400', '500', '600', '700', '900'),
'subsets' => array('cyrillic', 'chinese-traditional', 'vietnamese', 'latin'),
'category' => 'serif'
),
'La Belle Aurore' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'handwriting'
),
'Skranji' => array(
'variants' => array('400', '700'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Kadwa' => array(
'variants' => array('400', '700'),
'subsets' => array('devanagari', 'latin'),
'category' => 'serif'
),
'Mirza' => array(
'variants' => array('400', '500', '600', '700'),
'subsets' => array('arabic', 'latin-ext', 'latin'),
'category' => 'display'
),
'Atma' => array(
'variants' => array('300', '400', '500', '600', '700'),
'subsets' => array('latin-ext', 'latin', 'bengali'),
'category' => 'display'
),
'Oleo Script Swash Caps' => array(
'variants' => array('400', '700'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Qwigley' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'handwriting'
),
'Tauri' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Baumans' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Proza Libre' => array(
'variants' => array('400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic', '800', '800italic'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Bilbo Swash Caps' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'handwriting'
),
'Katibeh' => array(
'variants' => array('400'),
'subsets' => array('arabic', 'latin-ext', 'latin'),
'category' => 'display'
),
'Aref Ruqaa' => array(
'variants' => array('400', '700'),
'subsets' => array('arabic', 'latin'),
'category' => 'serif'
),
'Rozha One' => array(
'variants' => array('400'),
'subsets' => array('devanagari', 'latin-ext', 'latin'),
'category' => 'serif'
),
'Sofia' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'handwriting'
),
'Stardos Stencil' => array(
'variants' => array('400', '700'),
'subsets' => array('latin'),
'category' => 'display'
),
'Baloo Bhai' => array(
'variants' => array('400'),
'subsets' => array('gujarati', 'vietnamese', 'latin-ext', 'latin'),
'category' => 'display'
),
'Overpass Mono' => array(
'variants' => array('300', '400', '600', '700'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'monospace'
),
'Short Stack' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'handwriting'
),
'Sansita' => array(
'variants' => array('400', 'italic', '700', '700italic', '800', '800italic', '900', '900italic'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Strait' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'sans-serif'
),
'Mouse Memoirs' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Megrim' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Rationale' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'sans-serif'
),
'Prociono' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'serif'
),
'Crafty Girls' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'handwriting'
),
'Expletus Sans' => array(
'variants' => array('400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic'),
'subsets' => array('latin'),
'category' => 'display'
),
'Fondamento' => array(
'variants' => array('400', 'italic'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'handwriting'
),
'Clicker Script' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'handwriting'
),
'The Girl Next Door' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'handwriting'
),
'Brawler' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'serif'
),
'Salsa' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Bungee Shade' => array(
'variants' => array('400'),
'subsets' => array('vietnamese', 'latin-ext', 'latin'),
'category' => 'display'
),
'Quando' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'serif'
),
'Zeyada' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'handwriting'
),
'Podkova' => array(
'variants' => array('400', '500', '600', '700', '800'),
'subsets' => array('cyrillic', 'vietnamese', 'cyrillic-ext', 'latin-ext', 'latin'),
'category' => 'serif'
),
'Krub' => array(
'variants' => array('200', '200italic', '300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic'),
'subsets' => array('thai', 'vietnamese', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Meddon' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'handwriting'
),
'Andika' => array(
'variants' => array('400'),
'subsets' => array('cyrillic', 'vietnamese', 'cyrillic-ext', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Cantora One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Eater' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Tienne' => array(
'variants' => array('400', '700', '900'),
'subsets' => array('latin'),
'category' => 'serif'
),
'Waiting for the Sunrise' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'handwriting'
),
'Walter Turncoat' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'handwriting'
),
'Cormorant SC' => array(
'variants' => array('300', '400', '500', '600', '700'),
'subsets' => array('cyrillic', 'vietnamese', 'cyrillic-ext', 'latin-ext', 'latin'),
'category' => 'serif'
),
'Loved by the King' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'handwriting'
),
'Belgrano' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'serif'
),
'Lemon' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Metamorphous' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Italiana' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'serif'
),
'Denk One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Aguafina Script' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'handwriting'
),
'Harmattan' => array(
'variants' => array('400'),
'subsets' => array('arabic', 'latin'),
'category' => 'sans-serif'
),
'Aleo' => array(
'variants' => array('300', '300italic', '400', 'italic', '700', '700italic'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'serif'
),
'Wire One' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'sans-serif'
),
'Mako' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'sans-serif'
),
'Creepster' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Orienta' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Bellefair' => array(
'variants' => array('400'),
'subsets' => array('hebrew', 'latin-ext', 'latin'),
'category' => 'serif'
),
'Codystar' => array(
'variants' => array('300', '400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Spirax' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Artifika' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'serif'
),
'UnifrakturMaguntia' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Ramabhadra' => array(
'variants' => array('400'),
'subsets' => array('telugu', 'latin'),
'category' => 'sans-serif'
),
'Iceland' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Vibur' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'handwriting'
),
'Averia Libre' => array(
'variants' => array('300', '300italic', '400', 'italic', '700', '700italic'),
'subsets' => array('latin'),
'category' => 'display'
),
'Galada' => array(
'variants' => array('400'),
'subsets' => array('latin', 'bengali'),
'category' => 'display'
),
'Kurale' => array(
'variants' => array('400'),
'subsets' => array('cyrillic', 'devanagari', 'cyrillic-ext', 'latin-ext', 'latin'),
'category' => 'serif'
),
'Spectral SC' => array(
'variants' => array('200', '200italic', '300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic', '800', '800italic'),
'subsets' => array('cyrillic', 'vietnamese', 'latin-ext', 'latin'),
'category' => 'serif'
),
'Lily Script One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Amita' => array(
'variants' => array('400', '700'),
'subsets' => array('devanagari', 'latin-ext', 'latin'),
'category' => 'handwriting'
),
'Fjord One' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'serif'
),
'NTR' => array(
'variants' => array('400'),
'subsets' => array('telugu', 'latin'),
'category' => 'sans-serif'
),
'Baloo Paaji' => array(
'variants' => array('400'),
'subsets' => array('gurmukhi', 'vietnamese', 'latin-ext', 'latin'),
'category' => 'display'
),
'Scope One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'serif'
),
'Just Me Again Down Here' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'handwriting'
),
'Ledger' => array(
'variants' => array('400'),
'subsets' => array('cyrillic', 'latin-ext', 'latin'),
'category' => 'serif'
),
'Bowlby One' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Mallanna' => array(
'variants' => array('400'),
'subsets' => array('telugu', 'latin'),
'category' => 'sans-serif'
),
'Medula One' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Euphoria Script' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'handwriting'
),
'Princess Sofia' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'handwriting'
),
'Jua' => array(
'variants' => array('400'),
'subsets' => array('latin', 'korean'),
'category' => 'sans-serif'
),
'Vampiro One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Finger Paint' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Headland One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'serif'
),
'Dawning of a New Day' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'handwriting'
),
'Delius Swash Caps' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'handwriting'
),
'Hanalei Fill' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Over the Rainbow' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'handwriting'
),
'Poller One' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Sail' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'McLaren' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Averia Sans Libre' => array(
'variants' => array('300', '300italic', '400', 'italic', '700', '700italic'),
'subsets' => array('latin'),
'category' => 'display'
),
'Fontdiner Swanky' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Cherry Swash' => array(
'variants' => array('400', '700'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Imprima' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Encode Sans Semi Condensed' => array(
'variants' => array('100', '200', '300', '400', '500', '600', '700', '800', '900'),
'subsets' => array('vietnamese', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Rammetto One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Gafata' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Mrs Saint Delafield' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'handwriting'
),
'Rakkas' => array(
'variants' => array('400'),
'subsets' => array('arabic', 'latin-ext', 'latin'),
'category' => 'display'
),
'Padauk' => array(
'variants' => array('400', '700'),
'subsets' => array('latin', 'myanmar'),
'category' => 'sans-serif'
),
'Londrina Outline' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Charm' => array(
'variants' => array('400', '700'),
'subsets' => array('thai', 'vietnamese', 'latin-ext', 'latin'),
'category' => 'handwriting'
),
'Almendra' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'serif'
),
'Sarina' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Holtwood One SC' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'serif'
),
'Nova Round' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Voces' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Alike Angular' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'serif'
),
'Kosugi' => array(
'variants' => array('400'),
'subsets' => array('cyrillic', 'japanese', 'latin'),
'category' => 'sans-serif'
),
'BioRhyme' => array(
'variants' => array('200', '300', '400', '700', '800'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'serif'
),
'IM Fell English SC' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'serif'
),
'Sedgwick Ave' => array(
'variants' => array('400'),
'subsets' => array('vietnamese', 'latin-ext', 'latin'),
'category' => 'handwriting'
),
'Manuale' => array(
'variants' => array('400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic'),
'subsets' => array('vietnamese', 'latin-ext', 'latin'),
'category' => 'serif'
),
'Nova Mono' => array(
'variants' => array('400'),
'subsets' => array('greek', 'latin'),
'category' => 'monospace'
),
'Habibi' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'serif'
),
'Spicy Rice' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Life Savers' => array(
'variants' => array('400', '700', '800'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Libre Barcode 39' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Quintessential' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'handwriting'
),
'Kumar One' => array(
'variants' => array('400'),
'subsets' => array('gujarati', 'latin-ext', 'latin'),
'category' => 'display'
),
'Asul' => array(
'variants' => array('400', '700'),
'subsets' => array('latin'),
'category' => 'sans-serif'
),
'Nova Square' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Timmana' => array(
'variants' => array('400'),
'subsets' => array('telugu', 'latin'),
'category' => 'sans-serif'
),
'Crushed' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Share Tech' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'sans-serif'
),
'Noto Sans HK' => array(
'variants' => array('100', '300', '400', '500', '700', '900'),
'subsets' => array('chinese-hongkong', 'latin'),
'category' => 'sans-serif'
),
'Montserrat Subrayada' => array(
'variants' => array('400', '700'),
'subsets' => array('latin'),
'category' => 'sans-serif'
),
'Engagement' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'handwriting'
),
'Frijole' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Dekko' => array(
'variants' => array('400'),
'subsets' => array('devanagari', 'latin-ext', 'latin'),
'category' => 'handwriting'
),
'Sarpanch' => array(
'variants' => array('400', '500', '600', '700', '800', '900'),
'subsets' => array('devanagari', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Baloo Chettan' => array(
'variants' => array('400'),
'subsets' => array('vietnamese', 'latin-ext', 'malayalam', 'latin'),
'category' => 'display'
),
'Kranky' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Shanti' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'sans-serif'
),
'Chau Philomene One' => array(
'variants' => array('400', 'italic'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Numans' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'sans-serif'
),
'Port Lligat Slab' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'serif'
),
'Cormorant Upright' => array(
'variants' => array('300', '400', '500', '600', '700'),
'subsets' => array('vietnamese', 'latin-ext', 'latin'),
'category' => 'serif'
),
'Baloo Thambi' => array(
'variants' => array('400'),
'subsets' => array('vietnamese', 'latin-ext', 'latin', 'tamil'),
'category' => 'display'
),
'Fresca' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Antic Didone' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'serif'
),
'Meera Inimai' => array(
'variants' => array('400'),
'subsets' => array('latin', 'tamil'),
'category' => 'sans-serif'
),
'Mogra' => array(
'variants' => array('400'),
'subsets' => array('gujarati', 'latin-ext', 'latin'),
'category' => 'display'
),
'Nosifer' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Dynalight' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Chicle' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'David Libre' => array(
'variants' => array('400', '500', '700'),
'subsets' => array('hebrew', 'vietnamese', 'latin-ext', 'latin'),
'category' => 'serif'
),
'Pavanam' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin', 'tamil'),
'category' => 'sans-serif'
),
'Akronim' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Sancreek' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Slackey' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'DM Serif Display' => array(
'variants' => array('400', 'italic'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'serif'
),
'Milonga' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Cherry Cream Soda' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Esteban' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'serif'
),
'Kite One' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'sans-serif'
),
'Koulen' => array(
'variants' => array('400'),
'subsets' => array('khmer'),
'category' => 'display'
),
'Elsie' => array(
'variants' => array('400', '900'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Pirata One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Kotta One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'serif'
),
'Swanky and Moo Moo' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'handwriting'
),
'Fenix' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'serif'
),
'Germania One' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Khmer' => array(
'variants' => array('400'),
'subsets' => array('khmer'),
'category' => 'display'
),
'K2D' => array(
'variants' => array('100', '100italic', '200', '200italic', '300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic', '800', '800italic'),
'subsets' => array('thai', 'vietnamese', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Mandali' => array(
'variants' => array('400'),
'subsets' => array('telugu', 'latin'),
'category' => 'sans-serif'
),
'Mate SC' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'serif'
),
'Uncial Antiqua' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Geo' => array(
'variants' => array('400', 'italic'),
'subsets' => array('latin'),
'category' => 'sans-serif'
),
'Henny Penny' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Monsieur La Doulaise' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'handwriting'
),
'Gugi' => array(
'variants' => array('400'),
'subsets' => array('latin', 'korean'),
'category' => 'display'
),
'Yatra One' => array(
'variants' => array('400'),
'subsets' => array('devanagari', 'latin-ext', 'latin'),
'category' => 'display'
),
'Baloo Tamma' => array(
'variants' => array('400'),
'subsets' => array('kannada', 'vietnamese', 'latin-ext', 'latin'),
'category' => 'display'
),
'Mukta Mahee' => array(
'variants' => array('200', '300', '400', '500', '600', '700', '800'),
'subsets' => array('gurmukhi', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Condiment' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'handwriting'
),
'B612 Mono' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('latin'),
'category' => 'monospace'
),
'Dorsa' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'sans-serif'
),
'Arya' => array(
'variants' => array('400', '700'),
'subsets' => array('devanagari', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Donegal One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'serif'
),
'Eagle Lake' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'handwriting'
),
'Fascinate Inline' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Lovers Quarrel' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'handwriting'
),
'Sirin Stencil' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Simonetta' => array(
'variants' => array('400', 'italic', '900', '900italic'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Paprika' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Major Mono Display' => array(
'variants' => array('400'),
'subsets' => array('vietnamese', 'latin-ext', 'latin'),
'category' => 'monospace'
),
'Coiny' => array(
'variants' => array('400'),
'subsets' => array('vietnamese', 'latin-ext', 'latin', 'tamil'),
'category' => 'display'
),
'Flamenco' => array(
'variants' => array('300', '400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Peralta' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Ramaraja' => array(
'variants' => array('400'),
'subsets' => array('telugu', 'latin'),
'category' => 'serif'
),
'Mystery Quest' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Inknut Antiqua' => array(
'variants' => array('300', '400', '500', '600', '700', '800', '900'),
'subsets' => array('devanagari', 'latin-ext', 'latin'),
'category' => 'serif'
),
'Amarante' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Cagliostro' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'sans-serif'
),
'Rum Raisin' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Port Lligat Sans' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'sans-serif'
),
'League Script' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'handwriting'
),
'Ruluko' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Sumana' => array(
'variants' => array('400', '700'),
'subsets' => array('devanagari', 'latin-ext', 'latin'),
'category' => 'serif'
),
'Nova Slim' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Literata' => array(
'variants' => array('400', '500', '600', '700', 'italic', '500italic', '600italic', '700italic'),
'subsets' => array('cyrillic', 'greek-ext', 'vietnamese', 'latin-ext', 'greek', 'latin'),
'category' => 'serif'
),
'Bubbler One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Buda' => array(
'variants' => array('300'),
'subsets' => array('latin'),
'category' => 'display'
),
'IM Fell French Canon' => array(
'variants' => array('400', 'italic'),
'subsets' => array('latin'),
'category' => 'serif'
),
'Sura' => array(
'variants' => array('400', '700'),
'subsets' => array('devanagari', 'latin-ext', 'latin'),
'category' => 'serif'
),
'Stint Ultra Condensed' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Maiden Orange' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Metal Mania' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Dokdo' => array(
'variants' => array('400'),
'subsets' => array('latin', 'korean'),
'category' => 'handwriting'
),
'Rosarivo' => array(
'variants' => array('400', 'italic'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'serif'
),
'Meie Script' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'handwriting'
),
'Junge' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'serif'
),
'Baloo Da' => array(
'variants' => array('400'),
'subsets' => array('vietnamese', 'latin-ext', 'latin', 'bengali'),
'category' => 'display'
),
'Chela One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Ribeye' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Stalemate' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'handwriting'
),
'Stint Ultra Expanded' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Atomic Age' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Nokora' => array(
'variants' => array('400', '700'),
'subsets' => array('khmer'),
'category' => 'serif'
),
'Bilbo' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'handwriting'
),
'Trade Winds' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Averia Gruesa Libre' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Snowburst One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Ruslan Display' => array(
'variants' => array('400'),
'subsets' => array('cyrillic', 'latin-ext', 'latin'),
'category' => 'display'
),
'Text Me One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Ewert' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Jacques Francois Shadow' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Gaegu' => array(
'variants' => array('300', '400', '700'),
'subsets' => array('latin', 'korean'),
'category' => 'handwriting'
),
'Delius Unicase' => array(
'variants' => array('400', '700'),
'subsets' => array('latin'),
'category' => 'handwriting'
),
'Farsan' => array(
'variants' => array('400'),
'subsets' => array('gujarati', 'vietnamese', 'latin-ext', 'latin'),
'category' => 'display'
),
'Londrina Shadow' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Overlock SC' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Underdog' => array(
'variants' => array('400'),
'subsets' => array('cyrillic', 'latin-ext', 'latin'),
'category' => 'display'
),
'Taprom' => array(
'variants' => array('400'),
'subsets' => array('khmer'),
'category' => 'display'
),
'Stoke' => array(
'variants' => array('300', '400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'serif'
),
'Ranga' => array(
'variants' => array('400', '700'),
'subsets' => array('devanagari', 'latin-ext', 'latin'),
'category' => 'display'
),
'Almendra Display' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Englebert' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Bungee Outline' => array(
'variants' => array('400'),
'subsets' => array('vietnamese', 'latin-ext', 'latin'),
'category' => 'display'
),
'IM Fell DW Pica SC' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'serif'
),
'Moul' => array(
'variants' => array('400'),
'subsets' => array('khmer'),
'category' => 'display'
),
'Ranchers' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Butterfly Kids' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'handwriting'
),
'New Rocker' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Offside' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Linden Hill' => array(
'variants' => array('400', 'italic'),
'subsets' => array('latin'),
'category' => 'serif'
),
'Revalia' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Geostar Fill' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'IM Fell Great Primer' => array(
'variants' => array('400', 'italic'),
'subsets' => array('latin'),
'category' => 'serif'
),
'Kavoon' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Plaster' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Fascinate' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Glass Antiqua' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Angkor' => array(
'variants' => array('400'),
'subsets' => array('khmer'),
'category' => 'display'
),
'Butcherman' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Mrs Sheppards' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'handwriting'
),
'Croissant One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Sonsie One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Mina' => array(
'variants' => array('400', '700'),
'subsets' => array('latin-ext', 'latin', 'bengali'),
'category' => 'sans-serif'
),
'Thasadith' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('thai', 'vietnamese', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Geostar' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Wellfleet' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Diplomata' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Julee' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'handwriting'
),
'Smythe' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Darker Grotesque' => array(
'variants' => array('300', '400', '500', '600', '700', '800', '900'),
'subsets' => array('vietnamese', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Margarine' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Bonbon' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'handwriting'
),
'Griffy' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Marko One' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'serif'
),
'Song Myung' => array(
'variants' => array('400'),
'subsets' => array('latin', 'korean'),
'category' => 'serif'
),
'Miniver' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Petrona' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'serif'
),
'Galdeano' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'sans-serif'
),
'Redressed' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'handwriting'
),
'Autour One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Modak' => array(
'variants' => array('400'),
'subsets' => array('devanagari', 'latin-ext', 'latin'),
'category' => 'display'
),
'Hanalei' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'DM Sans' => array(
'variants' => array('400', 'italic', '500', '500italic', '700', '700italic'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Bokor' => array(
'variants' => array('400'),
'subsets' => array('khmer'),
'category' => 'display'
),
'Ruthie' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'handwriting'
),
'Irish Grover' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Tillana' => array(
'variants' => array('400', '500', '600', '700', '800'),
'subsets' => array('devanagari', 'latin-ext', 'latin'),
'category' => 'handwriting'
),
'Della Respira' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'serif'
),
'Joti One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'IM Fell Great Primer SC' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'serif'
),
'Nova Flat' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Montaga' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'serif'
),
'Miss Fajardose' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'handwriting'
),
'Cormorant Unicase' => array(
'variants' => array('300', '400', '500', '600', '700'),
'subsets' => array('cyrillic', 'vietnamese', 'cyrillic-ext', 'latin-ext', 'latin'),
'category' => 'serif'
),
'Content' => array(
'variants' => array('400', '700'),
'subsets' => array('khmer'),
'category' => 'display'
),
'ZCOOL QingKe HuangYou' => array(
'variants' => array('400'),
'subsets' => array('chinese-simplified', 'latin'),
'category' => 'display'
),
'Jim Nightshade' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'handwriting'
),
'Elsie Swash Caps' => array(
'variants' => array('400', '900'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Trykker' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'serif'
),
'Inika' => array(
'variants' => array('400', '700'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'serif'
),
'Baloo Tammudu' => array(
'variants' => array('400'),
'subsets' => array('telugu', 'vietnamese', 'latin-ext', 'latin'),
'category' => 'display'
),
'UnifrakturCook' => array(
'variants' => array('700'),
'subsets' => array('latin'),
'category' => 'display'
),
'ZCOOL KuaiLe' => array(
'variants' => array('400'),
'subsets' => array('chinese-simplified', 'latin'),
'category' => 'display'
),
'Barrio' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'MedievalSharp' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Sree Krushnadevaraya' => array(
'variants' => array('400'),
'subsets' => array('telugu', 'latin'),
'category' => 'serif'
),
'Siemreap' => array(
'variants' => array('400'),
'subsets' => array('khmer'),
'category' => 'display'
),
'Rhodium Libre' => array(
'variants' => array('400'),
'subsets' => array('devanagari', 'latin-ext', 'latin'),
'category' => 'serif'
),
'Iceberg' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Charmonman' => array(
'variants' => array('400', '700'),
'subsets' => array('thai', 'vietnamese', 'latin-ext', 'latin'),
'category' => 'handwriting'
),
'Dangrek' => array(
'variants' => array('400'),
'subsets' => array('khmer'),
'category' => 'display'
),
'Monofett' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Ravi Prakash' => array(
'variants' => array('400'),
'subsets' => array('telugu', 'latin'),
'category' => 'display'
),
'Macondo Swash Caps' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Arbutus' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Astloch' => array(
'variants' => array('400', '700'),
'subsets' => array('latin'),
'category' => 'display'
),
'Keania One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Bahiana' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Poor Story' => array(
'variants' => array('400'),
'subsets' => array('latin', 'korean'),
'category' => 'display'
),
'Original Surfer' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Dr Sugiyama' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'handwriting'
),
'Diplomata SC' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Kodchasan' => array(
'variants' => array('200', '200italic', '300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic'),
'subsets' => array('thai', 'vietnamese', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Oldenburg' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Suwannaphum' => array(
'variants' => array('400'),
'subsets' => array('khmer'),
'category' => 'display'
),
'Asar' => array(
'variants' => array('400'),
'subsets' => array('devanagari', 'latin-ext', 'latin'),
'category' => 'serif'
),
'Bigshot One' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Chathura' => array(
'variants' => array('100', '300', '400', '700', '800'),
'subsets' => array('telugu', 'latin'),
'category' => 'sans-serif'
),
'KoHo' => array(
'variants' => array('200', '200italic', '300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic'),
'subsets' => array('thai', 'vietnamese', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Lancelot' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Ribeye Marrow' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Ruge Boogie' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'handwriting'
),
'Zilla Slab Highlight' => array(
'variants' => array('400', '700'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Purple Purse' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'IM Fell French Canon SC' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'serif'
),
'Snippet' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'sans-serif'
),
'Devonshire' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'handwriting'
),
'Felipa' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'handwriting'
),
'Kantumruy' => array(
'variants' => array('300', '400', '700'),
'subsets' => array('khmer'),
'category' => 'sans-serif'
),
'Modern Antiqua' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Asset' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Chango' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Molle' => array(
'variants' => array('italic'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'handwriting'
),
'Goblin One' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Sahitya' => array(
'variants' => array('400', '700'),
'subsets' => array('devanagari', 'latin'),
'category' => 'serif'
),
'ZCOOL XiaoWei' => array(
'variants' => array('400'),
'subsets' => array('chinese-simplified', 'latin'),
'category' => 'serif'
),
'Jomhuria' => array(
'variants' => array('400'),
'subsets' => array('arabic', 'latin-ext', 'latin'),
'category' => 'display'
),
'Freehand' => array(
'variants' => array('400'),
'subsets' => array('khmer'),
'category' => 'display'
),
'Stylish' => array(
'variants' => array('400'),
'subsets' => array('latin', 'korean'),
'category' => 'sans-serif'
),
'Chenla' => array(
'variants' => array('400'),
'subsets' => array('khmer'),
'category' => 'display'
),
'Vollkorn SC' => array(
'variants' => array('400', '600', '700', '900'),
'subsets' => array('cyrillic', 'vietnamese', 'cyrillic-ext', 'latin-ext', 'latin'),
'category' => 'serif'
),
'Odor Mean Chey' => array(
'variants' => array('400'),
'subsets' => array('khmer'),
'category' => 'display'
),
'Miltonian Tattoo' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Bayon' => array(
'variants' => array('400'),
'subsets' => array('khmer'),
'category' => 'display'
),
'IM Fell Double Pica SC' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'serif'
),
'Libre Barcode 39 Extended Text' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Flavors' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Kavivanar' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin', 'tamil'),
'category' => 'handwriting'
),
'Caesar Dressing' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Seymour One' => array(
'variants' => array('400'),
'subsets' => array('cyrillic', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Galindo' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Libre Barcode 39 Text' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Piedra' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Kenia' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Kirang Haerang' => array(
'variants' => array('400'),
'subsets' => array('latin', 'korean'),
'category' => 'display'
),
'Libre Barcode 128' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Smokum' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Romanesco' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'handwriting'
),
'Fahkwang' => array(
'variants' => array('200', '200italic', '300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic'),
'subsets' => array('thai', 'vietnamese', 'latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Libre Barcode 39 Extended' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Almendra SC' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'serif'
),
'East Sea Dokdo' => array(
'variants' => array('400'),
'subsets' => array('latin', 'korean'),
'category' => 'handwriting'
),
'Sunshiney' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'handwriting'
),
'GFS Neohellenic' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('greek'),
'category' => 'sans-serif'
),
'Jacques Francois' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'serif'
),
'Macondo' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Lakki Reddy' => array(
'variants' => array('400'),
'subsets' => array('telugu', 'latin'),
'category' => 'handwriting'
),
'Gorditas' => array(
'variants' => array('400', '700'),
'subsets' => array('latin'),
'category' => 'display'
),
'Jolly Lodger' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Tenali Ramakrishna' => array(
'variants' => array('400'),
'subsets' => array('telugu', 'latin'),
'category' => 'sans-serif'
),
'Nova Cut' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Srisakdi' => array(
'variants' => array('400', '700'),
'subsets' => array('thai', 'vietnamese', 'latin-ext', 'latin'),
'category' => 'display'
),
'Londrina Sketch' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Trochut' => array(
'variants' => array('400', 'italic', '700'),
'subsets' => array('latin'),
'category' => 'display'
),
'Nova Script' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Combo' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Notable' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'sans-serif'
),
'Nova Oval' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Sedgwick Ave Display' => array(
'variants' => array('400'),
'subsets' => array('vietnamese', 'latin-ext', 'latin'),
'category' => 'handwriting'
),
'Metal' => array(
'variants' => array('400'),
'subsets' => array('khmer'),
'category' => 'display'
),
'Risque' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Gamja Flower' => array(
'variants' => array('400'),
'subsets' => array('latin', 'korean'),
'category' => 'handwriting'
),
'Miltonian' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Kumar One Outline' => array(
'variants' => array('400'),
'subsets' => array('gujarati', 'latin-ext', 'latin'),
'category' => 'display'
),
'Preahvihear' => array(
'variants' => array('400'),
'subsets' => array('khmer'),
'category' => 'display'
),
'Supermercado One' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Black And White Picture' => array(
'variants' => array('400'),
'subsets' => array('latin', 'korean'),
'category' => 'sans-serif'
),
'Passero One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Sevillana' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Sofadi One' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Cute Font' => array(
'variants' => array('400'),
'subsets' => array('latin', 'korean'),
'category' => 'display'
),
'Bigelow Rules' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Mr Bedfort' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'handwriting'
),
'B612' => array(
'variants' => array('400', 'italic', '700', '700italic'),
'subsets' => array('latin'),
'category' => 'sans-serif'
),
'Erica One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Bungee Hairline' => array(
'variants' => array('400'),
'subsets' => array('vietnamese', 'latin-ext', 'latin'),
'category' => 'display'
),
'Gidugu' => array(
'variants' => array('400'),
'subsets' => array('telugu', 'latin'),
'category' => 'sans-serif'
),
'Suravaram' => array(
'variants' => array('400'),
'subsets' => array('telugu', 'latin'),
'category' => 'serif'
),
'Fruktur' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Aubrey' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Peddana' => array(
'variants' => array('400'),
'subsets' => array('telugu', 'latin'),
'category' => 'serif'
),
'Emblema One' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Federant' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Kdam Thmor' => array(
'variants' => array('400'),
'subsets' => array('khmer'),
'category' => 'display'
),
'Unlock' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Libre Barcode 128 Text' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'display'
),
'Fasthand' => array(
'variants' => array('400'),
'subsets' => array('khmer'),
'category' => 'serif'
),
'Barriecito' => array(
'variants' => array('400'),
'subsets' => array('vietnamese', 'latin-ext', 'latin'),
'category' => 'display'
),
'DM Serif Text' => array(
'variants' => array('400', 'italic'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'serif'
),
'Yeon Sung' => array(
'variants' => array('400'),
'subsets' => array('latin', 'korean'),
'category' => 'display'
),
'Stalinist One' => array(
'variants' => array('400'),
'subsets' => array('cyrillic', 'latin-ext', 'latin'),
'category' => 'display'
),
'Dhurjati' => array(
'variants' => array('400'),
'subsets' => array('telugu', 'latin'),
'category' => 'sans-serif'
),
'BioRhyme Expanded' => array(
'variants' => array('200', '300', '400', '700', '800'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'serif'
),
'Moulpali' => array(
'variants' => array('400'),
'subsets' => array('khmer'),
'category' => 'display'
),
'Hi Melody' => array(
'variants' => array('400'),
'subsets' => array('latin', 'korean'),
'category' => 'handwriting'
),
'Warnes' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'display'
),
'Bahianita' => array(
'variants' => array('400'),
'subsets' => array('vietnamese', 'latin-ext', 'latin'),
'category' => 'display'
),
'Libre Caslon Text' => array(
'variants' => array('400', 'italic', '700'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'serif'
),
'Fira Code' => array(
'variants' => array('300', '400', '500', '600', '700'),
'subsets' => array('cyrillic', 'greek-ext', 'cyrillic-ext', 'latin-ext', 'greek', 'latin'),
'category' => 'monospace'
),
'Red Hat Text' => array(
'variants' => array('400', 'italic', '500', '500italic', '700', '700italic'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Beth Ellen' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'handwriting'
),
'Liu Jian Mao Cao' => array(
'variants' => array('400'),
'subsets' => array('chinese-simplified', 'latin'),
'category' => 'handwriting'
),
'Blinker' => array(
'variants' => array('100', '200', '300', '400', '600', '700', '800', '900'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Red Hat Display' => array(
'variants' => array('400', 'italic', '500', '500italic', '700', '700italic', '900', '900italic'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Libre Caslon Display' => array(
'variants' => array('400'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'serif'
),
'Zhi Mang Xing' => array(
'variants' => array('400'),
'subsets' => array('chinese-simplified', 'latin'),
'category' => 'handwriting'
),
'Ma Shan Zheng' => array(
'variants' => array('400'),
'subsets' => array('chinese-simplified', 'latin'),
'category' => 'handwriting'
),
'Single Day' => array(
'variants' => array('400'),
'subsets' => array('korean'),
'category' => 'display'
),
'Saira Stencil One' => array(
'variants' => array('400'),
'subsets' => array('vietnamese', 'latin-ext', 'latin'),
'category' => 'display'
),
'Crimson Pro' => array(
'variants' => array('200', '300', '400', '500', '600', '700', '800', '900', '200italic', '300italic', 'italic', '500italic', '600italic', '700italic', '800italic', '900italic'),
'subsets' => array('vietnamese', 'latin-ext', 'latin'),
'category' => 'serif'
),
'Long Cang' => array(
'variants' => array('400'),
'subsets' => array('chinese-simplified', 'latin'),
'category' => 'handwriting'
),
'Grenze' => array(
'variants' => array('100', '100italic', '200', '200italic', '300', '300italic', '400', 'italic', '500', '500italic', '600', '600italic', '700', '700italic', '800', '800italic', '900', '900italic'),
'subsets' => array('vietnamese', 'latin-ext', 'latin'),
'category' => 'serif'
),
'Farro' => array(
'variants' => array('300', '400', '500', '700'),
'subsets' => array('latin-ext', 'latin'),
'category' => 'sans-serif'
),
'Lacquer' => array(
'variants' => array('400'),
'subsets' => array('latin'),
'category' => 'sans-serif'
)
);

?>