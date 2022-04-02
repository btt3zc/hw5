<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">  
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="Jason Nguyen" content="CS4640">
        <meta name="description" content="CS4640 Wordle Login Page">  
        <title>Wordle Game Login</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous"> 
    </head>
    <body>
        <div class="container" style="margin-top: 15px;">
            <div class="row col-xs-8">
                <h1>Please Submit Transaction</h1>
                <p> Welcome to our Wordle game!  To get started, enter a username and password.</p>
            </div>
            <div class="row justify-content-center">
                <div class="col-4">
                <?php
                    if (!empty($error_msg)) {
                        echo "<div class='alert alert-danger'>$error_msg</div>";
                    }
                ?>
                <form action="?command=question" method="post">
                    <div class="mb-3">
                        <label for="Name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="Transaction_Name" name="Transaction_Name"/>
                    </div>
                    <div class="mb-3">
                        <label for="Category" class="form-label">Category</label>
                        <input type="text" class="form-control" id="Category" name="Category"/>
                    </div>

                    <div class="mb-3">
                        <label for="Date" class="form-label">Date</label>
                        <input type="text" class="form-control" id="Date" name="Date"/>
                    </div>

                    <div class="mb-3">
                        <label for="Amount" class="form-label">Amount</label>
                        <input type="text" class="form-control" id="Amount" name="Amount"/>
                    </div>

                    

                    <select name="Type" id="Type">
                        <option value="Debit">Debit</option>
                        <option value="Credit">Credit</option>
                    </select> Type <br>

                    <div class="text-center">                
                    <button type="submit" class="btn btn-primary">Submit</button>                  
                    </div>
                </form>
                

                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
    </body>
</html>