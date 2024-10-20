<h1>
  Получение информации о местоположении по IP-адресу
</h1>
<p>
  Компонент служит для выполнения задачи по определению местоположения по IP-адресу.<br>
  При вызове компонента на странице сайта появляется форма с полем для ввода IP-адреса.<br>
  При нажатии кнопки отправки формы срабатывает AJAX запрос к серверу, который отправляет сначала ищет такой IP-адрес в HL-блоке на сайте.
</p>
<p>
  Если не находит, то обращается к сервису определения IP через HTTP клиент Битрикс.<br>
  Полученные данные сохраняются в HL-блок и выводятся на странице.<br>
  Иначе найденные данные в HL-блоке выводятся на странице.<br>
</p>
<p>
  Выполняется валидация поля через JS.<br>
  Компонент разработан на основе стандартного компонента form.result.new
</p>
<br>
<h2>Применение</h2>
<p>
  Для использования компонента скопируйте папку из репозитория в корень сайта.<br>
  Если папка local/components уже есть на сайте, то скопируйте папку <b>testwork</b> в папку local/components
</p>
<p>
  Создайте веб-форму с Символьным идентификатором "GET_GEOIP_DATA".<br>
  Создайте вопрос в веб-форме с Символьным идентификатором "IP_ADDRESS", заголовком IP-адрес, ответом - "Текст"
</p>
<p>
  Для вызова компонента вставьте код:
</p>
<code><?$APPLICATION->IncludeComponent(
	"testwork:form.result.new",
	"get_geoip_data", 
	array(
		"AJAX_MODE" => "Y",
		"AJAX_OPTION_SHADOW" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"CACHE_TIME" => "3600",
		"CACHE_TYPE" => "A",
		"CHAIN_ITEM_LINK" => "",
		"CHAIN_ITEM_TEXT" => "",
		"EDIT_URL" => "",
		"IGNORE_CUSTOM_TEMPLATE" => "N",
		"LIST_URL" => "",
		"SEF_MODE" => "N",
		"SUCCESS_URL" => "",
		"USE_EXTENDED_ERRORS" => "N",
		"WEB_FORM_ID" => "1",
		"COMPONENT_TEMPLATE" => "get_geoip_data",
		"VARIABLE_ALIASES" => array(
			"WEB_FORM_ID" => "WEB_FORM_ID",
			"RESULT_ID" => "RESULT_ID",
		)
	),
	false
);?>
</code>
