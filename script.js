$(document).ready(() => {

    $('#documentacao').on('click', () => {
        // $('#pagina').load('documentacao.html')

        // $.get('documentacao.html', data => {
        //     $('#pagina').html(data)
        // })

        $.post('documentacao.html', data => {
            $('#pagina').html(data)
        })
    })

    $('#suporte').on('click', () => {
        // $('#pagina').load('suporte.html')

        // $.get('suporte.html', data => {
        //     $('#pagina').html(data)
        // })

        $.post('suporte.html', data => {
            $('#pagina').html(data)
        })
    })

    // ajax
    $('#competencia').on('change', (e) => {

        // método, url, dados, sucesso, erro
        $.ajax({
            type: 'GET',
            url: 'app.php',
            data: 'competencia=' + $(e.target).val(), //x-www-form-urlencoded
            dataType: 'json',
            success: dados => {
                $('#numero_vendas').html(dados.numero_vendas)
                $('#total_vendas').html(dados.total_vendas)
                $('#total_despesas').html(dados.total_despesas)
                $('#clientes_ativos').html(dados.clientes_ativos)
                $('#clientes_inativos').html(dados.clientes_inativos)
                $('#total_reclamacoes').html(dados.total_reclamacoes)
                $('#total_sugestoes').html(dados.total_sugestoes)
                $('#total_elogios').html(dados.total_elogios)
            },
            erro: erro => {console.log(erro)}
        })
    })
	
})