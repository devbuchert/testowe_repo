<?php

//komentarz do skryptu

$postdata= $_POST['s'];
if (isset($postdata) && $postdata != "") {
    $BASE_URL      = "http://query.yahooapis.com/v1/public/yql";
    $yql_query     = 'select * from geo.places  where text="' . $_POST['s'] . '"';
    $yql_query_url = $BASE_URL . "?q=" . urlencode($yql_query) . "&format=json";
    $session       = curl_init($yql_query_url);
    curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
    $json   = curl_exec($session);
    $phpObj = json_decode($json);
    if (!is_null($phpObj->query->results)) {
        echo '<p class="resh">Results ('.$phpObj->query->count.') : '.$postdata.'</p>';
        
        echo '<table class="table table-condensed table-bordered">
			  <thead class="thead-inverse">
				<tr>
					<th>District-County</th>
					<th>Province-State</th>
					<th>Country</th>
					<th class="woeid">WOEID</th>
				</tr>
				</thead>
				<tbody>';
        $count_all = $phpObj->query->count;
        if ($phpObj->query->count > 1) {
            for ($i = 0; $i <= $count_all - 1; $i++) {
                echo '<tr>';
                if (isset($phpObj->query->results->place[$i]->admin2))
                    echo '<td>' . $phpObj->query->results->place[$i]->admin2->content . '</td>';
                else
                    echo '<td>NONE</td>';
                if (isset($phpObj->query->results->place[$i]->admin1))
                    echo '<td>' . $phpObj->query->results->place[$i]->admin1->content . '</td>';
                else
                    echo '<td>NONE</td>';
                echo '<td>' . $phpObj->query->results->place[$i]->country->content . '</td>';
                echo '<td>' . $phpObj->query->results->place[$i]->woeid . '</td>';
                echo '</tr>';
            }
        } else if ($phpObj->query->count == 1) {
            echo '<tr>';
            if (isset($phpObj->query->results->place->admin2))
                echo '<td>' . $phpObj->query->results->place->admin2->content . '</td>';
            else
                echo '<td>NONE</td>';
            if (isset($phpObj->query->results->place->admin1))
                echo '<td>' . $phpObj->query->results->place->admin1->content . '</td>';
            else
                echo '<td>NONE</td>';
            echo '<td>' . $phpObj->query->results->place->country->content . '</td>';
            echo '<td>' . $phpObj->query->results->place->woeid . '</td>';
            echo '</tr>';
            echo '</tbody>';
            echo '</table>';
        }
    } else {
        echo '<div class="alert alert-warning" role="alert">No Result for word : <b>' . $_POST["s"] . '</div>';
    }
} else {
    echo '<div id="errorBox"><b>Please enter city or county name. This field is blank or chars is not required</b></div>';
}
?>
