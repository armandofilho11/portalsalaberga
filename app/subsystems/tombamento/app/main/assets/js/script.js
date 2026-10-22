// Validação de formulário Bootstrap-like
document.addEventListener('DOMContentLoaded', function () {
    const forms = document.querySelectorAll('.needs-validation');

    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
});

// Confirmação antes de exclusões
function confirmarExclusao() {
    return confirm("Tem certeza que deseja excluir este item?");
}

// Aplica automaticamente em botões de exclusão
document.querySelectorAll('form .btn-excluir, form button[name="excluir"]').forEach(btn => {
    btn.addEventListener('click', function (e) {
        if (!confirmarExclusao()) {
            e.preventDefault();
        }
    });
});