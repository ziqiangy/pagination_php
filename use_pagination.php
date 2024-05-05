include('../Pagination.php');

$timeClock = new Pagination($query_time_clock, $pageAt, $limitPerPage);



$pageAt = 1;
$limitPerPage = 10;


$timeClock = new Pagination($query_time_clock, $pageAt, $limitPerPage);


//var_dump($timeClock->getPageOptions());
//var_dump($timeClock->getSql());

$pdo_time_clock = new Db;

$data_time_clock = $pdo_time_clock->pdo()->query($timeClock->getSql())->fetchAll(PDO::FETCH_ASSOC);

$pagination_time_clock = $timeClock->getPageOptions();


$data_matrx = array();

if ($data_time_clock) {
    array_push($data_matrx, ["data_time_clock" => $data_time_clock]);
}


if($pagination_time_clock){
    array_push($data_matrx, ["pagination_time_clock" => $pagination_time_clock]);
}


if ($data_matrx) {
    echo json_encode($data_matrx);
} else {
    http_response_code(404);
    echo json_encode(
        array("message" => "No items found.")
    );
}