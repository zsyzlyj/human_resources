<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Wage extends Admin_Controller 
{
	public function __construct()
	{
		parent::__construct();

		$this->not_logged_in_super();

		$this->data['page_title'] = 'Wage';

        $this->load->model('model_wage');
        $this->load->model('model_super_user');
	}

    /* 
    * It only redirects to the manage product page
    */
    function importExecl($file='', $sheet=0){  

        $file = iconv("utf-8", "gb2312", $file);   //转码  
    
        if(empty($file) OR !file_exists($file)) {  
    
            die('file not exists!');  
    
        }  
    
        include('PHPExcel.php');  //引入PHP EXCEL类  
    
        $objRead = new PHPExcel_Reader_Excel2007();   //建立reader对象  
    
        if(!$objRead->canRead($file)){  
    
            $objRead = new PHPExcel_Reader_Excel5();  
    
            if(!$objRead->canRead($file)){  
    
                die('No Excel!');  
    
            }  
    
        }  
    
       
    
        $cellName = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ');  
    
       
    
        $obj = $objRead->load($file);  //建立excel对象  
    
        $currSheet = $obj->getSheet($sheet);   //获取指定的sheet表  
    
        $columnH = $currSheet->getHighestColumn();   //取得最大的列号  
    
        $columnCnt = array_search($columnH, $cellName);  
    
        $rowCnt = $currSheet->getHighestRow();   //获取总行数  
    
       
    
        $data = array();  
    
        for($_row=1; $_row<=$rowCnt; $_row++){  //读取内容  
    
            for($_column=0; $_column<=$columnCnt; $_column++){  
    
                $cellId = $cellName[$_column].$_row;  
    
                $cellValue = $currSheet->getCell($cellId)->getValue();  
    
                 //$cellValue = $currSheet->getCell($cellId)->getCalculatedValue();  #获取公式计算的值  
    
                if($cellValue instanceof PHPExcel_RichText){   //富文本转换字符串  
    
                    $cellValue = $cellValue->__toString();  
    
                }
                $data[$_row][$cellName[$_column]] = $cellValue;  
            }  
        }
        return $data;  
    
    }
	public function index()
	{

        $this->data['user_permission'] = $this->session->userdata('user_permission');
        $this->data['user_name'] = $this->session->userdata('user_name');
        
		$this->render_template('wage/index', $this->data);
    }
    /*
    * It Fetches the products data from the product table 
    * this function is called from the datatable ajax function
    */
	public function fetchWageData()
	{
		$result = array('data' => array());

        $data = $this->model_holiday->getWageData();
        echo $data;
        
		foreach ($data as $key => $value) {
			$result['data'][$key] = array(
				$value['name'],
				$value['indate'],
                $value['companyage'],
                $value['sumage'],
                $value['sumday'],
                $value['lastyear'],
                $value['thisyear'],
                $value['bonus'],
                $value['used'],
                $value['rest'],
            );
            console($result['data']['name']);
		} // /foreach

        echo json_encode($result);
       /* */
	}	

    /*
    * If the validation is not valid, then it redirects to the create page.
    * If the validation for each input field is valid then it inserts the data into the database 
    * and it stores the operation message into the session flashdata and display on the manage product page
    */
	public function create()
	{
		$this->form_validation->set_rules('product_name', 'Product name', 'trim|required');
		$this->form_validation->set_rules('sku', 'SKU', 'trim|required');
		$this->form_validation->set_rules('price', 'Price', 'trim|required');
		$this->form_validation->set_rules('qty', 'Qty', 'trim|required');
        #$this->form_validation->set_rules('store', 'Store', 'trim|required');
		#$this->form_validation->set_rules('availability', 'Availability', 'trim|required');
		
	
        if ($this->form_validation->run() == TRUE) {
            // true case
        	$upload_image = $this->upload_image();

        	$data = array(
        		'name' => $this->input->post('product_name'),
        		'sku' => $this->input->post('sku'),
        		'price' => $this->input->post('price'),
        		'qty' => $this->input->post('qty'),
        		'image' => $upload_image,
        		'description' => $this->input->post('description'),
        		#'attribute_value_id' => json_encode($this->input->post('attributes_value_id')),
        		#'brand_id' => json_encode($this->input->post('brands')),
        		#'category_id' => json_encode($this->input->post('category')),
                #'store_id' => $this->input->post('store'),
        		#'availability' => $this->input->post('availability'),
        	);

        	$create = $this->model_products->create($data);
        	if($create == true) {
        		$this->session->set_flashdata('success', 'Successfully created');
        		redirect('products/', 'refresh');
        	}
        	else {
        		$this->session->set_flashdata('errors', 'Error occurred!!');
        		redirect('products/create', 'refresh');
        	}
        }
        else {
            // false case

        	// attributes 
        	$attribute_data = $this->model_attributes->getActiveAttributeData();

        	$attributes_final_data = array();
        	foreach ($attribute_data as $k => $v) {
        		$attributes_final_data[$k]['attribute_data'] = $v;

        		$value = $this->model_attributes->getAttributeValueData($v['id']);

        		$attributes_final_data[$k]['attribute_value'] = $value;
        	}

        	$this->data['attributes'] = $attributes_final_data;
			$this->data['brands'] = $this->model_brands->getActiveBrands();        	
			$this->data['category'] = $this->model_category->getActiveCategroy();        	
			#$this->data['stores'] = $this->model_stores->getActiveStore();        	

            $this->render_template('products/create', $this->data);
        }	
	}

    /*
    * If the validation is not valid, then it redirects to the edit product page 
    * If the validation is successfully then it updates the data into the database 
    * and it stores the operation message into the session flashdata and display on the manage product page
    */
	public function update($product_id)
	{      
        if(!in_array('updateProduct', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

        if(!$product_id) {
            redirect('dashboard', 'refresh');
        }

        $this->form_validation->set_rules('product_name', 'Product name', 'trim|required');
        $this->form_validation->set_rules('sku', 'SKU', 'trim|required');
        $this->form_validation->set_rules('price', 'Price', 'trim|required');
        $this->form_validation->set_rules('qty', 'Qty', 'trim|required');
        $this->form_validation->set_rules('store', 'Store', 'trim|required');
        $this->form_validation->set_rules('availability', 'Availability', 'trim|required');

        if ($this->form_validation->run() == TRUE) {
            // true case
            
            $data = array(
                'name' => $this->input->post('product_name'),
                'sku' => $this->input->post('sku'),
                'price' => $this->input->post('price'),
                'qty' => $this->input->post('qty'),
                'description' => $this->input->post('description'),
                #'attribute_value_id' => json_encode($this->input->post('attributes_value_id')),
                #'brand_id' => json_encode($this->input->post('brands')),
                #'category_id' => json_encode($this->input->post('category')),
                #'store_id' => $this->input->post('store'),
                #'availability' => $this->input->post('availability'),
            );

            
            if($_FILES['product_image']['size'] > 0) {
                $upload_image = $this->upload_image();
                $upload_image = array('image' => $upload_image);
                
                $this->model_products->update($upload_image, $product_id);
            }

            $update = $this->model_products->update($data, $product_id);
            if($update == true) {
                $this->session->set_flashdata('success', 'Successfully updated');
                redirect('products/', 'refresh');
            }
            else {
                $this->session->set_flashdata('errors', 'Error occurred!!');
                redirect('products/update/'.$product_id, 'refresh');
            }
        }
        else {
            // attributes 
            $attribute_data = $this->model_attributes->getActiveAttributeData();

            $attributes_final_data = array();
            foreach ($attribute_data as $k => $v) {
                $attributes_final_data[$k]['attribute_data'] = $v;

                $value = $this->model_attributes->getAttributeValueData($v['id']);

                $attributes_final_data[$k]['attribute_value'] = $value;
            }
            
            // false case
            $this->data['attributes'] = $attributes_final_data;
            $this->data['brands'] = $this->model_brands->getActiveBrands();         
            $this->data['category'] = $this->model_category->getActiveCategroy();           
            $this->data['stores'] = $this->model_stores->getActiveStore();          

            $product_data = $this->model_products->getProductData($product_id);
            $this->data['product_data'] = $product_data;
            $this->render_template('products/edit', $this->data); 
        }   
	}
    public function delete($id)
	{
		if($id) {
			if($this->input->post('confirm')) {
				$delete = $this->model_holiday->remove($id);
				if($delete == true) {
                    $this->session->set_flashdata('success', 'Successfully removed');
                    #redirect('holiday/', 'refresh');
                    $this->index();
                }
                else {
                    $this->session->set_flashdata('error', 'Error occurred!!');
                    redirect('holiday/delete/'.$id, 'refresh');
                }
			}	
			else {
				$this->data['user_id'] = $id;
				$this->render_template('holiday/delete', $this->data);
			}	
		}
	}
    /*
    * It removes the data from the database
    * and it returns the response into the json format
    */
	public function remove()
	{
        

        $product_id = $this->input->post('product_id');

        $response = array();
        if($product_id) {
            $delete = $this->model_products->remove($product_id);
            if($delete == true) {
                $response['success'] = true;
                $response['messages'] = "Successfully removed"; 
            }
            else {
                $response['success'] = false;
                $response['messages'] = "Error in the database while removing the product information";
            }
        }
        else {
            $response['success'] = false;
            $response['messages'] = "Refersh the page again!!";
        }

        echo json_encode($response);
    }

    public function excel(){
        $this->load->library('PHPExcel');
        $this->load->library('PHPExcel/IOFactory');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
 
        $objPHPExcel->setActiveSheetIndex(0);
        
        $result = $this->model_holiday->exportWageData();
        // Field names in the first row
        $fields = $result->list_fields();
        $col = 0;
        foreach ($fields as $field)
        {
            $v="";
            switch($field)
            {
                case 'name':$v="姓名\t";break;
                case 'department':$v="部门\t";break;
                case 'initdate':$v="开始工作时间\t";break;
                case 'indate':$v="入职时间\t";break;
                case 'Companyage':$v="社会工龄\t";break;
                case 'Totalage':$v="公司工龄\t";break;
                case 'Totalday':$v="可休假总数\t";break;
                case 'Lastyear':$v="去年休假数\t";break;
                case 'Thisyear':$v="今年休假数\t";break;
                case 'Bonus':$v="荣誉休假数\t";break;
                case 'Used':$v="已休假数\t";break;
                case 'Rest':$v="未休假数\t";break;
                case 'Jan':$v="一月\t";break;
                case 'Feb':$v="二月\t";break;
                case 'Mar':$v="三月\t";break;
                case 'Apr':$v="四月\t";break;
                case 'May':$v="五月\t";break;
                case 'Jun':$v="六月\t";break;
                case 'Jul':$v="七月\t";break;
                case 'Aug':$v="八月\t";break;
                case 'Sep':$v="九月\t";break;
                case 'Oct':$v="十月\t";break;
                case 'Nov':$v="十一月\t";break;
                case 'Dece':$v="十二月\t";break;    	
                default:break;
            }
            if($v != ""){
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $v);
                $col++;
            }
        }
 
        // Fetching the table data
        $row = 2;
        
        foreach($result->result() as $data)
        {
            $col = 0;
            foreach ($fields as $field)
            {
                if($field != 'initflag' and $field != 'user_id')
                {
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $data->$field);
                    $col++;
                }
                else{}
            }
            /*
            if($field != 'initflag' and $field != 'user_id')
            {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $data->$field);
            }
 
            */
            $row++;
        }
 
        $objPHPExcel->setActiveSheetIndex(0);
 
        $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
 
        $filename = date('YmdHis').".xlsx";

        // Sending headers to force the user to download the file
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename);
        header("Content-Disposition:filename=".$filename);
        header('Cache-Control: max-age=0');
 
        $objWriter->save('php://output');

    }

    public function download_page()
    {
        $this->data['user_name'] = $this->session->userdata('user_name');
        $this->render_template('holiday/export',$this->data);
    }

    public function excel_mydeptholiday($dept){
        $this->load->library('PHPExcel');
        $this->load->library('PHPExcel/IOFactory');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
 
        $objPHPExcel->setActiveSheetIndex(0);
        
        $result = $this->model_holiday->exportmydeptWageData($dept);
        
        // Field names in the first row
        
        $fields = $result->list_fields();
        $col = 0;
        
        foreach ($fields as $field)
        {
            $v="";
            switch($field)
            {
                case 'name':$v="姓名\t";break;
                case 'department':$v="部门\t";break;
                case 'initdate':$v="开始工作时间\t";break;
                case 'indate':$v="入职时间\t";break;
                case 'Companyage':$v="社会工龄\t";break;
                case 'Totalage':$v="公司工龄\t";break;
                case 'Totalday':$v="可休假总数\t";break;
                case 'Lastyear':$v="去年休假数\t";break;
                case 'Thisyear':$v="今年休假数\t";break;
                case 'Bonus':$v="荣誉休假数\t";break;
                case 'Used':$v="已休假数\t";break;
                case 'Rest':$v="未休假数\t";break;
                case 'Jan':$v="一月\t";break;
                case 'Feb':$v="二月\t";break;
                case 'Mar':$v="三月\t";break;
                case 'Apr':$v="四月\t";break;
                case 'May':$v="五月\t";break;
                case 'Jun':$v="六月\t";break;
                case 'Jul':$v="七月\t";break;
                case 'Aug':$v="八月\t";break;
                case 'Sep':$v="九月\t";break;
                case 'Oct':$v="十月\t";break;
                case 'Nov':$v="十一月\t";break;
                case 'Dece':$v="十二月\t";break;    	
                default:break;
            }
            if($v != ""){
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $v);
                $col++;
            }
        }
 
        // Fetching the table data
        $row = 2;
        
        foreach($result->result() as $data)
        {
            $col = 0;
            foreach ($fields as $field)
            {
                if($field != 'initflag' and $field != 'user_id')
                {
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $data->$field);
                    $col++;
                }
            }
            $row++;
            
        }
 
        $objPHPExcel->setActiveSheetIndex(0);
 
        $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
 
        $filename = date('YmdHis').".xlsx";
        // Sending headers to force the user to download the file
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename);
        header("Content-Disposition:filename=".$filename);
        header('Cache-Control: max-age=0');
 
        $objWriter->save('php://output');

    }
    public function export_holiday()
    {
        $this->excel();
        redirect('holiday/index', 'refresh');

    }

    public function excel_plan(){
        $this->load->library('PHPExcel');
        $this->load->library('PHPExcel/IOFactory');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
 
        $objPHPExcel->setActiveSheetIndex(0);
        
        $result = $this->model_plan->exportPlanData();
        // Field names in the first row
        $fields = $result->list_fields();
        $col = 0;
        foreach ($fields as $field)
        {
            $v="";
            switch($field)
            {
                case 'name':$v="姓名\t";break;
                case 'department':$v="部门\t";break;
                case 'Totalday':$v="可休假总数\t";break;
                case 'Lastyear':$v="去年休假数\t";break;
                case 'Thisyear':$v="今年休假数\t";break;
                case 'Bonus':$v="荣誉休假数\t";break;
                case 'firstquater':$v="第一季度\t";break;
                case 'secondquater':$v="第二季度\t";break;
                case 'thirdquater':$v="第三季度\t";break;
                case 'fourthquater':$v="第四季度\t";break;
                default:break;
            }
            if($v!="")
            {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $v);
                $col++;
            }
        }
 
        // Fetching the table data
        $row = 2;
        
        foreach($result->result() as $data)
        {
            $col = 0;
            
            foreach ($fields as $field)
            {
                if($field != 'user_id' and $field != 'submit_tag')
                {
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $data->$field);
                    $col++;
                }
            }
            /*
            if($field != 'user_id' and $field != 'submit_tag')
            {
               $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $data->$field);
            }
            */
            $row++;
        }
 
        $objPHPExcel->setActiveSheetIndex(0);
 
        $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
 
        $filename = date('YmdHis').".xlsx";
        
        // Sending headers to force the user to download the file
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename);
        header("Content-Disposition:filename=".$filename);
        header('Cache-Control: max-age=0');
 
        $objWriter->save('php://output');

    }
    public function export_plan()
    {

        $this->excel_plan();
    }
    public function excel_mydeptplan($dept){
        $this->load->library('PHPExcel');
        $this->load->library('PHPExcel/IOFactory');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
 
        $objPHPExcel->setActiveSheetIndex(0);
        $result=array();
        $result = $this->model_plan->exportmydeptPlanData($dept);
        // Field names in the first row
        $fields = $result->list_fields();
        $col = 0;
        foreach ($fields as $field)
        {
            $v="";
            switch($field)
            {
                case 'name':$v="姓名\t";break;
                case 'department':$v="部门\t";break;
                case 'Thisyear':$v="今年休假数\t";break;
                case 'Lastyear':$v="去年休假数\t";break;
                case 'Bonus':$v="荣誉休假数\t";break;
                case 'Totalday':$v="可休假总数\t";break;                
                case 'firstquater':$v="第一季度\t";break;
                case 'secondquater':$v="第二季度\t";break;
                case 'thirdquater':$v="第三季度\t";break;
                case 'fourthquater':$v="第四季度\t";break;
                default:break;
            }
            if($v!="")
            {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $v);
                $col++;
            }
        }
 
        // Fetching the table data
        $row = 2;
        
        foreach($result->result() as $data)
        {
            $col = 0;
            
            foreach ($fields as $field)
            {
                if($field != 'user_id' and $field != 'submit_tag')
                {
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $data->$field);
                    $col++;
                }
            }
            /*
            if($field != 'user_id' and $field != 'submit_tag')
            {
               $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $data->$field);
            }
            */
            $row++;
        }
        /**/
 
        $objPHPExcel->setActiveSheetIndex(0);
 
        $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
 
        $filename = date('YmdHis').".xlsx";
        
        // Sending headers to force the user to download the file
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename);
        header("Content-Disposition:filename=".$filename);
        header('Cache-Control: max-age=0');
 
        $objWriter->save('php://output');

    }
    
    public function export_mydeptplan()
    {
        $user_id=$this->session->userdata('user_id');
        $my_data = $this->model_plan->getPlanById($user_id);

        $this->excel_mydeptplan($_POST['current_dept']);
    }

    public function export_mydeptholiday()
    {
        $user_id=$this->session->userdata('user_id');
        $my_data = $this->model_plan->getPlanById($user_id);

        $this->excel_mydeptholiday($_POST['current_dept']);
    }
    






    public function excel_put(){
        
        $this->load->library("phpexcel");//ci框架中引入excel类
        $this->load->library('PHPExcel/IOFactory');
        //先做一个文件上传，保存文件
        $path=$_FILES['file'];
        $filePath = "uploads/".$path["name"];
        move_uploaded_file($path["tmp_name"],$filePath);
        //根据上传类型做不同处理
        
        if (strstr($_FILES['file']['name'],'xlsx')) {
            $reader = new PHPExcel_Reader_Excel2007();
        }
        else{
            if (strstr($_FILES['file']['name'], 'xls')) {
                $reader = IOFactory::createReader('Excel5'); //设置以Excel5格式(Excel97-2003工作簿)
            }
        }

        $PHPExcel = $reader->load($filePath, 'utf-8'); // 载入excel文件
        $sheet = $PHPExcel->getSheet(0); // 读取第一個工作表
        $highestRow = $sheet->getHighestRow(); // 取得总行数
        $highestColumm = $sheet->getHighestColumn(); // 取得总列数

        $data = array();
        for ($rowIndex = 1; $rowIndex <= $highestRow; $rowIndex++) {        //循环读取每个单元格的内容。注意行从1开始，列从A开始
            for ($colIndex = 'A'; $colIndex <= $highestColumm; $colIndex++) {
                $addr = $colIndex . $rowIndex;
                $cell = $sheet->getCell($addr)->getValue();
                if ($cell instanceof PHPExcel_RichText) { //富文本转换字符串
                    $cell = $cell->__toString();
                }
                $data[$rowIndex][$colIndex] = $cell;
            }
        }

        $column=array();
        $column_name=array();
        $attribute_data=array();
        $first=true;
        $flag=false;
        $counter=0;
        $name="";
        $dept="";
        $Initdate=gmdate("Y-m-d") ;
        $Indate=gmdate("Y-m-d");
        $Totalage=0;
        $Comage=0;
        $Totalday=0;
        $Lastyear=0;
        $Thisyear=0;
        $Bonus=0;
        $Used=0;
        $Rest=0;
        $Jan=0;
        $Feb=0;
        $Mar=0;
        $Apr=0;
        $May=0;
        $Jun=0;
        $Jul=0;
        $Aug=0;
        $Sep=0;
        $Oct=0;
        $Nov=0;
        $Dece=0;
        $User_id="";
        foreach($data as $k => $v){
            if($first){
                $first=false;
                foreach($v as $a =>$b){
                    array_push($column_name,$b);
                }
            }
            else{
                array_push($column,$v);
            }
        }
        /* excel导入时间的方法！ */
        $initflag=0;
        foreach($column as $k => $v)
        {
            foreach($v as $a => $b)
            {
                echo $b.'<br />';

            }
            /*
            $Update_data=array(
                'name' => $name,
                'department' => $dept,
                'initdate' => $Initdate,
                'indate' => $Indate,
                'Companyage' => $Comage,
                'Totalage' => $Totalage,
                'Totalday' => $Totalday,
                'Lastyear' => $Lastyear,
                'Thisyear' => $Thisyear,
                'Bonus' => $Bonus,
                'Used' => $Used,
                'Rest' => $Rest,
                'Jan' => $Jan,
                'Feb' => $Feb,
                'Mar' => $Mar,
                'Apr' => $Apr,
                'May' => $May,
                'Jun' => $Jun,
                'Jul' => $Jul,
                'Aug' => $Aug,
                'Sep' => $Sep,
                'Oct' => $Oct,
                'Nov' => $Nov,
                'Dece' => $Dece,
                'initflag' => $initflag,
                'User_id' => $User_id
            );
*/
            $update_user=true;
            if(1)#($this->model_holiday->getWagebyID($User_id))
            {
                /*
                if(!(serialize($Update_data) == serialize($this->model_holiday->getWagebyID($User_id)))){
                   $update=$this->model_holiday->update($Update_data,$User_id);
                }
                */
            }
            else{
                /*
                $update=$this->model_holiday->create($Update_data);
                if($this->model_users->getUserById($User_id)==1){
                    $Update_user_data=array(
                        'user_id' => $User_id,
                        'username' => $name,
                        'password' => md5('hr'),
                        'permission' => '3'
                    );
            
                    $update_user=$this->model_users->create($Update_user_data,$name);
                }
                $submit_data=array(
                    'department' => $Update_data['department']
                );
                $this->model_submit->create($submit_data);
                $feedback_data=array(
                    'department' => $Update_data['department'],
                );
                $this->model_feedback->create($feedback_data);
                */
            }
            /*
            if($update == true and $update_user == true) {
                $response['success'] = true;
                $response['messages'] = 'Succesfully updated';
            }
            else {
                $response['success'] = false;
                $response['messages'] = 'Error in the database while updated the brand information';			
            }
            */
            
        }
    }
    public function import($filename=NULL)
    {
        
        $this->data['user_permission'] = $this->session->userdata('user_permission');
        $this->data['user_name'] = $this->session->userdata('user_name');
        if($_FILES){
        if($_FILES["file"])
            {
                if ($_FILES["file"]["error"] > 0)
                {
                    echo "Error: " . $_FILES["file"]["error"] . "<br />";
                }
                else
                {
                    $this->excel_put();
                    $this->index();
                }
            }
        }
        else{
            $this->render_template('wage/import',$this->data);
        }        
    }
    


    /*
        获取所有的计划，超管可以查看
     */

    public function plan_set()
    {
        $user_id=$this->session->userdata('user_id');

        $user_data=$this->model_users->getUserById($user_id);
        $plan_data = $this->model_plan->getPlanData();
        
        $result = array();
        
        if($plan_data)
        {
            foreach($plan_data as $k => $v)
            {
                if($v['submit_tag']==1){
                    $v['submit_tag']='已提交';
                }
                else if($v['submit_tag']==0){
                    $v['submit_tag']='未提交';
                }
                $result[$k]=$v;
            }  
        }
        else
        {
            $holiday_data=$this->model_holiday->getWageData();
            foreach($holiday_data as $k =>$v)
            {
                $plan_data=array(
                    'user_id' => $v['user_id'],
                    'name' => $v['name'],
                    'department' => $v['department'],
                    'Thisyear' => $v['Thisyear'],
                    'Lastyear' => $v['Lastyear'],
                    'Bonus' => $v['Bonus'],
                    'Totalday' => $v['Totalday'],
                    'firstquater' => 0,
                    'secondquater' => 0,
                    'thirdquater' => 0,
                    'fourthquater' => 0,
                    'submit_tag' => 0
                );
                $this->model_plan->create($plan_data);
            }
            $plan_data = $this->model_plan->getPlanData();
            foreach($plan_data as $k => $v)
            {
                if($v['submit_tag']==1){
                    $v['submit_tag']='已提交';
                }
                else if($v['submit_tag']==0){
                    $v['submit_tag']='未提交';
                }
                $result[$k]=$v;
            }
            
        }

        $this->data['plan_data'] = $result;
        
        $this->data['user_permission'] = $this->session->userdata('user_permission');
        $this->data['user_name'] = $this->session->userdata('user_name');
        

        $this->render_template('holiday/plan', $this->data);
    }

    /*
    ==============================================================================
    普通员工
    ==============================================================================
    */

    public function staff()
	{
        $user_id=$this->session->userdata('user_id');
        $holiday_data = $this->model_holiday->getWageById($user_id);
        $notice_data = $this->model_notice->getNoticeLatestWage();
        $result = array();
        $notice_result=array();
        foreach ($notice_data as $k => $v) {
            $notice_result[$k] = $v;
        }
        foreach ($holiday_data as $k => $v) {
            $result[$k] = $v;
        }
        //$result['']
        /**/

        $this->data['holiday_data'] = $result;
        $this->data['notice_data'] = $notice_result;
        $this->data['user_permission'] = $this->session->userdata('user_permission');
        $this->data['user_name'] = $this->session->userdata('user_name');
		$this->render_template('holiday/staff', $this->data);
    }
    


    /*
    ==============================================================================
    综合管理员
    ==============================================================================
    */
    public function admin()
	{
        $user_id=$this->session->userdata('user_id');
        $holiday_data = $this->model_holiday->getWageById($user_id);
        $notice_data = $this->model_notice->getNoticeLatest();
        $result = array();
        $notice_result=array();
        foreach ($notice_data as $k => $v) {
            $notice_result[$k] = $v;
        }

        foreach ($holiday_data as $k => $v) {
            $result[$k] = $v;
        }
        

        $this->data['holiday_data'] = $result;
        $this->data['notice_data'] = $notice_result;
        $this->data['user_permission'] = $this->session->userdata('user_permission');
        $this->data['user_name'] = $this->session->userdata('user_name');

		$this->render_template('holiday/index', $this->data);
    }

    public function mydeptholiday()
    {
        $result=array();
        $user_id=$this->session->userdata('user_id');
        $this->data['current_dept']="";
        if($_POST){
            $select_dept=$_POST['selected_dept'];
            $holiday_data = $this->model_holiday->getWageByDept($select_dept);
            $result = array();
            foreach ($holiday_data as $k => $v)
            {
                $result[$k] = $v;
            }

            $this->data['holiday_data'] = $result;
            $this->data['current_dept']=$select_dept;

        }
        
        $admin_data = $this->model_manager->getManagerById($user_id);

        $admin_result=array();
        $admin_result=explode('/',$admin_data['dept']);

        $this->data['dept_options']=$admin_result;

        $this->data['holiday_data'] = $result;
        $this->data['user_permission'] = $this->session->userdata('user_permission');
        $this->data['user_name'] = $this->session->userdata('user_name');
		$this->render_template('holiday/mydeptholiday', $this->data);
    }
    public function mydeptplan()
    {
        $user_id=$this->session->userdata('user_id');
        $result = array();
        $submitted=0;
        $this->data['current_dept']="";
        $this->data['submit_status'] ="";
        $select_dept="";
        if($_POST){
            if(array_key_exists('selected_dept', $_POST)){
                $select_dept=$_POST['selected_dept'];
            }
            if(array_key_exists('current_dept', $_POST)){
                $select_dept=$_POST['current_dept'];
            }
            $plan_data = $this->model_plan->getPlanByDept($select_dept);
            foreach ($plan_data as $k => $v) {
                $result[$k]=$v;
                if($v['submit_tag']==1){
                    $result[$k]['submit_tag'] = '已提交';
                    $submitted++;
                }
                else{
                    $result[$k]['submit_tag'] = '未提交';
                }
            }
            $this->data['current_dept']=$select_dept;
            $this->data['submit_status'] = $this->model_submit->getSubmitByDept($select_dept)['status'];
        }
        $admin_data = $this->model_manager->getManagerById($user_id);

        $admin_result=array();
        $admin_result=explode('/',$admin_data['dept']);

        $this->data['dept_options']=$admin_result;
        $this->data['submitted'] = $submitted;
        $this->data['plan_data'] = $result;
        $this->data['user_permission'] = $this->session->userdata('user_permission');
        $this->data['user_name'] = $this->session->userdata('user_name');
        $this->data['feedback'] = $this->model_feedback->getFeedbackByDept($select_dept);
        $this->render_template('holiday/mydeptplan', $this->data);
    }
    public function mydeptplan_submit(){
        $user_id = $this->session->userdata('user_id');
        $my_data = $this->model_manager->getManagerById($user_id);

        $dept_set=array();
        $data=array();
        $feedback_status=array();
        if(strstr($my_data['dept'],'/')){
            $dept_set=explode('/',$my_data['dept']);
            foreach($dept_set as $a => $b){
                if($this->model_submit->getSubmitByDept($b)){
                    array_push($data,$this->model_submit->getSubmitByDept($b));
                    $feedback[$b]=$this->model_feedback->getFeedbackByDept($b);
                }
            }
        }
        #$this->model_submit->getSubmitByDept($);
        
        $this->data['submit_data'] = $data;
        $this->data['feedback'] = $feedback;
        $this->data['user_name'] = $this->session->userdata('user_name');
        $this->render_template('holiday/submit_result', $this->data);
    }

    /*
    ==============================================================================
    部门经理
    ==============================================================================
    */
    public function manager()
	{
        $user_id=$this->session->userdata('user_id');
        $holiday_data = $this->model_holiday->getWageById($user_id);
        $notice_data = $this->model_notice->getNoticeLatest();
        $result = array();
        $notice_result=array();
        foreach ($notice_data as $k => $v) {
            $notice_result[$k] = $v;
        }
        
        foreach ($holiday_data as $k => $v) {
            $result[$k] = $v;
        }

        $this->data['holiday_data'] = $result;
        $this->data['notice_data'] = $notice_result;
        $this->data['user_permission'] = $this->session->userdata('user_permission');
        $this->data['user_name'] = $this->session->userdata('user_name');
        

		$this->render_template('holiday/index', $this->data);
    }


    /*
    ==============================================================================
    单个人的年假计划显示
    ==============================================================================
    */

    public function staff_plan()
    {
        $user_id=$this->session->userdata('user_id');
        
        $plan_data = $this->model_plan->getplanById($user_id);
        
        $notice_data = $this->model_notice->getNoticeLatestPlan();
        $result = array();
        
        $notice_result=array();
        if($notice_data)
        {
            foreach ($notice_data as $k => $v) {
                $notice_result[$k] = $v;
            }
        }
        if($plan_data)
        {
            $plan_data['Totalday']=$plan_data['Thisyear']+$plan_data['Lastyear']+$plan_data['Bonus'];
            $data = array(
                'Totalday' => $plan_data['Totalday'],
            );

            $update = $this->model_plan->update($data, $plan_data['name']);
        }
        $this->data['plan_data'] = $plan_data;
        $this->data['notice_data'] = $notice_result;
        $this->data['user_permission'] = $this->session->userdata('user_permission');
        $this->data['user_name'] = $this->session->userdata('user_name');

		$this->render_template('holiday/staff_plan', $this->data);
    }
    /*
    ==============================================================================
    年假计划提交
    ==============================================================================
    */
    public function update_plan()
    {
        /*============================================================*/
        /*
            首页必须要的信息，包括身份证，通知信息
        */
        /*============================================================*/
        $user_id=$this->session->userdata('user_id');
        $plan_data = $this->model_plan->getPlanById($user_id);
        $result = array();


        foreach ($plan_data as $k => $v) {
            $result[$k] = $v;
        }
        
        $this->data['plan_data'] = $result;
        $this->data['user_permission'] = $this->session->userdata('user_permission');
        $this->data['user_name'] = $this->session->userdata('user_name');
        /**/
        /*============================================================*/

        /*============================================================*/
        $this->form_validation->set_rules('firstquater', 'firstquater','is_natural|greater_than[-1]');
        $this->form_validation->set_rules('secondquater', 'secondquater','is_natural|greater_than[-1]');
        $this->form_validation->set_rules('thirdquater', 'thirdquater','is_natural|greater_than[-1]');
        $this->form_validation->set_rules('fourthquater', 'fourthquater','is_natural|greater_than[-1]');

        if ($this->form_validation->run() == TRUE) {
            if($_POST['firstquater']+$_POST['secondquater']+$_POST['thirdquater']+$_POST['fourthquater']==$_POST['total'])
            {
                $data = array(
                    'firstquater' => $_POST['firstquater'],
                    'secondquater' => $_POST['secondquater'],
                    'thirdquater' => $_POST['thirdquater'],
                    'fourthquater' => $_POST['fourthquater'],
                    'submit_tag' => 1
                );

                $create = $this->model_plan->update($data,$user_id);
                
                if($create == true) {
                    $this->session->set_flashdata('success', '提交成功');
                    $this->staff_plan();
                }
                else {
                    $this->session->set_flashdata('error', '提交失败');
                    $this->staff_plan();
                }
            }
            else
            {

                $this->session->set_flashdata('error', '提交失败，计划总数必须等于可休假总数');
                $this->staff_plan();
                #$this->render_template('holiday/staff_plan', $this->data);
                
            }
            /**/
        }
        else {
            $this->staff_plan();
        }
    }

    /*
    ==============================================================================
    超级管理员，综合管理员修改年假计划编辑权限
    ==============================================================================
    */
    public function change_submit(){
        if($_POST){
            if($_POST['submit_auth']==1){
                $data = array(
                    'submit_tag' => 0
                );
                
            }
            if($_POST['submit_revolt']==1){
                $data = array(
                    'submit_tag' => 1
                );
            }

            $update = $this->model_plan->update($data,$_POST['user_id']);
            
            if($update == true) {
                $this->session->set_flashdata('success', 'Successfully created');
                $this->plan_set();
            }
        }
        else{
            $this->plan_set();
        }

    }
    public function change_submit_mydeptplan(){
        if($_POST){
            if($_POST['submit_auth']==1){
                $data = array(
                    'submit_tag' => 0
                );
                
            }
            if($_POST['submit_revolt']==1){
                $data = array(
                    'submit_tag' => 1
                );
            }

            $update = $this->model_plan->update($data,$_POST['user_id']);
            
            if($update == true) {
                $this->session->set_flashdata('success', 'Successfully created');
                $this->mydeptplan();
            }
        }
        else{
            $this->mydeptplan();
        }

    }
    public function submit_to_audit(){
        if($_POST['current_dept']){
            $dept=$_POST['current_dept'];
            $data=array(
                'department' => $dept,
                'status' => '已提交'
            );
            //如果部门存在，那就更新部门的提交状态
            //如果部门不存在，那就创建新的部门状态
            if($this->model_submit->getSubmitByDept($dept)){
                $this->model_submit->update($data,$dept);
            }
            else{
                $this->model_submit->create($data);
            }
            $data_feedback=array(
                'department' => $dept,
                'content' => '',
                'confirm' => '',
                'status' => '未审核'
            );
            //如果部门存在，那就更新反馈状态，未审核
            //如果部门不存在，那就创建新的部门状态
            if($this->model_feedback->getFeedbackByDept($dept)){
                $this->model_feedback->update($data,$dept);
            }
            else{
                $this->model_feedback->create($data);
            }
        }
        $this->mydeptplan();
    }
    public function audit(){
        $user_id=$this->session->userdata('user_id');
        $result = array();
        
        $this->data['current_dept']="";
        $this->data['submit_status'] ="";
        $this->data['feedback_status'] = "";
        if($_POST){
            $select_dept=$_POST['selected_dept'];
            //如果有反馈内容的话，那么就检验是否选了同意和不同意
            //如果没有反馈内容，那就是普通的查看，直接去显示提交过来的计划表
            if(array_key_exists('feedback_content', $_POST)){
                $this->form_validation->set_rules('confirm', 'confirm', 'trim|required');
                if($this->form_validation->run() == TRUE) {
                    $data=array(
                        'content' => $_POST['feedback_content'],
                        'confirm' => $_POST['confirm'],
                        'status' => '已审核'
                    );
                    $this->model_feedback->update($data,$select_dept);
                    //如果反馈的结果是不同意，那么就把该部门的综管员的编辑和提交权限打开。
                    //如果反馈同意就不用做任何事情
                    if($_POST['confirm']==0){
                        #$this->model_submit->getSubmitByDept($select_dept);
                        $submit_data=array(
                            'status' => '未提交'
                        );
                        $this->model_submit->update($submit_data,$select_dept);
                    }
                }
                $plan_data = $this->model_plan->getPlanByDept($select_dept);
                foreach ($plan_data as $k => $v) {
                    $result[$k]=$v;
                }
            }
            else{
                $plan_data = $this->model_plan->getPlanByDept($select_dept);
                foreach ($plan_data as $k => $v) {
                    $result[$k]=$v;
                }
            }
            $this->data['current_dept']=$select_dept;

            $this->data['submit_status'] = $this->model_submit->getSubmitByDept($select_dept)['status'];
            $this->data['feedback_status'] = $this->model_feedback->getFeedbackByDept($select_dept)['status'];
            echo $this->data['feedback_status'];
        }
        $admin_data = $this->model_manager->getManagerById($user_id);

        $admin_result=array();
        $admin_result=explode('/',$admin_data['dept']);

        $this->data['dept_options']=$admin_result;
        $this->data['plan_data'] = $result;
        $this->data['user_permission'] = $this->session->userdata('user_permission');
        $this->data['user_name'] = $this->session->userdata('user_name');
        
        $this->render_template('holiday/audit', $this->data);

    }

    public function audit_result(){
        $user_id=$this->session->userdata('user_id');
        
        $my_data = $this->model_manager->getManagerById($user_id);

        $dept_set=array();
        $data=array();
        if(strstr($my_data['dept'],'/')){
            $dept_set=explode('/',$my_data['dept']);
            foreach($dept_set as $a => $b){
                if($this->model_feedback->getFeedbackByDept($b)){
                    array_push($data,$this->model_feedback->getFeedbackByDept($b));
                }
            }
        }
        else{
            array_push($data,$this->model_feedback->getFeedbackByDept($b));
        }  
        $this->data['feedback_data']=$data;
        $this->data['user_name'] = $this->session->userdata('user_name');
        $this->render_template('holiday/audit_result', $this->data);
    }

}