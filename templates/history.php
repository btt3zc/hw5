<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">  
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="your name">
        <meta name="description" content="include some description about your page">  
        <title>Wordle Game</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous"> 
    </head>
    <body>
        <div class="container" style="margin-top: 15px;">
            <div class="row col-xs-8">
                <h1>CS4640 Finance Controller</h1>
                <h3>Hello <?=$_SESSION["name"]?>! Email:<?=$_SESSION["email"]?>    <a href="?command=logout" class="btn btn-danger">logout</a>   <a href="?command=replay" class="btn btn-danger">Add new transaction</a>   </h3>
            </div>
            <div class="row">
                <div class="col-xs-8 mx-auto">
                <form action="?command=question" method="post">
                    <div class="h-100 p-5 bg-light border rounded-3">

                        <?php

                            echo "<table border='1'>

                                 <tr>
                            
                                <th>Name</th>
                            
                                <th>Category</th>
                            
                                <th>Date</th>
                            
                                <th>Amount</th>
                                <th>Type</th>
                            
                            </tr>";
                            $numbers = array(); 
                            for ($i = 0; $i < count($_SESSION["history"]); $i++) {
                                echo "<tr> "; 
                                    echo "<td>".$_SESSION["history"][$i]["Name"]."</td>"; 
			                        echo "<td>".$_SESSION["history"][$i]["Category"]."</td>"; 
                                    echo "<td>".$_SESSION["history"][$i]["t_date"]."</td>"; 
                                    echo "<td>".$_SESSION["history"][$i]["amount"]."</td>"; 
                                    echo "<td>". $_SESSION["history"][$i]["Type"] ."</td>"; 
                                echo "</tr>";
                            }

                            $current_balance = 0; 
                            for ($i = 0; $i < count($_SESSION["history"]); $i++) {
                                if(isset($numbers[$_SESSION["history"][$i]["Category"]])) {
                                    array_push($numbers[$_SESSION["history"][$i]["Category"]],$_SESSION["history"][$i]["amount"]);
                                } else{
                                    $numbers[$_SESSION["history"][$i]["Category"]] = array($_SESSION["history"][$i]["amount"]);
                                }

                                
                            }

                            

                            foreach ($numbers as $key => $val) {
                                $numbers[$key] = array_sum($val); 
                                $current_balance += array_sum($val);
                            }
                            foreach ($numbers as $key => $val) {
                                echo "<tr> "; 
                                    $string  = "sum of $key: $val ";
                                    echo "<td>".$string."</td>"; 
                                echo "</tr>";
                            }

                            echo "<tr> "; 
                                $str = "Balance:$current_balance";
                                echo "<td>".$str."</td>";
                            echo "</tr>";
                            
                            
                           

                             

                        ?>
                    </div>
                                       
                    <div class="text-center">                
                    </div>
                </form>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
    </body>
</html>