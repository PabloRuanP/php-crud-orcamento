<?php
require_once "_header.php";
require_once '../Model/ProdutoModel.php';

$produto = new Produto();
$listaProdutos = $produto->listarProduto();
?>

<div class="container mt-5 mb-5">
    <h2 class="mb-4">Cadastro de Oferta</h2>

    <form id="form-cadastro">
        <div class="">
            <input type="hidden" id="id" name="id">
        </div>
        <div class="mb-3">
            <label for="codigo" class="form-label">Produto</label>
            <select class="form-control" id="produto_id" name="produto_id" required>
                <option value="">Selecione um produto</option>
                <?php foreach ($listaProdutos as $prod) : ?>
                    <option value="<?= $prod['id'] ?>">
                        <?= $prod['descricao'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="quantidade_levar" class="form-label">Quantidade a Levar</label>
            <input type="number" class="form-control" id="quantidade_levar" name="quantidade_levar" step="1" required>
        </div>

        <div class="mb-3">
            <label for="quantidade_pagar" class="form-label">Quantidade a Pagar</label>
            <input type="number" class="form-control" id="quantidade_pagar" name="quantidade_pagar" step="1" required>
        </div>

        <button type="submit" class="btn btn-success">Cadastrar</button>
        <a href="ofertas.php" class="btn btn-secondary">Listar Ofertas</a>
    </form>
</div>

<script>
    $(document).ready(function() {
        
        $("#quantidade_pagar, #quantidade_levar").on("keypress", (e) => {
            if (!/[0-9]/.test(e.key)) {
                e.preventDefault();
            }
        });

        $('#form-cadastro').on('submit', function(e) {
            e.preventDefault();

            const data = {
                id: $("#id").val(),
                produto_id: $("#produto_id").val(),
                quantidade_levar: $("#quantidade_levar").val(),
                quantidade_pagar: $("#quantidade_pagar").val()
            };

            $.ajax({
                url: '../Controller/OfertaController.php?action=salvar',
                type: "POST",
                data: data,
                dataType: 'json',
                success: function(response) {
                    if (response.status == 'success') {
                        alert(response.message);
                        window.location.href = 'ofertas.php';
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