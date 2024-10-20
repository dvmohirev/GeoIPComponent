<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

foreach ($arResult["QUESTIONS"]['IP_ADDRESS']['STRUCTURE'] as $item) {
    $id = $item['ID'];
    $type = $item['FIELD_TYPE'];
    $name = "form_{$type}_{$id}";

}
$required = $arResult["QUESTIONS"]['IP_ADDRESS']['REQUIRED'] === 'Y' ? 'required' : '';
$class = ' ' . $arResult["QUESTIONS"]['IP_ADDRESS']['STRUCTURE'][0]['FIELD_PARAM'];
$arResult['new_inputs'] = "<input class=\"form-control {$class}\" type=\"text\" name=\"{$name}\" value=\"{$value}\" {$required}>";
