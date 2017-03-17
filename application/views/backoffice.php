<html>
<head>
    <meta charset="UTF-8">
    <title>CVMaps.lt</title>
</head>
<body>
<h2>Mano skelbimai</h2>
<table>
    <?php foreach ($jobs as $item):?>
    <tr>
        <td>
            <b><a href=""><?php echo $item['title']; ?></a></b>
            <p><?php echo $item['address']; ?></p>
            <code></code>
            <span></span>
        </td>
        <td></td>
        <td></td>
        <td><a href="">edit</td>

    </tr>
    <?php endforeach; ?>
</table>
</body>
</html>

<?php
/**
 * Created by PhpStorm.
 * User: Sergej
 * Date: 2017.02.01
 * Time: 12:24
 */