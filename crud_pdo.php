<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
</head>
<?php
// dica de leitura: https://imasters.com.br/back-end/como-usar-pdo-com-banco-de-dados-mysql

/* 
Realize as linhas abaixo no QueryBrowser ou Workbench ou PHPMyAdmin
  CREATE DATABASE bdconteiner1.0;
  use bdsite;  
  CREATE TABLE `tb_cliente` (
  `id` int(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `nome` varchar(250) NOT NULL,
  `numero` varchar(250) NOT NULL,
  `tipo` varchar(250) NOT NULL,
  `modelo` varchar(250) NOT NULL,
  `categoria` varchar(250) NOT NULL,
   `movimentacao` varchar(250) NOT NULL,
    `dt_hr` varchar(250) NOT NULL
  )
 */

// dados da conexao - Note que dependendo do computador os nomes são diferentes.
// Na etec o padrão é login:root senha:root
$database = 'bdconteiner';
$db_user = 'root';
$db_password = '';//no xampp deixar vazio, na etec deixar root


// instancia a classe, para USBSERVER, usar porta 3307 do mysql:
// $conn = new PDO('mysql:host=localhost:3307;dbname='. $database, $db_user, $db_password);
//depois é só trabalhar com o $conn

// instancia a classe==> para XAMPP:   MUDE PARA ESTE SE ESTIVER NO XAMPP
$conn = new PDO('mysql:host=localhost;dbname='. $database, $db_user, $db_password);


// pagina resgatada da URL, usando ternário. É um IF em uma linha só.
$page = (isset($_GET['page'])) ? $_GET['page'] : NULL;


// id restagado da URL
$id = (isset($_GET['id'])) ? (int) $_GET['id'] : 0;

// inicia a mensagem vazia
$mensagem = '';


// verifica se o BOTÃO do formulario foi acionado, existe o submit?
if (isset($_POST['submit'])) {
    //se sim
    // prepara os dados do formulario, Utilizando ternário
    $post_nome = (isset($_POST['nome'])) ? $_POST['nome'] : 'NULL';
    $post_numero = (isset($_POST['numero'])) ? $_POST['numero'] : 'NULL';
    $post_tipo = (isset($_POST['tipo'])) ? $_POST['tipo'] : 'NULL';
    $post_modelo = (isset($_POST['modelo'])) ? $_POST['modelo'] : 'NULL';
    $post_categoria = (isset($_POST['categoria'])) ? $_POST['categoria'] : 'NULL';
    $post_movimentacao = (isset($_POST['movimentacao'])) ? $_POST['movimentacao'] : 'NULL';
    $post_dt_hr = (isset($_POST['dt_hr'])) ? $_POST['dt_hr'] : 'NULL';
    $post_hr_inicio = (isset($_POST['hr_inicio'])) ? $_POST['hr_inicio'] : 'NULL';
    $post_id = (isset($_POST['id'])) ? (int) $_POST['id'] : 0;

    // verifica se foi o formulario de INSERT submit VALUE SALVAR
    if ($_POST['submit'] == 'Salvar') {

        // prepara o SQL, perceba aqui o $conn que é o objeto PDO, assim podemos usar o prepare
        $sql = $conn->prepare('INSERT INTO tb_cliente (nome, numero, tipo, modelo, categoria, movimentacao,dt_hr, hr_inicio)VALUES(:nome, :numero, :tipo, :modelo, :categoria, :movimentacao, :dt_hr, :hr_inicio)');

        // Prepara os dados do formulario
        $data = array(
            ':nome' => $post_nome,
            ':numero' => $post_numero,
            ':tipo' => $post_tipo,
            ':modelo' => $post_modelo,
            ':categoria' => $post_categoria,
            ':movimentacao' => $post_movimentacao,
            ':dt_hr' => $post_dt_hr,
            ':hr_inicio' => $post_hr_inicio,
        );

        try {

            // executa o SQL
            $sql->execute($data);

            // Mensagem de alerta
            $mensagem = alert('Registro Adicionado!');
            
        } catch (PDOException $e) {

            // mostra o erro
            $e->getMessage();
        }
    }


    // verifica se foi o formulario de UPDATE
    if ($_POST['submit'] == 'Alterar dados') {

        // prepara o SQL
        $sql = $conn->prepare('UPDATE tb_cliente SET nome= :nome, numero = :numero, tipo= :tipo, modelo= :modelo, categoria= :categoria, movimentacao= :movimentacao , dt_hr= :dt_hr,  hr_inicio= :hr_inicio WHERE id= :id');
   


// Prepara os dados do formulario        
        $data = array(
            ':nome' => $post_nome,
            ':numero' => $post_numero,
            ':tipo' => $post_tipo,
            ':modelo' => $post_modelo,
            ':categoria' => $post_categoria,
            ':movimentacao' => $post_movimentacao,
            ':dt_hr' => $post_dt_hr,
            ':hr_inicio' => $post_hr_inicio,
            
            ':id' => $post_id,
        );

        try {

        // executa
            $sql->execute($data);

        // mensagem
            $mensagem = alert('Registro Alterado com Sucesso!');
        } catch (PDOException $e) {

        // mostra o erro
            $e->getMessage("Erro no banco de dados (PDO)");
        }
    }
}


