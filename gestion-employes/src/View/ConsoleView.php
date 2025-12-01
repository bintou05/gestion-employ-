<?php
namespace App\View;

// Import de la classe du service métier
use App\Service\GestionEmployeService;

class ConsoleView
{
    private GestionEmployeService $service;
    
    // CORRECTION : Définition de la constante en MAJUSCULE et privée
    private const SPECIALITES_VALIDEES = ['FullStack', 'Front-End', 'Back-End', 'RH', 'Comptable']; 

    public function __construct(GestionEmployeService $service)
    {
        $this->service = $service;
    }

    /**
     * Point d'entrée principal de l'application.
     */
    public function run(): void
    {
        $choice = null;
        do {
            $this->displayMenu();
            // Fonction pour lire l'entrée de l'utilisateur
            $choice = trim(fgets(STDIN)); 

            switch ($choice) {
                case '1': $this->handleEnregistrerService(); break;
                case '2': $this->handleListerServices(); break;
                case '3': $this->handleEnregistrerEmploye(); break;
                case '4': $this->handleListerEmployesParService(); break;
                case '5': echo "👋 Application terminée. Au revoir !\n"; break;
                default: echo "❌ Choix invalide ($choice). Veuillez réessayer.\n";
            }
        } while ($choice != '5');
    }

    /**
     * Affiche le menu principal.
     */
    private function displayMenu(): void
    {
        echo "\n========== GESTION DES EMPLOYÉS ==========\n";
        echo "1. Enregistrer un service\n";
        echo "2. Lister tous les services\n";
        echo "3. Enregistrer un employé dans un service\n";
        echo "4. Lister les employés d'un service\n";
        echo "5. Quitter\n";
        echo "------------------------------------------\n";
        echo "Votre choix : ";
    }
    
    /**
     * Gère l'option pour enregistrer un nouveau service.
     */
    private function handleEnregistrerService(): void
    {
        echo "\n-- ENREGISTRER SERVICE --\n";
        echo "Nom du service : ";
        $nom = trim(fgets(STDIN));
        
        if (empty($nom)) {
            echo "🛑 Le nom du service ne peut pas être vide.\n";
            return;
        }

        $service = $this->service->createService($nom);
        echo "✅ Service '{$service->getNom()}' enregistré avec succès (ID: {$service->getId()}).\n";
    }

    /**
     * Gère l'option pour lister tous les services.
     */
    private function handleListerServices(): void
    {
        echo "\n-- LISTE DES SERVICES --\n";
        $services = $this->service->getAllServices();
        
        if (empty($services)) {
            echo "Aucun service enregistré.\n";
            return;
        }
        
        foreach ($services as $s) {
            echo "{$s}\n"; 
        }
    }
    
    /**
     * Gère l'option pour enregistrer un nouvel employé.
     */
    private function handleEnregistrerEmploye(): void
    {
        echo "\n-- ENREGISTRER EMPLOYÉ --\n";
        
        // Afficher les services pour faciliter le choix
        $this->handleListerServices();
        $services = $this->service->getAllServices();
        if (empty($services)) {
             echo "🛑 Vous devez enregistrer au moins un service avant de créer un employé.\n";
             return;
        }
        
        echo "Nom : "; $nom = trim(fgets(STDIN));
        echo "Téléphone : "; $tel = trim(fgets(STDIN));
        
        // Validation du salaire
        $salaire = null;
        while (!is_numeric($salaire) || $salaire <= 0) {
            echo "Salaire : ";
            $salaire = (float)trim(fgets(STDIN));
            if (!is_numeric($salaire) || $salaire <= 0) {
                echo "🛑 Salaire invalide. Entrez un nombre positif.\n";
            }
        }

        // Validation de la spécialité
        $specialite = null;
        echo "Spécialités disponibles : " . implode(', ', self::SPECIALITES_VALIDEES) . "\n";
        while (!in_array($specialite, self::SPECIALITES_VALIDEES)) {
            echo "Spécialité : ";
            $specialite = trim(fgets(STDIN));
            if (!in_array($specialite, self::SPECIALITES_VALIDEES)) {
                echo "🛑 Spécialité invalide. Choisissez parmi les options ci-dessus.\n";
            }
        }
        
        // Validation de l'ID du service
        $serviceId = null;
        $serviceSelectionne = null;
        while (!is_numeric($serviceId) || $serviceSelectionne === null) {
             echo "ID du service à affecter : ";
             $input = trim(fgets(STDIN));
             $serviceId = (int)$input;
             $serviceSelectionne = $this->service->findServiceById($serviceId);
             
             if (!$serviceSelectionne) {
                echo "🛑 ID de service invalide ou inexistant. Veuillez choisir un ID dans la liste ci-dessus.\n";
             }
        }

        $employe = $this->service->createEmploye($nom, $tel, $salaire, $specialite, $serviceId);

        if ($employe) {
            echo "✅ Employé '{$employe->getNom()}' enregistré avec succès dans le service {$employe->getService()->getNom()}.\n";
        } else {
            echo "🛑 Erreur critique : L'employé n'a pas pu être créé.\n";
        }
    }

    /**
     * Gère l'option pour lister les employés d'un service donné.
     */
    private function handleListerEmployesParService(): void
    {
        echo "\n-- LISTE DES EMPLOYÉS PAR SERVICE --\n";
        
        // Afficher les services pour aider l'utilisateur
        $this->handleListerServices();
        
        // Validation de l'ID du service
        $serviceId = null;
        $serviceSelectionne = null;
        while (!is_numeric($serviceId) || $serviceSelectionne === null) {
             echo "ID du service dont vous voulez lister les employés : ";
             $input = trim(fgets(STDIN));
             $serviceId = (int)$input;
             $serviceSelectionne = $this->service->findServiceById($serviceId);
             
             if (!$serviceSelectionne) {
                echo "🛑 ID de service invalide ou inexistant. Veuillez choisir un ID dans la liste ci-dessus.\n";
             }
        }

        $employes = $this->service->getEmployesByService($serviceId);

        if (empty($employes)) {
            echo "Aucun employé trouvé pour le service '{$serviceSelectionne->getNom()}'.\n";
            return;
        }
        
        echo "--- Employés du Service : {$serviceSelectionne->getNom()} ---\n";
        foreach ($employes as $e) {
            echo "{$e}\n";
        }
    }
}