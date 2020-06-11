<?php

include_once 'connectdb.php';

session_start();
include_once 'header.php';
function fill_product($pdo){
    $output = '';

    $select = $pdo->prepare("select * from tb_product order by pname asc");
    $select->execute();

    $result = $select->fetchAll();

    foreach ($result as $row) {
        $output .= '<option value="' . $row["pid"] . '">' . $row["pname"] . '</option>';
    }
    return $output;
}


if (isset($_POST['saveOrderBtn'])) {

    $customer_name = $_POST['customer'];
    $order_date = date('Y-m-d', strtotime($_POST['orderDate']));
    $subtotal = $_POST["subtotal"];
    $tax = $_POST['txttax'];
    $discount = $_POST['txtdiscount'];
    $total = $_POST['txttotal'];
    $paid = $_POST['txtpaid'];
    $due = $_POST['txtdue'];
    $payment_type = $_POST['paymentType'];
    // ==========================
    $arr_productid = $_POST['productid'];
    $arr_productname = $_POST['productname'];
    $arr_stock = $_POST['stock'];
    $arr_qty = $_POST['qty'];
    $arr_price = $_POST['price'];
    $arr_total = $_POST['total'];

    $insert = $pdo->prepare("insert into tb_invoice(customer_name,order_date,subtotal,tax,discount,total,paid,due,payment_type) 
                    values(:cust,:orderdate,:stotal,:tax,:disc,:total,:paid,:due,:ptype)");

    $insert->bindParam(':cust', $customer_name);
    $insert->bindParam(':orderdate', $order_date);
    $insert->bindParam(':stotal', $subtotal);
    $insert->bindParam(':tax', $tax);
    $insert->bindParam(':disc', $discount);
    $insert->bindParam(':total', $total);
    $insert->bindParam(':paid', $paid);
    $insert->bindParam(':due', $due);
    $insert->bindParam(':ptype', $payment_type);

    $insert->execute();
    //2nd  insert query for tb_invoice_details

    $invoice_id = $pdo->lastInsertId(); // 找最後一組ID （有可能前面的ID被刪掉，順序會是跳來跳去的）
    if ($invoice_id != null) {

        for ($i = 0; $i < count($arr_productid); $i++) {
            $rem_qty = $arr_stock[$i] - $arr_qty[$i]; // 剩餘的庫存數量(扣除訂單數量後)

            if ($rem_qty < 0) {
                echo'<script type="text/javascript">
                    jQuery(function validation(){
                        swal({
                        title: "注意!",
                        text: "訂單尚未完成!!",
                        icon: "error",
                        button: "Ok",
                        });
                    });
                    </script>';
                header("createorder.php");
            } else {
                $update = $pdo->prepare("update tb_product SET pstock ='$rem_qty' where pid='" . $arr_productid[$i] . "'");
                $update->execute();
            }
            $insert = $pdo->prepare("insert into tb_invoice_details(invoice_id,product_id,product_name,qty,price,order_date) 
            values(:invid,:pid,:pname,:qty,:price,:orderdate)");

            $insert->bindParam(':invid', $invoice_id);
            $insert->bindParam(':pid', $arr_productid[$i]);
            $insert->bindParam(':pname', $arr_productname[$i]);
            $insert->bindParam(':qty', $arr_qty[$i]);
            $insert->bindParam(':price', $arr_price[$i]);
            $insert->bindParam(':orderdate', $order_date);

            $insert->execute();
        }
        echo'<script type="text/javascript">
                    jQuery(function validation(){
                        swal({
                        title: "Great!",
                        text: "成功加入訂單!!",
                        icon: "success",
                        button: "Ok",
                        });
                    });
                    </script>';
        //  echo"success fully created order";    
        header('location:orderlist.php');
    }
}
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            建立訂單
            <small></small>
        </h1>
        
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
        <div class="box box-warning">
            <form action="" method="post" name="">

                <div class="box-header with-border">
                    <h3 class="box-title"> 新訂單 </h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <div class="box-body">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>客戶名稱</label>

                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-user"></i>
                                </div>
                                <input type="text" class="form-control" name="customer" placeholder="輸入客戶名稱" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">

                        <div class="form-group">
                            <label>日期:</label>

                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control pull-right" id="datepicker" name="orderDate" value="<?php echo date("Y-m-d"); ?>" data-date-format="yyyy-mm-dd">
                            </div>
                            <!-- /.input group -->
                        </div>
                    </div>
                </div> <!-- this is for customer and date -->
                <div class="box-body">
                    <div class="col-md-12">
                        <div style="overflow-x:auto;">
                            <table class="table table-bordered" id="productTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>搜尋商品</th>
                                        <th>庫存</th>
                                        <th>售價</th>
                                        <th>數量</th>
                                        <th>總計</th>
                                        <th>
                                            <center> <button type="button" name="add" class="btn btn-success btn-sm addBtn"><span class="glyphicon glyphicon-plus"></span></button></center>
                                        </th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div><!-- this for table -->
                <div class="box-body">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>小計</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-usd"></i>
                                </div>
                                <input type="text" class="form-control" name="subtotal" id="txtsubtotal" required readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>稅 (5%)</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-usd"></i>
                                </div>
                                <input type="text" class="form-control" name="txttax" id="txttax" required readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>折扣</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-usd"></i>
                                </div>
                                <input type="number" class="form-control" name="txtdiscount" id="txtdiscount" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>總計</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-usd"></i>
                                </div>
                                <input type="text" class="form-control" name="txttotal" id="txttotal" required readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>付款金額</label>

                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-usd"></i>
                                </div>

                                <input type="number" class="form-control" name="txtpaid" id="txtpaid" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>欠款</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-usd"></i>
                                </div>
                                <input type="text" class="form-control" name="txtdue" id="txtdue" required readonly>
                            </div>
                        </div>
                        <!-- radio -->
                        <label>付款方式</label>
                        <div class="form-group">

                            <label>
                                <input type="radio" name="paymentType" class="minimal-red" value="Cash" checked> 現金
                            </label>
                            <label>
                                <input type="radio" name="paymentType" class="minimal-red" value="Card"> 信用卡
                            </label>
                            <label>
                                <input type="radio" name="paymentType" class="minimal-red" value="Check">
                                支票
                            </label>
                        </div>
                    </div>
                </div><!-- tax dis. etc -->
                <hr>
                <div align="center">
                    <input type="submit" name="saveOrderBtn" value="儲存" class="btn btn-info">
                </div>

                <hr>

            </form>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
    //Date picker
    $('#datepicker').datepicker({
        autoclose: true
    });
    //Red color scheme for iCheck
    $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
        checkboxClass: 'icheckbox_minimal-red',
        radioClass: 'iradio_minimal-red'
    })
    $(document).ready(function() {
        $(document).on('click', '.addBtn', function() {
            let html = '';
            html += '<tr>';

            html += '<td><input type="hidden" class="form-control pname" name="productname[]" readonly></td>';

            html += '<td><select class="form-control productid" name="productid[]" style="width: 250px";><option value="">請選擇商品</option><?php echo fill_product($pdo); ?> </select></td>';

            html += '<td><input type="text" class="form-control stock" name="stock[]" readonly></td>';
            html += '<td><input type="text" class="form-control price" name="price[]" readonly></td>';
            html += '<td><input type="number" min="1" class="form-control qty" name="qty[]" ></td>';
            html += '<td><input type="text" class="form-control total" name="total[]" readonly></td>';
            html += '<td><center><button type="button" name="remove" class="btn btn-danger btn-sm removeBtn"><span class="glyphicon glyphicon-remove"></span></button><center></td></center>';

            $('#productTable').append(html);
            //Initialize Select2 Elements
            $('.productid').select2()

            $(".productid").on('change', function(e) {
                const productid = this.value;
                let tr = $(this).parent().parent();
                $.ajax({
                    url: "getproduct.php",
                    method: "get",
                    data: {
                        id: productid
                    },
                    success: function(data) {
                        // console.log(data);  // 取得商品資料
                        // 渲染商品資料
                        tr.find(".pname").val(data["pname"]);
                        tr.find(".stock").val(data["pstock"]);
                        tr.find(".price").val(data["saleprice"]);
                        tr.find(".qty").val(1); // 數量
                        // 計算總價值
                        tr.find(".total").val(tr.find(".qty").val() * tr.find(".price").val());
                        calculate(0, 0);
                    }
                })
            })
        }) // addBtn end here    
        $(document).on('click', '.removeBtn', function() {
            $(this).closest('tr').remove();
            calculate(0, 0);
            $("#txtpaid").val(0);

        }) // removeBtn end here  
        $("#productTable").on("keyup change", ".qty",  function() {
            let quantity = $(this);
            let tr = $(this).parent().parent();

            if ((quantity.val() - 0) > (tr.find(".stock").val() - 0)) {
                swal("警告!!", "抱歉，此數量已經超過庫存上限", "warning");
                quantity.val(1);

                tr.find(".total").val(quantity.val() * tr.find(".price").val());
                calculate(0, 0);
            } else {
                tr.find(".total").val(quantity.val() * tr.find(".price").val());
                calculate(0, 0);
            }
        })
        function calculate(dis, paid) {

            let subtotal = 0; // 小計
            let tax = 0; 
            let discount = dis;
            let net_total = 0;
            let paid_amt = paid; // 付款額
            let due = 0;
            $(".total").each(function() {
                subtotal += ($(this).val() * 1);
            })

            tax = 0.05 * subtotal;
            net_total = tax + subtotal; //50+1000 =1050
            net_total -= discount;
            due = net_total - paid_amt;
            $("#txtsubtotal").val(subtotal.toFixed(2));
            $("#txttax").val(tax.toFixed(2));
            $("#txttotal").val(net_total.toFixed(2));
            $("#txtdiscount").val(discount);
            $("#txtdue").val(due.toFixed(2));
        } // function calculate end here 

        $("#txtdiscount").keyup(function() {
            const discount = $(this).val();
            calculate(discount, 0);
        })

        $("#txtpaid").keyup(function() {
            const paid = $(this).val();
            const discount = $("#txtdiscount").val();
            calculate(discount, paid);
        })
    });
</script>
<?php include_once 'footer.php'; ?>