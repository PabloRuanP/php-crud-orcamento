<?php
require_once "_header.php";
require_once '../Model/ClienteModel.php';
require_once '../Model/VendedorModel.php';
require_once '../Model/ProdutoModel.php';

$cliente = new Cliente();
$listaClientes = $cliente->listarCliente();

$vendedor = new Vendedor();
$listaVendedores = $vendedor->listarVendedor();

$produto = new Produto();
$listaProdutos = $produto->listarProduto();
?>

<div class="container mt-5 mb-5">
    <h2 class="mb-4">Cadastro de Orçamento</h2>

    <form id="form-cadastro">
        <input type="hidden" id="id" name="id">

        <div class="mb-3">
            <label for="cliente_id" class="form-label">Cliente</label>
            <select class="form-control" id="cliente_id" name="cliente_id" required>
                <option value="">Selecione um cliente</option>
                <?php foreach ($listaClientes as $cli) : ?>
                    <option value="<?= $cli['id'] ?>" data-codigo="<?= $cli['codigo'] ?>">
                        <?= $cli['nome'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="vendedor_id" class="form-label">Vendedor</label>
            <select class="form-control" id="vendedor_id" name="vendedor_id" required>
                <option value="">Selecione um vendedor</option>
                <?php foreach ($listaVendedores as $vend) : ?>
                    <option value="<?= $vend['id'] ?>" data-codigo="<?= $vend['codigo'] ?>">
                        <?= $vend['nome'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="mb-3 fw-bold">Itens do Orçamento</h5>
                <div class="row g-2 align-items-end">
                    <div class="col-md-5">
                        <label for="produto_id" class="form-label">Produto</label>
                        <select class="form-control" id="produto_id">
                            <option value="">Selecione</option>
                            <?php foreach ($listaProdutos as $prod) : ?>
                                <option value="<?= $prod['id'] ?>" data-preco="<?= $prod['preco'] ?>">
                                    <?= $prod['descricao'] ?> (R$ <?= number_format($prod['preco'], 2, ',', '.') ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label for="quantidade" class="form-label">Quantidade</label>
                        <input type="number" id="quantidade" class="form-control" min="1">
                    </div>

                    <div class="col-md-2">
                        <button type="button" id="adicionarItem" class="btn btn-success w-100">
                            Adicionar
                        </button>
                    </div>
                </div>

                <table class="table table-bordered table-hover mt-4" id="tabela-itens">
                    <thead class="table-dark">
                        <tr>
                            <th>Produto</th>
                            <th class="text-center">Qtd</th>
                            <th class="text-center">Preço Unitário</th>
                            <th class="text-center">Subtotal (estimado)</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>

                <div class="text-end fw-bold fs-5">
                    Total (estimado): R$ <span id="total-geral">0,00</span>
                </div>
                <div class="text-muted">
                    * Descontos de ofertas aplicados no momento de salvar.
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-success">Salvar Orçamento</button>
        <a href="orcamentos.php" class="btn btn-secondary">Listar Orçamentos</a>
    </form>
</div>

<script>
    let itens = [];

    function atualizarTabela() {
        const tbody = $("#tabela-itens tbody");
        tbody.empty();

        let totalGeral = 0;

        itens.forEach((item, index) => {
            let subtotal = parseFloat(item.quantidade) * parseFloat(item.preco_unitario);
            totalGeral += subtotal;

            tbody.append(`
                <tr>
                    <td>${item.produto_nome}</td>
                    <td class="text-center">${item.quantidade}</td>
                    <td class="text-center">R$ ${parseFloat(item.preco_unitario).toFixed(2).replace('.', ',')}</td>
                    <td class="text-center">R$ ${subtotal.toFixed(2).replace('.', ',')}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-danger remover-item" data-index="${index}">Remover</button>
                    </td>
                </tr>
            `);
        });

        $("#total-geral").text(totalGeral.toFixed(2).replace('.', ','));
    }

    $(document).ready(function() {

        $("#quantidade").on("keypress", (e) => {
            if (!/[0-9]/.test(e.key)) {
                e.preventDefault();
            }
        });

        $("#adicionarItem").on('click', function() {
            const dados = {
                produtoId: $("#produto_id").val(),
                quantidade: parseInt($("#quantidade").val()),
                produtoNome: $("#produto_id option:selected").text(),
                precoUnitario: parseFloat($("#produto_id option:selected").data('preco'))
            }

            if (!dados.produtoId || dados.quantidade <= 0) {
                alert('Selecione um produto e informe a quantidade corretamente.');
                return;
            }

            itens.push({
                produto_id: dados.produtoId,
                produto_nome: dados.produtoNome,
                quantidade: dados.quantidade,
                preco_unitario: dados.precoUnitario
            });

            atualizarTabela();

            $("#produto_id").val('');
            $("#quantidade").val('');
        });

        $(document).on('click', '.remover-item', function() {
            const index = $(this).data('index');
            itens.splice(index, 1);
            atualizarTabela();
        });

        $('#form-cadastro').on('submit', function(e) {
            e.preventDefault();

            const data = {
                id: $("#id").val(),
                cliente_codigo: $("#cliente_id").data("codigo"),
                vendedor_codigo: $("#vendedor_id").data("codigo"),
                itens: itens
            };

            if (data.cliente_codigo === data.vendedor_codigo) {
                alert('Cliente e Vendedor não podem ser o mesmo.');
                return;
            }

            if (itens.length === 0) {
                alert('Adicione pelo menos um item ao orçamento.');
                return;
            }

            $.ajax({
                url: '../Controller/OrcamentoController.php?action=salvar',
                type: "POST",
                data: data,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        alert(response.message);
                        window.location.href = 'orcamentos.php';
                    } else {
                        alert(response.message);
                    }
                },
                error: function() {
                    alert('Erro na comunicação com o servidor.');
                }
            });
        });
    });
</script>

<?php require_once "_footer.php"; ?>
