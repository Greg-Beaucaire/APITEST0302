<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>API TRO BI1</title>
</head>

<body>
    <main>
        <form action="" method="POST">
            <label for="search">Cherchez le nom d'une ville</label>
            <input type="text" id="search" name="search">
            <input type="submit" value="Rechercher">
        </form>
        <?php
        if (isset($_POST['search'])) {
            $search = $_POST['search'];
            //API METEO
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "api.openweathermap.org/data/2.5/find?q=$search&units=metric&appid=d196fbd705116ddcd1911b5c8606c6e0&lang=fr",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "cache-control: no-cache"
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);

            $response = json_decode($response, true);


            //API GEO GOUV
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://geo.api.gouv.fr/communes?nom=$search",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "cache-control: no-cache"
                ),
            ));

            $responseMontcuq = curl_exec($curl);

            curl_close($curl);

            $responseMontcuq = json_decode($responseMontcuq, true);


            //API WIKIPEDIA
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://fr.wikipedia.org/w/api.php?action=query&titles=$search&prop=extracts&exchars=350&explaintext&utf8&format=json",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "cache-control: no-cache"
                ),
            ));

            $responseWiki = curl_exec($curl);

            curl_close($curl);

            $responseWiki = json_decode($responseWiki, true);


            //On affiche le tout dans un echo des enfers
            echo ("
        <h1>" . $responseMontcuq[0]['nom'] . "</h1>
        <br><p>La ville de <b>" . $responseMontcuq[0]['nom'] . "</b> a pour code postal <b>" . $responseMontcuq[0]['code'] . "</b>. ");
        if($search == 'Montcuq') {
            echo("Il y a <b>" . $responseMontcuq[0]['population'] . "</b> habitants dans <b>Montcuq</b>. ");
        } else {
            echo("Il y a <b>" . $responseMontcuq[0]['population'] . "</b> habitants dans <b>" . $responseMontcuq[0]['nom'] . "</b>. ");
        }
        echo("Le temps y est actuellement <b>" . $response['list'][0]['weather'][0]['description'] . "</b> ");
        echo("et la température est de <b>" . intval($response['list'][0]['main']['temp']) . "°C</b>");
            foreach ($responseWiki['query']['pages'] as $key => $value) {
                echo ("<p><b>Extrait de la page wikipédia : </b>" . $responseWiki['query']['pages'][$key]['extract'] . "</p>");
            }


            if ($search == 'Montcuq') {
                echo ('<iframe width="560" height="315" src="https://www.youtube.com/embed/EUqCM3Kb-wU" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>');
            }
        }
        ?>
    </main>
</body>

</html>