/* * ***************************************
 * 
 * SELECT    mostrar
 * 
 * **************************************** */


// prepara o SQL
$sql = $conn->prepare('SELECT * FROM tb_cliente ORDER BY id DESC');

try {
    // executa o SQL
    $sql->execute();

// Cria uma variavel de listagem
//PDOStatement::fetchAll — Retorna uma matriz contendo todas as linhas definidas pelo resultado
//PDO::FETCH_OBJ: retorna um objeto anônimo com nomes de propriedade que correspondem aos nomes das colunas retornados no seu conjunto de resultados
    $listar = $sql->fetchAll(PDO::FETCH_OBJ);
} catch (PDOException $e) {

    // mostra o erro
    $e->getMessage();
}


// verifica se o id foi acionado para o update
if ($id > 0) {

    // prepara o SQL
    $sql = $conn->prepare('SELECT * FROM tb_cliente WHERE id= :id');

    // Prepara os dados
    $data = array(':id' => $id);

    try {
        // executa o SQL
        $sql->execute($data);

    // Prepara o fetch
        $row = $sql->fetch(PDO::FETCH_OBJ);
    } catch (PDOException $e) {
    // mostra o erro
        $e->getMessage();
    }
}

/*
 * prepara os dados para serem mostrados
 * no php 5.3 as variaveis devem ser declaradas
 */
$value_id = (isset($row->id)) ? $row->id : FALSE;
$value_nome = (isset($row->nome)) ? $row->nome : '';
$value_numero = (isset($row->numero)) ? $row->numero : '';
$value_tipo = (isset($row->tipo)) ? $row->tipo : '';
$value_modelo = (isset($row->modelo)) ? $row->modelo : '';
$value_categoria = (isset($row->categoria)) ? $row->categoria : '';
$value_movimentacao = (isset($row->movimentacao)) ? $row->movimentacao : '/';
$value_dt_hr = (isset($row->dt_hr)) ? $row->dt_hr : '';
$value_hr_inicio = (isset($row->hr_inicio)) ? $row->hr_inicio : '';




/* * ***************************************
 * 
 * AREA DE DELETE
 * 
 * **************************************** */
if (($page == 'delete') && $id > 0) {

    // executa o SQL para excluir
    $sql = $conn->prepare('DELETE FROM tb_cliente WHERE id= :id');

    // prepara os dados
    $data = array(':id' => $id);

    try {
        // executa o SQL
        $sql->execute($data);

        // Mostra a mensagem de erro
        $mensagem = alert('Registro deletado!');
    } catch (PDOException $e) {

        // mostra a mensagem
        $e->getMessage();
    }
}



////////////////////////////////////////////////////////

