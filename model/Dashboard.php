<?php
namespace App\Model;

class Dashboard
{
    private ?int $id;
    private string $titulo;
    private string $urlIframe;

    private function __construct(
        ?int $id,
        string $titulo,
        string $urlIframe
    ) {
        $this->id = $id;
        $this->titulo = $titulo;
        $this->urlIframe = $urlIframe;
    }

    public static function criarDashboard(
        ?int $id,
        string $titulo,
        string $urlIframe
    ): static {
        if (empty($titulo) || empty($urlIframe)) {
            throw new \Exception("Título e URL do dashboard são obrigatórios.");
        }
        return new static($id, $titulo, $urlIframe);
    }

    public function getId(): ?int { return $this->id; }
    public function getTitulo(): string { return $this->titulo; }
    public function getUrlIframe(): string { return $this->urlIframe; }

    public function setTitulo(string $titulo): void { $this->titulo = $titulo; }
    public function setUrlIframe(string $urlIframe): void { $this->urlIframe = $urlIframe; }
}