<?php
$pdo =new PDO('mysql:host=localhost;dbname=formulairephp;charset=utf8','root','');

if(isset($_POST['forminscription']))
{
    if(!empty($_POST['Prenom']) && !empty($_POST['Nom']) && !empty($_POST['address_mail']) && !empty($_POST['password']) && !empty($_FILES))
    {
         /*reçois le nom*/
        $file_name = $_FILES['avatar']['name'];
       /*renvoie le type de fichier*/
             $file_extension = strrchr($file_name,".");
        /*image temporaire*/
        $file_tmp_name = $_FILES['avatar']['tmp_name'];
        /*emplacement du fichier*/
        $file_dest= 'img/'.$file_name;
        /*tableau d'extension durant l'upload*/
        $extension_autoriser= array('.jpeg', '.jpg','.png','.gif','.webp','.svg','.pdf'); /*!warning les fichier .ai et .psd marche pas*/
        
        if(in_array($file_extension, $extension_autoriser)){

            if(move_uploaded_file($file_tmp_name, $file_dest)){

                echo 'Fichier envoyé avec succés';
            }else{
                echo"Une erreur est survenu lors de l'envoi du fichier";
            }
        
        }else{
            echo 'seulement les fichiers avec les extensions jpeg, jpg, png, gif, webp sont autorisées';
        }
        $Prenom = strip_tags(trim($_POST['Prenom']));
        /*preg_match  Effectue une recherche de correspondance avec une expression rationnelle standard*/
        if(!preg_match("/^[a-zA-Z-']*$/", $Prenom)){
            echo 'le prenom de doit contenir que des lettre majuscule ou minuscule';
        }
        $Nom = strip_tags(trim($_POST['Nom']));
        if(!preg_match("/^[a-zA-Z-']*$/", $Nom )){
            echo 'le nom de doit contenir que des lettre majuscule ou minuscule';
        }
        $address_mail = strip_tags(trim($_POST['address_mail']));
        $password =sha1(trim($_POST['password']));
        $reqmail=$pdo->prepare("SELECT*FROM inscription WHERE address_mail = ?");
        $reqmail->execute(array($address_mail));
        /*if (filter_var($user_mail, FILTER_VALIDATE_EMAIL))*/
        $mailexist = $reqmail->rowCount();
        if($mailexist == 0)
        {
             $request=$pdo->prepare("INSERT INTO inscription(Prenom,Nom,address_mail,password,avatar) VALUES (?,?,?,?,?)");
        $request->execute(array($Prenom, $Nom, $address_mail, $password,$file_dest));
        $erreur= "Votre compte a bien été crée !";
        }
       else
       {
           $erreur = "Adresse mail déjà utilisés !"; 
       }
    }
    else
    {
        $erreur = "Tous les champs doivent être remplis !";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
</head>
<body>
   <div align="center">
    <form action="" method="post" enctype="multipart/form-data">
    <table>
    <tr>
       <td>
          <label for="Prenom">Prenom</label>
       </td>
       <td><input type="text" name="Prenom" />
       </td>
    </tr>
    <tr>  
      <td>
         <label for="Nom">Nom :</label>
     </td>
     <td>
         <input type="text" name="Nom" />
     </td>
    </tr>
     <td>
         <label for="address_mail">address_mail :</label>
     </td>
     <td>
         <input type="email" name="address_mail" />
     </td>
    </tr>
    <tr>
     <td>
         <label for="password">password :</label>
     </td>
     <td>
         <input type="password" name="password" />
     </td>
    </tr>
         <input type="hidden" name="MAX_FILE_SIZE" value="24209756" />
    <tr>
     <td>
         <label for="avatar">avatar :</label>
     </td>
     <td>
         <input type="file" name="avatar" />
     </td>
    </tr>
    <tr>
     <td>
         <input type="submit" name="forminscription" value="envoyer" />
     </td>
    </tr>
    </table>
    </form>
 <?php
    if(isset($erreur))
    {
        echo '<font color="red">'.$erreur."</font>";
    }
 ?>
   </div>
</body>
</html>
