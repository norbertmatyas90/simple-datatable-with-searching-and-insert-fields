<?php
require_once 'dbsettings.php';
?>

<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>search field</title>
    <link rel="stylesheet" href="css/index.css">
</head>

<body>
    <header>
        <div class="row header">
            <h1>simple search field</h1>
        </div>
    </header>

    <main>

        <div class="row search-field">
            <form action="" method="POST">
                <label for="search">
                    <input type="text" name="search" id="search" placeholder="Search">
                </label>
                <button type="submit" name="submit">Search</button>
            </form>
        </div>
        <div class="row search-table">
            <table>

           <?php
               if(isset($_POST['search'])){
            ?>
                <tr>
                    <th>Name</th>
                    <th>Country</th>
                    <th>City</th>
                    <th>Department</th>
                </tr>
            <?php
               if(strlen($_POST['search']) < 3){
                  print('a minimum of three chacarcters are requred to search');
                   
               }else{
                  $search = mysqli_real_escape_string($conn, $_POST['search']);
                  $query = "SELECT * FROM employees JOIN department ON employees.department=department.id  
                                                    WHERE full_name LIKE '%$search%' 
                                                    OR country LIKE '%$search%'
                                                    OR city LIKE '%$search%'
                                                    OR department.department LIKE '%$search%' ";
                                                                                    
                    $result = mysqli_query($conn, $query);

                    $queryResult = mysqli_num_rows($result);

                    if($queryResult > 0) {
                        while($row = mysqli_fetch_assoc($result)){
            ?>

                <tr>
                    <td><?php print($row['full_name']); ?></td>
                    <td><?php print($row['country']); ?></td>
                    <td><?php print($row['city']); ?></td>
                    <td><?php print($row['department']); ?></td>
                </tr>

            <?php
                        }
                    mysqli_free_result($result);
                 }else{
                    print('There is no result found!');
                        
                        }
                    }
                }
            ?>

            </table>
        </div>

        <div class="row data-table">
            <table>
                <tr>
                    <th>Name</th>
                    <th>Country</th>
                    <th>City</th>
                    <th>Department</th>
                    <th>Delete</th>
                </tr>

                <?php
                    if(isset($_POST['f_name']) && !empty($_POST['f_name']) && !empty($_POST['country'])
                        && !empty($_POST['city'])){
                     
                        $name = mysqli_real_escape_string($conn, $_POST['f_name']);
                        $country = mysqli_real_escape_string($conn, $_POST['country']);
                        $city = mysqli_real_escape_string($conn, $_POST['city']);
                        $department = (int)$_POST['department'];

                        $query = "INSERT INTO employees(full_name, country, city, department)
                                    VALUES('$name','$country','$city','$department')";
         
                                mysqli_query($conn, $query);
                        }
                    

                    if(isset($_GET['id'])){
                       
                        mysqli_query($conn, 'UPDATE employees SET valid = 0
                                             WHERE employees.id = '.$_GET['id']);
                    }

                    if($result = mysqli_query($conn, 'SELECT employees.id, full_name, country, 
                                                city, department.department
                                        FROM employees
                                        JOIN department ON employees.department=department.id 
                                        WHERE valid = 1
                                        ORDER BY employees.id ASC'))
                    {

                        while($row = mysqli_fetch_assoc($result)){
                ?>
                
                <tr>
                    <td><?php print($row['full_name']);?></td>
                    <td><?php print($row['country']); ?></td>
                    <td><?php print($row['city']);?></td>
                    <td><?php print($row['department']);?></td>
                    <td>
                        <button><a href="http://localhost/search_field/root/table/?id=<?php print($row['id']);?>">Delete</a></button>
                    </td>
                </tr>
                
                <?php
                        } 

                    mysqli_free_result($result);  

                        }else{
                            print('problem with the query: '.mysqli_errno($conn). ' ' . mysqli_error($conn));
                            }
                ?>

            </table>
        </div>
        <div class="row input-form">
            <form action="" method="POST">
                <label for="f_name"><input type="text" name="f_name" placeholder="Name"></label>
                <label for="country"><input type="text" name="country" placeholder="Country"></label>
                <label for="city"><input type="text" name="city" placeholder="City"></label>
                <label for="department">
                    <select name="department" id="department">
                       
                <?php
                    if($result = mysqli_query($conn, 'SELECT id, department
                                                        FROM department')){

                        while($row = mysqli_fetch_assoc($result)){
                            print('<option value="'.$row['id'].'">'.$row['department'].'</option>');
                        }

                        mysqli_free_result($result);

                    }else{
                        print(mysqli_errno($result). ' problem with the query: '. mysqli_error($result));
                        }
                ?>

                    </select>
                </label>
                <button type="submit" name="insert">Insert</button>
            </form>
        </div>
    </main>
    <?php
         mysqli_close($conn);
    ?>
</body>

</html>
