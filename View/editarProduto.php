<?php
require_once "_header.php";
?>

<div class="container mt-5">
    <h2 class="mb-4">Editar Produto</h2>

    <form id="form-editar">
        <div class="">
            <input type="hidden" id="id" name="id">
        </div>
        <div class="mb-3">
            <label for="codigo" class="form-label">Código do Produto</label>
            <input type="text" class="form-control" id="codigo" name="codigo" required>
        </div>

        <div class="mb-3">
            <label for="descricao" class="form-label">Descrição do Produto</label>
            <input type="text" class="form-control" id="descricao" name="descricao" required>
        </div>

        <div class="mb-3">
            <label for="preco" class="form-label">Preço</label>
            <input type="number" step="0.01" min="0" class="form-control" id="preco" name="preco" required>
        </div>

        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        <a href="produtos.php" class="btn btn-danger">Cancelar</a>
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
            window.location.href = 'produtos.php';
        }

        // Carregar os dados do produto pelo ID
        $.ajax({
            url: '../Controller/ProdutoController.php?action=listar',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                const produto = response.data.find(c => c.id == id);
                if (produto) {
                    $('#id').val(produto.id);
                    $('#codigo').val(produto.codigo);
                    $('#descricao').val(produto.descricao);
                    $('#preco').val(produto.preco);
                } else {
                    alert('Produto não encontrado.');
                    window.location.href = 'produtos.php';
                }
            },
            error: function() {
                alert('Erro ao buscar dados do produto.');
            }
        });

        // Enviar atualização
        $('#form-editar').on('submit', function(e) {
            e.preventDefault();

            const data = {
                id: $('#id').val(),
                codigo: $('#codigo').val(),
                descricao: $('#descricao').val(),
                preco: $('#preco').val()
            };

            $.ajax({
                url: '../Controller/ProdutoController.php?action=salvar',
                type: 'POST',
                data: data,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        alert(response.message);
                        window.location.href = 'produtos.php';
                    } else {
                        alert(response.message);
                    }
                },
                error: function() {
                    alert('Erro ao atualizar produto.');
                }
            });
        });
    });
</script>

<?php
require_once "_footer.php";
?>