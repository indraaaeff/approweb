<!DOCTYPE html>
<!-- <html> -->
<head>
<script>
function showUser(str) {
  if (str=="") {
    document.getElementById("txtHint").innerHTML="";
    return;
  } 
  // alert(this.id);
  if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  } else { // code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function() {
    if (this.readyState==4 && this.status==200) {
      document.getElementById("txtHint").innerHTML=this.responseText;
    }
  }
  xmlhttp.open("GET","detail_tabel.php?q="+str,true);
  xmlhttp.send();
  // $("select#list-po").val(str);
  // alert(str);
  // $("select:nth-child(1)").val(str);
  // $("#list-po").val($("#target option:first").val(str));
}
</script>
</head>

<!-- <body onload="showUser(this.value)"> -->
  <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" id="modal_detail" onload="showUser(this.value)">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <div class="row">
            <div class="col-md-6 col-sm-12">
              <h4>Details PO</h4>
            </div>
            <div class="col-md-6 col-sm-12">
              <form>
                <select id="list-po" name="po" onChange="showUser(this.value)" style="float:right;">
                  <?php
                  while ($row = $PPO_TableDetail->fetch_assoc()) {
                    $no_po = $row['no_po'];
                    ?>
                    <option id="<?php echo $no_po; ?>" value="<?php echo $no_po; ?>"><?php echo $no_po; ?></option>
                    <?php
                  }
                  $PPO_TableDetail->data_seek(0);
                  ?>
                </select>
              </form>
            </div>            
          </div>
        </div>
        <div class="modal-body">
              <div class="row" id="txtHint">
                <!-- di isi tabel detail -->
              </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

<!-- </body> -->
<!-- </html> -->