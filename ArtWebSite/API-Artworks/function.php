<?php

require '../inc/dbconfig.php';

function getArtWorksList($type)
{

    global $connect;

    if ($type == 'all') :
        $query = "SELECT * FROM art";
    elseif ($type == 'liquidPaint') :
        $query = "SELECT * FROM art WHERE style = 'Paint Liquid' ";
    else :
        $query = "SELECT * FROM art WHERE `year` > 2007 and  `year` < 2015";
    endif;

    $query_run = mysqli_query($connect, $query);

    try {
        if ($query_run) :

            if (mysqli_num_rows($query_run) > 0) :

                $response = mysqli_fetch_all($query_run, MYSQLI_ASSOC);

                $data = [
                    'status' => 200,
                    'message' => 'Artworks List Fetched Successfully',
                    'data' => $response,
                ];


                header("HTTP/1.0 200 OK");
                return json_encode($data);

            else :

                $data = [
                    'status' => 404,
                    'message' => 'No Artworks Found',
                ];
                header("HTTP/1.0 404 Found No Data");
                return json_encode($data);

            endif;

        else :

            $data = [
                'status' => 500,
                'message' => 'Internal Server Error',
            ];
            header("HTTP/1.0 500 Internal Server Error");
            return json_encode($data);

        endif;
    } catch (Exception $e) {
        echo "An error occurred: " . $e->getMessage();
    }
}
