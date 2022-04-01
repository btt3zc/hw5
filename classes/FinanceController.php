<?php
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
                $this->resetVariables();
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
    public function login() {
        if (isset($_POST["email"]) && !empty($_POST["email"])) { /// validate the email coming in
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
            
        }
        } else {
            $insert = $this->db->query("insert into user (name, email, password) values (?, ?, ?);", 
                        "sss", $_POST["name"], $_POST["email"], 
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
        $user = [
            "name" => $_SESSION["name"],
            "email" => $_SESSION["email"],
        ];

        // load the word
        //if (isset($question) == False) {
        //    $this->loadQuestion();
        //    $question = $_SESSION["target_word"];
       // }
        $question = $this->loadQuestion();
        $this->addGuess();
        $this->addLength(); 
        $this->correct(); 
        $this->in_word();
        $l_in_word = 0;
        $correct_letters = 0;
        if (isset($_POST["answer"])) { 
            array_push($_SESSION["guess"],$_POST["answer"]);
            if(strcasecmp($question,$_POST["answer"])  == 0){
                header("Location: ?command=gameover");
            }

            elseif(strlen($question) == strlen($_POST["answer"]) ) {
                // lengths are the same
                array_push($_SESSION["guess_length"], "correct word length");
                
                for($i = 0; $i < strlen($_POST["answer"]);  $i++) { 
                    // case for same letters
                    //strpos($_POST["answer"][$i], $question[$i])
                    if(strcasecmp($question[$i],$_POST["answer"][$i])  == 0 ) {
                        $correct_letters += 1;
                    }
                    //case for in word 
                    else {
                        for($j = 0; $j < strlen($question);  $j++) {
                            if ($this->CheckWord($question,$_POST["answer"], $i,$j) == 1) { 
                                $l_in_word += 1;
                                break;
                            } 
                            
                        }
                        //echo $in_word; 
                    
                        //echo  $_POST["answer"][$i]; 
                        
                    }
                }
                array_push($_SESSION["letters_in_word"],$l_in_word);
                array_push($_SESSION["correct_letter"],$correct_letters);
            } else {
                //lengths are not the same
                $length_1 = strlen($question); 
                $length_2 = strlen($_POST["answer"]); 
                if($length_1 >  $length_2 ) {
                    array_push($_SESSION["guess_length"], "too short");
                    $shortest = $length_2; 
                } else {
                    $shortest = $length_1; 
                    array_push($_SESSION["guess_length"], "too long");
                }
                for($i = 0; $i < $shortest;  $i++) { 
                    // case for same letters
                    //strpos($_POST["answer"][$i], $question[$i])
                    if(  strcasecmp($question[$i],$_POST["answer"][$i])  == 0 ) {
                        $correct_letters += 1;
                    }
                    //case for in word 
                    else {
                        for($j = 0; $j < strlen($question);  $j++) {
                            if ($this->CheckWord($question,$_POST["answer"], $i,$j) == 1) {
                                $l_in_word += 1;
                                break;
                            } 
                        }
                        //echo $in_word; 
                    
                        //echo  $_POST["answer"][$i]; 
                        
                    }
                }
                array_push($_SESSION["letters_in_word"],$l_in_word);
                array_push($_SESSION["correct_letter"],$correct_letters);
            }

        }

        // if the user submitted an answer, check it

        include("templates/question.php");
    }

    public function gameover() {
        
        include("templates/GameOver.php");
    }
}