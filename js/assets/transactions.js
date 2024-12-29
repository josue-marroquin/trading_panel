// Funcion Ajax maestra para interactuar con PHP y la Base de Datos
async function makeAjaxRequest(option, data = {}) {
    return new Promise((resolve, reject) => {
        $.ajax({
            type: 'POST',
            url: './php/services.php',  // Nueva ruta
            data: { option, formulario: data },
            dataType: 'json',
            success: function(response) {
                resolve(response);
            },
            error: function(error) {
                reject(error);
            }
        });
    });
}