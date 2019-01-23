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
   <h1 align="center">Temporary Withdarwn from Pusthaka Library </h1>
   <br />
    <br />
    <div class="row">
     
     <div class="col-md-4">
<!--       <input type="button" name="search" id="search" value="Search" class="btn btn-info" />
 -->    

 <form method="post" action="export1.php">
        <input align="right"  type="submit" name="export" value="Download" class="btn btn-info" />
        

      </form>


  </div>
    </div>
    <br />
    <table id="order_data" class="table table-bordered table-striped" >
     <thead>
      <tr>
      
       <th>Book id</th>       
              <th>Copy id</th>
       <th>Access id</th>
              <th>Book Title</th>
                            <th> Authors</th>

              <th> Barcode</th>

              <th>ISBN</th>
                            <th>Publisher</th>
 <th> Language</th>




       

       
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
    url:"fetch4.php",
    type:"POST"
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
  }
 }); 
 
});
</script>