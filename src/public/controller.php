<?php

define('SRC_PATH', dirname(__FILE__) . '/../');

$controller = new controller();
$data = (isset($_GET['method'])) ? $_GET['method'] : 'index';

if (method_exists($controller,$data)){
    call_user_func([$controller, $data], '');
}else{
    header("HTTP/1.1 404 Not Found");
    echo 'Not Found';
}


/**
 * @property convert_model $convert_model
 */
class controller
{
    private $convert_model;

    public function __construct()
    {
        require_once(SRC_PATH . 'vendor/autoload.php');
        require_once(SRC_PATH . 'models/convert_model.php');
        $this->convert_model = new convert_model();
    }

    /**
     * top page
     *
     * @param string $message
     */
    public function index($message='')
    {
        $data['message'] = $message;
        $this->view('index.php', $data);
    }

    /**
     * excel to csv
     */
    public function convert_excel_to_csv()
    {
        // check select file
        if (empty(($_FILES['xlsxfile']['tmp_name'])) || !is_uploaded_file($_FILES['xlsxfile']['tmp_name'])) {
            $this->index('not select file');
            return;
        }
        $uploaded_file = $_FILES['xlsxfile']['tmp_name'];
        $input_filename = basename($_FILES['xlsxfile']['name']);
        $download_filename = $this->get_replace_extension($input_filename, 'csv');

        // xlsx to array
        $array_data = $this->convert_model->read_excel($uploaded_file);
        if (is_null($array_data)){
            $this->index($this->convert_model->get_error_message());
            return;
        }
        // create csv file
        $filepath = $this->convert_model->output_csv($array_data);

        header('Cache-Control: public');
        header('Pragma: public');
        header('Content-Type: text/csv');
        header('Content-Length: '.filesize($filepath));
        header('Content-Disposition: attachment; filename="' .$download_filename . '"');
        readfile($filepath);
    }

    /**
     * csv to excel
     */
    public function convert_csv_to_excel()
    {
        // check select file
        if (empty(($_FILES['csvfile']['tmp_name'])) || !is_uploaded_file($_FILES['csvfile']['tmp_name'])) {
            $this->index('not select file');
            return;
        }
        $uploaded_file = $_FILES['csvfile']['tmp_name'];
        $input_filename = basename($_FILES['csvfile']['name']);
        $download_filename = $this->get_replace_extension($input_filename, 'xlsx');

        // csv to array
        $array_data = $this->convert_model->read_csv($uploaded_file);
        if (is_null($array_data)){
            $this->index($this->convert_model->get_error_message());
            return;
        }
        // create excel file
        $filepath = $this->convert_model->output_excel($array_data);

        header('Cache-Control: public');
        header('Pragma: public');
        header('Content-Type: application/xlsx');
        header('Content-Length: '.filesize($filepath));
        header('Content-Disposition: attachment; filename="' .$download_filename . '"');
        readfile($filepath);
    }

    private function get_replace_extension($filename, $new_extension)
    {
        $pathinfo = pathinfo($filename);
        return rtrim($filename, $pathinfo['extension']) . $new_extension;
    }

    private function view($viewfile, $params = [])
    {
        $filepath = SRC_PATH . 'views/' . $viewfile;

        if (is_file($filepath)) {
            extract($params);
            include ($filepath);
            return;
        }
        throw new \LogicException('view file not found');
    }
}
