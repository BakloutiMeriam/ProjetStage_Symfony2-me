<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Required;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Entity\Employe;
use App\Entity\Empreinte;
use App\Entity\Emplois;
use App\Entity\Jour;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

use App\Repository\JourRepository;
//request : C'est un objet représentant la requête HTTP entrante.
// PersistenceManagerRegistry :récupérer des instances d'EntityManager ou de ObjectManager pour interagir avec la base de données à travers Doctrine
/*#[Route("/api",name:'api')]*/
class ApiemployeController extends AbstractController
{
    #[Route('/getemploye', name: 'app_apiemploye',methods:['get'])]
    public function index(Request $request, PersistenceManagerRegistry $doctrine): Response
    {
        
 
        $employe = $doctrine->getRepository(Employe::class)->findAll();
   
 
        return $this->json($employe);
    }
    #[Route ("/add",name:"employe_add", methods:['post'])]
    public function add(Request $request, PersistenceManagerRegistry $doctrine): Response
    {
        $data=json_decode($request->getContent(), true);
        $entityManager=$doctrine->getManager();
        $employe = new Employe();
        $employe->setNom($data['nom']);
        $employe->setPrenom($data['prenom']);
        $employe->setMail($data['mail']);
        $employe->setTel($data['tel']);
        $entityManager->persist($employe);
        $entityManager->flush();

        return $this->json( $employe);
    }

    #[Route('/getemployeid/{id}', name: 'employe_id' , methods:['get'])]
    public function show(int $id, PersistenceManagerRegistry $doctrine): Response
    {
        $employe = $doctrine->getRepository(Employe::class)->find($id);

        if (!$employe) {
            return $this->json('No employe found for id' . $id, 404);
        }

        $data = [
            'id' => $employe->getId(),
            'nom' => $employe->getNom(),
            'prenom' => $employe->getPrenom(),
            'mail' => $employe->getMail(),
            'tel' => $employe->getTel(),
    
        ];

        return $this->json($data);
    }
    #[Route('/deleteid/{id}', name: 'employe_delete', methods:['delete'] )]
    public function deleteid(int $id,PersistenceManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $employe = $entityManager->getRepository(Employe::class)->find($id);

        if (!$employe) {
            return $this->json('No employe found for id ' . $id, 404);
        }

        $entityManager->remove($employe);
        $entityManager->flush();

        return $this->json('Deleted a employe successfully with id ' . $id);
    }
    
    
     #[Route("/edit/{id}", name:"employe_edit", methods:['PUT'])]
    
    public function edit(Request $request, int $id,PersistenceManagerRegistry $doctrine): Response
    {
        $data = json_decode($request->getContent(), true);
        $entityManager=$doctrine->getManager();
        $employe = $entityManager->getRepository(Employe::class)->find($id);
 
        if (!$employe) {
            return $this->json('No project found for id' . $id, 404);
        }
 
        $employe->setNom($data['nom']);
        $employe->setPrenom($data['prenom']);
        $employe->setMail($data['mail']);
        $employe->setTel($data['tel']);
        $entityManager->flush();
 
        $data =  [
            'id' => $employe->getId(),
            'nom' => $employe->getNom(),
            'prenom' => $employe->getPrenom(),
            'mail' => $employe->getMail(),
            'tel' => $employe->getTel(),
        ];
         
        return $this->json($data);
    }
    
    #[Route("/total", name:"total_employes", methods:['get'])]
     
