<?php
namespace App\Entity;

class Employe
{
    private int $id;
    private string $nom;
    private string $tel;
    private float $salaire;
    private string $specialite; // FullStack, Front-End, Back-End
    private Service $service;    // Relation vers l'objet Service
    
    private static int $nextId = 1;

    public function __construct(string $nom, string $tel, float $salaire, string $specialite, Service $service, ?int $id = null)
    {
        $this->id = $id ?? self::$nextId++;
        $this->nom = $nom;
        $this->tel = $tel;
        $this->salaire = $salaire;
        $this->specialite = $specialite;
        $this->service = $service;

        if ($this->id >= self::$nextId) {
            self::$nextId = $this->id + 1;
        }
    }

    // --- Getters ---
    public function getId(): int { return $this->id; }
    public function getNom(): string { return $this->nom; }
    public function getService(): Service { return $this->service; }

    // Méthode magique pour l'affichage facile en console
    public function __toString(): string
    {
        return "Employé [ID: {$this->id}] - {$this->nom} ({$this->specialite}), Sal: {$this->salaire}, Service: {$this->service->getNom()} (ID: {$this->service->getId()})";
    }
}