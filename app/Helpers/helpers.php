<?php
/**
 * File name: helpers.php
 * Last modified: 2020.06.11 at 16:10:52
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2020
 */

use InfyOm\Generator\Common\GeneratorCuisine;
use InfyOm\Generator\Utils\GeneratorCuisinesInputUtil;
use InfyOm\Generator\Utils\HTMLCuisineGenerator;
use Symfony\Component\Debug\Exception\FatalThrowableError;
use App\Models\Category;

require_once(__DIR__ . '/PDFMerger/PDFMerger.php');

/**
 * @param $bytes
 * @param int $precision
 * @return string
 */
function formatedSize($bytes, $precision = 1)
{
    $units = array('B', 'KB', 'MB', 'GB', 'TB');

    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);

    $bytes /= pow(1024, $pow);

    return round($bytes, $precision) . ' ' . $units[$pow];
}

function getMediaColumn($mediaModel, $mediaCollectionName = '', $extraClass = '', $mediaThumbnail = 'icon')
{
    $extraClass = $extraClass == '' ? ' rounded ' : $extraClass;

    if ($mediaModel->hasMedia($mediaCollectionName)) {
        return "<img class='" . $extraClass . "' style='width:50px' src='" . $mediaModel->getFirstMediaUrl($mediaCollectionName, $mediaThumbnail) . "' alt='" . $mediaModel->getFirstMedia($mediaCollectionName)->name . "'>";
    } else {
        return "<img class='" . $extraClass . "' style='width:50px' src='" . asset('images/image_default.png') . "' alt='image_default'>";
    }
}

/**
 * @param $modelObject
 * @param string $attributeName
 * @return null|string|string[]
 */
function getDateColumn($modelObject, $attributeName = 'updated_at')
{
    if (setting('is_human_date_format', false)) {
        $html = '<p data-toggle="tooltip" data-placement="bottom" title="${date}">${dateHuman}</p>';
    } else {
        $html = '<p data-toggle="tooltip" data-placement="bottom" title="${dateHuman}">${date}</p>';
    }
    if (!isset($modelObject[$attributeName])) {
        return '';
    }
    $dateObj = new Carbon\Carbon($modelObject[$attributeName]);
    $replace = preg_replace('/\$\{date\}/', $dateObj->format(setting('date_format', 'l jS F Y (h:i:s)')), $html);
    $replace = preg_replace('/\$\{dateHuman\}/', $dateObj->diffForHumans(), $replace);
    return $replace;
}

function getPriceColumn($modelObject, $attributeName = 'price', $currency = null)
{

    if ($modelObject[$attributeName] != null && strlen($modelObject[$attributeName]) > 0) {
        $modelObject[$attributeName] = number_format((float)$modelObject[$attributeName], 2, '.', '');
        if (setting('currency_right', false) != false) {
            return $modelObject[$attributeName] . "<span>" . ($currency ?? setting('default_currency')) . "</span>";
        } else {
            return "<span>" . ($currency ?? setting('default_currency')) . "</span>" . $modelObject[$attributeName];
        }
    }
    return '-';
}

function getPrice($price = 0)
{
    if (setting('currency_right', false) != false) {
        return number_format((float)$price, 2, '.', '') . "<span>" . setting('default_currency') . "</span>";
    } else {
        return "<span>" . setting('default_currency') . "</span>" . number_format((float)$price, 2, '.', ' ');
    }
}

/**
 * generate boolean column for datatable
 * @param $column
 * @return string
 */
function getBooleanColumn($column, $attributeName)
{
    if (isset($column)) {
        if ($column[$attributeName]) {
            return "<span class='badge badge-success'>" . trans('lang.yes') . "</span>";
        } else {
            return "<span class='badge badge-danger'>" . trans('lang.no') . "</span>";
        }
    }
}

/**
 * generate not boolean column for datatable
 * @param $column
 * @return string
 */