    public function getTotalEmployes(PersistenceManagerRegistry $doctrine): JsonResponse
    {
        $entityManager=$doctrine->getManager();
        $totalEmployes = $entityManager->getRepository(Employe::class)->getTotalEmployes();

        return $this->json($totalEmployes);
    }
    

    
    #[Route("/nbrheuretot/{idEmploye}/{dateDebut}/{dateFin}", name:"total_stats", methods:['GET'])]
    public function getTotalheure($idEmploye, $dateDebut, $dateFin,PersistenceManagerRegistry $doctrine)
    {
        $entityManager = $doctrine->getManager();

    // Convertissez les chaînes de date en objets DateTime
    $dateDebutObj = new \DateTime($dateDebut);
    $dateFinObj = new \DateTime($dateFin);

    
// Afficher les valeurs des dates pour vérifier si elles sont correctes
//var_dump($dateDebutObj);
//var_dump($dateFinObj);

    // Récupérez les empreintes pour l'employé et la période donnée
    //$empreintes = $entityManager->getRepository(Empreinte::class)
        //->findBy([
            //'employe' => $idEmploye,
            //'date' => $dateDebutObj
        //]);
        // Récupérez les empreintes pour l'employé et la période donnée
    $empreintes = $entityManager->getRepository(Empreinte::class)
    ->createQueryBuilder('e')
    ->andWhere('e.employe = :idEmploye')
    ->andWhere('e.date BETWEEN :dateDebut AND :dateFin')
    ->setParameter('idEmploye', $idEmploye)
    ->setParameter('dateDebut', $dateDebutObj->format('Y-m-d'))
    ->setParameter('dateFin', $dateFinObj->format('Y-m-d'))
    ->getQuery()
    ->getResult();

    // Calculez le total des heures
    $totalHours = 0;
    $totalMinutes = 0;

    foreach ($empreintes as $empreinte) {
        $heureEntree = strtotime($empreinte->getTentree()->format('H:i:s'));
        $heureSortie = strtotime($empreinte->getTsortie()->format('H:i:s'));
        
        $diff = $heureSortie - $heureEntree;
        if ($diff >= 0) {
        $totalHours += floor($diff / 3600); // Ajoutez le nombre d'heures entières
        $totalMinutes += ($diff % 3600) / 60;
        } // Ajoutez le nombre de minutes restantes
    }

    
    // Ajoutez les minutes supplémentaires aux heures si nécessaire
    $totalHours += floor($totalMinutes / 60);
    $totalMinutes = $totalMinutes % 60;

    // Formattez le résultat
    $totalTime = sprintf("%02d:%02d", $totalHours, $totalMinutes);

    // Retournez le résultat en JSON
    return new JsonResponse([
        'totalTime' => $totalTime,
    ]);
}

#[Route("/absences/{idEmploye}/{dateDebut}/{dateFin}", name: "api_absences")]
public function getAbsences($idEmploye, $dateDebut, $dateFin,PersistenceManagerRegistry $doctrine)
{
    // Obtenez le repository de l'entité "Emplois" et "Empreinte"
    $emploisRepository = $doctrine->getRepository(Emplois::class);
    $empreinteRepository = $doctrine->getRepository(Empreinte::class);

    // Convertir les dates de début et de fin en objets DateTime
    $dateDebutObj = new \DateTime($dateDebut);
    $dateFinObj = new \DateTime($dateFin);
// Récupérer les empreintes pour l'employé dans la période donnée
$empreintes = $empreinteRepository->createQueryBuilder('e')
->where('e.employe = :idEmploye')
->andWhere('e.date >= :dateDebut')
->andWhere('e.date <= :dateFin')
->setParameter('idEmploye', $idEmploye)
->setParameter('dateDebut', $dateDebutObj)
->setParameter('dateFin', $dateFinObj)
->getQuery()
->getResult();

    // Récupérer les heures de travail de l'employé dans la période donnée
    $emplois = $emploisRepository->createQueryBuilder('emp')
        ->where('emp.employe = :idEmploye')
        ->andWhere('emp.tdebut >= :dateDebut')
        ->andWhere('emp.tfin <= :dateFin')
        ->setParameter('idEmploye', $idEmploye)
        ->setParameter('dateDebut', $dateDebutObj)
        ->setParameter('dateFin', $dateFinObj)
        ->getQuery()
        ->getResult();


    // Initialiser le compteur d'absences
    $absenceCount = 0;

  // Boucle sur chaque emploi
foreach ($emplois as $emploi) {
    // Récupérer les jours travaillés pour cet emploi
    $joursTravailles = $jourRepository->createQueryBuilder('j')
        ->where('j.date >= :dateDebut')
        ->andWhere('j.date <= :dateFin')
        ->setParameter('dateDebut', $emploi->getTdebut()->format('Y-m-d'))
        ->setParameter('dateFin', $emploi->getTfin()->format('Y-m-d'))
        ->getQuery()
        ->getResult();

    // Vérifier les jours où aucune empreinte n'est enregistrée
    foreach ($joursTravailles as $jourTravaille) {
        $empreinte = $empreinteRepository->findOneBy([
            'date' => $jourTravaille->getDate(),
            'employe' => $idEmploye,
        ]);

        // Si aucune empreinte n'est enregistrée pour ce jour, incrémentez le compteur d'absences
        if (!$empreinte) {
            $absenceCount++;
        }
    }
}
    // Retournez le nombre d'absences pour l'employé dans la période donnée
    return new JsonResponse([
        'absenceCount' => $absenceCount,
    ]);
}

// Méthode pour vérifier si l'empreinte est dans la plage horaire de l'emploi


private function isEmpreinteInRange($emploi, $empreinte)
{
    $heureEntreeEmploi = $emploi->getTDebut()->getTimestamp();
    $heureSortieEmploi = $emploi->getTFin()->getTimestamp();

    $heureEntreeEmpreinte = $empreinte->getTentree()->getTimestamp();
    $heureSortieEmpreinte = $empreinte->getTsortie()->getTimestamp();

    // Vérifiez si l'empreinte est dans la plage horaire de l'emploi
    return ($heureEntreeEmpreinte >= $heureEntreeEmploi && $heureSortieEmpreinte <= $heureSortieEmploi);
}

// Méthode pour obtenir les dates entre deux timestamps
private function getDatesBetween($timestampDebut, $timestampFin)
{
    $dates = array();
    $currentDate = $timestampDebut;

    while ($currentDate <= $timestampFin) {
        $dates[] = date('Y-m-d', $currentDate);
        $currentDate = strtotime('+1 day', $currentDate);
    }

    return $dates;
}

