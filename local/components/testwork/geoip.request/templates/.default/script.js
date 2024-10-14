BX.ready(function(){
    const formGeo = document.getElementById("geo-ip-form");
    var inputFormGeo = formGeo.querySelector("#exampleInputEmail1");
    var errorMessage = formGeo.querySelector("#email-error-text");

    //валидация поля на IP-адрес
    function ValidateIPaddress(ipaddress) {
        if (/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/.test(ipaddress)) {
            return (true)
        }
        return (false)
    }
    formGeo.addEventListener('submit', function (event) {
        event.preventDefault();
        errorMessage.style.display = 'none';
        if (ValidateIPaddress(inputFormGeo.value)){ // если валидация пройдена, то отправляем данные формы на сервер
            const bxFormData = new BX.ajax.FormData();
            bxFormData.append(inputFormGeo.name, inputFormGeo.value);

            bxFormData.send(
                "/local/components/testwork/geoip.request/templates/.default/requestIP.php", //ajax
                function (res) {
                    let resJson = JSON.parse(res);
                    document.querySelector("#self_ip").innerHTML = resJson.ip;
                    document.querySelector("#address_ip").innerHTML = resJson.address;
                    document.querySelector(".hide_data").style.display = 'block';
                },
                null,
                function (error) {
                    console.log(`error: ${error}`);
                },
            );
        } else {
            errorMessage.style.display = 'block';
        }
    })
});