function getNotBooleanColumn($column, $attributeName)
{
    if (isset($column)) {
        if ($column[$attributeName]) {
            return "<span class='badge badge-danger'>" . trans('lang.yes') . "</span>";
        } else {
            return "<span class='badge badge-success'>" . trans('lang.no') . "</span>";
        }
    }
}

/**
 * generate order payment column for datatable
 * @param $column
 * @return string
 */
function getPayment($column, $attributeName)
{
    if (isset($column) && $column[$attributeName]) {
        return "<span class='badge badge-success'>" . $column[$attributeName] . "</span> ";
    } else {
        return "<span class='badge badge-danger'>" . trans('lang.order_not_paid') . "</span>";
    }
}

/**
 * @param array $array
 * @param $baseUrl
 * @param string $idAttribute
 * @param string $titleAttribute
 * @return string
 */
function getLinksColumn($array, $baseUrl, $idAttribute = 'id', $titleAttribute = 'title')
{
    $html = '<a href="${href}" class="text-bold text-dark">${title}</a>';
    $result = [];
    foreach ($array as $link) {
        $replace = preg_replace('/\$\{href\}/', url($baseUrl, $link[$idAttribute]), $html);
        $replace = preg_replace('/\$\{title\}/', $link[$titleAttribute], $replace);
        $result[] = $replace;
    }
    return implode(', ', $result);
}

/**
 * @param array $array
 * @param $routeName
 * @param string $idAttribute
 * @param string $titleAttribute
 * @return string
 */
function getLinksColumnByRouteName($array, $routeName, $idAttribute = 'id', $titleAttribute = 'title')
{
    $html = '<a href="${href}" class="text-bold text-dark">${title}</a>';
    $result = [];
    foreach ($array as $link) {
        $replace = preg_replace('/\$\{href\}/', route($routeName, $link[$idAttribute]), $html);
        $replace = preg_replace('/\$\{title\}/', $link[$titleAttribute], $replace);
        $result[] = $replace;
    }
    return implode(', ', $result);
}

function getArrayColumn($array = [], $titleAttribute = 'title', $extraClass = '', $separator = ', ')
{
    $result = [];
    foreach ($array as $link) {
        $title = $link[$titleAttribute];
//        $replace = preg_replace('/\$\{href\}/', url($baseUrl, $link[$idAttribute]), $html);
//        $replace = preg_replace('/\$\{title\}/', $link[$titleAttribute], $replace);
        $html = "<span class='{$extraClass}'>{$title}</span>";
        $result[] = $html;
    }
    return implode($separator, $result);
}

function getEmailColumn($column, $attributeName)
{
    if (isset($column)) {
        if ($column[$attributeName]) {
            return "<a class='btn btn-outline-secondary btn-sm' href='mailto:" . $column[$attributeName] . "'><i class='fa fa-envelope mr-1'></i>" . $column[$attributeName] . "</a>";
        } else {
            return '';
        }
    }
}

/**
 * get available languages on the application
 */
function getAvailableLanguages()
{
    $dir = base_path('resources/lang');
    $languages = array_diff(scandir($dir), array('..', '.'));
    $languages = array_map(function ($value) {
        return ['id' => $value, 'value' => trans('lang.app_setting_' . $value)];
    }, $languages);

    return array_column($languages, 'value', 'id');
}

/**
 * get all languages
 */

