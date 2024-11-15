<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<style>

    ul{
        background-color: black;
        list-style-type: none;
        padding:0;
        margin:0;
        overflow: hidden;

    }

    li{
        float:left;
    }

    li a, .dropbtn{
        display: inline-block;
        color:white;
        padding:14px 16px;
        text-decoration: none;
        text-align: center;
    }

    li a:hover, .dropdown:hover .dropbtn{
        background-color: red;
    }

    li.dropdown{
        display: inline-block;
    }

    .dropdown-content{
        display: none;
        position:absolute;
        background-color: white;
        width:150px;
    }

    .dropdown-content a{
        color: black;
        display: block;
        text-align: left;
        text-decoration: none;
        border:1px solid black;
    }

    .dropdown:hover .dropdown-content{
        display: block;
    }


</style>


<body>
    <h1>Teste</h1>

    <ul>
        <li><a href=#">Link</a></li>
        <li><a href=#">Link</a></li>
        <li><a href=#">Link</a></li>
        <li class="dropdown">
            <a class="dropbtn" href=#">Link</a>
            <div class="dropdown-content">
                <a href="#">Link</a>
                <a href="#">Link</a>
                <a href="#">Link</a>
                <a href="#">Link</a>
            </div>
        </li>
    </ul>
</body>
</html>