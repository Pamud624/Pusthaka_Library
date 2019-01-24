<?php
  $allow = "ADMIN;LIBSTAFF";
  $PageTitle = "Circulation";
  include('../inc/init.php');

  ?>
 <?php include("../inc/top.php"); ?>



<html>
 <head>
  <title>Date Range Search in Datatables using PHP Ajax</title>
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
  <script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.js"></script>
  <style>
   body
   {
    margin:0;
    padding:0;
    background-color:#f1f1f1;
   }
   .box
   {
    width:1270px;
    padding:20px;
    background-color:#fff;
    border:1px solid #ccc;
    border-radius:5px;
    margin-top:25px;
   }
  </style>
  

 </head>
 <body>
  <div class="container box" style="border: solid black 2px">
   <h1 align="center">Inventory Missing Report </h1>
   <br />
   <div class="table-responsive">
    <br />
    <div class="row">
     <div class="col-md-4">
      
            <form method="post" action="export5.php">
        <input align="right"  type="submit" name="export" value="Download" class="btn btn-info" />
        

      </form>
     </div>
     
    </div>
    <br />
    <table id="order_data" class="table table-bordered table-striped">
     <thead>
      <tr>
       <th>Copy id</th>
       <th>Book id</th>
              <th>Access No</th>
              <th>Barcode</th>

              <th>Availability</th>
               <th>Copy status</th>
                            <th>Title</th>
                            <th>Author</th>
       <th>Edition</th>
              <th>Publisher</th>
              <th>Published_year</th>

              <th>IBSN</th>
                           



       

       
        <!-- <th>Return</th> -->

       


      </tr>
     </thead>
    </table>
    
   </div>
  </div>
 </body>
</html>



<script type="text/javascript" language="javascript" >
$(document).ready(function(){
 
 $('.input-daterange').datepicker({
  todayBtn:'linked',
  format: "yyyy-mm-dd",
  autoclose: true
 });

 fetch_data('no');

 function fetch_data(is_date_search, start_date='', end_date='')
 {
  var dataTable = $('#order_data').DataTable({
   "processing" : true,
   "serverSide" : true,
   "order" : [],
   "ajax" : {
    url:"fetch8.php",
    type:"POST",
    data:{
     is_date_search:is_date_search, start_date:start_date, end_date:end_date
    }
   }
  });
 }

 $('#search').click(function(){
  var start_date = $('#start_date').val();
  var end_date = $('#end_date').val();
  if(start_date != '' && end_date !='')
  {
   $('#order_data').DataTable().destroy();
   fetch_data('yes', start_date, end_date);
  }
  else
  {
   alert("Both Date is Required");

   //ggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggg
  }
 }); 
 
});
</script>