function getLanguages()
{

    return array(
        'aa' => 'Afar',
        'ab' => 'Abkhaz',
        'ae' => 'Avestan',
        'af' => 'Afrikaans',
        'ak' => 'Akan',
        'am' => 'Amharic',
        'an' => 'Aragonese',
        'ar' => 'Arabic',
        'as' => 'Assamese',
        'av' => 'Avaric',
        'ay' => 'Aymara',
        'az' => 'Azerbaijani',
        'ba' => 'Bashkir',
        'be' => 'Belarusian',
        'bg' => 'Bulgarian',
        'bh' => 'Bihari',
        'bi' => 'Bislama',
        'bm' => 'Bambara',
        'bn' => 'Bengali',
        'bo' => 'Tibetan Standard, Tibetan, Central',
        'br' => 'Breton',
        'bs' => 'Bosnian',
        'ca' => 'Catalan; Valencian',
        'ce' => 'Chechen',
        'ch' => 'Chamorro',
        'co' => 'Corsican',
        'cr' => 'Cree',
        'cs' => 'Czech',
        'cu' => 'Old Church Slavonic, Church Slavic, Church Slavonic, Old Bulgarian, Old Slavonic',
        'cv' => 'Chuvash',
        'cy' => 'Welsh',
        'da' => 'Danish',
        'de' => 'German',
        'dv' => 'Divehi; Dhivehi; Maldivian;',
        'dz' => 'Dzongkha',
        'ee' => 'Ewe',
        'el' => 'Greek, Modern',
        'en' => 'English',
        'eo' => 'Esperanto',
        'es' => 'Spanish; Castilian',
        'et' => 'Estonian',
        'eu' => 'Basque',
        'fa' => 'Persian',
        'ff' => 'Fula; Fulah; Pulaar; Pular',
        'fi' => 'Finnish',
        'fj' => 'Fijian',
        'fo' => 'Faroese',
        'fr' => 'French',
        'fy' => 'Western Frisian',
        'ga' => 'Irish',
        'gd' => 'Scottish Gaelic; Gaelic',
        'gl' => 'Galician',
        'gn' => 'GuaranÃƒÂ­',
        'gu' => 'Gujarati',
        'gv' => 'Manx',
        'ha' => 'Hausa',
        'he' => 'Hebrew (modern)',
        'hi' => 'Hindi',
        'ho' => 'Hiri Motu',
        'hr' => 'Croatian',
        'ht' => 'Haitian; Haitian Creole',
        'hu' => 'Hungarian',
        'hy' => 'Armenian',
        'hz' => 'Herero',
        'ia' => 'Interlingua',
        'id' => 'Indonesian',
        'ie' => 'Interlingue',
        'ig' => 'Igbo',
        'ii' => 'Nuosu',
        'ik' => 'Inupiaq',
        'io' => 'Ido',
        'is' => 'Icelandic',
        'it' => 'Italian',
        'iu' => 'Inuktitut',
        'ja' => 'Japanese (ja)',
        'jv' => 'Javanese (jv)',
        'ka' => 'Georgian',
        'kg' => 'Kongo',
        'ki' => 'Kikuyu, Gikuyu',
        'kj' => 'Kwanyama, Kuanyama',
        'kk' => 'Kazakh',
        'kl' => 'Kalaallisut, Greenlandic',
        'km' => 'Khmer',
        'kn' => 'Kannada',
        'ko' => 'Korean',
        'kr' => 'Kanuri',
        'ks' => 'Kashmiri',
        'ku' => 'Kurdish',
        'kv' => 'Komi',
        'kw' => 'Cornish',
        'ky' => 'Kirghiz, Kyrgyz',
        'la' => 'Latin',
        'lb' => 'Luxembourgish, Letzeburgesch',
        'lg' => 'Luganda',
        'li' => 'Limburgish, Limburgan, Limburger',
        'ln' => 'Lingala',
        'lo' => 'Lao',
        'lt' => 'Lithuanian',
        'lu' => 'Luba-Katanga',
        'lv' => 'Latvian',
        'mg' => 'Malagasy',
        'mh' => 'Marshallese',
        'mi' => 'Maori',
        'mk' => 'Macedonian',
        'ml' => 'Malayalam',
        'mn' => 'Mongolian',
        'mr' => 'Marathi (Mara?hi)',
        'ms' => 'Malay',
        'mt' => 'Maltese',
        'my' => 'Burmese',
        'na' => 'Nauru',
        'nb' => 'Norwegian BokmÃƒÂ¥l',
        'nd' => 'North Ndebele',
        'ne' => 'Nepali',
        'ng' => 'Ndonga',
        'nl' => 'Dutch',
        'nn' => 'Norwegian Nynorsk',
        'no' => 'Norwegian',
        'nr' => 'South Ndebele',
        'nv' => 'Navajo, Navaho',
        'ny' => 'Chichewa; Chewa; Nyanja',
        'oc' => 'Occitan',
        'oj' => 'Ojibwe, Ojibwa',
        'om' => 'Oromo',
        'or' => 'Oriya',
        'os' => 'Ossetian, Ossetic',
        'pa' => 'Panjabi, Punjabi',
        'pi' => 'Pali',
        'pl' => 'Polish',
        'ps' => 'Pashto, Pushto',
        'pt' => 'Portuguese',
        'qu' => 'Quechua',
        'rm' => 'Romansh',
        'rn' => 'Kirundi',
        'ro' => 'Romanian, Moldavian, Moldovan',
        'ru' => 'Russian',
        'rw' => 'Kinyarwanda',
        'sa' => 'Sanskrit (Sa?sk?ta)',
        'sc' => 'Sardinian',
        'sd' => 'Sindhi',
        'se' => 'Northern Sami',
        'sg' => 'Sango',
        'si' => 'Sinhala, Sinhalese',
        'sk' => 'Slovak',
        'sl' => 'Slovene',
        'sm' => 'Samoan',
        'sn' => 'Shona',
        'so' => 'Somali',
        'sq' => 'Albanian',
        'sr' => 'Serbian',
        'ss' => 'Swati',
        'st' => 'Southern Sotho',
        'su' => 'Sundanese',
        'sv' => 'Swedish',
        'sw' => 'Swahili',
        'ta' => 'Tamil',
        'te' => 'Telugu',
        'tg' => 'Tajik',
        'th' => 'Thai',
        'ti' => 'Tigrinya',
        'tk' => 'Turkmen',
        'tl' => 'Tagalog',
        'tn' => 'Tswana',
        'to' => 'Tonga (Tonga Islands)',
        'tr' => 'Turkish',
        'ts' => 'Tsonga',
        'tt' => 'Tatar',
        'tw' => 'Twi',
        'ty' => 'Tahitian',
        'ug' => 'Uighur, Uyghur',
        'uk' => 'Ukrainian',
        'ur' => 'Urdu',
        'uz' => 'Uzbek',
        've' => 'Venda',
        'vi' => 'Vietnamese',
        'vo' => 'VolapÃƒÂ¼k',
        'wa' => 'Walloon',
        'wo' => 'Wolof',
        'xh' => 'Xhosa',
        'yi' => 'Yiddish',
        'yo' => 'Yoruba',
        'za' => 'Zhuang, Chuang',
        'zh' => 'Chinese',
        'zu' => 'Zulu',
    );

}

