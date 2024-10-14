<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule('highloadblock');
use \Bitrix\Main\Application,
    \Bitrix\Main\Web\Uri,
    \Bitrix\Main\Web\HttpClient;

function createHlBlockIP(): int
{
    //создание HL-блока
    $createHlBlock = \Bitrix\Highloadblock\HighloadBlockTable::add(array(
        'NAME' => 'ListIP',
        'TABLE_NAME' => 'iplist',
    ));

    if ($createHlBlock->isSuccess()){
        $id = $createHlBlock->getId();
        $UFObject = 'HLBLOCK_'.$id;
        //создание пользовательских полей HL-блока
        $arCartFields = Array(
            'UF_IP_ADDRESS'=>Array(
                'ENTITY_ID' => $UFObject,
                'FIELD_NAME' => 'UF_IP_ADDRESS',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                "EDIT_FORM_LABEL" => Array('ru'=>'IP-адрес'),
                "LIST_COLUMN_LABEL" => Array('ru'=>'IP-адрес'),
                "LIST_FILTER_LABEL" => Array('ru'=>'IP-адрес'),
                "ERROR_MESSAGE" => Array('ru'=>'', 'en'=>''),
                "HELP_MESSAGE" => Array('ru'=>'', 'en'=>''),
            ),
            'UF_ADDRESS'=>Array(
                'ENTITY_ID' => $UFObject,
                'FIELD_NAME' => 'UF_ADDRESS',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                "EDIT_FORM_LABEL" => Array('ru'=>'Адрес'),
                "LIST_COLUMN_LABEL" => Array('ru'=>'Адрес'),
                "LIST_FILTER_LABEL" => Array('ru'=>'Адрес'),
                "ERROR_MESSAGE" => Array('ru'=>'', 'en'=>''),
                "HELP_MESSAGE" => Array('ru'=>'', 'en'=>''),
            ),
        );
        //привязка пользовательских полей к HL-блоку
        foreach($arCartFields as $arCartField){
            $obUserField = new CUserTypeEntity;
            $obUserField->Add($arCartField);
        }
        return $createHlBlock->getId();
    }
    return false;
}
function getData(string $ipAddr): array
{
    $hlblock = \Bitrix\Highloadblock\HighloadBlockTable::getList(
        array(
            "filter" => ['=NAME' => 'ListIP']
        )
    )->fetch();
    if (empty($hlblock)){
        //создаем HL-блок с IP и адресами, если его не существует
        $hlblock = createHlBlockIP();
    }
    $result = [];
    $entity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
    $entity_data_class = $entity->getDataClass();
    if (isset($hlblock['ID'])) {
        //получаем данные из HL-блока, чтобы не делать запрос к сервису
        $res = $entity_data_class::getList(
            [
                'filter' => ['UF_IP_ADDRESS' => $ipAddr]
            ]
        )->fetchAll();
        foreach ($res as $item) {
            $result['ip'] = $item['UF_IP_ADDRESS'];
            $result['address'] = $item['UF_ADDRESS'];
        }
    }
    if (!empty($result)){
        return $result;
    } else {
        // опции по умолчанию для создания объекта HTTP клиента
        $options = array(
            "redirect" => false, // true, если нужно выполнять редиректы
            "redirectMax" => 5, // Максимальное количество редиректов
            "waitResponse" => true, // true - ждать ответа, false - отключаться после запроса
            "socketTimeout" => 30, // Таймаут соединения, сек
            "streamTimeout" => 60, // Таймаут чтения ответа, сек, 0 - без таймаута
            "version" => HttpClient::HTTP_1_0, // версия HTTP (HttpClient::HTTP_1_0 или HttpClient::HTTP_1_1)
            "proxyHost" => "", // адрес
            "proxyPort" => "", // порт
            "proxyUser" => "", // имя
            "proxyPassword" => "", // пароль
            "compress" => false, // true - принимать gzip (Accept-Encoding: gzip)
            "charset" => "", // Кодировка тела для POST и PUT
            "disableSslVerification" => false, // true - отключить проверку ssl (с 15.5.9)
        );
        // отправляем GET-запрос для получения данных от сервиса
        $httpClient = new HttpClient($options);
        $url = 'http://api.sypexgeo.net/json/'.$ipAddr;
        $httpClient->get($url);
        $result['ip'] = $ipAddr;
        $result['address'] = $httpClient->getResult();
        $result['create_request'] = 'yes';

        //данные для создания записи в HL-блоке
        $data = array(
            "UF_IP_ADDRESS"=>$ipAddr,
            "UF_ADDRESS"=>$httpClient->getResult(),
        );
        //создание записи в HL-блоке
        $entity_data_class::add($data);

        return $result;
    }
    return false;
}

$request = \Bitrix\Main\Context::getCurrent()->getRequest();
$params = $request->getPostList()->toArray();

try {
    if ($params['ip-request']) {
        // если нам передался IP-адрес из формы, то работаем дальше по программе
        $resData = getData($params['ip-request']);
    } else {
        throw new \Exception('Не указан IP-адрес', 422);
    }
    echo json_encode($resData);
    die();
} catch (\Exception $exception) {
    //отправляем данные на почту администратору сайта, если произошла ошибка
    $adminMail = COption::GetOptionString("main", "email_from");
    mail($adminMail, $adminMail, $exception->getMessage()); // сделал бы лучше через CEvent::send, но для этого нужно создать почтовый шаблон. Не хотел усложнять сейчас.
    die();
}

?>