<?php
namespace App\Service;

use App\Entity\Employe;
use App\Entity\Service;
use App\Repository\EmployeRepository;
use App\Repository\ServiceRepository;

class GestionEmployeService
{
    private ServiceRepository $serviceRepo;
    private EmployeRepository $employeRepo;

    // Injection des dépendances des Repositories via le constructeur
    public function __construct(ServiceRepository $sr, EmployeRepository $er)
    {
        $this->serviceRepo = $sr;
        $this->employeRepo = $er;
    }

    // --- Fonctionnalités de Gestion des Services ---

    public function createService(string $nom): Service
    {
        $service = new Service($nom); 
        $this->serviceRepo->save($service);
        return $service;
    }

    /** @return Service[] */
    public function getAllServices(): array
    {
        return $this->serviceRepo->findAll();
    }
    
    public function findServiceById(int $id): ?Service
    {
        return $this->serviceRepo->findById($id);
    }

    // --- Fonctionnalités de Gestion des Employés ---

    /**
     * Crée un nouvel employé et le sauvegarde.
     * Retourne l'employé créé ou null si le service n'est pas trouvé.
     */
    public function createEmploye(string $nom, string $tel, float $salaire, string $specialite, int $serviceId): ?Employe
    {
        // Règle de Gestion : Vérifier si le service existe
        $service = $this->serviceRepo->findById($serviceId);
        if (!$service) {
            return null; 
        }

        // Création et enregistrement de l'employé
        $employe = new Employe($nom, $tel, $salaire, $specialite, $service);
        $this->employeRepo->save($employe);
        return $employe;
    }

    /** * Récupère les employés affectés à un service spécifique.
     * @return Employe[] 
     */
    public function getEmployesByService(int $serviceId): array
    {
        $service = $this->serviceRepo->findById($serviceId);
        
        if (!$service) {
            return []; // Service non trouvé
        }
        
        return $this->employeRepo->findByService($service);
    }
}