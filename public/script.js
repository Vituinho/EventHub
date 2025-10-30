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
        if (/[a-z]/.test(senha)) forca++;
        if (/[A-Z]/.test(senha)) forca++;
        if (/[\d]/.test(senha)) forca++;
        if (/[^\p{L}\p{N}]/u.test(senha)) forca++;
        return forca;
    }

    function atualizarBarra(forca) {
        // Se a forca for 0, o nível também será 0
        let cores = ['#e74c3c', '#e67e22', '#f1c40f', '#3498db', '#2ecc71', '#9b59b6'];
        let niveis = ['Muito Fraca', 'Fraca', 'Média', 'Forte', 'Muito Forte', 'Extremamente Forte'];
        let indice = Math.min(forca, 5); 

        barra.style.width = ((forca/5)*100) + '%';
        barra.style.backgroundColor = cores[indice];
        barra.style.transition = 'width 0.6s ease, background-color 0.3s ease';
        nivelTexto.textContent = niveis[indice];
    }
});