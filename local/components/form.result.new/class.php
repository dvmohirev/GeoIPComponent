<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

CModule::IncludeModule('highloadblock');
use \Bitrix\Main\Application,
    \Bitrix\Main\Web\Uri,
    \Bitrix\Main\Web\HttpClient;

class GetDataGeo extends CBitrixComponent
{
    public function getHlBlockIP(string $value): int
    {
        //создание HL-блока
        $createHlBlock = \Bitrix\Highloadblock\HighloadBlockTable::add(
            array(
                'NAME' => $value,
                'TABLE_NAME' => strtolower($value),
            )
        );

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

        return \Bitrix\Highloadblock\HighloadBlockTable::getList(
            array(
                "filter" => ['=NAME' => $value]
            )
        )->fetch()['ID'];
    }

    public function requestGeoIp(string $value): array
    {
        // опции по умолчанию для создания объекта HTTP клиента
        $options = array(
            "redirect" => false,
            "redirectMax" => 5,
            "waitResponse" => true,
            "socketTimeout" => 30,
            "streamTimeout" => 60,
            "version" => HttpClient::HTTP_1_0,
            "proxyHost" => "",
            "proxyPort" => "",
            "proxyUser" => "",
            "proxyPassword" => "",
            "compress" => false,
            "charset" => "",
            "disableSslVerification" => false,
        );
        // отправляем GET-запрос для получения данных от сервиса
        $httpClient = new HttpClient($options);
        $url = 'http://api.sypexgeo.net/json/'.$value;
        $httpClient->get($url);
        $requestData['ip'] = $value;
        $requestData['address'] = $httpClient->getResult();
        $requestData['create_request'] = 'yes';

        return $requestData;
    }

    public function getData(string $ipAddr): array
    {
        $result = [];
        $entity_data_class = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity(
            $this->getHlBlockIP('ListIP')
        )->getDataClass();
        //получаем данные из HL-блока, чтобы не делать запрос к сервису
        $res = $entity_data_class::getList(
            [
                'filter' => ['UF_IP_ADDRESS' => $ipAddr]
            ]
        )->fetchAll();
        if (!empty($res)) {
            foreach ($res as $item) {
                $result['ip'] = $item['UF_IP_ADDRESS'];
                $result['address'] = $item['UF_ADDRESS'];
            }
        } else {
            $dateRes = $this->requestGeoIp($ipAddr);
            //данные для создания записи в HL-блоке
            $data = array(
                "UF_IP_ADDRESS"=>$dateRes['ip'],
                "UF_ADDRESS"=>$dateRes['address'],
            );
            //создание записи в HL-блоке
            $entity_data_class::add($data);
            $result = $dateRes;
        }

        return $result;
    }
}
?>