function generateCustomField($fields, $fieldsValues = null)
{
    $htmlFields = [];
    $startSeparator = '<div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">';
    $endSeparator = '</div>';
    foreach ($fields as $field) {
        $dynamicVars = [
            '$RANDOM_VARIABLE$' => 'var' . time() . rand() . 'ble',
            '$FIELD_NAME$' => $field->name,
            '$DISABLED$' => $field->disabled === true ? '"disabled" => "disabled",' : '',
            '$REQUIRED$' => $field->required === true ? '"required" => "required",' : '',
            '$MODEL_NAME_SNAKE$' => getOnlyClassName($field->custom_field_model),
            '$FIELD_VALUE$' => 'null',
            '$INPUT_ARR_SELECTED$' => '[]',

        ];
        $gf = new \InfyOm\Generator\Common\GeneratorField();
        if ($fieldsValues) {
            foreach ($fieldsValues as $value) {
                if ($field->id === $value->customField->id) {
                    $dynamicVars['$INPUT_ARR_SELECTED$'] = $value->value ? $value->value: '[]';
                    $dynamicVars['$FIELD_VALUE$'] = '\'' . addslashes($value->value) . '\'';
                    $gf->validations[] = $value->value;
                    continue;
                }
            }
        }
        // dd($gf->validations);
        $gf->htmlType = $field['type'];
        $gf->htmlValues = $field['values'];
        $gf->dbInput = '';
        if ($field['type'] === 'selects') {
            $gf->htmlType = 'select';
            $gf->dbInput = 'hidden,mtm';
        }
        $fieldTemplate = \InfyOm\Generator\Utils\HTMLFieldGenerator::generateCustomFieldHTML($gf, config('infyom.laravel_generator.templates', 'adminlte-templates'));


        if (!empty($fieldTemplate)) {
            foreach ($dynamicVars as $variable => $value) {
                $fieldTemplate = str_replace($variable, $value, $fieldTemplate);
            }
            $htmlFields[] = $fieldTemplate;
        }
//    dd($fieldTemplate);
    }
    foreach ($htmlFields as $index => $field) {
        if (round(count($htmlFields) / 2) == $index + 1) {
            $htmlFields[$index] = $htmlFields[$index] . "\n" . $endSeparator . "\n" . $startSeparator;
        }
    }
    $htmlFieldsString = implode("\n\n", $htmlFields);
    $htmlFieldsString = $startSeparator . "\n" . $htmlFieldsString . "\n" . $endSeparator;
//    dd($htmlFieldsString);
    $renderedHtml = "";
    try {
        $renderedHtml = render(Blade::compileString($htmlFieldsString));
//        dd($renderedHtml);
    } catch (FatalThrowableError $e) {
    }
    return $renderedHtml;
}

