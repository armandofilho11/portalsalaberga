function validarFormularioBem() {
    var nome = document.getElementsByName('nome')[0].value;
    var estado = document.getElementsByName('estado_conservacao')[0].value;
    var setor = document.getElementsByName('setor_id')[0].value;
    if (!nome || !estado || !setor) {
        alert("Preencha todos os campos obrigatórios!");
        return false;
    }
    return true;
}