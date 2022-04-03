<?php
// 
class FinanceController {

    private $command;

    public function __construct($command) {
        $this->command = $command;
        $this->db = new Database();
    }

    public function run() {
        switch($this->command) {
            case "question":
                // $this->resetVariables();
                $this->question();
                break;
            case "logout":
                $this->destroyCookies();
                header("Location: ?command=login");
            case "replay":
                //$this->resetVariables();
                header("Location: ?command=question");
            case "gameover":
                $this->gameover();
                break;
            case "login":
            default:
                $this->login();
                break;
        }
    }

    // Clear all the cookies that we've set
    private function destroyCookies() {          
        session_destroy(); 
    }
    
    private function resetVariables() {          
        if (isset($_SESSION["guess"])) { 
            unset($_SESSION["target_word"]);
            unset($_SESSION["guess"]);
            unset($_SESSION["guess_length"]);
            unset($_SESSION["letters_in_word"]);
            unset($_SESSION["correct_letter"]);
        }
    }

    // Display the login page (and handle login logic)


private function login() {
    $this->db->query("
        CREATE TABLE IF NOT EXISTS USER(
        id int not null AUTO_INCREMENT, 
        email text not null, 
        name text not null, 
        password text not null, 
        primary key(id)); "); 

    
    if (isset($_POST["email"])) {
        $data = $this->db->query("select * from user where email = ?;", "s", $_POST["email"]);
        if ($data === false) {
            $error_msg = "Error checking for user";
        } else if (!empty($data)) {
            if (password_verify($_POST["password"], $data[0]["password"])) {
                $_SESSION["email"] = $_POST["email"]; 
                $_SESSION["name"] = $_POST["name"]; 
                $_SESSION["password"] = $_POST["password"];
                header("Location: ?command=question");
            } else {
                $error_msg = "Wrong password";
            }
        } else { // empty, no user found
            // TODO: input validation
            // Note: never store clear-text passwords in the database
            //       PHP provides password_hash() and password_verify()
            //       to provide password verification
            $insert = $this->db->query("insert into user (email, name, password) values (?, ?, ?);", 
                    "sss", $_POST["email"], $_POST["name"], 
                    password_hash($_POST["password"], PASSWORD_DEFAULT));
            if ($insert === false) {
                $error_msg = "Error inserting user";
            } else {
                $_SESSION["email"] = $_POST["email"]; 
                $_SESSION["name"] = $_POST["name"]; 
                $_SESSION["password"] = $_POST["password"];
                header("Location: ?command=question");
            }
        }
    }
    include("templates/login.php");
}

    public function transaction() {

    }

    public function database() {
        $servername = "localhost";
        $username = "username";
        $password = "password";

    }




    // Load a question from the API
    private function loadQuestion() {
        
        $file = file("https://www.cs.virginia.edu/~jh2jf/courses/cs4640/spring2022/wordlist.txt",true);
        $wCount = count($file);
        // Return the question
        
        if (isset($_SESSION["target_word"]) == False) {
            $word =  trim($file[rand(0, $wCount - 1)]); 
            $_SESSION["target_word"] = $word;

            //$question = $_SESSION["target_word"];
        }
        return $_SESSION["target_word"];

    }
    //works
    private function addGuess() {
        if(!isset($_SESSION["guess"])) {
            $_SESSION["guess"] = array(); 
        }

        //print_r($_SESSION["guess"]); 
    }


    private function addLength() {
        if(!isset($_SESSION["guess_length"])) {
            $_SESSION["guess_length"] = array(); 
        
        }
    }


    private function in_word() {
        if(!isset($_SESSION["letters_in_word"])) {
            $_SESSION["letters_in_word"] = array(); 
        }
    }

    private function correct() {
        if(!isset($_SESSION["correct_letter"])) {
            $_SESSION["correct_letter"] = array(); 
        }
    }



    private function CheckWord($q,$a,$incrementi,$incrementj) {
        strcasecmp($q[$incrementj],$a[$incrementi]); 
            if (strcasecmp($q[$incrementj],$a[$incrementi]) == 0) {
                return 1; // in the word, somewhere
            } else {
                return 2; // not in word
            }
                        
    }


    // Display the question template (and handle question logic)
    public function question() {
        // set user information for the page from the cookie


        $this->db->query("
        CREATE TABLE IF NOT EXISTS hw5_transaction(
            id int not null AUTO_INCREMENT, 
            Name text not null, 
            Category text not null, 
            t_date date not Null, 
            amount decimal(10,2) not null, 
            Type text not null, 
            primary key(id)); ");
            
            
            if (isset($_POST["Transaction_Name"])) {
                //echo ($_POST["Amount"]); 
                if(($_POST["Type"] == "Debit" && $_POST["Amount"] > 0) || ($_POST["Type"] == "Credit"  && $_POST["Amount"] < 0  )  ) {
                    $error_msg = "Debit needs to be less than or equal to 0 and credit needs to more than or equal to 0";
                } else { // empty, no user found
                    // TODO: input validation
                    // Note: never store clear-text passwords in the database
                    //       PHP provides password_hash() and password_verify()
                    //       to provide password verification

                    $insert = $this->db->query("insert into hw5_transaction (Name, Category, t_date,amount,Type) values (?, ?, ?,?,?);", 
                            "sssss", $_POST["Transaction_Name"], $_POST["Category"], $_POST["Date"], $_POST["Amount"], $_POST["Type"]
                            );
                    if ($insert === false) {
                        $error_msg = "Error inserting user";
                    } else {
                        echo $_POST["Transaction_Name"];
                        $_SESSION["Transaction_Name"] = $_POST["Transaction_Name"]; 
                        $_SESSION["Category"] = $_POST["Category"]; 
                        $_SESSION["Date"] = $_POST["Date"]; 
                        $_SESSION["Amount"] = $_POST["Amount"]; 
                        $_SESSION["Type"] = $_POST["Type"]; 
                        header("Location: ?command=gameover");
                    }
                }
        
            

        //include("templates/transaction.php");
        }
        include("templates/transaction.php");
        
    }
    

    public function gameover() {


       // echo $name; 
        $name = $_SESSION["Transaction_Name"]; 
        //echo $name; 
        $query = "select * from hw5_transaction where Name ='$name' order by t_date desc;"; 
        $this->db->query($query);
        $_SESSION["history"]  = $this->db->query($query);
        include("templates/history.php");
    }
}
