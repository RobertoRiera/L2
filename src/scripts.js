function prueba(){
    alert("pruebaaaa");
}

function validateField(){
    //alert('Entro en la funcion');
    var clave2 = document.getElementById('clave').value;
    if (clave2.length < 2) {
        alert('El campo debe tener m�s de 2 caracteres');
        return false;
    }
    return true;
}