/**
 * render php code in string give with compiling data
 *
 * @param $__php
 * @param null $__data
 * @return string
 * @throws FatalThrowableError
 */
function render($__php, $__data = null)
{
    $obLevel = ob_get_level();
    ob_start();
    if ($__data) {
        extract($__data, EXTR_SKIP);
    }
    try {
        eval('?' . '>' . $__php);
    } catch (Exception $e) {
        while (ob_get_level() > $obLevel) ob_end_clean();
        throw $e;
    } catch (Throwable $e) {
        while (ob_get_level() > $obLevel) ob_end_clean();
        throw new FatalThrowableError($e);
    }
    return ob_get_clean();
}

/**
 * @param $categoryId
 * @param $request
 * @return array
 */
function getCustomAttributeValues($categoryId, $request, $advId)
{
    if (!$request || !$categoryId) return [];

    $values = [];

    $customAttributes = \App\Models\Category::find($categoryId)->customAttribute;
    foreach ($customAttributes as $ca) {
        $values[] = [
            'value' => $request->input($ca->name),
            'attribute_id' => $ca->id,
            'advertisement_id' => $advId
        ];
    }

    return $values;
}

/**
 * get custom field value from custom fields collection given
 * @param null $customFields
 * @param $request
 * @return array
 */
function getCustomFieldsValues($customFields = null, $request = null)
{

    if (!$customFields) {
        return [];
    }
    if (!$request) {
        return [];
    }
    $customFieldsValues = [];
    foreach ($customFields as $cf) {
        $value = $request->input($cf->name);
        $view = $value;
        $fieldType = $cf->type;
        if ($fieldType === 'selects') {
            $view = GeneratorFieldsInputUtil::prepareKeyValueArrFromLabelValueStr($cf->values);
            $view = array_filter($view, function ($v) use ($value) {
                return in_array($v, $value);
            });
            $view = implode(', ', array_flip($view));
            $value = json_encode($value);
        } elseif ($fieldType === 'select' || $fieldType === 'radio') {
            $view = GeneratorFieldsInputUtil::prepareKeyValueArrFromLabelValueStr($cf->values);
            $view = array_flip($view)[$value];
        } elseif ($fieldType === 'boolean') {
            $view = getBooleanColumn(['0' => $view], '0');

        } elseif ($fieldType === 'password') {
            $view = str_repeat('•', strlen($value));
            $value = bcrypt($value);
        } elseif ($fieldType === 'date') {
            $view = getDateColumn(['date' => $view], 'date');
        } elseif ($fieldType === 'email') {
            $view = getEmailColumn(['email' => $view], 'email');
        } elseif ($fieldType === 'textarea') {
            $view = strip_tags($view);
        }


        $customFieldsValues[] = [
            'custom_field_id' => $cf->id,
            'value' => $value,
            'view' => $view
        ];
    }

    return $customFieldsValues;
}


