<?php
session_start();
require 'vendor/autoload.php';
$connect = new mysqli('localhost', 'root', '', 'artexample');

use PhpOffice\PhpSpreadsheet\IOFactory;

function isRowEmpty($rowData)
{
    foreach ($rowData as $cell) {
        if (empty($cell)) {
            return true;
        }
    }
    return false;
}

if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}

if (isset($_POST['saveExcelData'])) :

    if (!$_POST['artists']) :
        $_SESSION['validate'] = 'Select item';
        header('Location: index.php');
        exit(0);
    endif;

    $fileName = $_FILES['importFile']['name'];
    $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);

    $allowed_ext = ['xls', 'csv', 'xlsx'];

    if (in_array($fileExt, $allowed_ext)) :

        $inputFileNamePath = $_FILES['importFile']['tmp_name'];
        $spreadsheet = IOFactory::load($inputFileNamePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $highestRow = $worksheet->getHighestRow();
        $highestColumn = $worksheet->getHighestColumn();
        $exist = false;
        for ($row = 1; $row <= $highestRow; ++$row) {
            // Get the row data as an array
            $rowData = $worksheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE)[0];

            // Skip the row if all cells are empty
            // var_dump($rowData);die;
            if (!isRowEmpty($rowData)) {
                $exist = true;
                var_dump($exist,$rowData);die;
                break;
            }
        }
        // var_dump($exist);die;

        // $exist = false;
        // foreach ($data as $row) {
        //     print_r($row);
        //     die;
        //     if (in_array(null, $row, true)) {
        //         $exist = true;
        //         break;
        //     }

        // $name = $row[0];
        // $image = $row[1];
        // $artist = $row[2];
        // $year = $row[3];
        // $size = $row[4];
        // $style = $row[5];
        // $cStyle = $row[6];

        // $query = "INSERT INTO art (`name`, `image`, artist, `year`, size, style, cStyle) VALUES ('$name', '$image', '$artist', '$year', '$size', '$style', '$cStyle')";
        // $result = mysqli_query($connect, $query);
        // $msg = true;
        //}
        // var_dump($exist);
        // die;

        if (isset($msg)) :
            if (isset($exist)) :
                $_SESSION['message'] =  'Successfully Imported and avoided rows that contain empty columns.';
                header('Location: index.php');
                exit(0);
            endif;
            $_SESSION['message'] = 'Successfully Imported';
            header('Location: index.php');
            exit(0);
        else :
            $_SESSION['message'] = "Import Failed";
            header('Location: index.php');
            exit(0);
        endif;

    else :

        $_SESSION['message'] = "Invalid File Format";
        header('Location: index.php');
        exit(0);
    endif;

endif;
