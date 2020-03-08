<?php


namespace App\Service;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\QueryBuilder;

class StatsService
{
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    public function getAllUsers(){
        return $this->manager->CreateQuery('SELECT u.firstName, u.lastName, u.email, u.phone, u.city, u.createdAt, u.id, ur.title
        FROM App\Entity\User u
        JOIN u.userRoles ur
        WHERE ur.title != \'ROLE_ADMIN\' AND ur.title != \'ROLE_OWNER\'
        ')->getResult();
    }

    public function getAdminUsers(){
        return $this->manager->CreateQuery('SELECT u.firstName, u.lastName, u.email, u.phone, u.city, u.createdAt, u.id, ur.title
        FROM App\Entity\User u
        JOIN u.userRoles ur
        WHERE ur.title != \'ROLE_USER\'
        ')->getResult();
    }

    public function getWaitList(){
        return $this->manager->createQuery('SELECT w.comment,w.createdAt,w.id,w.number,e.title,e.startDate,e.slug, u.firstName,u.lastName,u.city,u.phone,u.email,u.createdAt
        FROM App\Entity\WaitList w
        JOIN w.user u
        JOIN w.event e
        ORDER BY w.createdAt DESC
        ')->setMaxResults(100)->getResult();
    }

    public function getPrograms(){
        return $this->manager->createQuery('SELECT p.title, p.secondTitle,p.description, p.slug, p.image, p.id, p.limitedAge
        FROM App\Entity\Programs p
        ORDER BY p.title DESC
        ')->getResult();
    }

    // Permet d'afficher la liste des évenements créés
    public function getEvents() {
        return $this->manager->createQuery('SELECT e.title, e.startDate,e.seats, e.slug
        FROM App\Entity\Event e
        ORDER BY e.startDate DESC
        ')->getResult();
    }

    // Permet d'afficher la liste des réservations
    public function getBookings() {
        return $this->manager->createQuery('SELECT b.id, b.createdAt, b.comment,e.title, e.startDate, u.firstName, u.lastName, u.phone, u.email
        FROM App\Entity\Booking b
        JOIN b.event e
        JOIN b.booker u
        ORDER BY b.createdAt DESC
        ')->setMaxResults(100)->getResult();
    }

    public function getNewUser(){
        $newUserBooking = $this->getNewUserBooking();
        $oldUserBooking = $this->getOldUserBooking();

        return compact('newUserBooking', 'oldUserBooking');
    }

    // Gère les requetes pour les stats mensuelles du nombre d'enfants venus
    public function getMonthlyStats(){
        $januaryChildrenCount = $this->getJanuaryChildrens();
        $februaryChildrenCount = $this->getFebruaryChildrens();
        $marchChildrenCount = $this->getMarchChildrens();
        $aprilChildrenCount = $this->getAprilChildrens();
        $mayChildrenCount = $this->getMayChildrens();
        $juneChildrenCount = $this->getJuneChildrens();
        $julyChildrenCount = $this->getJulyChildrens();
        $augustChildrenCount = $this->getAugustChildrens();
        $septemberChildrenCount = $this->getSeptemberChildrens();
        $octoberChildrenCount = $this->getOctoberChildrens();
        $novemberChildrenCount = $this->getNovemberChildrens();
        $decemberChildrenCount =$this->getDecemberChildrens();

        return compact('januaryChildrenCount', 'februaryChildrenCount','marchChildrenCount', 'aprilChildrenCount', 'mayChildrenCount', 'juneChildrenCount',
            'julyChildrenCount', 'augustChildrenCount', 'septemberChildrenCount', 'octoberChildrenCount', 'novemberChildrenCount', 'decemberChildrenCount');
    }

    // Gère les requetes pour les stats mensuelles de la liste d'attente
    public function getMonthlyWaitList(){
        $januaryWaitList    = $this->getJanuaryWaitList();
        $februaryWaitList   = $this->getFebruaryWaitList();
        $marchWaitList   = $this->getMarchWaitList();
        $aprilWaitList   = $this->getAprilWaitList();
        $mayWaitList   = $this->getMayWaitList();
        $juneWaitList   = $this->getJuneWaitList();
        $julyWaitList   = $this->getJulyWaitList();
        $augustWaitList   = $this->getAugustWaitList();
        $septemberWaitList   = $this->getSeptemberWaitList();
        $octoberWaitList   = $this->getOctoberWaitList();
        $novemberWaitList   = $this->getNovemberWaitList();
        $decemberWaitList   = $this->getDecemberWaitList();

        return compact('januaryWaitList', 'februaryWaitList', 'marchWaitList', 'aprilWaitList', 'mayWaitList','juneWaitList', 'julyWaitList', 'augustWaitList', 'septemberWaitList', 'octoberWaitList', 'novemberWaitList', 'decemberWaitList');
    }

    public function getEventTypeStats(){
        $eventChildrenOnly = $this->getEventChildrenOnly();
        $eventChildrenAnd = $this->getEventChildrenAnd();

        return compact('eventChildrenOnly', 'eventChildrenAnd');
    }

    public function getGenderStats(){
        $countBoys  = $this->getBoysCount();
        $countGirls = $this->getGirlsCount();

        return compact('countBoys', 'countGirls');
    }

    public function getAgeStats() {
        $ageFirst   = $this->getFirstAge();
        $ageSecond  = $this->getSecondAge();
        $ageThird   = $this->getThirdAge();
        $ageFourth  = $this->getFourthAge();

        return compact('ageFirst', 'ageSecond', 'ageThird', 'ageFourth');
    }

    public function getCityStats() {
        $cityLille      = $this->getLilleChildrens();
        $cityLommes     = $this->getLommesChildrens();
        $cityHellemmes  = $this->getHellemmesChildrens();
        $cityOther      = $this->getOtherChildrens();

        return compact('cityLille', 'cityLommes', 'cityHellemmes', 'cityOther');
    }

    public function getActiveStats() {
        $activeEvents           = $this->getActiveEvents();
        $activeChildrensCount   = $this->getActiveChildrensCount();
        $activeChildrens        = $this->getActiveChildrens();
        $activeBookings         = $this->getActiveBookings();

        return compact('activeEvents', 'activeChildrensCount', 'activeChildrens', 'activeBookings');
    }

    public function getNowStats() {
        $nowEvents          = $this->getNowEvents();
        $nowChildrensCount  = $this->getNowChildrensCount();
        $nowChildrens       = $this->getNowChildrens();
        $nowBookings        = $this->getNowBookings();

        return compact('nowEvents', 'nowChildrensCount', 'nowChildrens', 'nowBookings');
    }

    public function getStats() {
        $users      = $this->getUserSCount();
        $events     = $this->getEventsCount();
        $programs   = $this->getProgramsCount();
        $bookings   = $this->getBookingsCount();
        $childrens  = $this->getAllChildrensCount();

        return compact('users', 'events', 'programs', 'bookings', 'childrens');
    }

    // Permet de compter le nombre d'utilisateurs
    public function getUserSCount() {
        return $this->manager->createQuery('SELECT COUNT(u) FROM App\Entity\User u')->getSingleScalarResult();
    }

    // Permet de compter le nombre d'événements créés
    public function getEventsCount() {
        return $this->manager->createQuery('SELECT COUNT(e) FROM App\Entity\Event e')->getSingleScalarResult();
    }

    // Permet de compter le nombre de programmes créés
    public function getProgramsCount() {
        return $this->manager->createQuery('SELECT COUNT(p) FROM App\Entity\Programs p')->getSingleScalarResult();
    }

    // Permet de compter le nombre de réservation effectuées
    public function getBookingsCount() {
        return $this->manager->createQuery('SELECT COUNT(b) FROM App\Entity\Booking b')->getSingleScalarResult();
    }

    public function getAllChildrensCount(){
        return $this->manager->createQuery('SELECT COUNT(c) FROM App\Entity\Children c')->getSingleScalarResult();
    }

    // Permet d'afficher les evenements actifs
    public function getActiveEvents() {
        return $this->manager->createQuery(
            'SELECT e.title, e.seats, e.category, e.startDate, e.ageMin, e.ageMax, e.location
            FROM App\Entity\Event e
            WHERE e.startDate <= CURRENT_DATE() AND e.endDate >= CURRENT_DATE()
            GROUP BY e
            ORDER BY e.startDate DESC'
        )->getResult();
    }

    // Permet de connaitre le nombre d'enfants inscrits sur des evenements actifs
    public function getActiveChildrensCount() {
        return $this->manager->createQuery(
            'SELECT COUNT(c)
            FROM App\Entity\Children c
            JOIN c.booking b
            JOIN b.event e
            WHERE e.startDate > CURRENT_DATE()
            '
        )->getSingleScalarResult();
    }

    // Permet de connaitre la liste des enfants inscrits sur des evenements actifs
    public function getActiveChildrens() {
        return $this->manager->createQuery(
            'SELECT c.sexe, c.firstName, c.lastName, c.age, e.title, e.startDate
            FROM App\Entity\Children c
            JOIN c.booking b
            JOIN b.event e
            WHERE e.startDate <= CURRENT_DATE() AND e.endDate >= CURRENT_DATE()
            '
        )->getResult();
    }

    // Permet d'afficher les réservations en cours
    public function getActiveBookings() {
        return $this->manager->createQuery(
            'SELECT b.comment, b.createdAt, e.title, e.startDate, b.id
            FROM App\Entity\Booking b
            JOIN b.event e
            WHERE e.startDate <= CURRENT_DATE() AND e.endDate >= CURRENT_DATE()
            '
        )->getResult();
    }

    // Affiche les evenements du jour
    public function getNowEvents() {
        return $this->manager->createQuery(
            'SELECT e.title, e.seats, e.category, e.startDate, e.ageMin, e.ageMax, e.location
            FROM App\Entity\Event e
            WHERE e.startDate = CURRENT_DATE()
            GROUP BY e
            ORDER BY e.startDate DESC'
        )->getResult();
    }

    // Affiche les réservations du jour
    public function getNowBookings() {
        return $this->manager->createQuery(
            'SELECT b.comment, b.createdAt, e.title 
            FROM App\Entity\Booking b
            JOIN b.event e
            WHERE e.startDate = CURRENT_DATE()
            '
        )->getResult();
    }

    // Permet de connaitre le nombre d'enfants inscrits ce jour
    public function getNowChildrensCount() {
        return $this->manager->createQuery(
            'SELECT COUNT(c)
            FROM App\Entity\Children c
            JOIN c.booking b
            JOIN b.event e
            WHERE e.startDate > CURRENT_DATE()
            '
        )->getSingleScalarResult();
    }

    // Permet de connaitre la liste des enfants inscrits ce jour
    public function getNowChildrens() {
        return $this->manager->createQuery(
            'SELECT c.sexe, c.firstName, c.lastName, c.age, e.title, e.startDate
            FROM App\Entity\Children c
            JOIN c.booking b
            JOIN b.event e
            WHERE e.startDate > CURRENT_DATE()
            '
        )->getResult();
    }



    // Permet de compter le nombre d'enfants venant de Lille
    public function getLilleChildrens(){
        return $this->manager->createQuery(
            'SELECT COUNT(c) as count, u.city
            FROM App\Entity\children c
            JOIN c.booking b
            JOIN b.booker u
            WHERE u.city = \'Lille\'
            '
        )->getResult();
    }

    // Permet de compter le nombre d'enfants venant de Lommes
    public function getLommesChildrens(){
        return $this->manager->createQuery(
            'SELECT COUNT(c) as count, u.city
            FROM App\Entity\children c
            JOIN c.booking b
            JOIN b.booker u
            WHERE u.city = \'Lommes\'
            '
        )->getScalarResult();
    }

    // Permet de compter le nombre d'enfants venant de Hellemmes
    public function getHellemmesChildrens(){
        return $this->manager->createQuery(
            'SELECT COUNT(c) as count, u.city
            FROM App\Entity\children c
            JOIN c.booking b
            JOIN b.booker u
            WHERE u.city = \'Hellemmes\'
            '
        )->getScalarResult();
    }

    // Permet de compter le nombre d'enfants venant des autres villes
    public function getOtherChildrens(){
        return $this->manager->createQuery(
            'SELECT COUNT(c) as count, u.city
            FROM App\Entity\children c
            JOIN c.booking b
            JOIN b.booker u
            WHERE u.city != \'Lille\' AND u.city != \'Lommes\' AND u.city != \'Hellemmes\'
            '
        )->getScalarResult();
    }

    // Permet de compter le nombre d'enfants agé de 4 à 6 ans
    public function getFirstAge(){
        return $this->manager->createQuery(
            'SELECT COUNT(c) as count
            FROM App\Entity\children c
            WHERE c.age >= 4 AND c.age <= 6
            '
        )->getResult();
    }

    // Permet de compter le nombre d'enfants agé de 7 à 9 ans
    public function getSecondAge(){
        return $this->manager->createQuery(
            'SELECT COUNT(c) as count
            FROM App\Entity\children c
            WHERE c.age >= 7 AND c.age <= 9
            '
        )->getResult();
    }

    // Permet de compter le nombre d'enfants agé de 10 à 12 ans
    public function getThirdAge(){
        return $this->manager->createQuery(
            'SELECT COUNT(c) as count
            FROM App\Entity\children c
            WHERE c.age >= 10 AND c.age <= 12
            '
        )->getResult();
    }

    // Permet de compter le nombre d'enfants agé de plus 12 ans
    public function getFourthAge(){
        return $this->manager->createQuery(
            'SELECT COUNT(c) as count
            FROM App\Entity\children c
            WHERE c.age >= 13
            '
        )->getResult();
    }

    // Permet de compter le nombre de garçons inscrits au total
    public function getBoysCount(){
        return $this->manager->createQuery(
            'SELECT COUNT(c) as count
            FROM App\Entity\children c
            WHERE c.sexe = \'garçon\'
            '
        )->getResult();
    }

    // Permet de compter le nombre de filles inscrites au total
    public function getGirlsCount(){
        return $this->manager->createQuery(
            'SELECT COUNT(c) as count
            FROM App\Entity\children c
            WHERE c.sexe = \'fille\'
            '
        )->getResult();
    }

    // Permet de compter le nombre d'evenement du planning pour enfants uniquement
    public function getEventChildrenOnly(){
        return $this->manager->createQuery(
            'SELECT COUNT(e) as count
            FROM App\Entity\Event e
            WHERE e.category = \'enfants\''
        )->getResult();
    }

    // Permet de compter le nombre d'evenement du planning pour enfants uniquement
    public function getEventChildrenAnd(){
        return $this->manager->createQuery(
            'SELECT COUNT(e) as count
            FROM App\Entity\Event e
            WHERE e.category = \'parents/enfants\''
        )->getResult();
    }

    // Permet de compter le nombre d'enfants venus à euratech kids donc inscris à un event du planning
    public function getJanuaryChildrens(){
        $dateStart = new \DateTime();
        $currentYear=$dateStart->format('Y');
        $dateStart->setDate($currentYear, 1,01);
        $dateStart = $dateStart->format('Y-m-d');

        $dateEnd = new \DateTime();
        $dateEnd->setDate($currentYear,1,31);
        $dateEnd = $dateEnd->format('Y-m-d');

        return $this->manager->createQuery(
            'SELECT COUNT(c)
            FROM App\Entity\Children c
            JOIN c.booking b
            JOIN b.event e
            WHERE e.startDate <=\'' . $dateEnd .'\' AND e.startDate >= \''. $dateStart.'\''
        )->getSingleScalarResult();
    }

    // Permet de compter le nombre de personne en liste d'attente au mois de Janvier
    public function getJanuaryWaitList(){
        $dateStart = new \DateTime();
        $currentYear=$dateStart->format('Y');
        $dateStart->setDate($currentYear, 1,01);
        $dateStart = $dateStart->format('Y-m-d');

        $dateEnd = new \DateTime();
        $dateEnd->setDate($currentYear,1,31);
        $dateEnd = $dateEnd->format('Y-m-d');

        return $this->manager->createQuery(
            'SELECT COUNT(w)
            FROM App\Entity\WaitList w
            JOIN w.event e
            WHERE e.startDate <=\'' . $dateEnd .'\' AND e.startDate >= \''. $dateStart.'\''
        )->getSingleScalarResult();
    }

    // Permet de compter le nombre d'enfants venus à euratech kids donc inscris à un event du planning
    public function getFebruaryChildrens(){
        $dateStart = new \DateTime();
        $currentYear=$dateStart->format('Y');
        $dateStart->setDate($currentYear, 2,01);
        $dateStart = $dateStart->format('Y-m-d');

        $dateEnd = new \DateTime();
        $dateEnd->setDate($currentYear,2,27);
        $dateEnd = $dateEnd->format('Y-m-d');

        return $this->manager->createQuery(
            'SELECT COUNT(c) as count
            FROM App\Entity\Children c
            JOIN c.booking b
            JOIN b.event e
            WHERE e.startDate <= \'' . $dateEnd .'\' AND e.startDate >= \''. $dateStart.'\''
        )->getSingleScalarResult();
    }

    // Permet de compter le nombre de personne en liste d'attente au mois de fevrier
    public function getFebruaryWaitList(){
        $dateStart = new \DateTime();
        $currentYear=$dateStart->format('Y');
        $dateStart->setDate($currentYear, 2,01);
        $dateStart = $dateStart->format('Y-m-d');

        $dateEnd = new \DateTime();
        $dateEnd->setDate($currentYear,2,27);
        $dateEnd = $dateEnd->format('Y-m-d');

        return $this->manager->createQuery(
            'SELECT COUNT(w)
            FROM App\Entity\WaitList w
            JOIN w.event e
            WHERE e.startDate <= \'' . $dateEnd .'\' AND e.startDate >= \''. $dateStart.'\''
        )->getSingleScalarResult();
    }

    // Permet de compter le nombre d'enfants venus à euratech kids donc inscris à un event du planning
    public function getMarchChildrens(){
        $dateStart = new \DateTime();
        $currentYear=$dateStart->format('Y');
        $dateStart->setDate($currentYear, 3,01);
        $dateStart = $dateStart->format('Y-m-d');

        $dateEnd = new \DateTime();
        $dateEnd->setDate($currentYear,3,31);
        $dateEnd = $dateEnd->format('Y-m-d');

        return $this->manager->createQuery(
            'SELECT COUNT(c)
            FROM App\Entity\Children c
            JOIN c.booking b
            JOIN b.event e
            WHERE e.startDate <= \'' . $dateEnd .'\' AND e.startDate >= \''. $dateStart.'\''
        )->getSingleScalarResult();
    }

    // Permet de compter le nombre de personne en liste d'attente au mois de mars
    public function getMarchWaitList(){
        $dateStart = new \DateTime();
        $currentYear=$dateStart->format('Y');
        $dateStart->setDate($currentYear, 3,01);
        $dateStart = $dateStart->format('Y-m-d');

        $dateEnd = new \DateTime();
        $dateEnd->setDate($currentYear,3,31);
        $dateEnd = $dateEnd->format('Y-m-d');

        return $this->manager->createQuery(
            'SELECT COUNT(w)
            FROM App\Entity\WaitList w
            JOIN w.event e
            WHERE e.startDate <= \'' . $dateEnd .'\' AND e.startDate >= \''. $dateStart.'\''
        )->getSingleScalarResult();
    }

    // Permet de compter le nombre d'enfants venus à euratech kids donc inscris à un event du planning
    public function getAprilChildrens(){
        $dateStart = new \DateTime();
        $currentYear=$dateStart->format('Y');
        $currentYear=$dateStart->format('Y');
        $dateStart->setDate($currentYear, 4,01);
        $dateStart = $dateStart->format('Y-m-d');

        $dateEnd = new \DateTime();
        $dateEnd->setDate($currentYear,4,30);
        $dateEnd = $dateEnd->format('Y-m-d');

        return $this->manager->createQuery(
            'SELECT COUNT(c)
            FROM App\Entity\Children c
            JOIN c.booking b
            JOIN b.event e
            WHERE e.startDate <= \'' . $dateEnd .'\' AND e.startDate >= \''. $dateStart.'\''
        )->getSingleScalarResult();
    }

    // Permet de compter le nombre de personne en liste d'attente au mois d'avril
    public function getAprilWaitList(){
        $dateStart = new \DateTime();
        $currentYear=$dateStart->format('Y');
        $dateStart->setDate($currentYear, 4,01);
        $dateStart = $dateStart->format('Y-m-d');

        $dateEnd = new \DateTime();
        $dateEnd->setDate($currentYear,4,30);
        $dateEnd = $dateEnd->format('Y-m-d');

        return $this->manager->createQuery(
            'SELECT COUNT(w)
            FROM App\Entity\WaitList w
            JOIN w.event e
            WHERE e.startDate <= \'' . $dateEnd .'\' AND e.startDate >= \''. $dateStart.'\''
        )->getSingleScalarResult();
    }

    // Permet de compter le nombre d'enfants venus à euratech kids donc inscris à un event du planning
    public function getMayChildrens(){
        $dateStart = new \DateTime();
        $currentYear=$dateStart->format('Y');
        $dateStart->setDate($currentYear, 5,01);
        $dateStart = $dateStart->format('Y-m-d');

        $dateEnd = new \DateTime();
        $dateEnd->setDate($currentYear,5,31);
        $dateEnd = $dateEnd->format('Y-m-d');

        return $this->manager->createQuery(
            'SELECT COUNT(c) as count
            FROM App\Entity\Children c
            JOIN c.booking b
            JOIN b.event e
            WHERE e.startDate <=\'' . $dateEnd .'\' AND e.startDate >= \''. $dateStart.'\''
        )->getSingleScalarResult();
    }

    // Permet de compter le nombre de personne en liste d'attente au mois de mai
    public function getMayWaitList(){
        $dateStart = new \DateTime();
        $currentYear=$dateStart->format('Y');
        $dateStart->setDate($currentYear, 5,01);
        $dateStart = $dateStart->format('Y-m-d');

        $dateEnd = new \DateTime();
        $dateEnd->setDate($currentYear,5,31);
        $dateEnd = $dateEnd->format('Y-m-d');

        return $this->manager->createQuery(
            'SELECT COUNT(w)
            FROM App\Entity\WaitList w
            JOIN w.event e
            WHERE e.startDate <=\'' . $dateEnd .'\' AND e.startDate >= \''. $dateStart.'\''
        )->getSingleScalarResult();
    }

    // Permet de compter le nombre d'enfants venus à euratech kids donc inscris à un event du planning
    public function getJuneChildrens(){
        $dateStart = new \DateTime();
        $currentYear=$dateStart->format('Y');
        $dateStart->setDate($currentYear, 6,01);
        $dateStart = $dateStart->format('Y-m-d');

        $dateEnd = new \DateTime();
        $dateEnd->setDate($currentYear,6,30);
        $dateEnd = $dateEnd->format('Y-m-d');

        return $this->manager->createQuery(
            'SELECT COUNT(c)
            FROM App\Entity\Children c
            JOIN c.booking b
            JOIN b.event e
            WHERE e.startDate <= \'' . $dateEnd .'\' AND e.startDate >= \''. $dateStart.'\''
        )->getSingleScalarResult();
    }

    // Permet de compter le nombre de personne en liste d'attente au mois de juin
    public function getJuneWaitList(){
        $dateStart = new \DateTime();
        $currentYear=$dateStart->format('Y');
        $dateStart->setDate($currentYear, 6,01);
        $dateStart = $dateStart->format('Y-m-d');

        $dateEnd = new \DateTime();
        $dateEnd->setDate($currentYear,6,30);
        $dateEnd = $dateEnd->format('Y-m-d');

        return $this->manager->createQuery(
            'SELECT COUNT(w)
            FROM App\Entity\WaitList w
            JOIN w.event e
            WHERE e.startDate <=\'' . $dateEnd .'\' AND e.startDate >= \''. $dateStart.'\''
        )->getSingleScalarResult();
    }

    // Permet de compter le nombre d'enfants venus à euratech kids donc inscris à un event du planning
    public function getJulyChildrens(){
        $dateStart = new \DateTime();
        $currentYear=$dateStart->format('Y');
        $dateStart->setDate($currentYear, 7,01);
        $dateStart = $dateStart->format('Y-m-d');

        $dateEnd = new \DateTime();
        $dateEnd->setDate($currentYear,7,31);
        $dateEnd = $dateEnd->format('Y-m-d');

        return $this->manager->createQuery(
            'SELECT COUNT(c)
            FROM App\Entity\Children c
            JOIN c.booking b
            JOIN b.event e
            WHERE e.startDate <=\'' . $dateEnd .'\' AND e.startDate >= \''. $dateStart.'\''
        )->getSingleScalarResult();
    }

    // Permet de compter le nombre de personne en liste d'attente au mois de juillet
    public function getJulyWaitList(){
        $dateStart = new \DateTime();
        $currentYear=$dateStart->format('Y');
        $dateStart->setDate($currentYear, 7,01);
        $dateStart = $dateStart->format('Y-m-d');

        $dateEnd = new \DateTime();
        $dateEnd->setDate($currentYear,7,31);
        $dateEnd = $dateEnd->format('Y-m-d');

        return $this->manager->createQuery(
            'SELECT COUNT(w)
            FROM App\Entity\WaitList w
            JOIN w.event e
            WHERE e.startDate <=\'' . $dateEnd .'\' AND e.startDate >= \''. $dateStart.'\''
        )->getSingleScalarResult();
    }

    // Permet de compter le nombre d'enfants venus à euratech kids donc inscris à un event du planning
    public function getAugustChildrens(){
        $dateStart = new \DateTime();
        $currentYear=$dateStart->format('Y');
        $dateStart->setDate($currentYear, 8,01);
        $dateStart = $dateStart->format('Y-m-d');

        $dateEnd = new \DateTime();
        $dateEnd->setDate($currentYear,8,31);
        $dateEnd = $dateEnd->format('Y-m-d');

        return $this->manager->createQuery(
            'SELECT COUNT(c)
            FROM App\Entity\Children c
            JOIN c.booking b
            JOIN b.event e
            WHERE e.startDate <=\'' . $dateEnd .'\' AND e.startDate >= \''. $dateStart.'\''
        )->getSingleScalarResult();
    }

    // Permet de compter le nombre de personne en liste d'attente au mois d'aout
    public function getAugustWaitList(){
        $dateStart = new \DateTime();
        $currentYear=$dateStart->format('Y');
        $dateStart->setDate($currentYear, 8,01);
        $dateStart = $dateStart->format('Y-m-d');

        $dateEnd = new \DateTime();
        $dateEnd->setDate($currentYear,8,31);
        $dateEnd = $dateEnd->format('Y-m-d');

        return $this->manager->createQuery(
            'SELECT COUNT(w)
            FROM App\Entity\WaitList w
            JOIN w.event e
            WHERE e.startDate <=\'' . $dateEnd .'\' AND e.startDate >= \''. $dateStart.'\''
        )->getSingleScalarResult();
    }

    // Permet de compter le nombre d'enfants venus à euratech kids donc inscris à un event du planning
    public function getSeptemberChildrens(){
        $dateStart = new \DateTime();
        $currentYear=$dateStart->format('Y');
        $dateStart->setDate($currentYear, 9,01);
        $dateStart = $dateStart->format('Y-m-d');

        $dateEnd = new \DateTime();
        $dateEnd->setDate($currentYear,9,30);
        $dateEnd = $dateEnd->format('Y-m-d');

        return $this->manager->createQuery(
            'SELECT COUNT(c)
            FROM App\Entity\Children c
            JOIN c.booking b
            JOIN b.event e
            WHERE e.startDate <=\'' . $dateEnd .'\' AND e.startDate >= \''. $dateStart.'\''
        )->getSingleScalarResult();
    }

    // Permet de compter le nombre de personne en liste d'attente au mois de septembre
    public function getSeptemberWaitList(){
        $dateStart = new \DateTime();
        $currentYear=$dateStart->format('Y');
        $dateStart->setDate($currentYear, 9,01);
        $dateStart = $dateStart->format('Y-m-d');

        $dateEnd = new \DateTime();
        $dateEnd->setDate($currentYear,9,30);
        $dateEnd = $dateEnd->format('Y-m-d');

        return $this->manager->createQuery(
            'SELECT COUNT(w)
            FROM App\Entity\WaitList w
            JOIN w.event e
            WHERE e.startDate <=\'' . $dateEnd .'\' AND e.startDate >= \''. $dateStart.'\''
        )->getSingleScalarResult();
    }

    // Permet de compter le nombre d'enfants venus à euratech kids donc inscris à un event du planning
    public function getOctoberChildrens(){
        $dateStart = new \DateTime();
        $currentYear=$dateStart->format('Y');
        $dateStart->setDate($currentYear, 10,01);
        $dateStart = $dateStart->format('Y-m-d');

        $dateEnd = new \DateTime();
        $dateEnd->setDate($currentYear,10,31);
        $dateEnd = $dateEnd->format('Y-m-d');

        return $this->manager->createQuery(
            'SELECT COUNT(c)
            FROM App\Entity\Children c
            JOIN c.booking b
            JOIN b.event e
            WHERE e.startDate <=\'' . $dateEnd .'\' AND e.startDate >= \''. $dateStart.'\''
        )->getSingleScalarResult();
    }

    // Permet de compter le nombre de personne en liste d'attente au mois d'octobre'
    public function getOctoberWaitList(){
        $dateStart = new \DateTime();
        $currentYear=$dateStart->format('Y');
        $dateStart->setDate($currentYear, 10,01);
        $dateStart = $dateStart->format('Y-m-d');

        $dateEnd = new \DateTime();
        $dateEnd->setDate($currentYear,10,31);
        $dateEnd = $dateEnd->format('Y-m-d');

        return $this->manager->createQuery(
            'SELECT COUNT(w)
            FROM App\Entity\WaitList w
            JOIN w.event e
            WHERE e.startDate <=\'' . $dateEnd .'\' AND e.startDate >= \''. $dateStart.'\''
        )->getSingleScalarResult();
    }

    // Permet de compter le nombre d'enfants venus à euratech kids donc inscris à un event du planning
    public function getNovemberChildrens(){
        $dateStart = new \DateTime();
        $currentYear=$dateStart->format('Y');
        $dateStart->setDate($currentYear, 11,01);
        $dateStart = $dateStart->format('Y-m-d');

        $dateEnd = new \DateTime();
        $dateEnd->setDate($currentYear,11,30);
        $dateEnd = $dateEnd->format('Y-m-d');

        return $this->manager->createQuery(
            'SELECT COUNT(c)
            FROM App\Entity\Children c
            JOIN c.booking b
            JOIN b.event e
            WHERE e.startDate <=\'' . $dateEnd .'\' AND e.startDate >= \''. $dateStart.'\''
        )->getSingleScalarResult();
    }

    // Permet de compter le nombre de personne en liste d'attente au mois de novembre
    public function getNovemberWaitList(){
        $dateStart = new \DateTime();
        $currentYear=$dateStart->format('Y');
        $dateStart->setDate($currentYear, 11,01);
        $dateStart = $dateStart->format('Y-m-d');

        $dateEnd = new \DateTime();
        $dateEnd->setDate($currentYear,11,30);
        $dateEnd = $dateEnd->format('Y-m-d');

        return $this->manager->createQuery(
            'SELECT COUNT(w)
            FROM App\Entity\WaitList w
            JOIN w.event e
            WHERE e.startDate <=\'' . $dateEnd .'\' AND e.startDate >= \''. $dateStart.'\''
        )->getSingleScalarResult();
    }

    // Permet de compter le nombre d'enfants venus à euratech kids donc inscris à un event du planning
    public function getDecemberChildrens(){
        $dateStart = new \DateTime();
        $currentYear=$dateStart->format('Y');
        $dateStart->setDate($currentYear, 12,01);
        $dateStart = $dateStart->format('Y-m-d');

        $dateEnd = new \DateTime();
        $dateEnd->setDate($currentYear,12,31);
        $dateEnd = $dateEnd->format('Y-m-d');

        return $this->manager->createQuery(
            'SELECT COUNT(c)
            FROM App\Entity\Children c
            JOIN c.booking b
            JOIN b.event e
            WHERE e.startDate <=\'' . $dateEnd .'\' AND e.startDate >= \''. $dateStart.'\''
        )->getSingleScalarResult();
    }

    // Permet de compter le nombre de personne en liste d'attente au mois de décembre
    public function getDecemberWaitList(){
        $dateStart = new \DateTime();
        $currentYear=$dateStart->format('Y');
        $dateStart->setDate($currentYear, 12,01);
        $dateStart = $dateStart->format('Y-m-d');

        $dateEnd = new \DateTime();
        $dateEnd->setDate($currentYear,12,31);
        $dateEnd = $dateEnd->format('Y-m-d');

        return $this->manager->createQuery(
            'SELECT COUNT(w)
            FROM App\Entity\WaitList w
            JOIN w.event e
            WHERE e.startDate <=\'' . $dateEnd .'\' AND e.startDate >= \''. $dateStart.'\''
        )->getSingleScalarResult();
    }

    // Permet de compter le nombre de gens inscrit à une session lors de leur premiere inscriptions
    public function getNewUserBooking(){
        return $this->manager->createQuery(
            'SELECT COUNT(b) as count
            FROM App\Entity\Booking b
            JOIN b.booker u
            WHERE DATE_FORMAT(b.createdAt, \'%d/%m/%Y\') = DATE_FORMAT(u.createdAt, \'%d/%m/%Y\')'
        )->getResult();

    }

    // Permet de compter le nombre de gens inscrit à une session lors de leur premiere inscriptions
    public function getOldUserBooking(){
        return $this->manager->createQuery(
            'SELECT COUNT(b) as count
            FROM App\Entity\Booking b
            JOIN b.booker u
            WHERE DATE_FORMAT(b.createdAt, \'%d/%m/%Y\') != DATE_FORMAT(u.createdAt, \'%d/%m/%Y\')'
        )->getResult();

    }
}