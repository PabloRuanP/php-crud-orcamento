<?php require_once "_header.php"; ?>

<div class="content-section mt-5">
    <div class="card shadow-lg rounded-4">
        <div class="card-body">
            <h2 class="mb-4 fw-bold text-center">Lista de Produtos</h2>

            <table id="tabela-produtos" class="table table-hover table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th class="text-center">Código</th>
                        <th>Descrição</th>
                        <th>Preço</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Dados dinamicamente preenchidos -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        // Inicializar a tabela
        const tabela = $("#tabela-produtos").DataTable({
            ajax: {
                url: '../Controller/ProdutoController.php?action=listar',
                dataSrc: 'data'
            },
            columns: [{
                    data: 'codigo'
                },
                {
                    data: 'descricao'
                },
                {
                    data: 'preco'
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return `
                            <div class="btn-group">
                                <button type="button" class="btn btn-warning dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    <img src="../source/img/icon-settings.svg" alt="Adicionar" width="16" height="16">
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="editarProduto.php?id=${row.id}" class="dropdown-item"><img src="../source/img/icon-edit.svg" alt="Adicionar" width="16" height="16"> Editar</a></li>
                                    <li><a href="#" class="dropdown-item deletar" data-id="${row.id}"><img src="../source/img/icon-trash-2.svg" alt="Adicionar" width="16" height="16"> Deletar</a></li>
                                </ul>
                            </div>
                        `;
                    }
                }
            ],

            columnDefs: [{
                    width: "5rem",
                    targets: 0
                },
                {
                    width: "5rem",
                    targets: 2
                },
                {
                    width: "5rem",
                    targets: 3
                },
            ],
            autoWidth: false
        });

        // Excluir produtos
        $("#tabela-produtos tbody").on("click", ".deletar", function() {
            const id = $(this).data("id");

            if (confirm('Deseja realmente excluir este produtos?')) {
                $.ajax({
                    url: "../Controller/ProdutoController.php?action=deletar",
                    type: "POST",
                    dataType: "json",
                    data: {
                        id: id
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            alert(response.message);
                            tabela.ajax.reload();
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function() {
                        alert('Erro na comunicação com o servidor.');
                    }
                });
            }
        });
    });
</script>

<?php require_once "_footer.php"; ?>