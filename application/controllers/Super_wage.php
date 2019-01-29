<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Super_wage extends Admin_Controller 
{

	public function __construct()
	{
        parent::__construct();
        $this->data['page_title'] = 'Super';
        $this->load->model('model_plan');
        $this->load->model('model_notice');
        $this->load->model('model_dept');
        $this->load->model('model_users');
        $this->load->model('model_all_user');
        $this->load->model('model_wage_tag');
        $this->load->model('model_wage_attr');
        $this->load->model('model_wage_record');
        $this->load->model('model_wage_apply');
        $this->load->model('model_wage');
        $this->load->model('model_wage_doc'); 
        $this->load->model('model_wage_func');
        $this->data['notice_data'] = $this->model_notice->getNoticeLatestWage();
        $this->data['permission']=$this->session->userdata('permission');
        $this->data['user_name'] = $this->session->userdata('user_id');
        if($this->data['user_name']==NULL){
            redirect('super_auth/login','refresh');
        }
    }
    /*
    ============================================================
    工资管理
    包括：
    1、主页
    ============================================================
    */ 
    public function index(){
        /*
        $this->data['wage_total']=$this->model_wage_attr->getWageTotalData()['total'];
        $this->data['wage_data']=$this->model_wage->getWageData();
        $this->data['attr_data']=$this->model_wage_attr->getWageAttrData();
        $counter=0;
        if($this->data['attr_data']){
            foreach($this->data['attr_data'] as $k => $v){
                
                if($v=='月度绩效工资小计'){
                    $this->data['yuedustart']=$counter;
                }
                if($v=='省核专项奖励小计'){
                    $this->data['yueduend']=$counter-1;
                    $this->data['shengzhuanstart']=$counter;
                }
                if($v=='分公司专项奖励小计'){
                    $this->data['shengzhuanend']=$counter-1;
                    $this->data['fengongsistart']=$counter;
                }
                if($v=='其他小计'){
                    $this->data['fengongsiend']=$counter-1;
                    $this->data['qitastart']=$counter;
                }
                if($v=='教育经费小计'){
                    $this->data['qitaend']=$counter-1;
                    $this->data['jiaoyustart']=$counter;
                }
                if($v=='福利费小计'){
                    $this->data['jiaoyuend']=$counter-1;
                    $this->data['fulistart']=$counter;
                }
                if($v=='当月月应收合计'){
                    $this->data['fuliend']=$counter-1;
                    $this->data['koufeistart']=$counter+1;
                    
                }
                if($v=='扣款小计'){
                    $this->data['koufeiend']=$counter;
                    break;
                }
                $counter++;
            }
        }
        $this->render_super_template('super/wage',$this->data);
        */
        $this->search();
    }
    public function this_month(){
        $this->data['wage_total']=$this->model_wage_attr->getWageTotalData()['total'];
        $this->data['wage_data']=$this->model_wage->getWageData();
        $this->data['attr_data']=$this->model_wage_attr->getWageAttrData();
        $counter=0;
        if($this->data['attr_data']){
            foreach($this->data['attr_data'] as $k => $v){
                if($v=='月度绩效工资小计'){
                    $this->data['yuedustart']=$counter;
                }
                if($v=='省核专项奖励小计'){
                    $this->data['yueduend']=$counter-1;
                    $this->data['shengzhuanstart']=$counter;
                }
                if($v=='分公司专项奖励小计'){
                    $this->data['shengzhuanend']=$counter-1;
                    $this->data['fengongsistart']=$counter;
                }
                if($v=='其他小计'){
                    $this->data['fengongsiend']=$counter-1;
                    $this->data['qitastart']=$counter;
                }
                if($v=='教育经费小计'){
                    $this->data['qitaend']=$counter-1;
                    $this->data['jiaoyustart']=$counter;
                }
                if($v=='福利费小计'){
                    $this->data['jiaoyuend']=$counter-1;
                    $this->data['fulistart']=$counter;
                }
                if($v=='当月月应收合计'){
                    $this->data['fuliend']=$counter-1;
                    $this->data['koufeistart']=$counter+1;
                }
                if($v=='扣款小计'){
                    $this->data['koufeiend']=$counter;
                    break;
                }
                $counter++;
            }
        }
        $this->render_super_template('super/wage',$this->data);
    }
    public function search_excel($doc_name){
        $this->load->library('phpexcel');//ci框架中引入excel类
        $this->load->library('PHPExcel/IOFactory');
        
        $dir='uploads/wage';
        $data=array();
        if(is_dir($dir)){
            $files = array();
            $child_dirs = scandir($dir);
            foreach($child_dirs as $child_dir){
                if($child_dir != '.' && $child_dir != '..'){
                    if(is_dir($dir.'/'.$child_dir)){
                        $files[$child_dir] = my_scandir($dir.'/'.$child_dir);
                    }else{
                        if(strstr($child_dir,$doc_name)){
                            if(strstr($child_dir,'xlsx')){
                                $reader = new PHPExcel_Reader_Excel2007();
                            }
                            else{
                                if(strstr($child_dir, 'xls')){
                                    $reader = IOFactory::createReader('Excel5'); //设置以Excel5格式(Excel97-2003工作簿)
                                }
                            }
                            $cellName = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ','BA', 'BB', 'BC', 'BD', 'BE', 'BF', 'BG', 'BH', 'BI', 'BJ', 'BK', 'BL', 'BM', 'BN', 'BO', 'BP', 'BQ', 'BR', 'BS', 'BT', 'BU', 'BV', 'BW', 'BX', 'BY', 'BZ','CA', 'CB', 'CC', 'CD', 'CE', 'CF', 'CG', 'CH', 'CI', 'CJ', 'CK', 'CL', 'CM', 'CN', 'CO', 'CP', 'CQ', 'CR', 'CS', 'CT', 'CU', 'CV', 'CW', 'CX', 'CY', 'CZ','DA', 'DB', 'DC', 'DD', 'DE', 'DF', 'DG', 'DH', 'DI', 'DJ', 'DK', 'DL', 'DM', 'DN', 'DO', 'DP', 'DQ', 'DR', 'DS', 'DT', 'DU', 'DV', 'DW', 'DX', 'DY', 'DZ'); 
                            $PHPExcel = $reader->load($dir.'/'.$child_dir, 'utf-8'); // 载入excel文件
                            $sheet = $PHPExcel->getSheet(0); // 读取第一個工作表
                            $highestRow = $sheet->getHighestRow(); // 取得总行数
                            $highestColumm = $sheet->getHighestColumn(); // 取得总列数
                        
                            $columnCnt = array_search($highestColumm, $cellName); 

                            $data = array();
                            for($rowIndex = 4; $rowIndex <= $highestRow; $rowIndex++){        //循环读取每个单元格的内容。注意行从1开始，列从A开始
                                for($colIndex = 0; $colIndex <= $columnCnt; $colIndex++){
                                    $cellId = $cellName[$colIndex].$rowIndex;  
                                    $cell = $sheet->getCell($cellId)->getValue();
                                    $cell = $sheet->getCell($cellId)->getCalculatedValue();
                                    if($cell instanceof PHPExcel_RichText){ //富文本转换字符串
                                        $cell = $cell->__toString();
                                    }
                                    if($cell!='' or $cell=='0'){
                                        $data[$rowIndex][$colIndex] = $cell;
                                    }
                                    if($cell=='' and $colIndex==$columnCnt){
                                        $data[$rowIndex][$colIndex] = $cell;
                                    }
                                }
                            }
                        }
                        break;
                    }
                }
            }
        }
        return $data;
    }
    public function search(){
        $this->data['wage_data']='';
        $this->data['attr_data']='';
        $this->data['chosen_month']='';
        if($_SERVER['REQUEST_METHOD'] == 'POST' and array_key_exists('chosen_month',$_POST)){
            $this->data['chosen_month']=$_POST['chosen_month'];
            $doc_name=substr($_POST['chosen_month'],0,4).substr($_POST['chosen_month'],5,6);
            if(strlen($doc_name)<=7 and $doc_name!=''){
                $this->data['attr_data']=$this->model_wage_attr->getWageAttrDataByDate($doc_name);
                if(!empty($this->data['attr_data'])){
                    $this->data['wage_data']=$this->model_wage->getWageByDate($doc_name);
                    $counter=0;
                    foreach($this->data['attr_data'] as $k => $v){
                        if($v=='月度绩效工资小计'){
                            $this->data['yuedustart']=$counter;
                        }
                        if($v=='省核专项奖励小计'){
                            $this->data['yueduend']=$counter-1;
                            $this->data['shengzhuanstart']=$counter;
                        }
                        if($v=='分公司专项奖励小计'){
                            $this->data['shengzhuanend']=$counter-1;
                            $this->data['fengongsistart']=$counter;
                        }
                        if($v=='其他小计'){
                            $this->data['fengongsiend']=$counter-1;
                            $this->data['qitastart']=$counter;
                        }
                        if($v=='教育经费小计'){
                            $this->data['qitaend']=$counter-1;
                            $this->data['jiaoyustart']=$counter;
                        }
                        if($v=='福利费小计'){
                            $this->data['jiaoyuend']=$counter-1;
                            $this->data['fulistart']=$counter;
                        }
                        if($v=='当月月应收合计'){
                            $this->data['fuliend']=$counter-1;
                            $this->data['koufeistart']=$counter+1;
                        }
                        if($v=='扣款小计'){
                            $this->data['koufeiend']=$counter;
                        }
                        if($v=='本月工资差异说明'){
                            $this->data['trueend']=$counter+1;
                            break;
                        }
                        $counter++;
                    }
                }
            }
            $this->render_super_template('super/wage_search',$this->data);
        }
        else{
            $this->render_super_template('super/wage_search',$this->data);
        }
    }
    
    /**
    *数字金额转换成中文大写金额的函数
    *String Int $num 要转换的小写数字或小写字符串
    *return 大写字母
    *小数位为两位
    **/
    private function num_to_rmb($num){
        $c1 = "零壹贰叁肆伍陆柒捌玖";
        $c2 = "分角圓拾佰仟萬拾佰仟億";
        //精确到分后面就不要了，所以只留两个小数位
        $num = round($num, 2); 
        //将数字转化为整数
        $num = $num * 100;
        if (strlen($num) > 10) {
            return "金额太大，请检查";
        } 
        $i = 0;
        $c = "";
        while (1) {
            if ($i == 0) {
                //获取最后一位数字
                $n = substr($num, strlen($num)-1, 1);
            } else {
                $n = $num % 10;
            }
            //每次将最后一位数字转化为中文
            $p1 = substr($c1, 3 * $n, 3);
            $p2 = substr($c2, 3 * $i, 3);
            if ($n != '0' || ($n == '0' && ($p2 == '亿' || $p2 == '萬' || $p2 == '圓'))) {
                $c = $p1 . $p2 . $c;
            } else {
                $c = $p1 . $c;
            }
            $i = $i + 1;
            //去掉数字最后一位了
            $num = $num / 10;
            $num = (int)$num;
            //结束循环
            if ($num == 0) {
                break;
            } 
        }
        $j = 0;
        $slen = strlen($c);
        while ($j < $slen) {
            //utf8一个汉字相当3个字符
            $m = substr($c, $j, 6);
            //处理数字中很多0的情况,每次循环去掉一个汉字“零”
            if ($m == '零圓' || $m == '零萬' || $m == '零亿' || $m == '零零') {
                $left = substr($c, 0, $j);
                $right = substr($c, $j + 3);
                $c = $left . $right;
                $j = $j-3;
                $slen = $slen-3;
            } 
            $j = $j + 3;
        } 
        //这个是为了去掉类似23.0中最后一个“零”字
        if (substr($c, strlen($c)-3, 3) == '零') {
            $c = substr($c, 0, strlen($c)-3);
        }
        //将处理的汉字加上“整”
        if (empty($c)) {
            return "零圓整";
        }else{
            return $c . "整";
        }
    }
    
    public function proof_Creator($user_id,$type){
        //图片水印制作
        $ori_img = 'assets/images/unicomletterb.jpg';    //原图
        /*
        $new_img = 'assets/images/new.jpg';    //生成水印后的图片
        
        $original = getimagesize($ori_img);    //得到图片的信息，可以print_r($original)发现它就是一个数组

        switch($original[2]){
            case 1 : $s_original = imagecreatefromgif($ori_img);
                break;
            case 2 : $s_original = imagecreatefromjpeg($ori_img);
                break;
            case 3 : $s_original = imagecreatefrompng($ori_img);
                break;
        }
        */
        /*
        $font_size = 22;    //字号
        $tilt = 45;    //文字的倾斜度
        $color = imagecolorallocatealpha($s_original,0,0,0,0);// 为一幅图像分配颜色 255,0,0表示红色
        $str = $this->session->userdata('user_id');
        $poxY = 350;    //Y坐标
        for($posX=200;$posX<$original[0];$posX+=600){
            imagettftext($s_original, $font_size, $tilt, $posX, $poxY, $color, 'C:/Windows/Fonts/simfang.ttf', $str);
        }
        $poxY = 650;    //Y坐标
        for($posX=450;$posX<$original[0];$posX+=600){
            imagettftext($s_original, $font_size, $tilt, $posX, $poxY, $color, 'C:/Windows/Fonts/simfang.ttf', $str);
        }
        $poxY = 950;    //Y坐标
        for($posX=200;$posX<$original[0];$posX+=600){
            imagettftext($s_original, $font_size, $tilt, $posX, $poxY, $color, 'C:/Windows/Fonts/simfang.ttf', $str);
        }
        $poxY = 1250;    //Y坐标
        for($posX=500;$posX<$original[0];$posX+=600){
            imagettftext($s_original, $font_size, $tilt, $posX, $poxY, $color, 'C:/Windows/Fonts/simfang.ttf', $str);
        }
        $poxY = 1550;    //Y坐标
        for($posX=200;$posX<$original[0];$posX+=600){
            imagettftext($s_original, $font_size, $tilt, $posX, $poxY, $color, 'C:/Windows/Fonts/simfang.ttf', $str);
        }
        $poxY = 1850;    //Y坐标
        for($posX=500;$posX<$original[0];$posX+=600){
            imagettftext($s_original, $font_size, $tilt, $posX, $poxY, $color, 'C:/Windows/Fonts/simfang.ttf', $str);
        }
        $loop = imagejpeg($s_original, $new_img);    //生成新的图片(jpg格式)，如果用imagepng可以生成png格式
        */
        //证明文件生成
        $this->load->library('tcpdf.php');
        //实例化 
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false); 
        #$pdf = new TCPDF('P', 'mm', 'A4', true, 'GB2312', false); 
        
        // 设置文档信息 
        $pdf->SetCreator('人力资源部'); 
        $pdf->SetAuthor('甘子运'); 
        $pdf->SetTitle('收入证明'); 
        $pdf->SetKeywords('TCPDF, PDF, PHP'); 
        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);
        // 设置页眉和页脚信息 
        $pdf->SetHeaderData('logo.png', 30, '页眉', '页眉', array(0,64,255), array(0,64,128)); 
        $pdf->setFooterData(array(0,64,0), array(0,64,128));         
        // 设置页眉和页脚字体 
        #$pdf->setHeaderFont(Array('songti', '', '10')); 
        #$pdf->setFooterFont(Array('helvetica', '', '8')); 
        // 设置默认等宽字体 
        #$pdf->SetDefaultMonospacedFont('courierB'); 
        // 设置间距 
        $pdf->SetMargins(27.5,40,27);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        // 设置分页 
        $pdf->SetAutoPageBreak(false,0); 
        // set image scale factor 
        $pdf->setImageScale(1.5); 
        // set default font subsetting mode 
        $pdf->setFontSubsetting(true); 
        $pdf->setFontStretching(100);
        $pdf->setFontSpacing(0);
        //设置字体 
        
        $pdf->setCellHeightRatio(3);
        $pdf->AddPage('P', 'A4'); 
        
        //设置背景图片
        #$img_file = 'assets/images/Unicom.jpg';
        #$img_file=$new_img;
        $img_file=$ori_img;
        $pdf->Image($img_file, 0, 0, 0, 500, '', '', '', false, 300, '', false, false, 0);
        $user_data=$this->model_wage_tag->getTagById($user_id);
        #$cage=$holiday_data['Companyage'];
        #$user_id=$user_data['user_id'];
        $username=$user_data['name'];
        $date_set=array();
        $date=date('Y年m月d日',strtotime($user_data['indate']));
        $ToEndMonth=strtotime(date('Y-m')); //转换一下
        $ToStartMonth=strtotime('-12 Month', $ToEndMonth);
        $i=false; //开始标示
        while( $ToStartMonth < $ToEndMonth ) {
            $NewMonth = !$i ? date('Y-m', strtotime('+0 Month', $ToStartMonth)) : date('Y-m', strtotime('+1 Month', $ToStartMonth));
            $ToStartMonth = strtotime( $NewMonth );
            $i = true;
            array_push($date_set,substr($NewMonth,0,4).substr($NewMonth,5,6));
        }
        $avg=$this->model_wage->countAvg($date_set,$user_id)['total'];
        $dept=$user_data['dept'];
        $gender=$user_data['gender'];
        $position=$user_data['position'];
        if(strstr($type,'收入')){
            $str="收 入 证 明\r\n";
        }
        elseif(strstr($type,'在职')){
            $str="证          明\r\n";
        }elseif(strstr($type,'现实表现')){
            $str="现 实 表 现 证 明\r\n";
        }elseif(strstr($type,'计生')){
            $str="计 生 证 明\r\n";
        }
        $pdf->SetFont('kozminproregular','B',24);
        $pdf->Write(0,$str,'', 0, 'C', true, 0, false, false, 0);
        $rmb=$this->num_to_rmb($avg);
        switch($type){
            case '收入证明':
                $str="\r\n          兹证明".$username."，身份证号码：".$user_id."为中国联合网络通信有限公司中山市分公司正式员工，自".$date."起为我司工作，现于我单位任职综合部 综合文秘室 综合秘书，其月收入（税前）包括工资、奖金、津贴约".$avg."元（大写：".$rmb."），以上情况属实。此证明仅限于申请贷款之用。\r\n          特此证明！\r\n";
                break;
            case '收入证明（农商银行）':
                $str="\r\n中山农村商业银行股份有限公司：\r\n          兹证明".$username."（身份证号码：".$user_id."）为我单位正式员工，自".$date."起为我单位工作，现于我单位任职".$dept.$position."，其月收入（税前）包括工资、奖金、津贴约".$avg."元（大写：".$rmb."），以上情况属实。此证明仅用于申请贷款之用。\r\n          特此证明！";
                break;
            case '收入证明（公积金）':
                $str="\r\n中山市住房公积金管理中心：\r\n          为申请住房公积金贷款事宜，兹证明".$username."，性别：".$gender."，身份证号 ".$user_id."，是我单位职工，已在我单位工作满".""."年，该职工上一年度在我单位总收入约为".$avg."元（大写：".$rmb."）。\r\n\r\n";
                break;
            case '现实表现证明':
                $str="\r\n          ".$username."（男，身份证号：".$user_id."） 同志自".$date."进入我单位至今，期间一直拥护中国共产党的领导，坚持四项基本原则和党的各项方针政策，深刻学习三个代表重要思想。没有参加“六四”“法轮功”等活动，未发现有任何违法乱纪行为。\r\n          特此证明!\r\n";
                $pdf->SetFont('kozminproregular','',14);
                $pdf->Write(0,$str,'', 0, 'L', true, 0, false, false, 0);
                $str="\r\n\r\n中国联合网络通信有限公司中山市分公司\r\n".date("Y年m月d日");
                $pdf->Write(0,$str,'', 0, 'R', true, 0, false, false, 0);
                break;
            case '在职证明1':
                $str="\r\n            兹有我单位员工".$username."，身份证号：".$user_id."，该员工于".$date."起至今在我公司工作。\r\n            特此证明。\r\n";
                $pdf->SetFont('kozminproregular','',14);
                $pdf->Write(0,$str,'', 0, 'L', true, 0, false, false, 0);
                $str="\r\n\r\n中国联合网络通信有限公司中山市分公司\r\n".date("Y年m月d日");
                $pdf->Write(0,$str,'', 0, 'R', true, 0, false, false, 0);
                break;
            case '在职证明2':
                $str="\r\n          兹有".$username."（".$gender."，身份证号：".$user_id."），为中国联合网络通信有限公司中山市分公司".$position."，现任中国联合网络通信有限公司中山市分公司".$position."。\r\n            特此证明。\r\n\r\n";
                $pdf->SetFont('kozminproregular','',14);
                $pdf->Write(0,$str,'', 0, 'L', true, 0, false, false, 0);
                $str="\r\n\r\n中国联合网络通信有限公司中山市分公司\r\n".date("Y年m月d日");
                $pdf->Write(0,$str,'', 0, 'R', true, 0, false, false, 0);
                break;
            case '在职证明（积分入户1）':
                $str="\r\n          兹有".$username."（性别".$gender."，身份证号：".$user_id."），为中国联合网络通信有限公司中山市分公司".$position."，现任中国联合网络通信有限公司中山市分公司".$position."。\r\n            特此证明。\r\n";
                $pdf->SetFont('kozminproregular','',14);
                $pdf->Write(0,$str,'', 0, 'L', true, 0, false, false, 0);
                $str="\r\n\r\n中国联合网络通信有限公司中山市分公司\r\n".date("Y年m月d日")."\r\n\r\n\r\n\r\n";
                $pdf->Write(0,$str,'', 0, 'R', true, 0, false, false, 0);
                $pdf->setCellHeightRatio(1.5); 
                $pdf->SetFont('kozminproregular', '', 9);
                $str="单位名称：中国联合网络通信有限公司中山市分公司\r\n联系地址：中山市东区长江北路6号联通大厦\r\n联系人：徐小姐           联系电话：0760-23771356";
                $pdf->Write(0,$str,'', 0, 'L', true, 0, false, false, 0);
                break;
            case '在职证明（积分入户2）':
                $str="\r\n          兹有我单位".$username."同志，性别：".$gender."，身份证号码：".$user_id."，于".$date."至今在我单位从事".$position."工作。\r\n单位名称：中国联合网络通信有限公司中山市分公司\r\n          联系地址：中山市东区长江北路6号联通大厦\r\n          联系人：徐小姐        联系电话：0760-23771356\r\n          特此证明。\r\n       （此证明仅用于办理流动人员积分制管理使用）\r\n";
                $pdf->SetFont('kozminproregular','',14);
                $pdf->Write(0,$str,'', 0, 'L', true, 0, false, false, 0);
                $str="\r\n\r\n中国联合网络通信有限公司中山市分公司\r\n".date("Y年m月d日")."\r\n\r\n\r\n\r\n";
                $pdf->Write(0,$str,'', 0, 'R', true, 0, false, false, 0);
                break;
            case '在职证明（居住证）':
                $str="\r\n          兹有".$username."（".$gender."，身份证号：".$user_id."），自".$date."进入我公司工作，现任中国联合网络通信有限公司中山市分公司员工".$position."。\r\n          特此证明。\r\n       （此证明仅用于办理居住证使用）";
                $pdf->SetFont('kozminproregular','',14);
                $pdf->Write(0,$str,'', 0, 'L', true, 0, false, false, 0);
                $str="\r\n\r\n中国联合网络通信有限公司中山市分公司\r\n".date("Y年m月d日")."\r\n\r\n\r\n\r\n";
                $pdf->Write(0,$str,'', 0, 'R', true, 0, false, false, 0);
                $pdf->setCellHeightRatio(1.5); 
                $pdf->SetFont('kozminproregular', '', 9);
                $str="单位名称：中国联合网络通信有限公司中山市分公司\r\n联系地址：中山市东区长江北路6号联通大厦\r\n联系人：徐小姐           联系电话：0760-23771356";
                $pdf->Write(0,$str,'', 0, 'L', true, 0, false, false, 0);
                break;
            case '在职证明（住房补贴）':
                $str="\r\n          ".$username."同志（".$gender."，身份证号码：".$user_id."），".$date."起在我司工作，在职期间未享受过实物分房及建、购房相关补贴，该证明用于申请住房补贴使用。\r\n          特此证明。";
                $pdf->SetFont('kozminproregular','',14);
                $pdf->Write(0,$str,'', 0, 'L', true, 0, false, false, 0);
                $str="\r\n\r\n\r\n中国联合网络通信有限公司中山市分公司\r\n人力资源部\r\n".date("Y年m月d日");
                $pdf->SetFont('kozminproregular','',14);
                $pdf->Write(0,$str,'', 0, 'R', true, 0, false, false, 0);
                break;
            /*
            case '计生证明':
                $str="\r\n          ".$username."（身份证号：".$user_id."），为中国联合网络通信有限公司中山市分公司在编员工，于20xx年xx月与xxx登记结婚，属初婚已育壹孩，没有违反计划生育政策。其在我司工作期间的计划生育工作由我司负责管理。\r\n          特此证明。";
                $pdf->SetFont('kozminproregular','',14);
                $pdf->Write(0,$str,'', 0, 'L', true, 0, false, false, 0);
                $str="\r\n\r\n\r\n中国联合网络通信有限公司中山市分公司\r\n".date("Y年m月d日");
                $pdf->SetFont('kozminproregular','',14);
                $pdf->Write(0,$str,'', 0, 'R', true, 0, false, false, 0);
                break;
            case '子女户口非在注册证明':
                $str="\r\n          兹有".$username."（".$gender."，身份证号：".$user_id."）为我司在编员工，户籍迁入我司集体户统一管理，于20xx年xx月xx日与xxx登记结婚，20xx年xx月xx日育有一女，其女xxx非我司在册集体户口。\r\n          特此证明。";
                $pdf->SetFont('kozminproregular','',14);
                $pdf->Write(0,$str,'', 0, 'L', true, 0, false, false, 0);
                $str="\r\n\r\n\r\n中国联合网络通信有限公司中山市分公司\r\n".date("Y年m月d日");
                $pdf->SetFont('kozminproregular','',14);
                $pdf->Write(0,$str,'', 0, 'R', true, 0, false, false, 0);
                break;
                */
            default:break;
        }

        if(strstr($type,'收入')){
            $pdf->SetFont('kozminproregular','',14);
            $pdf->Write(0,$str,'', 0, 'L', true, 0, false, false, 0);
            $str="\r\n\r\n经办人：\t\t\t\t\t\r\n中国联合网络通信有限公司中山市分公司\r\n人力资源与企业发展部\r\n单位（盖章）\r\n".date('Y年m月d日')."\r\n\r\n\r\n";
            $pdf->setCellHeightRatio(1.7); 
            $pdf->Write(0,$str,'', 0, 'R', true, 0, false, false); 
            $pdf->setCellHeightRatio(1.5); 
            $pdf->SetFont('kozminproregular', '', 9);
            $str="\r\n\r\n联系地址：中山市长江北路6号联通大厦\r\n联系人：甘先生\r\n联系电话：0760-23692312";
            $pdf->Write(0,$str,'', 0, 'L', false, 0, false, false);
        }
        //输出PDF
        $date_name=date('YmdHis');
        $path=dirname(__FILE__,3).'/wageproof/'.$username.'-'.$type.'.pdf';
        $pdf->Output($path, 'F');
        $url='wageproof/'.$username.'-'.$type.'.pdf';
        return $url;
    }
    public function wage_proof(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id=$_POST['id'];
            $data=array(
                'submit_status' => '未提交',
                'feedback_status' => '已审阅',
                'feedback_time' => date('Y-m-d H:i:s')
            );
            $this->model_wage_apply->update($data,$id);
        }
        $this->data['apply_data']=$this->model_wage_apply->getApplyData();
        $temp=array();
        foreach($this->data['apply_data'] as $k => $v){
            if(strstr($v['feedback_status'],'未')){
                $url=$this->proof_Creator($v['user_id'],$v['type']);
                $temp[$k]=$url;
            }
            else{
                $temp[$k]="";
            }
        }
        $this->data['url']=$temp;
        $this->render_super_template('super/wage_proof',$this->data);
    }
    public function wage_tag_excel_put(){
        $this->load->library('phpexcel');//ci框架中引入excel类
        $this->load->library('PHPExcel/IOFactory');
        //先做一个文件上传，保存文件
        $path=$_FILES['file'];
        $filePath = 'uploads/wage_user/'.$path['name'];
        move_uploaded_file($path['tmp_name'],$filePath);
        //根据上传类型做不同处理
        
        if(strstr($_FILES['file']['name'],'xlsx')){
            $reader = new PHPExcel_Reader_Excel2007();
        }
        else{
            if(strstr($_FILES['file']['name'], 'xls')){
                $reader = IOFactory::createReader('Excel5'); //设置以Excel5格式(Excel97-2003工作簿)
            }
        }

        $cellName = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ','BA', 'BB', 'BC', 'BD', 'BE', 'BF', 'BG', 'BH', 'BI', 'BJ', 'BK', 'BL', 'BM', 'BN', 'BO', 'BP', 'BQ', 'BR', 'BS', 'BT', 'BU', 'BV', 'BW', 'BX', 'BY', 'BZ','CA', 'CB', 'CC', 'CD', 'CE', 'CF', 'CG', 'CH', 'CI', 'CJ', 'CK', 'CL', 'CM', 'CN', 'CO', 'CP', 'CQ', 'CR', 'CS', 'CT', 'CU', 'CV', 'CW', 'CX', 'CY', 'CZ','DA', 'DB', 'DC', 'DD', 'DE', 'DF', 'DG', 'DH', 'DI', 'DJ', 'DK', 'DL', 'DM', 'DN', 'DO', 'DP', 'DQ', 'DR', 'DS', 'DT', 'DU', 'DV', 'DW', 'DX', 'DY', 'DZ'); 
        $PHPExcel = $reader->load($filePath, 'utf-8'); // 载入excel文件
        $sheet = $PHPExcel->getSheet(0); // 读取第一個工作表
        $highestRow = $sheet->getHighestRow(); // 取得总行数
        $highestColumm = $sheet->getHighestColumn(); // 取得总列数
    
        $columnCnt = array_search($highestColumm, $cellName); 

        $data = array();
        for($rowIndex = 1; $rowIndex <= $highestRow; $rowIndex++){        //循环读取每个单元格的内容。注意行从1开始，列从A开始
            for($colIndex = 0; $colIndex <= $columnCnt; $colIndex++){
                $cellId = $cellName[$colIndex].$rowIndex;  
                $cell = $sheet->getCell($cellId)->getValue();
                $cell = $sheet->getCell($cellId)->getCalculatedValue();
                if($cell instanceof PHPExcel_RichText){ //富文本转换字符串
                    $cell = $cell->__toString();
                }
                $data[$rowIndex][$colIndex] = $cell;
            }
        }

        $column=array();
        $column_name=array();
        $attribute_data=array();
        $name='';
        $user_id='';
        $gender='';
        $dept='';
        $position='';
        $company='';
        $marry='';
        $child='';
        $highest_qualification='';
        $highest_degree='';
        $ft_highest_degree='';
        $ft_highest_qualification='';
        $service_mode='';
        $indate='';
        $role='';
        $proof_tag='';
        $counter=0;

        $this->model_wage_tag->deleteAll();
        #$this->model_users->deleteAll();
        $this->model_dept->deleteAll();
        $wage_set=array();
        $user_set=array();
        $all_dept=array();
        $dept_set=array();
        foreach($data as $k => $v){
            #$row_data=array();
            if($counter>1){
                $col=0;
                foreach($v as $a=>$b){
                    switch($data[1][$a]){
                        case '员工姓名':$name=$b;break;
                        case '身份证号':$user_id=$b;break;
                        case '性别':$gender=$b;break;
                        case '所在部门':$dept=$b;break;
                        case '科室':$office=$b;break;
                        case '岗位':$position=$b;break;
                        case '合同签订公司':$company=$b;break;
                        case '婚姻情况':$marry=$b;break;
                        case '生育情况':$child=$b;break;
                        case '最高学历':$highest_qualification=$b;break;
                        case '最高学位':$highest_degree=$b;break;
                        case '全日制最高学历':$ft_highest_qualification=$b;break;
                        case '全日制最高学位':$ft_highest_degree=$b;break;
                        case '用工形式':$service_mode=$b;break;
                        case '加入本企业时间':$indate=gmdate('Y-m-d',PHPExcel_Shared_Date::ExcelToPHP($b));break;
                        case '收入证明标识':$proof_tag=$b;break;
                    }
                    if($col==13){
                        $qian3=$b;
                    }
                    if($col==14){
                        $qian2=$b;
                    }
                    if($col==15){
                        $qian1=$b;
                    }
                    $col++;
                }
                //新建用户标识
                $row_data=array(
                    'name' => $name,
                    'user_id' => $user_id,
                    'gender' => $gender,
                    'dept' => $dept,
                    'office' => $office,
                    'position' => $position,
                    'company' => $company,
                    'marry' => $marry,
                    'child' => $child,
                    'highest_qualification' => $highest_qualification,
                    'highest_degree' => $highest_degree,
                    'ft_highest_qualification' => $ft_highest_qualification,
                    'ft_highest_degree' => $ft_highest_degree,
                    'service_mode' => $service_mode,
                    'indate' => $indate,
                    'proof_tag' => $proof_tag,
                    'qian3' => $qian3,
                    'qian2' => $qian2,
                    'qian1' => $qian1
                );
                array_push($wage_set,$row_data);
                unset($row_data);
                //新建登陆用户
                //如果数据库中没有这个用户，那么就创建记录，否则不做任何事
                if(!$this->model_users->checkUserById($user_id)){
                    echo 'new user';
                    #$this->model_users->update();
                    $user_data=array(
                        'username' => $name,
                        'user_id' => $user_id,
                        'password' => md5(substr($user_id,-6)),
                        'permission' => 3,
                    );
                    array_push($user_set,$user_data);
                    unset($user_data);
                }
            }
            $counter++;
        }
        $this->model_wage_tag->createbatch($wage_set);
        $this->model_users->createbatch($user_set);
        
        unset($wage_set);
        unset($user_set);
    }
    public function wage_tag_import($filename=NULL){
        $this->data['path'] = 'uploads/standard/人员信息大表模板.xlsx';
        if($_FILES){
            if($_FILES['file']){
                if($_FILES['file']['error'] > 0){
                    $this->session->set_flashdata('error', '请选择要上传的文件！');
                    $this->render_super_template('super/wage_tag_import',$this->data);
                }
                else{
                    $this->wage_tag_excel_put();
                    $this->reset_pass();
                }
            }
        }
        else{
            $this->render_super_template('super/wage_tag_import',$this->data);
        } 
    }
    public function wage_all_user_put(){
        $this->load->library('phpexcel');//ci框架中引入excel类
        $this->load->library('PHPExcel/IOFactory');
        //先做一个文件上传，保存文件
        $path=$_FILES['file'];
        $filePath = 'uploads/wage_user/'.$path['name'];
        move_uploaded_file($path['tmp_name'],$filePath);
        //根据上传类型做不同处理
        
        if(strstr($_FILES['file']['name'],'xlsx')){
            $reader = new PHPExcel_Reader_Excel2007();
        }
        else{
            if(strstr($_FILES['file']['name'], 'xls')){
                $reader = IOFactory::createReader('Excel5'); //设置以Excel5格式(Excel97-2003工作簿)
            }
        }

        $cellName = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ','BA', 'BB', 'BC', 'BD', 'BE', 'BF', 'BG', 'BH', 'BI', 'BJ', 'BK', 'BL', 'BM', 'BN', 'BO', 'BP', 'BQ', 'BR', 'BS', 'BT', 'BU', 'BV', 'BW', 'BX', 'BY', 'BZ','CA', 'CB', 'CC', 'CD', 'CE', 'CF', 'CG', 'CH', 'CI', 'CJ', 'CK', 'CL', 'CM', 'CN', 'CO', 'CP', 'CQ', 'CR', 'CS', 'CT', 'CU', 'CV', 'CW', 'CX', 'CY', 'CZ','DA', 'DB', 'DC', 'DD', 'DE', 'DF', 'DG', 'DH', 'DI', 'DJ', 'DK', 'DL', 'DM', 'DN', 'DO', 'DP', 'DQ', 'DR', 'DS', 'DT', 'DU', 'DV', 'DW', 'DX', 'DY', 'DZ'); 
        $PHPExcel = $reader->load($filePath, 'utf-8'); // 载入excel文件
        $sheet = $PHPExcel->getSheet(0); // 读取第一個工作表
        $highestRow = $sheet->getHighestRow(); // 取得总行数
        $highestColumm = $sheet->getHighestColumn(); // 取得总列数
    
        $columnCnt = array_search($highestColumm, $cellName); 

        $data = array();
        for($rowIndex = 1; $rowIndex <= $highestRow; $rowIndex++){        //循环读取每个单元格的内容。注意行从1开始，列从A开始
            for($colIndex = 0; $colIndex <= $columnCnt; $colIndex++){
                $cellId = $cellName[$colIndex].$rowIndex;  
                $cell = $sheet->getCell($cellId)->getValue();
                $cell = $sheet->getCell($cellId)->getCalculatedValue();
                if($cell instanceof PHPExcel_RichText){ //富文本转换字符串
                    $cell = $cell->__toString();
                }
                $data[$rowIndex][$colIndex] = $cell;
            }
        }

        $column=array();
        $column_name=array();
        $attribute_data=array();
        
        $user_id='';
        $pwd='';
        $name='';
        $dept='';
        $mobile='';
        $counter=0;
        $this->model_all_user->deleteAll();
        $all_user_set=array();
        $user_set=array();

        foreach($data as $k => $v){
            if($counter>1){
                foreach($v as $a=>$b){
                    switch($data[1][$a]){
                        case 'id_num':$user_id=$b;break;
                        case 'pwd':$pwd=$b;break;
                        case 'department':$dept=$b;break;
                        case 'mobile':$mobile=$b;break;
                        case 'staff_name':$name=$b;break;
                    }
                }
                //新建全用户历史
                $row_data=array(
                    'id_num' => $user_id,
                    'name' => $name,
                    'pwd' => $pwd,
                    'dept' => $dept,
                    'mobile' => $mobile,
                );
                array_push($all_user_set,$row_data);
                unset($row_data);
                //新建登陆用户
                //如果用户数据库中有这个用户，那么就更新该用户的密码，否则不做任何事
                if(!$this->model_users->checkUserById($user_id)){
                    $user_data=array(
                        'user_id' => $user_id,
                        'password' => $pwd,
                    );
                    array_push($user_set,$user_data);
                    unset($user_data);
                }
            }
            $counter++;
        }
        $this->model_all_user->createbatch($all_user_set);
        $this->model_users->updatebatch($user_set);
        unset($all_user_set);
        unset($user_set);
    }
    public function wage_all_user_import($filename=NULL){
        if($_FILES){
            if($_FILES['file']){
                if($_FILES['file']['error'] > 0){
                    $this->session->set_flashdata('error', '请选择要上传的文件！');
                    $this->render_super_template('super/wage_tag_import',$this->data);
                }
                else{
                    $this->wage_all_user_put();
                    $this->reset_pass();
                }
            }
        }
        else{
            $this->render_super_template('super/wage_tag_import',$this->data);
        } 
    }
    public function wage_excel_put($filename){
        $this->load->library('phpexcel');//ci框架中引入excel类
        $this->load->library('PHPExcel/IOFactory');
 
        //先做一个文件上传，保存文件
        $path=$_FILES['file'];
        
        //根据上传类型做不同处理
        if(strstr($_FILES['file']['name'],'xlsx')){
            $reader = new PHPExcel_Reader_Excel2007();
            $filePath = 'uploads/wage/'.$filename.'.xlsx';
            move_uploaded_file($path['tmp_name'],$filePath);
        }
        elseif(strstr($_FILES['file']['name'], 'xls')){
            $reader = IOFactory::createReader('Excel5'); //设置以Excel5格式(Excel97-2003工作簿)
            $filePath = 'uploads/wage/'.$filename.'.xls';
            move_uploaded_file($path['tmp_name'],$filePath);
            
        }
        //薪酬文件记录写入
        //如果有相同时期的文件，直接覆盖记录，如果没有的话，那就创建新文件记录
        if($this->model_wage_record->getRecordByDate($filename)===null){
            $this->model_wage_record->create(array('upload_date' => $filename,'path' => $filePath));    
        }
        else{
            $this->model_wage_record->update(array('path' => $filePath),$filename);
        }
        
        $cellName = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ','BA', 'BB', 'BC', 'BD', 'BE', 'BF', 'BG', 'BH', 'BI', 'BJ', 'BK', 'BL', 'BM', 'BN', 'BO', 'BP', 'BQ', 'BR', 'BS', 'BT', 'BU', 'BV', 'BW', 'BX', 'BY', 'BZ','CA', 'CB', 'CC', 'CD', 'CE', 'CF', 'CG', 'CH', 'CI', 'CJ', 'CK', 'CL', 'CM', 'CN', 'CO', 'CP', 'CQ', 'CR', 'CS', 'CT', 'CU', 'CV', 'CW', 'CX', 'CY', 'CZ','DA', 'DB', 'DC', 'DD', 'DE', 'DF', 'DG', 'DH', 'DI', 'DJ', 'DK', 'DL', 'DM', 'DN', 'DO', 'DP', 'DQ', 'DR', 'DS', 'DT', 'DU', 'DV', 'DW', 'DX', 'DY', 'DZ'); 
        $PHPExcel = $reader->load($filePath, 'utf-8'); // 载入excel文件
        $sheet = $PHPExcel->getSheet(0); // 读取第一個工作表
        $highestRow = $sheet->getHighestRow(); // 取得总行数
        $highestColumm = $sheet->getHighestColumn(); // 取得总列数
        $columnCnt = array_search($highestColumm, $cellName); 

        $data = array();
        $attribute = array();
        $this->model_wage->deleteByDate($filename);
        
        for($rowIndex = 1; $rowIndex <= $highestRow; $rowIndex++){        //循环读取每个单元格的内容。注意行从1开始，列从A开始
            $temp = array();
            for($colIndex = 0; $colIndex <= $columnCnt; $colIndex++){
                $cellId = $cellName[$colIndex].$rowIndex;  
                $cell = $sheet->getCell($cellId)->getValue();
                $cell = $sheet->getCell($cellId)->getCalculatedValue();
                if($cell instanceof PHPExcel_RichText){ //富文本转换字符串
                    $cell = $cell->__toString();
                }
                $temp[$colIndex] = $cell;
            }
            if($rowIndex==3){
                $attr_counter=1;
                foreach($temp as $k => $v){
                    if($v!=''){
                        $attribute['attr_name'.$attr_counter]=$v;
                        $attr_counter++;
                    }
                }
                $attribute['date_tag']=$filename;
                $attr_counter--;
                $this->model_wage_attr->create_total(array('date_tag' => $filename,'total' => $attr_counter));
                if($this->model_wage_attr->getWageAttrDataByDate($filename)){
                    $this->model_wage_attr->update($attribute,$filename);
                }
                else{
                    $this->model_wage_attr->create_attr($attribute);
                }
            }
            if($rowIndex>3){
                $wage=array();
                $counter=0;
                foreach($temp as $k => $v){
                    if($counter==$attr_counter-1){
                        if($v!=''){
                            $wage['content'.($counter-3)]=$v;
                        }
                        else{
                            $wage['content'.($counter-3)]='';
                        }
                        $wage['date_tag']=$filename;
                        break;
                    }
                    if($v!=''){
                        switch($k){
                            case 0:$wage['number']=$v;break;
                            case 1:$wage['department']=$v;break;
                            case 2:$wage['user_id']=$v;break;
                            case 3:$wage['name']=$v;break;
                            default:$wage['content'.($counter-3)]=$v;break;
                        }
                        $counter++;
                    }
                    elseif(strlen($v)==1 and $v==0){
                        $wage['content'.($counter-3)]='0';
                        $counter++;
                    }
                }
                $dept=$wage['department'];
                $wage['total']=$wage['content'.(count($wage)-6)];
                array_push($data,$wage);
                unset($wage);
                //如果不是多部门，不包含/，那么就记录下来
                if(strpos($dept,'/') != true){
                    $dept_data=array(
                        'dept_name' => $dept,
                    );
                    //如果不存在，则创建
                    if(!$this->model_dept->check_dept($dept))
                        $this->model_dept->update($dept_data,$dept);
                }
            }
            unset($temp);
        }
        $this->model_wage->createbatch($data);   
    }
    
    public function wage_import(){
        $this->data['path'] = 'uploads/standard/薪酬导入模板.xlsx';
        if($_SERVER['REQUEST_METHOD'] == 'POST' and array_key_exists('chosen_month',$_POST)){
            $doc_name=substr($_POST['chosen_month'],0,4).substr($_POST['chosen_month'],5,6);
            if(strstr($doc_name,'1899')){
                $this->session->set_flashdata('error', '请选择月份!!');
                $this->render_super_template('super/wage_import',$this->data);
            }
            else{
                if(strlen($doc_name)<=7 and $doc_name!=''){
                    if($_FILES){
                        if($_FILES['file']){
                            if($_FILES['file']['error'] > 0){
                                $this->session->set_flashdata('error', '请选择文件!!');
                                $this->render_super_template('super/wage_import',$this->data);
                            }
                            else{
                                $this->wage_excel_put($doc_name);
                                $this->data['wage_data']='';
                                $this->data['attr_data']='';
                                $this->data['chosen_month']='';
                                $this->render_super_template('super/wage_import_list',$this->data);
                            }
                        }
                    }
                    else{
                        $this->session->set_flashdata('error', '请选择文件!!');
                        $this->render_super_template('super/wage_import',$this->data);
                    }
                }
            }
        }
        else{
            $this->render_super_template('super/wage_import',$this->data);
        }
    }
    public function show_import_list(){
        $date_tag=$this->model_wage_tag->getDatetag();
        $import_list=array();
        #echo var_dump($date_tag);
        foreach($date_tag as $k => $v){
            array_push($import_list,array('name' => $v['date_tag'],'url' => 'uploads/wage/'.$v['date_tag'].'.xlsx'));
        }
        $this->data['import_list']=$import_list;
        $this->render_super_template('super/wage_import_list',$this->data);
    }
    public function wage_temp_put(){
        $this->load->library('phpexcel');//ci框架中引入excel类
        $this->load->library('PHPExcel/IOFactory');
        $path=$_FILES['selected_user'];
        //根据上传类型做不同处理
        if(strstr($_FILES['selected_user']['name'],'xlsx')){
            $reader = new PHPExcel_Reader_Excel2007();
            $filePath = 'uploads/wage/temp.xlsx';
            move_uploaded_file($path['tmp_name'],$filePath);
        }
        elseif(strstr($_FILES['selected_user']['name'], 'xls')){
            $reader = IOFactory::createReader('Excel5'); //设置以Excel5格式(Excel97-2003工作簿)
            $filePath = 'uploads/wage/temp.xlsx';
            move_uploaded_file($path['tmp_name'],$filePath);
        }
        $cellName = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ','BA', 'BB', 'BC', 'BD', 'BE', 'BF', 'BG', 'BH', 'BI', 'BJ', 'BK', 'BL', 'BM', 'BN', 'BO', 'BP', 'BQ', 'BR', 'BS', 'BT', 'BU', 'BV', 'BW', 'BX', 'BY', 'BZ','CA', 'CB', 'CC', 'CD', 'CE', 'CF', 'CG', 'CH', 'CI', 'CJ', 'CK', 'CL', 'CM', 'CN', 'CO', 'CP', 'CQ', 'CR', 'CS', 'CT', 'CU', 'CV', 'CW', 'CX', 'CY', 'CZ','DA', 'DB', 'DC', 'DD', 'DE', 'DF', 'DG', 'DH', 'DI', 'DJ', 'DK', 'DL', 'DM', 'DN', 'DO', 'DP', 'DQ', 'DR', 'DS', 'DT', 'DU', 'DV', 'DW', 'DX', 'DY', 'DZ');  
        $PHPExcel = $reader->load($filePath, 'utf-8'); // 载入excel文件
        $sheet = $PHPExcel->getSheet(0); // 读取第一個工作表
        $highestRow = $sheet->getHighestRow(); // 取得总行数
        $highestColumm = $sheet->getHighestColumn(); // 取得总列数
    
        $columnCnt = array_search($highestColumm, $cellName); 
        $data = array();
        for($rowIndex = 1; $rowIndex <= $highestRow; $rowIndex++){        //循环读取每个单元格的内容。注意行从1开始，列从A开始
            for($colIndex = 0; $colIndex <= $columnCnt; $colIndex++){
                $cellId = $cellName[$colIndex].$rowIndex;  
                $cell = $sheet->getCell($cellId)->getValue();
                $cell = $sheet->getCell($cellId)->getCalculatedValue();
                if($cell instanceof PHPExcel_RichText){ //富文本转换字符串
                    $cell = $cell->__toString();
                }
                $data[$rowIndex][$colIndex] = $cell;
            }
        }
        $result=array();
        foreach($data as $k =>$v){
            if($k!=1){
                $temp=array(
                    'user_id' => $v[0]
                );
                array_push($result,$v[0]);
            }
            #unset($temp);
        }
        return $result;
    }
    public function excel($start_month = null,$end_month = null,$dept = null){
        $this->load->library('PHPExcel');
        $this->load->library('PHPExcel/IOFactory');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setTitle('export')->setDescription('none');
        $objPHPExcel->setActiveSheetIndex(0);
        $start=substr($start_month,0,4).substr($start_month,5,6);
        $end=substr($end_month,0,4).substr($end_month,5,6);
        //没有日期区间，差值为0
        $wage=array();
        $attr=array();
        $wage_set=array();
        $mark=array();
        $mark_set=array();
        $counter=0;
        if($end-$start==0){
            #echo $end-$start;
            $attr=$this->model_wage_attr->getWageAttrDataByDate($start);
            foreach($attr as $k => $v){
                $counter=0;
                foreach($attr as $k => $v){
                    if($v=='月度绩效工资小计'){
                        $mark['yuedustart']=$counter;
                    }
                    if($v=='省核专项奖励小计'){
                        #$mark['yueduend']=$counter-1;
                        $mark['shengzhuanstart']=$counter;
                    }
                    if($v=='分公司专项奖励小计'){
                        #$mark['shengzhuanend']=$counter-1;
                        $mark['fengongsistart']=$counter;
                    }
                    if($v=='其他小计'){
                        #$mark['fengongsiend']=$counter-1;
                        $mark['qitastart']=$counter;
                    }
                    if($v=='教育经费小计'){
                        #$mark['qitaend']=$counter-1;
                        $mark['jiaoyustart']=$counter;
                    }
                    if($v=='福利费小计'){
                        #$mark['jiaoyuend']=$counter-1;
                        $mark['fulistart']=$counter;
                    }
                    if($v=='当月月应收合计'){
                        #$mark['fuliend']=$counter-1;
                        $mark['yingshou']=$counter;
                        $mark['koufeistart']=$counter+1;
                    }
                    if($v=='扣款小计'){
                        $mark['koufeiend']=$counter;
                        break;
                    }
                    $counter++;
                }
            }

            //all,部门无条件,两种情况，一种是全部，一种是导入名单
            if($dept=='all'){
                //无导入名单和有导入名单
                if($_FILES){
                    if($_FILES['selected_user']){
                        if($_FILES['selected_user']['error']==4){
                            //无导入名单
                            //all,该部门全部导出
                            $wage=$this->model_wage->getWageByDate($start);
                        }
                        else{
                            //有导入名单
                            $user_set=$this->wage_temp_put();
                            $wage=$this->model_wage->getWageByDateAndIdset($user_set,$start);
                        }
                    }
                    else{
                        redirect('super_wage/download_page', 'refresh');                        
                    }
                }
                else{
                    redirect('super_wage/download_page', 'refresh');
                }
            }
            //指定部门,没有选用户
            elseif($dept!='all'){
                if($_FILES){
                    if($_FILES['selected_user']){
                        //没有上传文件
                        if($_FILES['selected_user']['error']==4){
                            //指定部门人员
                            $wage=$this->model_wage->getWageByDateAndDept($dept,$start);
                        }
                        //上传了文件
                        //异常处理,如果部门指定，同时有名单
                        else{
                            $this->session->set_flashdata('error', '不能同时指定部门和上传名单');
                            redirect('super_wage/download_page', 'refresh');
                        }
                    }
                    else{
                        redirect('super_wage/download_page', 'refresh');                        
                    }
                }
                else{
                    redirect('super_wage/download_page', 'refresh');
                }
            }
        }
        //有日期区间
        else{
            $ToStartMonth=strtotime($start_month); //转换一下
            $ToEndMonth=strtotime($end_month); //一样转换一下
            $i=false; //开始标示
            while( $ToStartMonth < $ToEndMonth ) {
                $NewMonth = !$i ? date('Y-m', strtotime('+0 Month', $ToStartMonth)) : date('Y-m', strtotime('+1 Month', $ToStartMonth));
                $ToStartMonth = strtotime( $NewMonth );
                $i = true;
                $date_tag=substr($NewMonth,0,4).substr($NewMonth,5,6);
                $attr=$this->model_wage_attr->getWageAttrDataByDate($date_tag);
                foreach($this->model_wage_attr->getWageAttrDataByDate($start) as $k => $v){
                    $counter=0;
                    foreach($attr as $k => $v){
                        if($v=='月度绩效工资小计'){
                            $mark['yuedustart']=$counter;
                        }
                        if($v=='省核专项奖励小计'){
                            #$mark['yueduend']=$counter-1;
                            $mark['shengzhuanstart']=$counter;
                        }
                        if($v=='分公司专项奖励小计'){
                            #$mark['shengzhuanend']=$counter-1;
                            $mark['fengongsistart']=$counter;
                        }
                        if($v=='其他小计'){
                            #$mark['fengongsiend']=$counter-1;
                            $mark['qitastart']=$counter;
                        }
                        if($v=='教育经费小计'){
                            #$mark['qitaend']=$counter-1;
                            $mark['jiaoyustart']=$counter;
                        }
                        if($v=='福利费小计'){
                            #$mark['jiaoyuend']=$counter-1;
                            $mark['fulistart']=$counter;
                        }
                        if($v=='当月月应收合计'){
                            #$mark['fuliend']=$counter-1;
                            $mark['yingshou']=$counter;
                            $mark['koufeistart']=$counter+1;
                        }
                        if($v=='扣款小计'){
                            $mark['koufeiend']=$counter;
                            array_push($mark_set,$mark);
                            break;
                        }
                        $counter++;
                    }
                }
                //all,部门无条件,两种情况，一种是全部，一种是导入名单
                if($dept=='all'){
                    //无导入名单和有导入名单
                    if($_FILES){
                        if($_FILES['selected_user']){
                            if($_FILES['selected_user']['error']==4){
                                //无导入名单
                                //all,该部门全部导出
                                $wage=$this->model_wage->getWageByDate($date_tag);
                            }
                            else{
                                //有导入名单
                                $user_set=$this->wage_temp_put();
                                $wage=$this->model_wage->getWageByDateAndIdset($user_set,$date_tag);
                            }
                        }
                        else{
                            redirect('super_wage/download_page', 'refresh');                        
                        }
                    }
                    else{
                        redirect('super_wage/download_page', 'refresh');
                    }
                }
                //指定部门,没有选用户
                elseif($dept!='all'){
                    if($_FILES){
                        if($_FILES['selected_user']){
                            //没有上传文件
                            if($_FILES['selected_user']['error']==4){
                                //指定部门人员
                                $wage=$this->model_wage->getWageByDateAndDept($dept,$date_tag);
                            }
                            //上传了文件
                            //异常处理,如果部门指定，同时有名单
                            else{
                                $this->session->set_flashdata('error', '不能同时指定部门和上传名单');
                                redirect('super_wage/download_page', 'refresh');
                            }
                        }
                        else{
                            redirect('super_wage/download_page', 'refresh');                        
                        }
                    }
                    else{
                        redirect('super_wage/download_page', 'refresh');
                    }
                }
                $one_month=array(
                    'attr' => $attr,
                    'wage' => $wage
                );
                array_push($wage_set,$one_month);
                unset($one_month);
            }
        }
        
        $row=1;
        $col=0;
        #echo var_dump($mark);
        $counter=0;
        if(empty($wage_set)){
            // Field names in the first row
            $col=0;
            $counter=0;
            foreach($attr as $k => $v){
                //如果该项目不为空，同时不是时间戳，同时
                if($v != '' and $k!='date_tag' and $k!='attr_name1' and in_array($counter,$mark)){
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $v);
                    $col++;
                }
                elseif($counter<$mark['yuedustart']){
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $v);
                    $col++;
                }elseif($k=='date_tag'){
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, '时间戳');
                    $col++;
                }
                
                $counter++;
            }
            $col=0;
            $counter=0;
            $row++;
            foreach($wage as $k => $v){
                foreach($v as $a => $b){
                    if($b != '' and $a!='date_tag' and $a!='number' and in_array($counter,$mark)){
                        if($a=='user_id'){
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, ' '.$b);
                        }
                        else{
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $b);
                        }
                        $col++;
                    }
                    elseif($counter<$mark['yuedustart']){
                        if($a=='user_id'){
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, ' '.$b);
                        }
                        else{
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $b);
                        }
                        $col++;
                    }elseif($a=='date_tag'){
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $b);
                        $col++;
                    }   
                    $counter++;
                }
                $col=0;
                $counter=0;
                $row++;
            }
        }
        else{
            #echo var_dump($wage);
            // Field names in the first row
            foreach($wage_set as $c => $wtemp){
                $col = 0;
                foreach($wtemp['attr'] as $k => $v){
                    if($v != '' and $k!='date_tag' and $k!='attr_name1' and in_array($counter,$mark_set[$c])){
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $v);
                        $col++;    
                    }
                    elseif($counter<$mark_set[$c]['yuedustart']){
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $v);
                        $col++;
                    }
                    elseif($k=='date_tag'){
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, '时间戳');
                        $col++;
                    }
                    $counter++;
                }
                $col=0;
                $counter=0;
                $row++;

                foreach($wtemp['wage'] as $k => $v){
                    foreach($v as $a => $b){
                        if($b != '' and $a!='date_tag' and $a!='number' and in_array($counter,$mark_set[$c])){
                            if($a=='user_id'){
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, ' '.$b);
                            }
                            else{
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $b);
                            }
                            $col++;
                        }
                        elseif($counter<$mark_set[$c]['yuedustart']){
                            if($a=='user_id'){
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, ' '.$b);
                            }
                            
                            else{
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $b);
                            }
                            $col++;
                        }
                        elseif($a=='date_tag'){
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $b);
                            $col++;
                        }
                        $counter++;
                        
                    }
                    $col=0;
                    $counter=0;
                    $row++;
                }
            }
            
        }
        $col=0;
        $objPHPExcel->setActiveSheetIndex(0);
        $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $filename = date('YmdHis').'.xlsx';
        // Sending headers to force the user to download the file
        
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename='.$filename);
        header('Content-Disposition:filename='.$filename);
        header('Cache-Control: max-age=0');
        $objWriter->save('php://output');
    }
    public function download_page(){
        $this->data['path'] = 'uploads/standard/人员导入模板.xlsx';
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            if($_POST['end_month']!='单击选择月份' and !strstr($_POST['end_month'],'1899') and $_POST['start_month']!='单击选择月份' and !strstr($_POST['start_month'],'1899')){
                $this->data['path'] = 'uploads/standard/人员导入模板.xlsx';
                $this->excel($_POST['start_month'],$_POST['end_month'],$_POST['selected_dept']);
                /*
                $this->data['path'] = 'uploads/standard/wage_sample.xlsx';
                $start_month=substr($_POST['start_month'],0,4).substr($_POST['start_month'],5,6);
                $end_month=substr($_POST['end_month'],0,4).substr($_POST['end_month'],5,6);
                $this->excel($start_month,$end_month,$_POST['selected_dept'],$_POST['selected_user']);
                */
                #$this->render_super_template('super/wage_export',$this->data);
            }
            else{
                $this->session->set_flashdata('error', '请选择月份!!');
                redirect('super_wage/download_page', 'refresh');
            }
        }
        else{
            $this->data['user_name'] = $this->session->userdata('user_name');
            $this->data['wage_data']='';
            $this->data['attr_data']='';
            $this->data['chosen_month']='';
            $this->data['dept_set']=$this->model_dept->getDeptData();
            $this->data['user_set']=$this->model_wage_tag->getTagData();
            $this->render_super_template('super/wage_export',$this->data);
        }
    }
    public function wage_doc_put(){
        //先做一个文件上传，保存文件
        $path=$_FILES['file'];
        $filePath = 'uploads/wage_doc/'.$path['name'];
        move_uploaded_file($path['tmp_name'],$filePath);
        $doc_data=array(
            'number' => date('Y-m-d H:i:s'),
            'doc_name' => basename($filePath,'.pdf'),
            'doc_path' => $filePath,
        );
        $this->model_wage_doc->create($doc_data);
    }
    public function wage_doc_import($filename=NULL){
        if($_FILES){
            if($_FILES['file']){
                if($_FILES['file']['error'] > 0){
                    $this->session->set_flashdata('error', '请选择要上传的文件！');
                    $this->render_super_template('super/wage_doc_import',$this->data);
                }
                else{
                    $this->wage_doc_put();
                    $this->wage_doc_list();
                }
            }
        }
        else{
            $this->render_super_template('super/wage_doc_import',$this->data);
        } 
    }

    public function wage_doc_list(){
        $wage_doc=$this->model_wage_doc->getWageDocData();
        $this->data['wage_doc']=$wage_doc;
        $this->render_super_template('super/wage_doc_list',$this->data);
    }
    
    public function wage_doc_delete(){
        $date = $_POST['time'];   
        if($date){
			$delete = $this->model_wage_doc->delete($date);
            if($delete == true){
                $this->session->set_flashdata('success', '薪酬文件删除成功');
                redirect('super_wage/wage_doc_list', 'refresh');
            }
            else{
                $this->session->set_flashdata('error', '系统发生未知错误!!');
                redirect('super_wage/wage_doc_list', 'refresh');
            }	
		}
    }

    /*
    ============================================================
    查看部门综管员和负责人主页
    ============================================================
    */ 
    public function tag(){
        $manager_data = $this->model_wage_tag->getTagData();
		$result = array();
		
		foreach($manager_data as $k => $v){
			$result[$k] = $v;
		}
		$permission_set=array(
			2 => '部门经理',
			3 => '普通员工'
		);

		$this->data['manager_data'] = $result;
		$this->data['permission_set']=$permission_set;
		$this->render_super_template('super/wage_reset_pass', $this->data);
    }
    public function notification(){
        $notice_data = $this->model_notice->getNoticeData();
		$result = array();
		foreach($notice_data as $k => $v){
            if($v['type']=='wage'){
                $v['type']='薪酬';
                $result[$k] = $v;
            }
		}
		$this->data['notice_data'] = $result;
		unset($result);
        $this->render_super_template('super/wage_notification', $this->data);
    }
    public function publish_wage(){
        $this->form_validation->set_rules('title', 'title', 'required');
		$this->form_validation->set_rules('content', 'content', 'required');
		
        if($this->form_validation->run() == TRUE){
            // true case
			$title=$this->input->post('title');
			$content=$this->input->post('content');
        	$data = array(
				'pubtime' => date('Y-m-d H:i:s'),
				'username' => $this->session->userdata('user_id'),
        		'title' => $this->input->post('title'),
				'content' => $this->input->post('content'),
				'type' => 'wage'
			);
			$create = $this->model_notice->create($data);
        	if($create == true){
        		$this->session->set_flashdata('success', '公告发布成功');
        		redirect('super_wage/notification', 'refresh');
        	}
        	else{
        		$this->session->set_flashdata('error', '系统发生未知错误!!');
        		redirect('super_wage/publish_wage', 'refresh');
        	}

        }
        else{
            // false case
			$notice_data = $this->model_notice->getNoticeData();
			$result = array();
			foreach($notice_data as $k => $v){
				$result[$k] = $v;
			}
			$this->data['notice_data'] = $result;
            $this->render_super_template('super/wage_publish_wage', $this->data);
        }
    }
    
    public function tax_counter(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->data['result']=10;
            $this->render_super_template('super/tax_result',$this->data);    
        }
        else{
            $this->render_super_template('super/tax',$this->data);
        }
    }
    public function log_show(){
        $this->data['log']=$this->model_log_action->getLogData();
        #print_r($this->data['log']);
        $this->render_super_template('super/wage_log',$this->data);
    }
    public function reset_pass(){
        if($_SERVER['REQUEST_METHOD'] == 'POST' and array_key_exists('user_id',$_POST)){
            $update=$this->model_users->edit(array('password'=>md5(substr($_POST['user_id'],-6))),$_POST['user_id']);
            if($update == true){
                $this->session->set_flashdata('success', '密码重置成功！');
            }
            else{
                $this->session->set_flashdata('error', '遇到未知错误!!');
            }
        }
        $tag_data=$this->model_wage_tag->getTagData();

        $result=array();
        $tag='';
        foreach($tag_data as $k => $v){
            if($this->model_users->getUserById($v['user_id'])['password']===md5(substr($v['user_id'],-6))){
                $tag='密码为初始密码';
            }
            else{
                $tag='密码已更改';
            }
            array_push($result,array('name'=>$v['name'],'user_id'=>$v['user_id'],'dept'=>$v['dept'],'reset_tag'=>$tag));
        }
        $this->data['user_data'] = $result;
        unset($result);
        $this->render_super_template('super/wage_reset_pass',$this->data);
    }
    public function switch_function(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            if(array_key_exists('func_name_off', $_POST)){
                $name=$_POST['func_name_off'];
                $status='已关闭';
            }
            if(array_key_exists('func_name_on', $_POST)){
                $name=$_POST['func_name_on'];
                $status='已开启';
            }
            $this->model_wage_func->edit(array('name' => $name,'status' => $status),$name);
        }
        $this->data['wage_func']=$this->model_wage_func->getFuncData();
        $this->render_super_template('super/wage_switch_function',$this->data);
    }
}