/**  javascript alert() */
function alert($texto, $redirect = TRUE)
{
    $redirect = ($redirect) ? 'location.href="crud_pdo.php";' : '';
    return '
        <script type="text/javascript">
        alert("' . $texto . '");
        ' . $redirect . '
        </script>
    ';
}

/* * ***************************************
 * 
 * conteúdo da pagina 
 * 
 * **************************************** */


echo '<a href="crud_pdo.php">Lista de Clientes Cadastrados</a> |' . "\n";
echo '<a href="crud_pdo.php?page=Salvar">Cadastrar Novo Cliente</a> | ' . "\n";
echo '<a href="crud_pdo.php">Total de Importações e exportações</a> |' . "\n";
echo '<hr />' . "\n";
echo $mensagem;



/* * ***************************************
 * 
 * Listar/Mostrar registros 
 * 
 * **************************************** */

if ($page == NULL) {

    echo "<h1>Lista de Clientes Cadastrados</h1>\n";

    if (count($listar) > 0) :

        foreach ($listar as $row) :
            echo "<p>\n";
            echo 'ID: ', $row->id, "<br>\n";
            echo 'Nome: ', $row->nome, "<br>\n";
            echo 'Número do Contêiner: ', $row->numero, "<br>\n";
            echo 'Tipo do Contêiner: ', $row->tipo, "<br>\n";
            echo 'Status: ', $row->modelo, "<br>\n";
            echo 'Categoria: ', $row->categoria, "<br>\n";
            echo 'Tipo de movimentação:  ',  $row->movimentacao, "<br>\n";
            echo 'Hora da movimentação: ', $row->hr_inicio,  "<br>\n";
            echo '<a href="crud_pdo.php?page=Alterar dados&id=' . $row->id . '">Editar Dados</a> | ' . "\n";
            echo '<a href="crud_pdo.php?page=delete&id=' . $row->id . '"">Remover Cliente <br><br></a>' . "\n";
            echo 'Importações: ', $row->id, "<br>\n";
            echo 'Exportações: ', $row->id, "<br>\n";
            echo "</p>\n";
        endforeach;


    else :

        echo 'Adicione um registro <a href="crud_pdo.php?page=Salvar">Aqui</a>';

    endif;

    /* * ***************************************
* 
* Adicionar registro 
* 
* **************************************** */
} elseif ($page == 'Salvar') {

    echo "<h1>Cadastro de Clientes</h1>\n";

    echo '<form method="post">' . "\n";
    echo 'Nome:<br>' . "\n";
    echo '<input type="text" name="nome" required style="width: 350px" /><br>' . "\n";

    echo 'Número do Contêiner:<br>' . "\n";
    echo '<input type="text" name="numero"   required style="width: 350px" maxlength="11"><br>' . "\n";

    echo 'Tipo do Contêiner:<br>' . "\n";
    echo '<input type="radio" name="tipo" value="20" required checked >20<br>' . "\n";
    echo '<input type="radio" name="tipo" value="40"   >40<br>' . "\n";

    echo 'Status:<br>' . "\n";
    echo '<input type="radio" name="modelo" value="Cheio" required checked >Cheio<br>' . "\n";
    echo '<input type="radio" name="modelo" value="Vazio"  >Vazio<br>' . "\n";

    echo 'Categoria:<br>' . "\n";
    echo '<input type="radio" name="categoria" value="Importação" required checked >Importação<br>' . "\n";
    echo '<input type="radio" name="categoria" value="Exportação" >Exportação<br>' . "\n";

    echo 'Tipo de movimentação:<br>' . "\n";
    echo '<select name="movimentacao" ><br>' . "\n";
    echo '<option value= "Embarque">Embarque</option><br>' . "\n";
    echo '<option value= "Descarga">Descarga</option><br>' . "\n";
    echo '<option value= "Get in">Get in</option><br>' . "\n";
    echo '<option value= "Get out">Get out</option><br>' . "\n";
    echo '<option value= "Pesagem">Pesagem</option><br>' . "\n";
    echo '<option value= "Scanner">Scanner</option></select><br>' . "\n";

    
    echo 'Data e hora do Início:<br>' . "\n";
    echo '<input type="date" name="dt_hr" required ' . "\n";


    echo 'Hora do Início:<br>' . "\n";
    echo '<input type="time" name="hr_inicio" required <br>' . "\n";

    echo '<br>Data e hora do Fim:<br>' . "\n";
    echo '<input type="date" name="dt_hr" required ' . "\n";


    echo 'Hora do Fim :<br>' . "\n";
    echo '<input type="time" name="hr_fim" required <br>' . "\n";



    echo '<br><br><input type="submit" name="submit" value="Salvar"> <br>' . "\n";
    echo '</form>' . "\n";

  
        
    
    

    /* * ***************************************
* 
* Editar registro
* 
* **************************************** */
} elseif ($page == 'Alterar dados') {

    echo "<h1>Editar Dados</h1>\n";

    if ($value_id) {

        echo '<form method="post">' . "\n";
        echo '<input type="hidden" name="id" value="' . $value_id . '"><br>' . "\n";
        echo 'Nome:<br>' . "\n";
        echo '<input type="text" name="nome" readonly value="' . $value_nome . '" style="width: 350px"><br>' . "\n";
        /* se desejar que possa editar o nome do funcionario, remova o readonly da linha acima */

        echo 'Número do Contêiner:<br>' . "\n";
        echo '<input type="text" name="numero" value="' . $value_numero . '"  maxlength="11" style="width: 350px"><br>' . "\n";

        echo 'Tipo do Contêiner:<br>' . "\n";
        echo '<input type="radio" name="tipo" value="20' . $value_tipo . '" checked >20<br>' . "\n";
        echo '<input type="radio" name="tipo" value="40' . $value_tipo . '" >40<br>' . "\n";

        echo 'Status:<br>' . "\n";
        echo '<input type="radio" name="modelo" value="Cheio' . $value_modelo . '" checked >Cheio<br>' . "\n";
        echo '<input type="radio" name="modelo" value="Vazio' . $value_modelo . '" >Vazio<br>' . "\n";

        echo 'Categoria:<br>' . "\n";
        echo '<input type="radio" name="categoria" value="Importação' . $value_categoria . '" checked >Importação<br>' . "\n";
        echo '<input type="radio" name="categoria" value="Exportação' . $value_categoria . '" >Exportação<br>' . "\n";

        echo 'Tipo de movimentação:<br>' . "\n";
        echo '<select name="movimentacao" ><br>' . "\n";
        echo '<option value="Embarque' . $value_movimentacao . '" >Embarque </option><br>' . "\n";
        echo '<option value="Descarga' . $value_movimentacao . '" >Descarga </option><br>' . "\n";
        echo '<option value="Get in' . $value_movimentacao . '" >Get in </option><br>' . "\n";
        echo '<option value="Get out' . $value_movimentacao . '" >Get out! </option><br>' . "\n";
        echo '<option value="Pesagem' . $value_movimentacao . '" >Pesagem </option><br>' . "\n";
        echo '<option value="Scanner' . $value_movimentacao . '" >Scanner</option></select><br>' . "\n";

        
        echo 'Data e hora do Início:<br>' . "\n";
        echo '<input type="date" name="dt_hr" value="' . $value_dt_hr . '" required ' . "\n";


        echo 'Hora do Início:<br>' . "\n";
        echo '<input type="time" name="hr_inicio"  value="' . $value_hr_inicio . '"   <br>' . "\n";

        echo '<br>Data e hora do Fim:<br>' . "\n";
        echo '<input type="date" name="dt_hr"   value="' . $value_dt_hr . '"  required ' . "\n";


        echo 'Hora do Fim :<br>' . "\n";
        echo '<input type="time" name="hr_fim"  value="' . $value_hr_inicio. '"  <br>' . "\n";


        echo ' <br><br><input type="submit" name="submit" value="Alterar dados">' . "\n";
        echo '</form>' . "\n";


        
    } else {

        echo 'Registro não existe';
    }
}





?>



</html>