<?php
namespace App\Repository;

use App\Entity\Employe;
use App\Entity\Service;

class EmployeRepository
{
    /** @var Employe[] */
    private static array $employes = []; 
    
    // Dépendance pour pouvoir créer les employés de test avec des objets Service réels
    public function __construct(ServiceRepository $serviceRepo)
    {
        if (empty(self::$employes)) {
            $serviceDev = $serviceRepo->findById(1); // Développement Web
            $serviceRH = $serviceRepo->findById(2);  // Ressources Humaines
            
            if ($serviceDev && $serviceRH) {
                // Initialisation des Employés pour avoir des données à la première exécution
                $this->save(new Employe('Alice Dupont', '0611223344', 4500.0, 'FullStack', $serviceDev, 1));
                $this->save(new Employe('Bob Martin', '0755667788', 3800.0, 'Front-End', $serviceDev, 2));
                $this->save(new Employe('Clara Roux', '0710101010', 3500.0, 'RH', $serviceRH, 3));
            }
        }
    }

    /**
     * Persiste (sauvegarde) un objet Employe dans notre collection statique.
     */
    public function save(Employe $employe): void
    {
        self::$employes[$employe->getId()] = $employe;
    }

    /** * Récupère tous les employés.
     * @return Employe[] 
     */
    public function findAll(): array
    {
        return array_values(self::$employes);
    }

    /**
     * Filtre les employés par leur service.
     * @return Employe[]
     */
    public function findByService(Service $service): array
    {
        $employesDuService = [];
        $serviceId = $service->getId();

        foreach (self::$employes as $employe) {
            if ($employe->getService()->getId() === $serviceId) {
                $employesDuService[] = $employe;
            }
        }

        return $employesDuService;
    }
}