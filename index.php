<?php include 'views/header.php'; ?>

<div class="container">
    <h2>Ações Disponíveis</h2>
    <div class="main-buttons-container">
        
        <!-- Formulário para selecionar a pasta -->
        <form action="func-pastas.php/abrirPasta" method="post" enctype="multipart/form-data">
            <div class="file-container">
                <label for="folder1" class="center-button">Selecionar Pasta</label>
                <input type="file" name="folder1" id="folder1" webkitdirectory directory required>
            </div>
        </form>
        
        <!-- Formulário para selecionar Tacs -->
        <form action="func-pastas.php/abrirTacs" method="post" enctype="multipart/form-data">
            <div class="file-container">
                <label for="folder2" class="center-button">Selecionar Tacs</label>
                <input type="file" name="folder2" id="folder2" webkitdirectory directory required>
            </div>
        </form>
        
        <!-- Formulário para confirmação -->
        <form action="func-pastas.php/Confirmar" method="post" enctype="multipart/form-data">
            <div class="file-container">
                <label for="folder3" class="center-button">Confirmar</label>
                <input type="file" name="folder3" id="folder3" webkitdirectory directory required>
            </div>
        </form>
    </div>
</div>

<?php include 'views/footer.php'; ?>
