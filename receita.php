<?php

// Verificar se foi enviando dados via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idReceita = (isset($_POST["idReceita"]) && $_POST["idReceita"] != null) ? $_POST["idReceita"] : "";
    $nome = (isset($_POST["nome"]) && $_POST["nome"] != null) ? $_POST["nome"] : "";
    $coz_elaborador = (isset($_POST["coz_elaborador"]) && $_POST["coz_elaborador"] != null) ? $_POST["coz_elaborador"] : "";
    $data_criacao = (isset($_POST["data_criacao"]) && $_POST["data_criacao"] != null) ? $_POST["data_criacao"] : "";
    $Categoria = (isset($_POST["Categoria"]) && $_POST["Categoria"] != null) ? $_POST["Categoria"] : "";
    $modo_preparo = (isset($_POST["modo_preparo"]) && $_POST["modo_preparo"] != null) ? $_POST["modo_preparo"] : NULL;
    $qtde_porcao = (isset($_POST["qtde_porcao"]) && $_POST["qtde_porcao"] != null) ? $_POST["qtde_porcao"] : "";
    $degustador = (isset($_POST["degustador"]) && $_POST["degustador"] != null) ? $_POST["degustador"] : "";
    $data_degustacao = (isset($_POST["data_degustacao"]) && $_POST["data_degustacao"] != null) ? $_POST["data_degustacao"] : "";
    $nota_degustacao = (isset($_POST["nota_degustacao"]) && $_POST["nota_degustacao"] != null) ? $_POST["nota_degustacao"] : "";
    $ind_inedita = (isset($_POST["ind_inedita"]) && $_POST["ind_inedita"] != null) ? $_POST["ind_inedita"] : "";
} else if (!isset($idReceita)) {
    // Se não se não foi setado nenhum valor para variável $id
    $idReceita = (isset($_GET["idReceita"]) && $_GET["idReceita"] != null) ? $_GET["idReceita"] : "";
    $nome = NULL;
    $coz_elaborador = NULL;
    $data_criacao = NULL;
    $Categoria = NULL;
    $modo_preparo = NULL;
    $qtde_porcao = NULL;
    $degustador = NULL;
    $data_degustacao = NULL;
    $nota_degustacao = NULL;
    $ind_inedita = NULL;
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
        if ($idReceita != "") {
            $stmt = $conexao->prepare("UPDATE g1_receita SET nome=?, coz_elaborador=?, data_criacao=? Categoria=? modo_preparoo=? qrde_porcao=? degustador=>? data_degustacao=? nota_degustacao=?
            ind_inedita=? WHERE idReceita = ?");
            $stmt->bindParam(8, $idReceita);
        } else {
            $stmt = $conexao->prepare("INSERT INTO g1_receita (nome, coz_elaborador, data_criacao, Categoria, modo_preparo, qtde_porcao, degustador, data_degustacao, nota_degustacao, ind_inedita) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        }
        $stmt->bindParam(1, $nome);
        $stmt->bindParam(2, $coz_elaborador);
        $stmt->bindParam(3, $data_criacao);
        $stmt->bindParam(4, $Categoria);
        $stmt->bindParam(5, $modo_preparo);
        $stmt->bindParam(6, $qtde_porcao);
        $stmt->bindParam(7, $degustador);
        $stmt->bindParam(8, $data_degustacao);
        $stmt->bindParam(9, $nota_degustacao);
        $stmt->bindParam(10, $ind_inedita);
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                echo "Dados cadastrados com sucesso!";
                $idReceita = null; 
                $nome = null;
                $coz_elaborador = null;
                $data_criacao = null;
                $Categoria = null;
                $modo_preparo = null;
                $qtde_porcao= null;
                $degustador = null;
                $data_degustacao = null;
                $nota_degustacao = null;
                $ind_inedita = null;
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
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "upd" && $idReceita != "") {
    try {
        $stmt = $conexao->prepare("SELECT * FROM g1_receita WHERE idReceita = ?");
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            $rs = $stmt->fetch(PDO::FETCH_OBJ);
            $id = $rs->idReceita;
            $nome = $rs->nome;
            $coz_elaborador = $rs->coz_elaborador;
            $data_criacao = $rs->data_criacao;
            $Categoria = $rs->Categoria;
            $modo_preparo = $rs->modo_preparo;
            $qtde_porcao = $rs->qtde_porcao;
            $degustador = $rs->degustador;
            $data_degustacao = $rs->data_degustacao;
            $nota_degustacao = $rs->nota_degustacao;
            $ind_inedita = $rs->ind_inedita;
        } else {
            throw new PDOException("Erro: Não foi possível executar a declaração sql");
        }
    } catch (PDOException $erro) {
        echo "Erro: ".$erro->getMessage();
    }
}
 
// Bloco if utilizado pela etapa Delete
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "del" && $idReceita != "") {
    try {
        $stmt = $conexao->prepare("DELETE FROM g1_receita WHERE idReceita = ?");
        $stmt->bindParam(1, $idReceita, PDO::PARAM_INT);
        if ($stmt->execute()) {
            echo "Registo foi excluído com êxito";
            $idReceita = null;
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
                if (isset($idReceita) && $idReceita != null || $idReceita != "") {
                    echo "value=\"{$idReceita}\"";
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
               <input type="text" name="coz_elaborador" <?php
 
               // Preenche o email no campo email com um valor "value"
               if (isset($coz_elaborador) && $coz_elaborador != null || $coz_elaborador != "") {
                   echo "value=\"{$coz_elaborador}\"";
               }
               ?> />
               Data admissao:
               <input type="date" name="data_criacao" <?php
 
               // Preenche o celular no campo celular com um valor "value"
               if (isset($data_criacao) && $data_criacao != null || $data_criacao != "") {
                   echo "value=\"{$data_criacao}\"";
               }
               ?> />

                Salario:
                <input type="text" name="modo_preparo" <?php
 
                 // Preenche o nome no campo nome com um valor "value"
                if (isset($modo_preparo) && $modo_preparo != null || $modo_preparo != "") {
                     echo "value=\"{$modo_preparo}\"";
                }
                 ?> />

               Nome Fantasia:
                <input type="text" name="qtde_porcao" <?php
 
                // Preenche o nome no campo nome com um valor "value"
                if (isset($qtde_porcao) && $qtde_porcao!= null || $qtde_porcao != "") {
                    echo "value=\"{$qtde_porcao}\"";
                 }
                ?> />

                Nome Fantasia:
                <input type="text" name="degustador" <?php
 
                // Preenche o nome no campo nome com um valor "value"
                if (isset($degustador) && $degustador!= null || $degustador != "") {
                    echo "value=\"{$degustador}\"";
                 }
                ?> />

                Nome Fantasia:
                <input type="text" name="data_degustacao" <?php
 
                // Preenche o nome no campo nome com um valor "value"
                if (isset($data_degustacao) && $data_degustacao!= null || $data_degustacao != "") {
                    echo "value=\"{$data_degustcao}\"";
                 }
                ?> />
               Nome Fantasia:
                <input type="text" name="nome_fantasia" <?php
 
                // Preenche o nome no campo nome com um valor "value"
                if (isset($nota_degustacao) && $nota_degustacao!= null || $nota_degustacao != "") {
                    echo "value=\"{$nota_degustacao}\"";
                 }
                ?> />

                Data Demissao:
                <input type="date" name="Categoria" <?php
 
                // Preenche o celular no campo celular com um valor "value"
                 if (isset($ind_inedita) && $ind_inedita != null || $ind_inedita != "") {
                    echo "value=\"{$ind_inedita}\"";
                }
                ?> />



                        <?php
                            $sql = " SELECT * FROM g1_categoria";
                            try {
                                $stmt = $conexao -> prepare($sql);
                                $stmt -> execute();
                                $results = $stmt -> fetchAll();
                            }
                            catch(Exception $ex){
                                echo ($ex -> getMessage());
                        
                            }
                        ?>
                        <!--Apresentando as opções em forma de dropbox-->
                        <label for="id">Categoria</label>
                        <select id="id" name="descricao">
                        <option>Cargo</option>
                        <?php foreach($results as $output) {?>
                    <option value="<?php $output["id"];?>"><?php echo $output["descricao"];?></option>
                        <?php } ?>
                    </select>
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
                                       ."</td><td><center><a href=\"?act=upd&id=".$rs->idReceita."\">[Alterar]</a>"
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
                <a href="index.html"><h3>voltar ao menu anterio</h3></a>

        </body>
    </html>
