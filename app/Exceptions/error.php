<html>
<head>
    <title>Exception <?= $this->exception->getCode() ?></title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin-top: 20px;
        }
    </style>
</head>
<body class="container">

<div class="alert alert-danger" role="alert">
    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
    <span class="sr-only">Message:</span>
    <?= $this->exception->getMessage(); ?>
</div>

<?php if (count($this->trace) > 0): ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            Error Trace
        </div>

        <div class="panel-body">
            <?php

            echo '<pre>';
            foreach ($this->trace as $index => $trace) {
                echo ($index + 1) . ') ' . $trace['file'] . ' -- ' . $trace['class'] . $trace['type'] . $trace['function'];
                echo '<br>';
            }
            echo '</pre>';

            ?>
        </div>

    </div>
<?php endif; ?>
</body>
</html>