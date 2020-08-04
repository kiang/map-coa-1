<?php
$fc = array(
    'type' => 'FeatureCollection',
    'features' => array(),
);
$addr = array();
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
        $addr[$f['attributes']['標準地籍址']] = true;
        $fc['features'][] = $gf;
    }
}
file_put_contents(__DIR__ . '/factories.json', json_encode($fc));

ksort($addr);
$addrList = array_keys($addr);
$fh = fopen(__DIR__ . '/factories.csv', 'w');
fputcsv($fh, array('住址'));
foreach($addrList AS $addr) {
    fputcsv($fh, array($addr));
}