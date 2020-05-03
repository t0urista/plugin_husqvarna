Elements statistiques d'utilisation entre 2 dates:
==================================================
* Nombre de cycle de tonte
* Nombre de cycle de charge
* Nombre d'heure de fonctionnement en coupe
* Nombre d'heure de recharge

* Durée en phase de recherche
* Durée en phase de départ


Options ultérieures:
* stats par zones



Planification de la tonte:
==========================
* Définir sur la page de configuration s'il faut gérer 2 zones, et définir les commandes pour activer chaque zone

* definir un planning d'utilisation sur une base hebdomadaire: (une ou 2 plages par jour)
* Gérer une répartition du temps sur 2 zones:
  * Soit une plage horaire est affectée à une zone, en totalité.
  * Soit un changement de zone est effectué à chaque cycle, avec un taux à respecter entre les 2 zones.
  * Ajouter des options (zone 1 uniquement / zone 2 uniquement)

* Ajouter une requête d'arret du robot. (retour à la base instantané, ou à la fin du cycle de tonte en cours, et arret de la planification)

* Ajouter une option pluie:(utilisation du plugin météo:pluie dans l'heure)
 => interrompre un cycle si de la pluie est annoncée (Si risque de pluie dans les prochaine 10 mn > 6)
 => Reprendre le cycle si la pluie s'est arrêtée (Pas de pluie dans l'heure: risque total = 12)
