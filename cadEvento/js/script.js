
const form = document.getElementById('formCadastro');
const errorsList = document.getElementById('errors');

form.addEventListener('submit', function (event) {
    errorsList.innerHTML = '';
    let errors = [];

    const nome = form.nome.value.trim();
    const desc = form.desc.value.trim();
    const tipo = form.tipo.value;
    const modi = form.modi.value;
    const quantCasas = Number(form.quantCasas.value);

    if (nome === '') errors.push('O campo NOME é obrigatório.');
    if (desc === '') errors.push('O campo DESCRIÇÃO é obrigatório.');
    if (!tipo) errors.push('O campo TIPO é obrigatório.');
    if (!modi) errors.push('Selecione o IMPACTO do evento.');

    if (!Number.isInteger(quantCasas) || quantCasas < 2 || quantCasas > 8) {
        errors.push('A quantidade de casas deve ser um número entre 2 e 8.');
    }

    if (errors.length > 0) {
        event.preventDefault();
        errors.forEach(function (e) {
            const li = document.createElement('li');
            li.textContent = e;
            errorsList.appendChild(li);
        });
    }
});