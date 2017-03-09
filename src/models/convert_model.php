<?php

class convert_model
{
    private $error = '';

    /**
     * csv to array
     *
     * @param filepath
     * @return array | null
     */
    public function read_csv($filepath)
    {
        try {
            $spl_object = new \SplFileObject($filepath);
            // ignore blank line
            $spl_object->setFlags(
                \SplFileObject::READ_CSV |
                \SplFileObject::READ_AHEAD |
                \SplFileObject::SKIP_EMPTY |
                \SplFileObject::DROP_NEW_LINE);

            $array_data = [];
            foreach ($spl_object as $row){
                if (phpversion() == '5.6.30'){
                    if($spl_object->key() == 0) $encode = mb_detect_encoding(implode(",", $row));
                    mb_convert_variables('UTF-8', $encode, $row);
                }else{
                    mb_convert_variables('UTF-8', ['UTF-8','CP932'], $row);
                }
                $array_data[] = $row;
            }

        } catch (Exception $e) {
            $this->error = $e->getMessage();
            return null;
        }

        return $array_data;
    }
    /**
     * output excel file
     *
     * @param array $input_data
     * @return string $filepath
     */
    public function output_excel(array $input_data)
    {
        $filepath = tempnam(sys_get_temp_dir(), 'xlsx');

        $excel = new \PHPExcel;
        $sheet = $excel->getActiveSheet();
        $sheet->getParent()->getDefaultStyle()->getFont()->setName("ＭＳ ゴシック");
        $sheet->fromArray($input_data, null, 'A1');

        $writer = \PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $writer->save($filepath);

        return $filepath;
    }


    /**
     * excel to array
     *
     * @param filepath
     * @return array | null
     */
    public function read_excel($filepath)
    {
        set_error_handler(function($severity, $message, $file, $line) {
            throw new ErrorException($message, 0, $severity, $file, $line);
        });

        try {
            $reader = PHPExcel_IOFactory::createReader('Excel2007');
            $excel = $reader->load($filepath);
            $excel->setActiveSheetIndex(0);
            $sheet = $excel->getActiveSheet();
            $array_data = $sheet->toArray( null, true, true, true );

        } catch (Exception $e) {
            $this->error = $e->getMessage();
            return null;
        }

        return $array_data;
    }
    /**
     * output csv file
     *
     * @param array $input_data
     * @return string $filepath
     */
    public function output_csv(array $input_data)
    {
        $filepath = tempnam(sys_get_temp_dir(), 'csv');

        $spl_object = new \SplFileObject($filepath, "w");
        foreach ($input_data as $row) {
            mb_convert_variables('CP932', 'UTF-8', $row);
            $spl_object->fputcsv($row);
        }

        return $filepath;
    }


    /**
     * get error message
     */
    public function get_error_message(){
        return $this->error;
    }
}
