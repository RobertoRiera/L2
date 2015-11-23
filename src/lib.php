<?php
class View{
    public static function  start($title){
        $html = "<!DOCTYPE html>
<html>
<head>
<meta charset=\"ISO-8859-1\">
<link rel=\"stylesheet\" type=\"text/css\" href=\"estilos.css\">
<script type=\"text/javascript\" src=\"scripts.js\"></script>
<title>$title</title>
</head>
<body>";
        User::session_start();
        echo $html;
    }
    public static function  start2($title){
        $html = "<!DOCTYPE html>
<html>
<head>
</head>
<body>";
        User::session_start();
        echo $html;
    }

    public static function end(){
        echo '</body>
</html>';
    }


}
class Util{

    public static function showForm(){

        echo '<form method="post" onsubmit="return validarPass()">';
        echo 'Nombre <input id="nombre" type="text" name="usuario">\n';
        echo 'Forma de contacto <input id="clave" type="text" name="clave">';
        echo 'Forma de contacto <input id="clave" type="text" name="clave">';
        echo 'Servicio a realizar <input id="clave" type="text" name="clave">';
        echo'Servicio a realizar?
        <select name="service">
            <option value="Void">Select...</option>
            <option value="Localizaci�n de Software">Localizaci�n de Software</option>
            <option value="Consultor�a en adaptaci�n cultural">Consultor�a en adaptaci�n cultural</option>
            <option value="Consultor�a en adaptaci�n cultural">Consultor�a web multiling�e</option>
            <option value="Consultor�a en adaptaci�n cultural">Consultor�a web multiling�e</option>
        </select>';
        echo '<input type="submit" value="Entrar">';
        echo '</form>';

        echo'<form method="post" action="upload.php" enctype="multipart/form-data">
            �Sube tu foto!: <input type="file" name="foto" /> <input type="submit" name="enviar" value="Enviar" />
      </form>';

    }

    public static function txt($txt){

        $file = fopen("prueba.txt", "w") or die("Problemas");
        fwrite($file, $txt);
        fclose($file);

    }

}


class DB{

    private static $connection=null;

    public static function get(){
        if(self::$connection === null){
            self::$connection = $db = new PDO("sqlite:./datos.db");
            self::$connection->exec('PRAGMA foreign_keys = ON;');
        }
        return self::$connection;
    }
}
class User{

    public static function session_start(){
        if(session_status () === PHP_SESSION_NONE){
            session_name('PR4_PAR1');
            session_start();
        }
    }

    public static function getLoggedUser(){ //Devuelve un array con los datos del usuario o false
        self::session_start();
        if(!isset($_SESSION['user'])) return false;
        return $_SESSION['user'];
    }
    public static function login($user,$pass){ //Devuelve verdadero o falso seg�n
        self::session_start();
        $db=DB::get();
        $inst=$db->prepare('SELECT * FROM operadores WHERE usuario=? and clave=?');
        $inst->execute(array($user,md5($pass)));
        $inst->setFetchMode(PDO::FETCH_NAMED);
        $res=$inst->fetchAll();
        if(count($res)==1){
            $_SESSION['user']=$res[0];
            return true;
        }
        return false;
    }

    public static function getUserByName($nameOrPhone){
        self::session_start();
        $db=DB::get();
        $inst=$db->prepare("SELECT nombre, id, telefono FROM clientes");
        $inst->execute();
        $inst->setFetchMode(PDO::FETCH_NUM);
        $result=$inst->fetchAll();
        echo "<table class='tabla'>";

        for($i=0;$i<sizeof($result)-1; $i++){
            echo "<tr>";
            if($nameOrPhone==$result[$i][0] || $nameOrPhone==$result[$i][2]){
                $_POST['users'][$i]=$result[$i];

                for($j=0;$j<sizeof($result)-1; $j++){
                    echo "<td class='tabla'>";
                    echo $result[$i][$j];
                    echo "</td>";
                }

                echo "<td>" ;
                echo "<form action='queja.php' method='POST'>
                <input type='hidden' name='clienteSeleccionado' value=".$result[$i][1]." />
                <input type='submit' value='A�adir Queja' />
                </form>
                </td>
                <td>
                <form action='PreviousAnswers.php' method='POST'>
                <input type='hidden' name='clientSelected' value=".$result[$i][1]." />
                <input type='submit' value='Ver atenciones previas' />
                </form>";
                echo "</td>";
            }
            echo "</tr>";
        }
        echo '</table>';
    }

    public static function getUserById($id){
        //self::session_start();
        $db=DB::get();
        $inst=$db->prepare("SELECT nombre, id, telefono FROM clientes");
        $inst->execute();
        $inst->setFetchMode(PDO::FETCH_NUM);
        $result=$inst->fetchAll();
        echo "<table class='tabla'>";

        for($i=0;$i<sizeof($result)-1; $i++){
            echo "<tr>";
            if($id==$result[$i][1]){
                $_POST['users'][$i]=$result[$i];

                for($j=0;$j<sizeof($result)-1; $j++){
                    echo '<td class="tabla">';
                    echo $result[$i][$j];
                    echo "</td>";
                }

            }
            echo "</tr>";
        }
        echo '</table>';
    }

    public static function getComplainAnswerForm(){

        echo "<form action='insertdb.php' id='AnswerQuestion' method='POST'>
            <input type='text' class='AnswerQuestion' name='Question' value='Escriba aqu� la pregunta o queja' maxlength='300'>
            <input type='text' class='AnswerQuestion' name='Answer' value='Escriba aqu� la respuesta' maxlength='300'>
            <input type='hidden' name='clienteSeleccionado' value=".$_POST['clienteSeleccionado']." />            
            <input type='submit' value='Enviar'>
        </form>";
    }

    public static function getPreviousAnswers(){
        $db=DB::get();
        $inst=$db->prepare("SELECT hora, cliente, operador, peticion, respuesta FROM atenciones");
        $inst->execute();
        $inst->setFetchMode(PDO::FETCH_NUM);
        $result=$inst->fetchAll();
        echo "<table class='tabla'>";

        for($i=0;$i<sizeof($result); $i++){
            echo "<tr>";
            if($_POST['clientSelected']==$result[$i][1]){

                for($j=0;$j<sizeof($result[$i]); $j++){
                    echo '<td class="tabla">';
                    echo $result[$i][$j];
                    echo "</td>";
                }

            }
            echo "</tr>";
        }
        echo '</table>';
    }


}