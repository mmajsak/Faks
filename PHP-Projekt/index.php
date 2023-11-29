<?php
	
	define('__APP__', TRUE);
    session_start();
	include ("dbconn.php");
	
    if(isset($_GET['menu'])) 
        { 
            $menu   = (int)$_GET['menu']; 
        }

    if(!isset($_POST['_action_']))  
        { 
            $_POST['_action_'] = FALSE;
        }
	
	if (!isset($menu)) 
        { 
            $menu = 1; 
        };
	
print '
<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" href="style2.css">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="content-type" content="text/html">
        <meta name="descrtiption" content="">
        <meta name="keywords" content="">
        <meta name="author" content="Matija Majsak">
    </head>

    <body>
        <header>

            <div class="Naslovna-slika"></div>

            <nav>';
                include("menu.php");
                print '
            </nav>

        </header>

        <main>';

        if (isset($_SESSION['message'])) 
            {
                print $_SESSION['message'];
                unset($_SESSION['message']);
            }


        if (!isset($menu) || $menu == 1) { include("home.php"); }
        else if ($menu == 2) { include("news.php"); }
        else if ($menu == 3) { include("contact.php"); }
        else if ($menu == 4) { include("grid.php"); }
        else if ($menu == 5) { include("register.php"); }
        else if ($menu == 6) { include("signin.php"); }
        
        print '
        </main>

        <footer>
            <span>Copyright &copy; ' . date("Y") . ' Matija Majsak. </span> 
            <a href="https://github.com/mmajsak/Faks/tree/main" target="_blank"> <img src="Slike/github.png" alt="github" title="github" style="width: 40px"></a>
        </footer>
    
    </body>
</html>';

?>
