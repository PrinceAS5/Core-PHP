<?php
session_start();
require 'vendor/autoload.php';
$connect = new mysqli('localhost', 'root', '', 'artexample');

use PhpOffice\PhpSpreadsheet\IOFactory;


if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}

try {
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
            $data = $spreadsheet->getActiveSheet()->toArray();
            $exist = false;

            for ($row = 1; $row <= $highestRow; ++$row) {
                $rowData = $worksheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE)[0];
                if (isRowEmpty($rowData)) {
                    $exist = true;
                    break;
                }
            }
            if (!$exist) :
                foreach ($data as $row) {

                    $name = $row[0];
                    $image = $row[1];
                    $artist = $row[2];
                    $year = $row[3];
                    $size = $row[4];
                    $style = $row[5];
                    $cStyle = $row[6];

                    $query = "INSERT INTO art (`name`, `image`, artist, `year`, size, style, cStyle) VALUES ('$name', '$image', '$artist', '$year', '$size', '$style', '$cStyle')";
                    $result = mysqli_query($connect, $query);
                    $msg = true;
                }
            else :
                $_SESSION['message'] = 'Import failed, Found empty cell';
                header('Location: index.php');
                exit(0);
            endif;

            if (isset($msg)) :
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
} catch (Exception $e) {
    echo "An error occurred: " . $e->getMessage();
}

function isRowEmpty($rowData)
{
    foreach ($rowData as $cell) {
        if (empty($cell)) {
            return true;
        }
    }
    return false;
}

function debug_to_console($data)
{
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}
