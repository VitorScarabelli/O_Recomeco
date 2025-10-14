const form = document.getElementById('formCadastro');
const errorsList = document.getElementById('errors');

form.addEventListener('submit', function (event) {
    errorsList.innerHTML = '';
    let errors = [];

    const nome = form.nome.value.trim();
    const email = form.email.value.trim();
    const senha = form.senha.value;
    const confirmaSenha = form.confirmaSenha.value;

    if (nome === '') errors.push('O campo nome é obrigatório.');
    if (email === '') errors.push('O campo e-mail é obrigatório.');
    else {
        const regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!regexEmail.test(email)) errors.push('E-mail inválido.');
    }

    if (senha.length < 6) errors.push('A senha deve ter pelo menos 6 caracteres.');
    if (senha !== confirmaSenha) errors.push('As senhas não coincidem.');

    if (errors.length > 0) {
        event.preventDefault();
        errors.forEach(function (e) {
            const li = document.createElement('li');
            li.textContent = e;
            errorsList.appendChild(li);
        });
    }
});
