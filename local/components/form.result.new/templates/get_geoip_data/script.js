BX.ready(function(){
    var inputFormGeo = document.querySelector(".form-control");
    var submitFormGeo = document.querySelector(".btn.btn-primary");

    //валидация поля на IP-адрес
    function ValidateIPaddress(ipaddress) {
        if (/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/.test(ipaddress)) {
            return (true)
        }
        return (false)
    }
    submitFormGeo.disabled = true;
    inputFormGeo.addEventListener('input', function (event) {
        if (ValidateIPaddress(inputFormGeo.value)){
            submitFormGeo.disabled = false;
        } else {
            submitFormGeo.disabled = true;
        }
    })
});