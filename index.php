<?php

    //define variables and set to empty values
    $startErr = $endErr = "";
    $start = $end = $number_of_days = $number_of_week_days = $number_of_weeks = null;

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
            $number_of_days =  floor($number_of_days); // gets the complete number of days
            $number_of_week_days = findWeekDays($start, $end);
            $number_of_weeks = findNoOfWeeks($start, $end);
        }

    }

    /**
     * Find the complete number of days between two datetime parameters
     * @param $start
     * @param $end
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
        //$number_of_days =  floor($number_of_days); // gets the complete number of days
        return $number_of_days;
    }

    /**
     * Find the number of complete weekdays between two datetime parameters
     * @param $start
     * @param $end
     * @return integer
     */
    function findWeekDays($start, $end)
    {
        $number_of_days = findDateDiff($start, $end); // find total number of days between the date range

        /** 
         * If number_of_days is not a whole number which means 
         * there is a half day which cannot be considered  as
         * a complete day therefore ignore that day 
         * */
        if (floor($number_of_days) == $number_of_days){
            $no_days = 0;
        } else {
            // ignore the day
            $no_days = -1;
        }
        
        $weekends = 0;
        $start = strtotime($start);
        $end = strtotime($end);

        // If start is bigger than end
        // Then swap start and end
        if ($start > $end) {
            $stime = $start;
            $start = $end;
            $end = $stime;
        } 
              
        $start += 86400; // dont consider the start day

        while($start <= $end){
            $no_days++; // no of days in the given date range   
            $what_day = date('N',$start); // N - The ISO-8601 numeric representation of a day (1 for Monday, 7 for Sunday)            
            if($what_day > 5) { // 6 and 7 are weekend days
                $weekends++;
            }             
            $start += 86400; // +1 day                    
        }
        
        $week_days = $no_days - $weekends;

        if($week_days < 1){ // do not return negative value instead return 0
            $week_days = 0;
        }

        return $week_days;
    }

    /**
     * Find the complete number of weeks between two datetime parameters
     * @param $start
     * @param $end
     * @return integer
     */
    function findNoOfWeeks($start, $end)
    {
        $datediff = (strtotime($start) - strtotime($end));
        // 7 days = 24 hours * 7
        // 24 * 7 * 60 * 60 = 604800 seconds 
        $number_of_weeks = abs($datediff / (60 * 60 * 24 * 7)); // gets the positive value
        $number_of_weeks =  floor($number_of_weeks); // gets the complete number of weeks
        return $number_of_weeks;
    }

?>
<!DOCTYPE html>
<html lang="en">
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

            <?php if(isset($start) && isset($end)) { ?>

            <div class="title mt-5">
            <h3>From (Not included): <b><?php echo $start; ?></b> - To (Included): <b><?php echo $end; ?></b></h3>
            </div>

            <div class="title mt-5">
                <p>Number of days: <?php echo $number_of_days; ?></p>
                <p>Number of weekdays: <?php echo $number_of_week_days; ?></p>
                <p>Number of complete weeks: <?php echo $number_of_weeks; ?></p>
            </div>

            <?php } ?>

            <div class="title mt-5">
            <p><?php if(isset($message)) { echo $message; } ?></p>
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

</html>