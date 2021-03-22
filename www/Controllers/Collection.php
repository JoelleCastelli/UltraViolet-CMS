<?php

namespace App\Controller;

use App\Core\View;

class
Collection {
    
    public function displayCollectionAction() {

		$item_url = 'https://api.themoviedb.org/3/movie/76341?api_key='.APIKEY.'&append_to_response=credits&language=fr';

		// Curl request
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $item_url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Résultat de curl_exec() = string au lieu de l'afficher
		curl_setopt($ch, CURLOPT_FAILONERROR, 1); // Echoue verbalement si code HTTP >= 400
		$results = curl_exec($ch);
		curl_close($ch);

		$item = json_decode(($results));
		$originalTitle = $item->original_title;
		$title = $item->title;
		$overview = $item->overview;
		$posterPath = $item->poster_path;
		$releaseDate = $item->release_date;
		$runtime = $item->runtime;
		$genres = $item->genres;

		$actor1 = $item->credits->cast[0];
		$actor2 = $item->credits->cast[1];
		$actor3 = $item->credits->cast[2];

		echo "Titre : ".$title."<br>";
		echo "Titre original : ".$originalTitle."<br>";
		echo "Genres : ".$genres[0]->name.", ".$genres[1]->name.", ".$genres[2]->name."<br>";
		echo "Résumé : ".$overview."<br>";
		echo "Date de sortie : ".date("d-m-Y", strtotime($releaseDate))."<br>";
		echo "Durée : ".$runtime." minutes (".($runtime/60)." heures)<br>";
		echo "<img src='https://image.tmdb.org/t/p/w200".$posterPath."'/><br>";

		echo "
		<table>
			<thead>
				<tr>
					<th>".$actor1->name."</th>
					<th>".$actor2->name."</th>
					<th>".$actor3->name."</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><img src='https://image.tmdb.org/t/p/w200".$actor1->profile_path."' />.</td>
					<td><img src='https://image.tmdb.org/t/p/w200".$actor2->profile_path."' />.</td>
					<td><img src='https://image.tmdb.org/t/p/w200".$actor3->profile_path."' />.</td>
				</tr>
			</tbody>
		</table>";
		die;
		
        $view = new View("displayCollection", "back");
    }

}