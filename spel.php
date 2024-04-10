<?php
$win =  "   .-'''''-.\n" .
        "  ('      _`-.\n" .
        "  |'--...__\\_`\\\n" .
        "  |         `'-'\n" .
        "  '-,___,--.  \n" .
        "   |         `'.\n" .
        "   |            \\\n" .
        "   |             \\\n" .
        "    \\            |\n" .
        "     \\           |\n" .
        "      '.___.. -'\n" .
        "Schat gevonden! 🏴‍☠️\n";

// Introductiescherm voor het spel
echo "Welkom bij het Avonturenspel op een Verlaten Eiland!\n\n";
echo "            .---._\n" .
     "        .--(. '  .).--.\n" .
     "     . ( ' _) .)` (   )`)\n" .
     "    ( ,  ).        `(' .)\n" .
     "  (')  _________      /    \n" .
     "      |   _      `\"\"\"`|\n" .
     "      |_/__)_________|\n\n";

// Startpunt
echo "Je bevindt je op een verlaten eiland, omringd door eindeloze oceaan.\n";
echo "Optie 1: Verken het eiland.\n";
echo "Optie 2: Rust even uit op het strand.\n";
echo "Maak je keuze (1 of 2):\n";

// Verzamel keuze van de speler
$userChoice = readline("");

// Controleer de keuze van de speler
if ($userChoice == 1) {
    // Optie 1: Verken het eiland
    echo "\nJe begint het eiland te verkennen en vindt een oude schatkist begraven in het zand.\n";
    echo "Optie 1: Graaf de schatkist op.\n";
    echo "Optie 2: Ga verder met het verkennen van het eiland.\n";
    echo "Maak je keuze (1 of 2):\n";
    $userChoice = readline("");
    
    // Controleer de keuze van de speler
    if ($userChoice == 1) {
        // Optie 1: Graaf de schatkist op
        echo "\nJe graaft de schatkist op en opent het deksel. Binnenin vind je de legendarische schat!\n";
        echo "Gefeliciteerd, je hebt gewonnen!\n";
        echo "$win\n";
    } else {
        // Optie 2: Ga verder met het verkennen van het eiland
        echo "\nJe besluit om verder te gaan met het verkennen van het eiland.\n";
        echo "Na een tijdje kom je een vervallen piratenfort tegen.\n";
        echo "Optie 1: Verken het piratenfort.\n";
        echo "Optie 2: Ga terug naar het strand.\n";
        echo "Maak je keuze (1 of 2):\n";
        $userChoice = readline("");
        
        // Controleer de keuze van de speler
        if ($userChoice == 1) {
            // Optie 1: Verken het piratenfort
            echo "\nJe verkent het piratenfort en vindt een oud kaartfragment.\n";
            echo "Het kaartfragment wijst naar de locatie van de schat!\n";
            echo "Je begint je zoektocht naar de schat op basis van de aanwijzingen op het kaartfragment.\n";
            echo "Na een avontuurlijke reis vind je eindelijk de schat!\n";
            echo "Gefeliciteerd, je hebt gewonnen!\n";
            echo "$win\n";
        } else {
            // Optie 2: Ga terug naar het strand
            echo "\nJe besluit om terug te keren naar het strand en uit te rusten.\n";
            echo "Terwijl je ontspant op het strand, geniet je van het geluid van de golven.\n";
            echo "Game Over!\n";
        }
    }
} else {
    // Optie 2: Rust even uit op het strand
    echo "\nJe besluit om even uit te rusten op het strand, genietend van de rustgevende omgeving.\n";
    echo "Na een tijdje sta je op en besluit je om verder te gaan met je avontuur.\n";
    echo "Je kijkt uit over de oceaan, vastbesloten om de legendarische schat te vinden.\n";
    echo "Game Over!\n";
}
?>
