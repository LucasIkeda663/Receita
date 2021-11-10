<?php

// Verificar se foi enviando dados via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (isset($_POST["id"]) && $_POST["id"] != null) ? $_POST["id"] : "";
    $titulo = (isset($_POST["titulo"]) && $_POST["titulo"] != null) ? $_POST["titulo"] : "";
    $isbn = (isset($_POST["isbn"]) && $_POST["isbn"] != null) ? $_POST["isbn"] : "";
    $editor = (isset($_POST["editor"]) && $_POST["editor"] != null) ? $_POST["data_adm"] : "";

    
} else if (!isset($id)) {
    // Se não se não foi setado nenhum valor para variável $id
    $id = (isset($_GET["id"]) && $_GET["id"] != null) ? $_GET["id"] : "";
    $titulo = NULL;
    $isbn = NULL;
    $editor = NULL;


}
 
// Cria a conexão com o banco de dados
try {
    $conexao = new PDO("mysql:host=127.0.0.1:3307 ; dbname=acervo_receitas", "root", "Lukinhas34.");
    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conexao->exec("set names utf8");
} catch (PDOException $erro) {
    echo "Erro na conexão:".$erro->getMessage();
}
 
// Bloco If que Salva os dados no Banco - atua como Create e Update
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "save" && $nome != "") {
    try {
        if ($id != "") {
            $stmt = $conexao->prepare("UPDATE g1_livro SET nome=?, rg=?, data_adm=?  WHERE id = ?");
            $stmt->bindParam(8, $id);
        } else {
            $stmt = $conexao->prepare("INSERT INTO g1_livro (nome, rg, data_adm) VALUES (?, ?, ?)");
        }
        $stmt->bindParam(1, $titulo);
        $stmt->bindParam(2, $isbn);
        $stmt->bindParam(3, $editor);

        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                echo "Dados cadastrados com sucesso!";
                $id = null;
                $titulo = null;
                $isbn = null;
                $editor = null;

            } else {
                echo "Erro ao tentar efetivar cadastro";
            }
        } else {
            throw new PDOException("Erro: Não foi possível executar a declaração sql");
        }
    } catch (PDOException $erro) {
        echo "Erro: ".$erro->getMessage();
    }
}
 
// Bloco if que recupera as informações no formulário, etapa utilizada pelo Update
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "upd" && $id != "") {
    try {
        $stmt = $conexao->prepare("SELECT * FROM g1_livro WHERE id = ?");
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            $rs = $stmt->fetch(PDO::FETCH_OBJ);
            $id = $rs->id;
            $titulo = $rs->titulo;
            $isbn = $rs->titulo;
            $editor = $rs->titulo;
        } else {
            throw new PDOException("Erro: Não foi possível executar a declaração sql");
        }
    } catch (PDOException $erro) {
        echo "Erro: ".$erro->getMessage();
    }
}
 
// Bloco if utilizado pela etapa Delete
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "del" && $id != "") {
    try {
        $stmt = $conexao->prepare("DELETE FROM g1_livro WHERE id = ?");
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            echo "Registo foi excluído com êxito";
            $id = null;
        } else {
            throw new PDOException("Erro: Não foi possível executar a declaração sql");
        }
    } catch (PDOException $erro) {
        echo "Erro: ".$erro->getMessage();
    }
}
?>
<!DOCTYPE html>
    <html>
        <head>
            <meta charset="UTF-8">
            <title>Funcionarios</title>
        </head>
        <body>
            <form action="?act=save" method="POST" name="form1" >
                <h1>Funcionarios</h1>
                <hr>
                <input type="hidden" name="id" <?php
                 
                // Preenche o id no campo id com um valor "value"
                if (isset($id) && $id != null || $id != "") {
                    echo "value=\"{$id}\"";
                }
                ?> />
                Titulo:
               <input type="text" name="titulo" <?php
 
               // Preenche o nome no campo nome com um valor "value"
               if (isset($titulo) && $titulo != null || $titulo != "") {
                   echo "value=\"{$titulo}\"";
               }
               ?> />
               RG:
               <input type="text" name="isbn" <?php
 
               // Preenche o email no campo email com um valor "value"
               if (isset($isbn) && $isbn != null || $isbn != "") {
                   echo "value=\"{$isbn}\"";
               }
               ?> />
               Data admissao:
               <input type="text" name="editor" <?php
 
               // Preenche o celular no campo celular com um valor "value"
               if (isset($editor) && $editor != null || $editor != "") {
                   echo "value=\"{$editor}\"";
               }
               ?> />

               <input type="submit" value="salvar" />
               <input type="reset" value="Novo" />
               <hr>
            </form>
            <table border="1" width="100%">
                <tr>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Celular</th>
                    <th>Ações</th>
                </tr>
                <?php
 
                // Bloco que realiza o papel do Read - recupera os dados e apresenta na tela
                try {
                    $stmt = $conexao->prepare("SELECT * FROM g1_livro");
                    if ($stmt->execute()) {
                        while ($rs = $stmt->fetch(PDO::FETCH_OBJ)) {
                            echo "<tr>";
                            echo "<td>".$rs->nome."</td><td>".$rs->email."</td><td>".$rs->celular
                                       ."</td><td><center><a href=\"?act=upd&id=".$rs->id."\">[Alterar]</a>"
                                       ."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
                                       ."<a href=\"?act=del&id=".$rs->id."\">[Excluir]</a></center></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "Erro: Não foi possível recuperar os dados do banco de dados";
                    }
                } catch (PDOException $erro) {
                    echo "Erro: ".$erro->getMessage();
                }
                ?>
                
            </table>
                <a href="index.html"><h3>voltar ao menu anterio

        </body>
    </html>
