<?php
// Inclut l'autoloader généré par Composer pour charger toutes les classes
require 'vendor/autoload.php';

use App\Repository\ServiceRepository;
use App\Repository\EmployeRepository;
use App\Service\GestionEmployeService;
use App\View\ConsoleView;

// --- 1. INITIALISATION DES COUCHES ---

// Repositories
$serviceRepo = new ServiceRepository();
// L'EmployeRepository dépend du ServiceRepository
$employeRepo = new EmployeRepository($serviceRepo); 

// Service Métier
$gestionService = new GestionEmployeService($serviceRepo, $employeRepo);

// Vue Console
$view = new ConsoleView($gestionService);

// --- 2. LANCEMENT DE L'APPLICATION ---
$view->run();