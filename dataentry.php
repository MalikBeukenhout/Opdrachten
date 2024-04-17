<?php

// Functie om gegevens van gebruiker te vragen
function vraagGegevens() {
    $gegevens = [];

    // Vragen om invoer
    $gegevens['naam'] = readline("Naam");
    $gegevens['datum'] = readline("datum (YYYY-MM-DD) ");
    $gegevens['gewerkte_uren'] = readline("Uren");
    $gegevens['project'] = readline("project");

    return $gegevens;
}

// Functie om gegevens naar CSV-bestand te schrijven
function schrijfNaarCSV($gegevens) {
    $bestandsnaam = 'urenregistratie.csv';

    // Als het bestand nog niet bestaat, voeg header toe
    if (!file_exists($bestandsnaam)) {
        $header = ['Naam', 'Datum', 'Gewerkte Uren', 'Project'];
        file_put_contents($bestandsnaam, implode(',', $header) . PHP_EOL);
    }

    // Voeg gegevens toe aan CSV-bestand
    $regel = implode(',', $gegevens);
    file_put_contents($bestandsnaam, $regel . PHP_EOL, FILE_APPEND);
}

// Hoofdprogramma
function main() {
    // Vraag gegevens aan gebruiker
    $gegevens = vraagGegevens();

    // Sla gegevens op in CSV-bestand
    schrijfNaarCSV($gegevens);

    echo "Gegevens succesvol opgeslagen in urenregistratie.csv\n";
}

// Start programma
main();

?>
