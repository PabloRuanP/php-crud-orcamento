<?php
require_once "_header.php";
?>

<div class="container mt-5">
    <h2 class="mb-4">Editar Vendedor</h2>

    <form id="form-editar">
        <div class="">
            <input type="hidden" id="id" name="id">
        </div>
        <div class="mb-3">
            <label for="codigo" class="form-label">Código do Vendedor</label>
            <input type="text" class="form-control" id="codigo" name="codigo" required>
        </div>

        <div class="mb-3">
            <label for="nome" class="form-label">Nome do Vendedor</label>
            <input type="text" class="form-control" id="nome" name="nome" required>
        </div>

        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        <a href="vendedores.php" class="btn btn-danger">Cancelar</a>
    </form>
</div>

<script>
    $(document).ready(function() {

        $("#codigo").on("input", function() {
            this.value = this.value.replace(/\D/g, '');
        });
        
        const urlParams = new URLSearchParams(window.location.search);
        const id = urlParams.get('id');

        if (!id) {
            alert('ID não informado!');
            window.location.href = 'vendedores.php';
        }

        // Carregar os dados do vendedor pelo ID
        $.ajax({
            url: '../Controller/VendedorController.php?action=listar',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                const vendedor = response.data.find(c => c.id == id);
                if (vendedor) {
                    $('#id').val(vendedor.id);
                    $('#codigo').val(vendedor.codigo);
                    $('#nome').val(vendedor.nome);
                } else {
                    alert('Vendedor não encontrado.');
                    window.location.href = 'vendedores.php';
                }
            },
            error: function() {
                alert('Erro ao buscar dados do vendedor.');
            }
        });

        // Enviar atualização
        $('#form-editar').on('submit', function(e) {
            e.preventDefault();

            const data = {
                id: $('#id').val(),
                codigo: $('#codigo').val(),
                nome: $('#nome').val()
            };

            $.ajax({
                url: '../Controller/VendedorController.php?action=salvar',
                type: 'POST',
                data: data,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        alert(response.message);
                        window.location.href = 'vendedores.php';
                    } else {
                        alert(response.message);
                    }
                },
                error: function() {
                    alert('Erro ao atualizar vendedor.');
                }
            });
        });
    });
</script>

<?php
require_once "_footer.php";
?>