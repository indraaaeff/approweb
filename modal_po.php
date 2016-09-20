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
}
</script>
</head>

<!-- <body onload="showUser(this.value)"> -->
  <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" id="modal_detail" onload="showUser(this.value)">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header" style="padding:10px">
          <div class="row">
              <form>
            <div class="col-md-12 col-sm-12 col-xs-12">
              <label for="select" style="color:white;">Nomor PO :</label>
                <select id="list-po" class="form-control" name="po" onChange="showUser(this.value)">
                  <?php
                  while ($row = $PPO_TableDetail->fetch_assoc()) {
                    $no_po = $row['no_po'];
                                          
                        $po_approve_by_rt   = $row['approve_by_rt'];
                        $po_tgl_approved_rt = $row['tgl_approved_rt'];    
                        $po_comment_rt      = $row['comment_rt'];   

                        $po_approve_by_hp   = $row['approve_by_hp'];
                        $po_tgl_approved_hp = $row['tgl_approved_hp'];    
                        $po_comment_hp      = $row['comment_hp'];   

                        $po_approve_by_dl   = $row['approve_by_dl'];
                        $po_tgl_approved_dl = $row['tgl_approved_dl'];    
                        $po_comment_dl      = $row['comment_dl'];
                        
                    ?>
                    <option id="<?php echo $no_po; ?>" value="<?php echo $no_po; ?>"><?php echo $no_po; ?></option>
                    <?php
                  }
                  $PPO_TableDetail->data_seek(0);
                  ?>
                </select>
              <button type="button" class="btn btn-default modalclosebtn" data-dismiss="modal">X</button>
            </div>
              </form>
            <div class="col-md-6 col-sm-12">
            </div>            
          </div>
        </div>
        <div class="modal-body" style="padding:5px 15px 5px 15px;">
              <div class="row" id="txtHint">
                <!-- di isi tabel detail -->
              </div>
        </div>
      </div>
    </div>
  </div>