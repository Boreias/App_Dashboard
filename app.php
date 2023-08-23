<?php

    class Dashboard {
        public $data_inicio;
        public $data_fim;
        public $numero_vendas;
        public $total_vendas;
        public $clientes_ativos;
        public $clientes_inativos;
        public $total_reclamacoes;
        public $total_elogios;
        public $total_sugestoes;
        public $total_despesas;

        public function __get($name)
        {
            return $this->$name;
        }

        public function __set($atr, $valor)
        {
            $this->$atr = $valor;
            return $this;
        }
    }

    class Conexao {
        private $host = 'localhost';
        private $dbname = 'dashboard';
        private $user = 'root';
        private $pass = '';

        public function conectar() {
            try {
                $conexao = new PDO(
                    "mysql:host=$this->host;dbname=$this->dbname",
                    "$this->user",
                    "$this->pass"
                );

                $conexao->exec('set charset utf8');

                return $conexao;

            } catch (PDOException $e) {
                echo '<p>' . $e->getMessage() . '</p>';
            }
        }
    }

    class Bd {
        private $conexao;
        private $dashboard;

        public function __construct(Conexao $conexao, Dashboard $dashboard)
        {
            $this->conexao = $conexao->conectar();
            $this->dashboard = $dashboard;
        }

        public function getNumeroVendas () {
            $query = 'SELECT COUNT(*) AS numero_vendas FROM tb_vendas WHERE data_venda BETWEEN :data_inicio AND :data_fim';

            $stmt = $this->conexao->prepare($query);
            $stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
            $stmt->bindValue(':data_fim', $this->dashboard->__get('data_fim'));
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_OBJ)->numero_vendas;
        }

        public function getTotalVendas () {
            $query = 'SELECT SUM(total) AS total_vendas FROM tb_vendas WHERE data_venda BETWEEN :data_inicio AND :data_fim';

            $stmt = $this->conexao->prepare($query);
            $stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
            $stmt->bindValue(':data_fim', $this->dashboard->__get('data_fim'));
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_OBJ)->total_vendas;
        }

        public function getTotalDespesas () {
            $query = 'SELECT SUM(total) AS total_despesas FROM tb_despesas WHERE data_despesa BETWEEN :data_inicio AND :data_fim';

            $stmt = $this->conexao->prepare($query);
            $stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
            $stmt->bindValue(':data_fim', $this->dashboard->__get('data_fim'));
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_OBJ)->total_despesas;
        }

        public function getClietesAtivos () {
            $query = 'SELECT COUNT(*) AS clientes_ativos FROM tb_clientes WHERE cliente_ativo = :clientes_ativos';

            $stmt = $this->conexao->prepare($query);
            $stmt->bindValue(':clientes_ativos', true);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_OBJ)->clientes_ativos;
        }

        public function getClietesInativos () {
            $query = 'SELECT COUNT(*) AS clientes_inativos FROM tb_clientes WHERE cliente_ativo = :clientes_inativos';

            $stmt = $this->conexao->prepare($query);
            $stmt->bindValue(':clientes_inativos', false);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_OBJ)->clientes_inativos;
        }

        public function getReclamacoes () {
            $query = 'SELECT COUNT(*) AS total_reclamacoes FROM tb_contatos WHERE tipo_contato = :reclamacoes';

            $stmt = $this->conexao->prepare($query);
            $stmt->bindValue(':reclamacoes', 1);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_OBJ)->total_reclamacoes;
        }

        public function getSugestoes () {
            $query = 'SELECT COUNT(*) AS total_sugestoes FROM tb_contatos WHERE tipo_contato = :sugestoes';

            $stmt = $this->conexao->prepare($query);
            $stmt->bindValue(':sugestoes', 2);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_OBJ)->total_sugestoes;
        }

        public function getElogios () {
            $query = 'SELECT COUNT(*) AS total_elogios FROM tb_contatos WHERE tipo_contato = :elogios';

            $stmt = $this->conexao->prepare($query);
            $stmt->bindValue(':elogios', 3);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_OBJ)->total_elogios;
        }
    }

    $dashboard = new Dashboard();

    $conexao = new Conexao();

    $competencia = explode('-', $_GET['competencia']);
    $ano = $competencia[0];
    $mes = $competencia[1];

    $dias_do_mes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);

    $dashboard->__set('data_inicio', $ano . '-' . $mes . '-01');
    $dashboard->__set('data_fim', $ano . '-' . $mes . '-' . $dias_do_mes);

    $bd = new Bd($conexao, $dashboard);

    $dashboard->__set('numero_vendas', $bd->getNumeroVendas());
    $dashboard->__set('total_vendas', $bd->getTotalVendas());
    $dashboard->__set('total_despesas', $bd->getTotalDespesas());
    $dashboard->__set('clientes_ativos', $bd->getClietesAtivos());
    $dashboard->__set('clientes_inativos', $bd->getClietesInativos());
    $dashboard->__set('total_reclamacoes', $bd->getReclamacoes());
    $dashboard->__set('total_sugestoes', $bd->getSugestoes());
    $dashboard->__set('total_elogios', $bd->getElogios());
    echo json_encode($dashboard);
    // print_r($_GET);

?>