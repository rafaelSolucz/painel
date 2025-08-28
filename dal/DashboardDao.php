<?php
namespace App\Dal;

use App\Dal\Conn;
use App\Model\Dashboard;
use \PDO;
use \PDOException;
use \Exception;

abstract class DashboardDao
{
    public static function cadastrar(Dashboard $dashboard, array $roles): int
    {
        try {
            $pdo = Conn::getConn();
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("INSERT INTO dashboards (titulo, url_iframe) VALUES (?, ?)");
            $stmt->execute([$dashboard->getTitulo(), $dashboard->getUrlIframe()]);
            $dashboardId = (int) $pdo->lastInsertId();

            $stmtAcesso = $pdo->prepare("INSERT INTO acessos_dashboard (id_dashboard, role) VALUES (?, ?)");
            foreach ($roles as $role) {
                $stmtAcesso->execute([$dashboardId, $role]);
            }

            $pdo->commit();
            return $dashboardId;
        } catch (\PDOException $e) {
            $pdo->rollBack();
            throw new \PDOException("Erro ao salvar dashboard no Banco de Dados: " . $e->getMessage());
        }
    }

    public static function buscarPorId(int $id): ?Dashboard
    {
        try {
            $pdo = Conn::getConn();
            $stmt = $pdo->prepare("SELECT * FROM dashboards WHERE id=?");
            $stmt->execute([$id]);
            $dados = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$dados)
                return null;
            return Dashboard::criarDashboard(
                $dados["id"],
                $dados["titulo"],
                $dados["url_iframe"]
            );
        } catch (\PDOException $e) {
            throw new \PDOException("Erro ao buscar dashboard por ID: " . $e->getMessage());
        }
    }

    public static function listarPorRole(string $role): array
    {
        try {
            $pdo = Conn::getConn();
            $stmt = $pdo->prepare("
                SELECT d.id, d.titulo, d.url_iframe
                FROM dashboards d
                JOIN acessos_dashboard ad ON d.id = ad.id_dashboard
                WHERE ad.role = ?
            ");
            $stmt->execute([$role]);
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $dashboards = [];
            foreach ($resultados as $dados) {
                $dashboards[] = Dashboard::criarDashboard(
                    $dados["id"],
                    $dados["titulo"],
                    $dados["url_iframe"]
                );
            }
            return $dashboards;
        } catch (\PDOException $e) {
            throw new \PDOException("Erro ao listar dashboards: " . $e->getMessage());
        }
    }

    // NOVO: Lista todos os dashboards para o painel de admin
    public static function listarTodos(): array
    {
        try {
            $pdo = Conn::getConn();
            $stmt = $pdo->query("SELECT * FROM dashboards");
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $dashboards = [];
            foreach ($resultados as $dados) {
                $dashboards[] = Dashboard::criarDashboard(
                    $dados["id"],
                    $dados["titulo"],
                    $dados["url_iframe"]
                );
            }
            return $dashboards;
        } catch (\PDOException $e) {
            throw new \PDOException("Erro ao listar todos os dashboards: " . $e->getMessage());
        }
    }

    // NOVO: Busca os roles associados a um dashboard
    public static function getRoles(int $dashboardId): array
    {
        try {
            $pdo = Conn::getConn();
            $stmt = $pdo->prepare("SELECT role FROM acessos_dashboard WHERE id_dashboard = ?");
            $stmt->execute([$dashboardId]);
            // Retorna um array simples com os nomes dos roles, ex: ['admin', 'planejamento']
            return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
        } catch (\PDOException $e) {
            throw new \PDOException("Erro ao buscar roles do dashboard: " . $e->getMessage());
        }
    }
    
    // NOVO: Edita um dashboard e seus acessos
    public static function editar(Dashboard $dashboard, array $roles): void
    {
        try {
            $pdo = Conn::getConn();
            $pdo->beginTransaction();

            // 1. Atualiza a tabela dashboards
            $stmt = $pdo->prepare("UPDATE dashboards SET titulo = ?, url_iframe = ? WHERE id = ?");
            $stmt->execute([$dashboard->getTitulo(), $dashboard->getUrlIframe(), $dashboard->getId()]);

            // 2. Remove todos os acessos antigos para este dashboard
            $stmtDelete = $pdo->prepare("DELETE FROM acessos_dashboard WHERE id_dashboard = ?");
            $stmtDelete->execute([$dashboard->getId()]);

            // 3. Insere os novos acessos
            $stmtInsert = $pdo->prepare("INSERT INTO acessos_dashboard (id_dashboard, role) VALUES (?, ?)");
            foreach ($roles as $role) {
                $stmtInsert->execute([$dashboard->getId(), $role]);
            }

            $pdo->commit();
        } catch (\PDOException $e) {
            $pdo->rollBack();
            throw new \PDOException("Erro ao editar o dashboard: " . $e->getMessage());
        }
    }

    // NOVO: Exclui um dashboard
    public static function excluir(int $id): void
    {
        try {
            $pdo = Conn::getConn();
            // A restrição ON DELETE CASCADE no banco de dados cuidará de remover
            // as entradas correspondentes na tabela 'acessos_dashboard'
            $stmt = $pdo->prepare("DELETE FROM dashboards WHERE id = ?");
            $stmt->execute([$id]);

            if ($stmt->rowCount() === 0) {
                throw new \Exception("Nenhum dashboard foi excluído. O ID pode não ter sido encontrado.");
            }
        } catch (\PDOException $e) {
            throw new \PDOException("Erro ao excluir o dashboard: " . $e->getMessage());
        }
    }
}