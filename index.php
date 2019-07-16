<?php

    //define variables and set to empty values
    $startErr = $endErr = "";
    $start = $end = "";
    // initially the number of a days will be set to null 
    $number_of_days = null;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        /* Validations */ 

        // Check whether the start date is empty. if not store the post value in $start variable
        if (empty($_POST["start"])) {
            $startErr = "Please enter a start date";
        } else {
            $start = $_POST['start'];
        }
        // Check whether the end date is empty. if not store the post value in $end variable
        if (empty($_POST["end"])) {
            $endErr = "Please enter an end date";
        } else {
            $end = $_POST['end'];
        }

        // If there are no validation errors run functions.
        if(empty($startErr) && empty($endErr)){
            //$message = 'Form submitted!';
            $number_of_days = findDateDiff($start, $end);
        }

    }

    /**
     * Find the complete number of days between two datetime parameters
     * @param string $start
     * @param string $end
     * @return integer
     */
    function findDateDiff($start, $end)
    {
        /**
         *   The strtotime() function parses an English textual datetime into a Unix timestamp 
         *   (the number of seconds since January 1 1970 00:00:00 GMT). 
        */
        $datediff = (strtotime($start) - strtotime($end));
        // 1 day = 24 hours 
        // 24 * 60 * 60 = 86400 seconds 
        $number_of_days = abs($datediff / (60 * 60 * 24)); // gets the positive value
        $number_of_days =  floor($number_of_days); // gets the complete number of days
        return $number_of_days;
    }

?>
<head>
    <link href="https://unpkg.com/bootstrap-material-design@4.1.1/dist/css/bootstrap-material-design.min.css" integrity="sha384-wXznGJNEXNG1NFsbm0ugrLFMQPWswR3lds2VeinahP8N0zJw9VWSopbjv2x7WCvX" crossorigin="anonymous" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" rel="stylesheet" type="text/css" >
    <link href="https://rawgit.com/creativetimofficial/material-kit/master/assets/css/material-kit.css" rel="stylesgeet" >
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link href="style.css" rel="stylesheet">
</head>

<body>
    
    <div class="container mt-5">
        
        <form class="mt-5" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="label-control">Start Date</label>
                        <input type="text" class="form-control datetimepicker_start" name="start" value="">
                        <span class="error"><?php echo $startErr;?></span>
                    </div>                    
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="label-control">End Date</label>
                        <input type="text" class="form-control datetimepicker_end" name="end" value="">
                        <span class="error"><?php echo $endErr;?></span>
                    </div>                    
                </div>
                <div class="col-md-12">
                    <button class="btn btn-primary btn-sm" type="submit" name="submit">Find</button>
                </div>
            </div>
        </form>

        <div class="title mt-5">
          <h3>Number of days: <?php echo $number_of_days; ?></h3>
        </div>

        <div class="title mt-5">
          <h3>Number of weekdays</h3>
        </div>

        <div class="title mt-5">
          <h3>Number of complete weeks</h3>
        </div>

        <div class="title mt-5">
          <h3><?php if(isset($message)) { echo $message; } ?></h3>
        </div>        

    </div>
    
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="script.js"></script>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/popper.js@1.12.6/dist/umd/popper.js" integrity="sha384-fA23ZRQ3G/J53mElWqVJEGJzU0sTs+SvzG8fXVWP+kJQ1lwFAOkcUOysnlKJC33U" crossorigin="anonymous"></script>
    
    <script src="https://unpkg.com/bootstrap-material-design@4.1.1/dist/js/bootstrap-material-design.js" integrity="sha384-CauSuKpEqAFajSpkdjv3z9t8E7RlpJ1UP0lKM/+NdtSarroVKu069AlsRPKkFBz9" crossorigin="anonymous"></script>
    <script src="https://rawgit.com/creativetimofficial/material-kit/master/assets/js/core/bootstrap-material-design.min.js"></script>
    <script src="https://rawgit.com/creativetimofficial/material-kit/master/assets/js/plugins/moment.min.js"></script>
    <script src="https://rawgit.com/creativetimofficial/material-kit/master/assets/js/plugins/bootstrap-datetimepicker.js"></script>
    <script src="https://rawgit.com/creativetimofficial/material-kit/master/assets/js/material-kit.js"></script>
</body>