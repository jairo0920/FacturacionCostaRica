//Funcion que escucha los eventos del HTML
$(document).ready(function() { 
    //$("input").blur(function() {
    $("input[name=cifnif]").blur(function() {
        if ($("input[name=cifnif]")) {
            var cedula = $("input[name=cifnif]")[0].value;
            var nombre = $("input[name=administrador]")[0].value;
            if (cedula !== ""){
                GetObtenerNombreAdministrador(cedula);
            };
        };       
    }); 
}); 

window.onload = function () {
    var cedula = $("input[name=cifnif]")[0].value;
    if (cedula !== ""){
        GetObtenerNombreAdministrador(cedula);
    };
};

function GetObtenerNombreAdministrador(cedula) {
    $.ajax({
      "url": "https://api.hacienda.go.cr/fe/ae?identificacion=" + cedula,
      "method": "GET"
    }).done(function (response) {
        var jsondata = JSON.stringify(response);
        var obj = jQuery.parseJSON(jsondata);
        //console.log(jsondata);
        document.getElementsByName("administrador")[0].value = obj.nombre;
        if (obj.tipoIdentificacion === "01"){
            document.getElementsByName("personafisica")[0].checked = true;
        };
        document.getElementsByName("tipoidentificacion")[0].value = "Tipo " + obj.tipoIdentificacion;
        var jsondataregimen = JSON.stringify(obj.regimen);
        var objregimen = jQuery.parseJSON(jsondataregimen);
        document.getElementsByName("tiporegimen")[0].value = objregimen.codigo + " - " + objregimen.descripcion;
        var jsondatasituacion = JSON.stringify(obj.situacion);
        var objsituacion = jQuery.parseJSON(jsondatasituacion);
        document.getElementsByName("administracionTributaria")[0].value = objsituacion.administracionTributaria;
        document.getElementsByName("estado")[0].value = objsituacion.estado;
        document.getElementsByName("moroso")[0].value = objsituacion.moroso;
        document.getElementsByName("omiso")[0].value = objsituacion.omiso;
        
        if (objsituacion.moroso === "SI"){
            $('#modaltest').modal('show');
        };
    });
};