document.getElementById('telefone').addEventListener('input', function (e) {
let x = e.target.value.replace(/\D/g, '').match(/(\d{0,2})(\d{0,5})(\d{0,4})/);
e.target.value = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
});

document.addEventListener('DOMContentLoaded', () => {
    let senhaInput = document.getElementById('senha');
    let barra = document.getElementById('barra-forca');
    let nivelTexto = document.getElementById('nivel-senha');

    senhaInput.addEventListener('input', () => {

        let forca = calcularForca(senhaInput.value);
        atualizarBarra(forca);
    });

    function calcularForca(senha) {
        let forca = 0;

        if (senha.length >= 8) forca++;
        if (/[A-Z]/.test(senha)) forca++;
        if (/[0-9]/.test(senha)) forca++;
        if (/[^a-zA-Z0-9]/.test(senha)) forca++;
        return forca; // 0 a 4
    }

    function atualizarBarra(forca) {
        // Se a forca for 0, o nível também será 0
        let cores = ['#e74c3c', '#e67e22', '#f1c40f', '#3498db', '#2ecc71'];
        let niveis = ['Muito Fraca', 'Fraca', 'Média', 'Forte', 'Muito Forte'];

        // Garante que o índice não exceda o array (embora forca seja de 0 a 4)
        let indice = Math.min(forca, 4); 

        barra.style.width = ((forca/4)*100) + '%';
        barra.style.backgroundColor = cores[indice];
        barra.style.transition = 'width 0.5s ease';
        nivelTexto.textContent = niveis[indice];
    }
});