

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Products</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <form action="<?php echo base_url('holiday/import') ?>" method="post"
            name="frmExcelImport" id="frmExcelImport" enctype="multipart/form-data">
            <div>
                <label>选择上传文件</label> 
                <br />
                <br />
                <h5><input type="file" name="file" id="file" accept=".xls,.xlsx"/></h5>
                <br />
                <button type="submit" id="submit" name="import" class="btn btn-warning" >Import</button>
        
            </div>
        
        </form>
    </div>  

  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
