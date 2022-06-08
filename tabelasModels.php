<?php
    
	class tabelasModels{

    //---------- Funções de uso geral -------------------------------------------------------------
    
    //---------- Verifica se há registro no banco de dados com o valor informado --------------    
        public static function possuiMovimento($id, $tabela, $campo){
            $sql = MySql::conectar()->prepare("SELECT * FROM $tabela WHERE $campo = ?");
            $sql->execute(array($id));
            if($sql->rowCount() > 0){
                return true;
            }else{
                return false;
            }
        }

    //---------- Retorna um registro com valor informado --------------------------------------
        public static function selecionaRegistroId($id, $tabela, $campo){
            $sql = MySql::conectar()->prepare("SELECT * FROM $tabela WHERE $campo = ?");
            $sql->execute(array($id));
            $sql = $sql->fetch();

            return $sql;

        }

    //---------- Retorna todos os registros com valor informado -------------------------------
        public static function listaRegistrosSelecao($tabela, $campo, $valor){
            $sql = MySql::conectar()->prepare("SELECT * FROM $tabela WHERE $campo = ?");
            $sql->execute(array($valor));
            $sql = $sql->fetchAll();

            return $sql;

        }

    //---------- Retorna todos os registros na ordem informada --------------------------------
        public static function listaRegistros($tabela, $ordem=""){
            if($ordem == ""){
                $sql = MySql::conectar()->prepare("SELECT * FROM $tabela");
            }else{
                $sql = MySql::conectar()->prepare("SELECT * FROM $tabela ORDER BY $ordem");
            }
            
            $sql->execute();
            $sql = $sql->fetchAll();

            return $sql;
        }

     //---------- Função para salvar dados no banco de dados ---------------------------------------
        public static function salvaTabela($id, $dados, $tabela, $campoId=""){
    
            //---------- Salva registro novo ------------------------------------------------------
            if($id == ""){
                
                $certo = true;
                $query = "INSERT INTO $tabela VALUES (null";
                    foreach ($dados as $key => $value) {
                        $nome = $key;
                        $valor = $value;
                        //if($value == ''){
                        //    $certo = false;
                        //    break;
                        //}
                        $query.=",?";
                        $parametros[] = $value;
                    }
                $query.=")";
                
                if($certo == true){
                    $sql = MySql::conectar()->prepare($query);
                    $sql->execute($parametros);
                    
                }

                return $certo;
                    
            //---------- Salva resgitro alterado --------------------------------------------------
            }else{
                $certo = true;
                $first = false;

                $query = "UPDATE $tabela SET ";
                
                foreach ($dados as $key => $value) {
                    $nome = $key;
                    $valor = $value;
                
                    //if($value == ''){
                    //    $certo = false;
                    //    break;
                    //}

                    if($first == false){
                        $first = true;
                        $query.="$nome=?";
                    }else{
                        $query.=",$nome=?";
                    }

                    $parametros[] = $value;
                }

                if($certo == true){
                    $parametros[] = $id;
                    $sql = MySql::conectar()->prepare($query.' WHERE '.$campoId.'=?');
                    $sql->execute($parametros);
                }

                return $certo;
            }

        }
     
    //---------- Função para excluir registro no banco de dados ----------------------------------
        public static function deletarRegistro($id, $tabela, $campo){
            $sql = MySql::conectar()->prepare("DELETE FROM $tabela WHERE $campo = ?");
            if($sql->execute(array($id))){
                return true;
            }else{
                return false;
            };
        }
     
     //---------- Função para pesquisar registro no banco de dados --------------------------------
        public static function pesquisaRegistros($campo, $valor, $tabela){
            $sql = MySql::conectar()->prepare("SELECT * FROM $tabela WHERE $campo LIKE '%".$valor."%' ");
            $sql->execute();
            $sql = $sql->fetchAll();

            return $sql;
        }

    //---------- Função para pesquisar registro no banco de dados e retornar outro campo ----------
        public static function retornaId($tabela, $campoPesq, $valorPesq, $campoRetorno){
            $sql = MySql::conectar()->prepare("SELECT * FROM $tabela WHERE $campoPesq = ?");
            $sql->execute(array($valorPesq));
            if($sql->rowCount() > 0){
                $sql = $sql->fetch();
                return $sql[$campoRetorno];
            }else{
                return 0;
            }
        }

	}