/**
 * convert an array to assoc array using one attribute in the array
 * 0 => [
 *      name => 'The_Name'
 *      title => 'TITLE'
 * ]
 *
 * to
 *
 * The_Name => [
 *      name => 'The_Name'
 *      title => 'TITLE'
 * ]
 */
function convertToAssoc($collection, $key)
{
    $newCollection = [];
    foreach ($collection as $c) {
        $newCollection[$c[$key]] = $c;
    }
    return $newCollection;
}

/**
 * Get class name by giving the full name of th class
 * Ex:
 * $fullClassName = App\Models\UserModel
 * $isSnake = true
 * return
 * user_model
 * $fullClassName = App\Models\UserModel
 * $isSnake = false
 * return
 * UserModel
 * @param $fullClassName
 * @param bool $isSnake
 * @return mixed|string
 */
function getOnlyClassName($fullClassName, $isSnake = true)
{
    $modelNames = preg_split('/\\\\/', $fullClassName);
    if ($isSnake) {
        return snake_case(end($modelNames));
    }
    return end($modelNames);

}

function getModelsClasses(string $dir, array $excepts = null)
{
    if ($excepts === null) {
        $excepts = [
            'App\Models\Upload',
            'App\Models\CustomField',
            'App\Models\Media',
            'App\Models\CustomFieldValue',
        ];
    }
    $customFieldModels = array();
    $cdir = scandir($dir);
    foreach ($cdir as $key => $value) {
        if (!in_array($value, array(".", ".."))) {
            if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) {
                $customFieldModels[$value] = getModelsClasses($dir . DIRECTORY_SEPARATOR . $value);
            } else {
                $fullClassName = "App\\Models\\" . basename($value, '.php');
                if (!in_array($fullClassName, $excepts)) {
                    $customFieldModels[$fullClassName] = trans('lang.' . snake_case(basename($value, '.php')) . '_plural');
                }

            }
        }
    }
    return $customFieldModels;
}

function getCategoryChildren($category)
{
    for ($index = 0; $index < count($category->children); $index++) {
        $category->children[$index]['custom_attribute'] = $category->customAttribute;
        return $category->children[$index]['children'] = getCategoryChildren($category->children[$index]);
    }

    return $category->children;
}

function getCategoryParentsTree($category)
{
    $parents = collect([]);

    $parent = Category::find($category->parent_id);

    while(!is_null($parent)) {
        $parents->push($parent);
        $parent = Category::find($parent->parent_id);
    }

    return $parents;
}

function allParents($child)
{
    $tree = [];

    while($child) {
        array_push($tree, $child);
        $child = $child->parent;
    }

    return $tree;
}

/**
 * Get the login username to be used by the controller.
 *
 * @return string
 */
function findUsername()
{
    $login = request()->input('login');
    $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'login';
    request()->merge([$fieldType => $login]);
    return $fieldType;
}

function splitPdf($bookPath)
{
    $basePath = base_path();
    $command = "$basePath/pdfsplitter-js/pdf-split.js --filename=$bookPath --excerpt-page-count=20";
    if (substr(php_uname(), 0, 7) == "Windows"){
        pclose(popen("start /B node ". $command, "r"));
    } else {
        shell_exec("/usr/local/bin/node " . $command . " > /dev/null");
        dd($command);
    }
}

function deleteDirWithFiles($dir)
{
    try {
        $it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new RecursiveIteratorIterator($it,
            RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($files as $file) {
            if ($file->isDir()) {
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }
        rmdir($dir);
    } catch (Exception $e) {}
}
