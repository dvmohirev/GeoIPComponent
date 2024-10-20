<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>
<?php
use Bitrix\Main\Page\Asset;
//подключаем CSS для Bootstrap
Asset::getInstance()->addString('<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">');
?>

<? if ($arResult["isFormNote"] === "Y"): ?>
    <div class="container hide_data additional_margin">
        <div>
            IP-адрес: <span id="self_ip"><?=$arResult["RESULT_GEOIP"]['ip']?></span>
        </div>
        <div>
            Адрес: <span id="address_ip"><br><?=$arResult["RESULT_GEOIP"]['address']?></span>
        </div>
    </div>
<? else: ?>
    <?=$arResult["FORM_HEADER"]?>
    <input type="hidden" name="web_form_submit" value="Y">

    <? if ($arResult["isFormErrors"] === "Y"): ?>
        <div class="errors">
            <?=$arResult["FORM_ERRORS_TEXT"]?>
        </div>
    <? endif; ?>
    <div class="form-group">
        <label><?=$arResult["QUESTIONS"]['IP_ADDRESS']['CAPTION']?></label>
        <?=($arResult["QUESTIONS"]['IP_ADDRESS']['REQUIRED'] === 'Y' ? ' *' : '')?>:
        <?php /*=$arResult["QUESTIONS"]['IP_ADDRESS']['HTML_CODE']*/?>
        <?=$arResult['new_inputs']?>
        <?php /*=$arResult['funcGetInputHtml']($arResult["QUESTIONS"]['IP_ADDRESS'], $arResult['arrVALUES'])*/?>

    </div>

    <input type="submit" class="btn btn-primary" value="<?=$arResult["arForm"]["BUTTON"]?>">

    <?=$arResult["FORM_FOOTER"]?>
<? endif; ?>
<?php
//подключаем JS для Bootstrap
Asset::getInstance()->addString('<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>');
Asset::getInstance()->addString('<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>');
Asset::getInstance()->addString('<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>');
?>
