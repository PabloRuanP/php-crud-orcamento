<?php
require_once "_header.php";
?>

<div class="container mt-5 mb-5">
    <h2 class="mb-4">Cadastro de Produto</h2>

    <form id="form-cadastro">
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

        <button type="submit" class="btn btn-success">Cadastrar</button>
        <a href="produtos.php" class="btn btn-secondary">Listar Produtos</a>
    </form>
</div>

<script>
    $(document).ready(function() {
        
        $("#preco").on("keypress", (e) => {
            if (!/[0-9]/.test(e.key)) {
                e.preventDefault();
            }
        });
        
        $('#form-cadastro').on('submit', function(e) {
            e.preventDefault();

            const data = {
                id: $("#id").val(),
                codigo: $("#codigo").val(),
                descricao: $("#descricao").val(),
                preco: $("#preco").val()
            };

            $.ajax({
                url: '../Controller/ProdutoController.php?action=salvar',
                type: "POST",
                data: data,
                dataType: 'json',
                success: function(response) {
                    if (response.status == 'success') {
                        alert(response.message);
                        window.location.href = 'produtos.php';
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                    alert('Erro na comunicação com o servidor.');
                }
            });
        });
    });
</script>

<?php
require_once "_footer.php";
?>