    #[Route("/heures-supplementaires/{idEmploye}/{dateDebut}/{dateFin}", name:"api_heures_supplementaires", methods:['GET'])]
    
    public function getHeuresSupplementaires($idEmploye, $dateDebut, $dateFin,PersistenceManagerRegistry $doctrine): JsonResponse
    {
        $entityManager = $doctrine->getManager();

        // Récupérez les données nécessaires depuis la base de données
        $emplois = $entityManager->getRepository(Emplois::class)->findBy([
            'employe' => $idEmploye,
            'tdebut' => new \DateTime($dateDebut),
            'tfin' => new \DateTime($dateFin),
        ]);

        $joursTravailles = $entityManager->getRepository(Jour::class)->findByDateRange(new \DateTime($dateDebut), new \DateTime($dateFin));

        $empreintes = $entityManager->getRepository(Empreinte::class)->findBy([
            'employe' => $idEmploye,
            'date' => new \DateTime($dateDebut),
            // Ajoutez d'autres conditions si nécessaire
        ]);

        // Calculez les heures supplémentaires
        $heuresSupplementaires = 0;

        foreach ($emplois as $emploi) {
            // Calcul des heures travaillées pour cet emploi
            $heuresTravaillees = $emploi->getTfin()->diff($emploi->getTdebut())->h;

            foreach ($joursTravailles as $jour) {
                if ($jour->getDate() >= $emploi->getJour()->getDate()) {
                    foreach ($empreintes as $empreinte) {
                        if ($empreinte->getDate() == $jour->getDate()) {
                            // Calculez les heures supplémentaires pour ce jour
                            $heuresSupplementaires += $empreinte->getTsortie()->diff($empreinte->getTentree())->h - $heuresTravaillees;
                        }
                    }
                }
            }
        }

        // Retournez le résultat sous forme de réponse JSON
        return new JsonResponse([
            'idEmploye' => $idEmploye,
            'dateDebut' => $dateDebut,
            'dateFin' => $dateFin,
            'heuresSupplementaires' => $heuresSupplementaires,
        ]);
    }
 
    #[Route("/import-excel", name:"import_excel", methods:['POST'])]
     
public function importExcel(Request $request,PersistenceManagerRegistry $doctrine): Response
{
    // c. Vérifiez si un fichier a été envoyé
    $uploadedFile = $request->files->get('file');

    if (!$uploadedFile) {
        return new Response('No file uploaded', Response::HTTP_BAD_REQUEST);
    }

    // d. Récupérez le chemin du fichier temporaire
    $tempFilePath = $uploadedFile->getRealPath();

    // e. Créez un objet Reader pour le fichier Excel
    $reader = new Xlsx();
    $spreadsheet = $reader->load($tempFilePath);
    $sheet = $spreadsheet->getActiveSheet();


    $entityManager = $doctrine->getManager();
    // f. Parcourez les lignes du fichier Excel et traitez-les
    foreach ($sheet->getRowIterator() as $row) {
        // g. Récupérez les données de chaque colonne dans la ligne
        $data = [];
        foreach ($row->getCellIterator() as $cell) {
            $data[] = $cell->getValue();
        }

        // h. Créez un objet Empreinte avec les données de la ligne
        $empreinte = new Empreinte();
        $empreinte->setId($data[0]); // Par exemple, la première colonne contient le code employé
        $empreinte->setDate($data[1]); // Par exemple, la deuxième colonne contient la date
        $empreinte->setTEntree($data[2]); // Par exemple, la troisième colonne contient l'heure d'entrée
        $empreinte->setTSortie($data[3]);
        $empreinte->setEmploye($data[4]); // Par exemple, la quatrième colonne contient l'heure de sortie

        // i. Enregistrez l'objet Empreinte dans la base de données
        
        $entityManager->persist($empreinte);
    }

    // j. Flush pour enregistrer les modifications dans la base de données
    $entityManager->flush();

    // k. Retournez une réponse indiquant que l'importation a réussi
    return new Response('Import successful', Response::HTTP_OK);
}

     #[Route("/register", name:"api_register_admin", methods:['POST'])]
     
    public function register(Request $request,PersistenceManagerRegistry $doctrine): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $admin = new Admin();
        $admin->setEmail($data['email']);
        $admin->setPassword($data['password']);

        $entityManager = $doctrine->getManager();
        $entityManager->persist($admin);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Admin registered successfully'], JsonResponse::HTTP_CREATED);
    }


}




