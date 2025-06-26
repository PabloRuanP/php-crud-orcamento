<?php
require_once "_header.php";
require_once '../Model/ProdutoModel.php'; // Certifique-se que a classe está sendo importada

$produto = new Produto();
$listaProdutos = $produto->listarProduto();
?>

<div class="container mt-5">
    <h2 class="mb-4">Editar Oferta</h2>

    <form id="form-editar">
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

        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        <a href="ofertas.php" class="btn btn-danger">Cancelar</a>
    </form>
</div>

<script>
    $(document).ready(function() {
        const urlParams = new URLSearchParams(window.location.search);
        const id = urlParams.get('id');

        if (!id) {
            alert('ID não informado!');
            window.location.href = 'ofertas.php';
        }

        // Carregar os dados do produto pelo ID
        $.ajax({
            url: '../Controller/OfertaController.php?action=listar',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                const oferta = response.data.find(c => c.id == id);
                if (oferta) {
                    $('#id').val(oferta.id);
                    $('#produto_id').val(oferta.produto_id);
                    $('#quantidade_levar').val(oferta.leve);
                    $('#quantidade_pagar').val(oferta.pague);
                } else {
                    alert('Oferta não encontrado.');
                    window.location.href = 'ofertas.php';
                }
            },
            error: function() {
                alert('Erro ao buscar dados da oferta.');
            }
        });

        // Enviar atualização
        $('#form-editar').on('submit', function(e) {
            e.preventDefault();

            const data = {
                id: $('#id').val(),
                produto_id: $('#produto_id').val(),
                quantidade_levar: $('#quantidade_levar').val(),
                quantidade_pagar: $('#quantidade_pagar').val()
            };

            $.ajax({
                url: '../Controller/OfertaController.php?action=salvar',
                type: 'POST',
                data: data,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        alert(response.message);
                        window.location.href = 'ofertas.php';
                    } else {
                        alert(response.message);
                    }
                },
                error: function() {
                    alert('Erro ao atualizar oferta.');
                }
            });
        });
    });
</script>

<?php
require_once "_footer.php";
?>