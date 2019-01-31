


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        证明审核
      </h1>  
    </section>
    <hr />
    <!-- Main content -->
    <section class="content">
      <div class="col-md-12 col-xs-12">
        <div class="row">
          <div class="box">
            <div class="box-header">
              <h3>证明审核</h3>
            </div>
            <div class="box-body"> 
              <table id="proofTable" class="table table-bordered table-striped" style="overflow:scroll;text-align:center;">
                <thead>
                  <tr>
                    <!--<th>编号</th>-->
                    <th>申请日期</th>
                    <th>申请人</th>
                    <th>申请类型</th>
                    <th>提交状态</th>
                    <th>审核状态</th>
                    <th>操作</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $counter=1;?>
                  <?php foreach($apply_data as $k => $v):?>
                  
                  <tr>
                    <!--<td><?php echo $counter++;?></td>-->
                    <td><?php echo $v['submit_time'];?></td>
                    <td><?php echo $v['name'];?></td>
                    <td><?php echo $v['type'];?></td>
                    <td><?php echo $v['submit_status'];?></td>
                    <td><?php echo $v['feedback_status'];?></td>
                    <td>
                      <?php if(strstr($v['feedback_status'],'已')):?>
                      <a disabled href="javascript:void(0)" class="btn btn-success" data-toggle="modal" data-target="#myModal<?php echo $k;?>"><i class="fa fa-check-circle"></i></a>
                      <?php else:?>
                      <a href="javascript:void(0)" class="btn btn-success" data-toggle="modal" data-target="#myModal<?php echo $k;?>"><i class="fa fa-check-circle"></i></a>
                      <div class="modal-apply fade" tabindex="-1" data-backdrop="false" role="dialog" id="myModal<?php echo $k;?>">
                        <div class="modal-content-apply">
                          <div class="modal-header">
                            <h4>请确认</h4>
                          </div>
                          <div class="modal-body">
                            <iframe width="600" height="700" src="<?php echo base_url($url[$k]);?>"></iframe>
                          </div>
                          <div class="modal-footer">
                            <form action="<?php echo base_url('super_wage/wage_proof');?>" method="post">
                            <input name="id" type="hidden" value="<?php echo $v['id'];?>" />
                            <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                            <button type="submit" class="btn btn-success btn-ok">确认审核</a>
                            </form>
                          </div>
                        </div><!-- /.modal-content -->
                      </div><!-- /.modal -->
                      <?php endif;?>
                    </td>
                  </tr>
                  <?php endforeach;?>
                  
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
  
  <script type="text/javascript">
    $(document).ready(function() {
      $("#wageProofMainMenu").addClass('active');
      $('#proofTable').DataTable({
        language: {
            "sProcessing": "处理中...",
            "sLengthMenu": "显示 _MENU_ 项",
            "sZeroRecords": "没有匹配结果",
            "sInfo": "显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项",
            "sInfoEmpty": "显示第 0 至 0 项结果，共 0 项",
            "sInfoFiltered": "(由 _MAX_ 项结果过滤)",
            "sInfoPostFix": "",
            "sSearch": "搜索:",
            "sUrl": "",
            "sEmptyTable": "表中数据为空",
            "sLoadingRecords": "载入中...",
            "sInfoThousands": ",",
            "oPaginate": {
                "sFirst": "首页",
                "sPrevious": "上页",
                "sNext": "下页",
                "sLast": "末页"
            },
            "oAria": {
                "sSortAscending": ": 以升序排列此列",
                "sSortDescending": ": 以降序排列此列"
            }
        },
        "order":[[0,"desc"]],   
      });
    });
    
  </script>