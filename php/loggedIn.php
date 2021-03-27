<?php

if (isset($_POST['purchased'])) {
    $myRequest = "UPDATE `products` SET `purchased` = !`purchased` WHERE `id` = " . $_POST['purchased'];
    if ($myResult = mysqli_query($myConnection, $myRequest)) {
        $fatal = "Purchased update successful.";
    } else {
        $fatal = "Purchased update fail.";
    }
} elseif (isset($_POST['del'])) {
    $myRequest = "DELETE FROM `products` WHERE `id` = " . $_POST['del'];
    if ($myResult = mysqli_query($myConnection, $myRequest)) {
        $fatal = "Deletion successful.";
    } else {
        $fatal = "Deletion fail.";
    }
} elseif (isset($_POST['addProduct'])) {
    if ($_POST['addProduct'] != "") {
        $belongs_to = $_SESSION['id'];
        $myRequest = "INSERT INTO `products` (`product`, `belongs_to`) VALUES ('" . $_POST['addProduct'] . "', '" . $belongs_to . "')";
        if ($myResult = mysqli_query($myConnection, $myRequest)) {
            $fatal = "Added " . $_POST['addProduct'] . " to the grocery list.";
        } else {
            $fatal = "Addition fail.";
        }
    } else {
        $fatal = "Please submit a non-empty product.";
    }
} elseif (isset($_POST['editProduct']) && isset($_POST['modify'])) {
    if ($_POST['editProduct'] != "") {
        $myRequest = "UPDATE `products` SET `product` = '" . $_POST['editProduct'] . "' WHERE `products`.`id` =" . $_POST['modify'];
        if ($myResult = mysqli_query($myConnection, $myRequest)) {
            $fatal = "Modified " . $_POST['editProduct'] . ".";
        } else {
            $fatal = "Modification fail.";
        }
    } else {
        $fatal = "Please submit a non-empty value.";
    }
}

$tableContent = "";

function createTable($myResult)
{
    global $tableContent;
    while ($currentResult = mysqli_fetch_array($myResult)) {
        $checked = "";
        if ($currentResult['purchased'] == true) {
            $checked = " checked ";
        }
        $hiddenBox = "<input hidden type='checkbox' name='purchased' value='" . $currentResult[0] . "' checked>";

        $tableContent .= "<tr><td class='myTd'>" . $currentResult['product'] . "</td><td><form method='post'>" . $hiddenBox . "<input type='checkbox' onchange='submit()'" . $checked . "></form></td>";

        $tableContent .= "<td class='d-flex justify-content-center'><button class='btn btn-danger mr-1 modify d-none d-md-block' name='modif'>Modify</button><form method='post'><button type='submit' class='btn btn-danger' name='del' value='" . $currentResult[0] . "'>DELETE</button></form></td></tr>";
    }
}

$myRequest = "SELECT * FROM `products` INNER JOIN `users` ON `products`.`belongs_to` = `users`.`id` WHERE `users`.`username` = '" . $username . "'";
if ($myResult = mysqli_query($myConnection, $myRequest)) {
    createTable($myResult);
} else {
    $fatal = "DB request failed.<br>";
}

?>

<nav class="navbar navbar-expand-lg navbar-light bg-light justify-content-between">
    <a class="navbar-brand" href=".">Karot</a>
    <ul class="navbar-nav">
        <li class="nav-item">
            <form method="post">
                <button type="submit" class="btn btn-danger" name="signOut">Sign Out</button>
            </form>
        </li>
    </ul>
</nav>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-6 whiteBox">
            <div class="text-center">
                <h1>Logged in as <?php echo $username ?></h1>
            </div>
            <div class="d-flex justify-content-center text-center">
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Already purchased</th>
                            <th>Modify/Remove</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php echo $tableContent ?>
                    </tbody>
                </table>
            </div>
            <div>
                <form method="post" class="form-inline justify-content-center py-4">
                    <div class="form-group">
                        <label for="addProduct" class="mx-2">Product</label>
                        <input type="text" name="addProduct" id="addProduct" class="form-control" autofocus required>
                    </div>
                    <input type="submit" class="btn btn-primary ml-2" value="Add">
                </form>
            </div>
            <div class="text-center">
                <p><?php echo $fatal ?></p>
            </div>
        </div>
    </div>
</div>