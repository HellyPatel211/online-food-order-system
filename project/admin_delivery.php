<?php
include 'db.php';

$sql = "select * from delivery_boy";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Manage Users</title>
    <style>
        body {
            background-color: #f7fafc;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        main {
            flex: 1;

        }

        h1 {
            font-size: 2rem;
            margin-bottom: 20px;
            text-align: center;
        }

        table {
            width: 100%;
            background-color: #ffffff;
            border-collapse: collapse;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0, 1);
            border-radius: 5px;
            overflow: hidden;
        }

        th,
        td {
            padding: 10px;
            border: 1px solid #e2e8f0;
            text-align: left;
        }

        th {
            background-color: #edf2f7;
        }

        .text-center {
            text-align: center;
        }

        .text-blue-500 {
            color: #4299e1;
        }

        .text-red-500 {
            color: #e53e3e;
        }

        .btn {
            display: inline-block;
            padding: 5px 10px;
            border: none;
            color: white;
            cursor: pointer;
            border-radius: 5px;
            text-decoration: none;
            margin: 2px;
        }

        .add-btn {
            /* color:black; */
            background-color: green;
            margin-bottom:10px;

        }

        .button {
            text-align:center;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <?php include 'admin_nav.html'; ?>
        <main>
            <div class="container">
                <h1>Manage Delivery persons</h1>
                <div class="button">
                    <a href="add_del_per.php" class="btn add-btn">➕ Add Delivery Person</a>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email ID</th>
                            <th>Password</th>
                            <th>Phone No</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['password']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5' class='text-center'>No Users found</td></tr>";
                        }
                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
        <?php include 'admin_footer.html'; ?>
    </div>
</body>

</html>