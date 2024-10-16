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
  Выполняется валидация поля через JS
</p>
<br>
<h2>Применение</h2>
<p>
  Для использования компонента скопируйте папку из репозитория в корень сайта.<br>
  Если папка local/components уже есть на сайте, то скопируйте папку <b>testwork</b> в папку local/components
</p>
<p>
  Для вызова компонента вставьте код:
</p>
<code>$APPLICATION->IncludeComponent(
    "testwork:geoip.request",
    ".default",
    array()
);
</code>
