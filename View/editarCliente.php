<?php
require_once "_header.php";
?>

<div class="container mt-5">
    <h2 class="mb-4">Editar Cliente</h2>

    <form id="form-editar">
        <div class="">
            <input type="hidden" id="id" name="id">
        </div>
        <div class="mb-3">
            <label for="codigo" class="form-label">Código do Cliente</label>
            <input type="text" class="form-control" id="codigo" name="codigo" required>
        </div>

        <div class="mb-3">
            <label for="nome" class="form-label">Nome do Cliente</label>
            <input type="text" class="form-control" id="nome" name="nome" required>
        </div>

        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        <a href="clientes.php" class="btn btn-danger">Cancelar</a>
    </form>
</div>

<script>
    $(document).ready(function() {
        
        const urlParams = new URLSearchParams(window.location.search);
        const id = urlParams.get('id');

        if (!id) {
            alert('ID não informado!');
            window.location.href = 'clientes.php';
        }

        $.ajax({
            url: '../Controller/ClienteController.php?action=listar',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                const cliente = response.data.find(c => c.id == id);
                if (cliente) {
                    $('#id').val(cliente.id);
                    $('#codigo').val(cliente.codigo);
                    $('#nome').val(cliente.nome);
                } else {
                    alert('Cliente não encontrado.');
                    window.location.href = 'clientes.php';
                }
            },
            error: function() {
                alert('Erro ao buscar dados do cliente.');
            }
        });

        $('#form-editar').on('submit', function(e) {
            e.preventDefault();

            const data = {
                id: $('#id').val(),
                codigo: $('#codigo').val(),
                nome: $('#nome').val()
            };

            $.ajax({
                url: 'Controller/ClienteController.php?action=salvar',
                type: 'POST',
                data: data,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        alert(response.message);
                        window.location.href = 'clientes.php';
                    } else {
                        alert(response.message);
                    }
                },
                error: function() {
                    alert('Erro ao atualizar cliente.');
                }
            });
        });
    });
</script>

<?php
require_once "_footer.php";
?>