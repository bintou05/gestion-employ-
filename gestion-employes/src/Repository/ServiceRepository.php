<?php
namespace App\Repository;

use App\Entity\Service;

class ServiceRepository
{
    /** @var Service[] */
    private static array $services = []; 
    
    // Initialisation des données de base au démarrage
    public function __construct()
    {
        if (empty(self::$services)) {
            // Initialisation des Services pour avoir des données à la première exécution
            $this->save(new Service('Développement Web', 1));
            $this->save(new Service('Ressources Humaines', 2));
            $this->save(new Service('Comptabilité', 3));
        }
    }

    /**
     * Persiste (sauvegarde) un objet Service dans notre collection statique.
     */
    public function save(Service $service): void
    {
        self::$services[$service->getId()] = $service;
    }

    /** * Récupère tous les services.
     * @return Service[] 
     */
    public function findAll(): array
    {
        return array_values(self::$services);
    }

    /**
     * Recherche un service par son ID.
     */
    public function findById(int $id): ?Service
    {
        return self::$services[$id] ?? null;
    }
}