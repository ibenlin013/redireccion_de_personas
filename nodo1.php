<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de administración</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .arrow {
            width: 50px;
            height: 50px;
        }
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
        .arrow-container {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <table>
            <h1>nodo 1 </h1>
            <thead>
                <tr>
                    <th>Move in</th>
                    <th>In Time</th>
                    <th>Move Out</th>
                    <th>Out Time</th>
                </tr>
            </thead>
            <tbody>
                <?php
                function connectionDB()
                {
                    $host = "localhost:3306";
                    $dbName = 'counting_db';
                    $user = 'ismael';
                    $pass = 'ismael';
                    $hostDB = 'mysql:host=' . $host . ';dbname=' . $dbName . ';';

                    try {
                        $connection = new PDO($hostDB, $user, $pass);
                        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        return $connection;
                    } catch (PDOException $e) {
                        die('ERROR: ' . $e->getMessage());
                    }
                }

                $conexion = connectionDB();

                // Obtener fecha de hoy
                $hoy = date('Y-m-d');

                // Consulta para obtener los registros del día de hoy
                $consulta = "
                    SELECT * FROM logs
                    WHERE DATE(in_time) = :hoy
                    order by move_in desc
                    
                ";
                
                $select = $conexion->prepare($consulta);
                $select->execute([':hoy' => $hoy]);

                $resultado = $select->fetchAll(PDO::FETCH_ASSOC);
                //variables para calcular el total
                $dentro=0;
                $fuera=0;
                $total=0;
                // Iterar sobre los resultados y mostrar en la tabla
                foreach ($resultado as $fila) {
                    echo "<tr>";
                    if ($fila['move_in'== null]){
                        echo "<td>no entrada</td>";
                    }else{
                        echo "<td>{$fila['move_in']}</td>";
                        $dentro +=$fila['move_in'];
                    }
                    if ($fila['in_time']== null){
                        echo "<td>no entrada</td>";
                    }else{
                        echo "<td>{$fila['in_time']}</td>";
                    }
                    if ($fila['move_out']== null){
                        echo "<td>no entrada</td>";
                    }else{
                        echo "<td>{$fila['move_out']}</td>";
                        $fuera += $fila['move_out'];
                    }
                    if ($fila['out_time']== null){
                        echo "<td>no entrada</td>";
                    }else{
                        echo "<td>{$fila['out_time']}</td>";
                    }
                    echo "</tr>";
                    $total= $dentro-$fuera;
                    
                    
                                    
                }
                
               
                echo $total;
                if ($total > 10){
                    echo '<img src="./flecha_izquierda.jpg">';
                }else{
                    echo '<img src="./flecha_derecha.jpg">';
                }
                
                ?>
            </tbody>
        </table>

        <div class="arrow-container">
        </div>
    </div>
</body>

</html>
