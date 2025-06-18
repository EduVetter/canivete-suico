jQuery(document).ready(function($) {
    // Delega o evento de clique para os botões de upload, mesmo que sejam adicionados dinamicamente.
    $(document).on('click', '.cs-upload-button', function(e) {
        e.preventDefault();

        var button = $(this);
        var inputField = button.prev('input'); // O campo de texto que armazena a URL

        // Cria o frame de mídia do WordPress.
        var frame = wp.media({
            title: 'Selecionar ou Fazer Upload do Logotipo',
            button: {
                text: 'Usar este logotipo'
            },
            multiple: false // Permite selecionar apenas uma imagem.
        });

        // Quando uma imagem é selecionada, esta função é executada.
        frame.on('select', function() {
            // Pega os dados da imagem selecionada.
            var attachment = frame.state().get('selection').first().toJSON();
            // Coloca a URL da imagem no nosso campo de texto.
            inputField.val(attachment.url);
        });

        // Abre o frame de mídia.
        frame.open();
    });
});