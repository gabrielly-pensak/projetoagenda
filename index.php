<?php
    
    require "agenda.php";
    require "conexao.bd.php";
    require "form.php";
    
    $cadastroRepositorio = new CadastroRepositorio($pdo);

    if (isset($_POST['cadastro'])) {
        $cadastro = new dados(null,
            $_POST['agenda_nome'],
            $_POST['agenda_telefone'],
            $_POST['agenda_email'],
            $_POST['agenda_data_nascimento'],
            );
        
        $cadastroRepositorio->salvar($cadastro);
       header("Location: cadastrar.php");
      exit;
    }
     

 if (isset($_POST['editar'])) {
    $cadastro = new dados(
        $_POST['agenda_id'],
        $_POST['agenda_nome'],
        $_POST['agenda_email'],
        $_POST['sobrenome'],
        $_POST['cidade'],
        $_POST['estado']
    );
    $cadastroRepositorio->atualizar($cadastro);
    header("Location: cadastrar.php");
    exit;
}
$cadastroEditando = null;
if (isset($_GET['agenda_id'])) {
    $cadastroEditando = $cadastroRepositorio->buscar($_GET['agenda_id']);
}
$cadastros = $cadastroRepositorio->buscarTodos();

if (isset($_POST['excluir'])) {
   $id = $_POST['excluir'];
    $cadastroRepositorio->deletar($id);
    header("Location: cadastrar.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="agenda.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <section class="titulo">
        <h2>Agenda de Contatos</h2>
    </section>
    <section class="container-form">
      <form method="post">
        <label for="agenda_nome" required value="<?= $cadastroEditando ? $cadastroEditando->getNome() : '' ?>">Nome:</label>
            <input type="text" name="agenda_nome">

            <label for="agenda_telefone"required value="<?= $cadastroEditando ? $cadastroEditando->getTelefone() : '' ?>">Telefone:</label>
            <input type="text" name="agenda_telefone">

            <label for="agenda_email" required value="<?= $cadastroEditando ? $cadastroEditando->getEmail() : '' ?>">Email:</label>
            <input type="text" name="agenda_email">

            <label for="agenda_data_nascimento" required value="<?= $cadastroEditando ? $cadastroEditando->getNascimento() : '' ?>">Data de Nascimento:</label>
            <input type="text" name="agenda_data_nascimento">

            <button type="submit" class="botao-cadastrar" name="<?= $cadastroEditando ? 'editar' : 'cadastro' ?>">
    </form>
    </section>
    <h2>Lista de Cadastros</h2>
    <table border="1">
        <tr>
            <th>Nome</th>
            <th>Telefone</th>
            <th>Email</th>
            <th>Data de Nascimento</th>
        </tr>
        <?php foreach ($cadastros as $cadastro): ?>
            <tr>
                <td><?= $cadastro->getId() ?></td>
                <td><?= $cadastro->getNome() ?></td>
                <td><?= $cadastro->getTelefone() ?></td>
                <td><?= $cadastro->getEmail() ?></td>
                <td><?= $cadastro->getNascimento() ?></td>
                <td>
                    <a class="botao-editar"  href="cadastrar.php?id=<?= $cadastro->getId() ?>">Editar</a>
                    <form method="post" action="cadastrar.php" style="display:inline" onsubmit="return confirmarExclusao();">>
                        <input  type="hidden" name="excluir" value="<?= $cadastro->getId() ?>">
                        <button  type="submit" class="botao-excluir">Excluir</button>
                    </form>
                    <script>
                    function confirmarExclusao() {
                        return confirm("Tem certeza que deseja excluir este cadastro?");    
                    }
                    
                    </script>

                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>