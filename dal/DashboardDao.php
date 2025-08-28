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
}