document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('formCadastro');
    const cpfInput = document.getElementById('cpf');

    // Máscara automática para CPF (000.000.000-00)
    cpfInput.addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 11) value = value.slice(0, 11);

        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');

        e.target.value = value;
    });

    // Validação de palavra-passe e campos antes de submeter
    form.addEventListener('submit', function (e) {
        const senha = document.getElementById('senha').value;
        const confirmarSenha = document.getElementById('confirmarSenha').value;

        if (senha !== confirmarSenha) {
            e.preventDefault();
            alert('As palavras-passe não coincidem. Por favor, verifique.');
            return false;
        }

        if (senha.length < 6) {
            e.preventDefault();
            alert('A palavra-passe deve conter no mínimo 6 caracteres.');
            return false;
        }
    });
});