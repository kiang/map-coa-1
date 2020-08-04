<?php
$fc = array(
    'type' => 'FeatureCollection',
    'features' => array(),
);
foreach(glob(__DIR__ . '/raw/*.json') AS $jsonFile) {
    $json = json_decode(file_get_contents($jsonFile), true);
    foreach($json['features'] AS $f) {
        $gf = array(
            'type' => 'Feature',
            'properties' => array(
                'addr' => $f['attributes']['標準地籍址'],
            ),
            'geometry' => array(
                'type' => 'Polygon',
                'coordinates' => $f['geometry']['rings'],
            ),
        );
        $fc['features'][] = $gf;
    }
}
file_put_contents(__DIR__ . '/factories.json', json_encode($fc));