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
