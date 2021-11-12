<?php

// Verificar se foi enviando dados via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (isset($_POST["id"]) && $_POST["id"] != null) ? $_POST["id"] : "";
    $nome = (isset($_POST["nome"]) && $_POST["nome"] != null) ? $_POST["nome"] : "";
    $rg = (isset($_POST["rg"]) && $_POST["rg"] != null) ? $_POST["rg"] : "";
    $data_adm = (isset($_POST["data_adm"]) && $_POST["data_adm"] != null) ? $_POST["data_adm"] : "";
    $data_demissao = (isset($_POST["data_demissao"]) && $_POST["data_demissao"] != null) ? $_POST["data_demissao"] : null;
    $salario = (isset($_POST["salario"]) && $_POST["salario"] != null) ? $_POST["salario"] : NULL;
    $cargo = (isset($_POST["cargo"]) && $_POST["cargo"] != null) ? $_POST["cargo"] : "";
    $nome_fantasia = (isset($_POST["nome_fantasia"]) && $_POST["nome_fantasia"] != null) ? $_POST["nome_fantasia"] : "";
    
} else if (!isset($id)) {
    // Se não se não foi setado nenhum valor para variável $id
    $id = (isset($_GET["id"]) && $_GET["id"] != null) ? $_GET["id"] : "";
    $nome = NULL;
    $rg = NULL;
    $data_adm = NULL;
    $data_demissao = NULL;
    $salario = NULL;
    $cargo = NULL;
    $nome_fantasia = NULL;

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
            $stmt = $conexao->prepare("UPDATE g1_funcionario SET nome=?, rg=?, data_adm=? data_demissao=? salario=? cargo=? nome_fantasia=>? WHERE id = ?");
            $stmt->bindParam(8, $id);
        } else {
            $stmt = $conexao->prepare("INSERT INTO g1_funcionario (nome, rg, data_adm, data_demissao, salario, cargo, nome_fantasia) VALUES (?, ?, ?, ?, ?, ?, ?)");
        }
        $stmt->bindParam(1, $nome);
        $stmt->bindParam(2, $rg);
        $stmt->bindParam(3, $data_adm);
        $stmt->bindParam(4, $data_demissao);
        $stmt->bindParam(5, $salario);
        $stmt->bindParam(6, $cargo);
        $stmt->bindParam(7, $nome_fantasia);
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                echo "Dados cadastrados com sucesso!";
                $id = null;
                $nome = null;
                $rg = null;
                $data_adm = null;
                $data_demissao = null;
                $salario = null;
                $cargo = null;
                $nome_fantasia = null;
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
        $stmt = $conexao->prepare("SELECT * FROM g1_funcionario WHERE id = ?");
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            $rs = $stmt->fetch(PDO::FETCH_OBJ);
            $id = $rs->id;
            $nome = $rs->nome;
            $rg = $rs->rg;
            $data_adm = $rs->data_adm;
            $data_demissao = $rs->data_demissao;
            $salario = $rs->salario;
            $cargo = $rs->cargo;
            $nome_fantasia = $rs->nome_fantasia;
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
        $stmt = $conexao->prepare("DELETE FROM g1_funcionario WHERE id = ?");
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
                Nome:
               <input type="text" name="nome" <?php
 
               // Preenche o nome no campo nome com um valor "value"
               if (isset($nome) && $nome != null || $nome != "") {
                   echo "value=\"{$nome}\"";
               }
               ?> />
               RG:
               <input type="text" name="rg" <?php
 
               // Preenche o email no campo email com um valor "value"
               if (isset($rg) && $rg != null || $rg != "") {
                   echo "value=\"{$rg}\"";
               }
               ?> />
               Data admissao:
               <input type="date" name="data_adm" <?php
 
               // Preenche o celular no campo celular com um valor "value"
               if (isset($data_adm) && $data_adm != null || $data_adm != "") {
                   echo "value=\"{$data_adm}\"";
               }
               ?> />
                Data Demissao:
                <input type="date" name="data_demissao" <?php
 
                // Preenche o celular no campo celular com um valor "value"
                 if (isset($data_demissao) && $data_demissao != null || $data_demissao != "") {
                    echo "value=\"{$data_demissao}\"";

                    
                }
                ?> />
                Salario:
                <input type="text" name="salario" <?php
 
                 // Preenche o nome no campo nome com um valor "value"
                if (isset($salario) && $salario != null || $salario != "") {
                     echo "value=\"{$salario}\"";
                }
                 ?> />

               Nome Fantasia:
                <input type="text" name="nome_fantasia" <?php
 
                // Preenche o nome no campo nome com um valor "value"
                if (isset($nome_fantasia) && $nome_fantasia!= null || $nome_fantasia != "") {
                    echo "value=\"{$nome_fantasia}\"";
                 }
                   ?> />

                <select id="id" name="cargo">
                 <option>Cargo</option>   
                <?php
                    $select = $conexao->prepare("SELECT * FROM g1_cargo ORDER BY descricao ASC");
                    $select->execute();
                    $fetchAll = $select->fetchAll();
                    foreach($fetchAll as $cargos)
                    {
                        echo '<option value="'.$cargos["id"].'">'.$cargos["descricao"].'</option>';
                    }

                ?>

       
               <input type="submit" value="salvar" />
               <input type="reset" value="Novo" />
               <hr>
            </form>
            <table border="1" width="100%">
                <tr>
                    <th>Nome</th>
                    <th>RG</th>
                    <th>Data de admissão</th>
                    <th>Data de Demissão</th>
                    <th>Salário</th>
                    <th>nome Fantasia</th>
                    <th>Cargo</th>
                    <th>Ações</th>
                </tr>
                <?php
 
                // Bloco que realiza o papel do Read - recupera os dados e apresenta na tela
                try {
                    $stmt = $conexao->prepare("SELECT * FROM g1_funcionario");
                    if ($stmt->execute()) {
                        while ($rs = $stmt->fetch(PDO::FETCH_OBJ)) {
                            echo "<tr>";
                            echo "<td>".$rs->nome."</td><td>".$rs->rg."</td><td>".$rs->data_adm."</td><td>".$rs->data_demissao."</td><td>".$rs->salario."</td><td>".$rs->nome_fantasia."</td><td>".$rs->cargo
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