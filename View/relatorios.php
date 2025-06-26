<?php
require_once "_header.php";
require_once '../Model/ProdutoModel.php';

$produto = new Produto();
$listaProdutos = $produto->listarProduto();
?>

<div class="container mt-5 mb-5">
    <h2 class="mb-4">Relatório de Orçamentos</h2>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form id="form-filtro" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Data Início</label>
                    <input type="date" class="form-control" id="data_inicio" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Data Fim</label>
                    <input type="date" class="form-control" id="data_fim" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Produto (Opcional)</label>
                    <select class="form-control" id="produto_id">
                        <option value="">Todos</option>
                        <?php foreach ($listaProdutos as $prod) : ?>
                            <option value="<?= $prod['id'] ?>"><?= $prod['descricao'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Gerar</button>
                </div>
            </form>
        </div>
    </div>

    <div id="resultado-relatorio">
        <!-- Relatório será exibido aqui -->
    </div>
</div>

<script>
$(document).ready(function() {
    $('#form-filtro').on('submit', function(e) {
        e.preventDefault();

        const data_inicio = $('#data_inicio').val();
        const data_fim = $('#data_fim').val();
        const produto_id = $('#produto_id').val();

        $.ajax({
            url: '../Controller/RelatorioController.php?action=relatorio',
            type: 'GET',
            data: {
                data_inicio,
                data_fim,
                produto_id
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    montarRelatorio(response.data);
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert('Erro na comunicação com o servidor.');
            }
        });
    });

    function montarRelatorio(data) {
        if (data.length === 0) {
            $('#resultado-relatorio').html('<div class="alert alert-info">Nenhum dado encontrado.</div>');
            return;
        }

        let html = '';
        let totalGeral = 0;

        const agrupado = {};

        data.forEach(item => {
            if (!agrupado[item.orcamento_id]) {
                agrupado[item.orcamento_id] = {
                    info: item,
                    itens: []
                };
            }
            agrupado[item.orcamento_id].itens.push(item);
        });

        for (const [orcamentoId, grupo] of Object.entries(agrupado)) {
            let totalOrcamento = 0;

            html += `
                <div class="card mb-3">
                    <div class="card-header bg-dark text-white">
                        Orçamento #${orcamentoId} | Cliente: ${grupo.info.cliente} | Vendedor: ${grupo.info.vendedor} | Data: ${grupo.info.data_orcamento}
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead class="table-secondary">
                                <tr>
                                    <th>Produto</th>
                                    <th class="text-center">Qtd</th>
                                    <th class="text-center">Preço Unitário</th>
                                    <th class="text-center">Desconto</th>
                                    <th class="text-center">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
            `;

            grupo.itens.forEach(item => {
                totalOrcamento += parseFloat(item.preco_total);

                html += `
                    <tr>
                        <td>${item.produto}</td>
                        <td class="text-center">${item.quantidade}</td>
                        <td class="text-center">R$ ${parseFloat(item.preco_unitario).toFixed(2).replace('.', ',')}</td>
                        <td class="text-center">R$ ${parseFloat(item.desconto).toFixed(2).replace('.', ',')}</td>
                        <td class="text-center">R$ ${parseFloat(item.preco_total).toFixed(2).replace('.', ',')}</td>
                    </tr>
                `;
            });

            html += `
                            </tbody>
                        </table>
                        <div class="text-end fw-bold">
                            Total deste orçamento: R$ ${totalOrcamento.toFixed(2).replace('.', ',')}
                        </div>
                    </div>
                </div>
            `;

            totalGeral += totalOrcamento;
        }

        html += `
            <div class="alert alert-success text-end fw-bold fs-5">
                TOTAL GERAL DOS ORÇAMENTOS: R$ ${totalGeral.toFixed(2).replace('.', ',')}
            </div>
        `;

        $('#resultado-relatorio').html(html);
    }
});
</script>

<?php require_once "_footer.php"; ?>
