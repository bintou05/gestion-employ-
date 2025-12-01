<?php
namespace App\Entity;

class Service
{
    private int $id;
    private string $nom;
    private static int $nextId = 1;

    public function __construct(string $nom, ?int $id = null)
    {
        // Si l'ID est fourni (ex: lors de la récupération), on l'utilise.
        // Sinon, on simule l'auto-incrément si c'est un nouvel objet.
        $this->id = $id ?? self::$nextId++;
        $this->nom = $nom;
        
        // S'assurer que le compteur nextId est à jour
        if ($this->id >= self::$nextId) {
            self::$nextId = $this->id + 1;
        }
    }

    public function getId(): int { return $this->id; }
    public function getNom(): string { return $this->nom; }

    public function __toString(): string
    {
        return "Service [ID: {$this->id}] - {$this->nom}";
    }
}