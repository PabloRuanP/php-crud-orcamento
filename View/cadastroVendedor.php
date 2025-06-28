<?php
require_once "_header.php";
?>

<div class="container mt-5 mb-5">
    <h2 class="mb-4">Cadastro de Vendedor</h2>

    <form id="form-cadastro">
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

        <button type="submit" class="btn btn-success">Cadastrar</button>
        <a href="vendedores.php" class="btn btn-secondary">Listar Vendedor</a>
    </form>
</div>

<script>
    $(document).ready(function() {

        $('#form-cadastro').on('submit', function(e) {
            e.preventDefault();

            const data = {
                id: $("#id").val(),
                codigo: $("#codigo").val(),
                nome: $("#nome").val()
            };

            $.ajax({
                url: '../Controller/VendedorController.php?action=salvar',
                type: "POST",
                data: data,
                dataType: 'json',
                success: function(response) {
                    if (response.status == 'success') {
                        alert(response.message);
                        window.location.href = 'vendedores.php';
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