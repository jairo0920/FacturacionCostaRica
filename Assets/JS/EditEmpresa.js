//Funcion que escucha los eventos del HTML
$(document).ready(function() { 
    //$("input").blur(function() {
    $("input[name=cifnif]").blur(function() {
        if ($("input[name=cifnif]")) {
            var cedula = $("input[name=cifnif]")[0].value;
            var nombre = $("input[name=administrador]")[0].value;
            if (cedula !== ""){
                if (nombre === ""){
                     GetObtenerNombreAdministrador(cedula);
                };
            };
        };       
    }); 
}); 

function GetObtenerNombreAdministrador(cedula) {
    $.ajax({
      "url": "https://api.hacienda.go.cr/fe/ae?identificacion=" + cedula,
      "method": "GET"
    }).done(function (response) {
        var jsondata = JSON.stringify(response);
        var obj = jQuery.parseJSON(jsondata);
        //var json = JSON.parse(jsondata);
        //console.log(jsondata);
        console.log(obj.nombre);
        document.getElementsByName("administrador")[0].value = obj.nombre;
    });
};