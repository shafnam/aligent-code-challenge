<?php

    //define variables and set to empty values
    $startErr = $endErr = "";
    $start = $end = $number_of_days = $number_of_week_days = $number_of_weeks = $timezone = null;

    //if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['submit'])){

        /* Validations */ 
        if(isset($_POST['timezone'])){
            $timezone = 1;
        }
        /** If timezone not specified set default timezone as 'Australia/Adelaide'
         *  else set the default timezone to start date timezone
         */
        if (empty($_POST["s_tzone"])) {
            // Set 'Australia/Adelaide' timezone as default timezone
            date_default_timezone_set('Australia/Adelaide'); 
        } else {
            $s_tzone = $_POST["s_tzone"];
            // Set start timezone as default timezone
            date_default_timezone_set($s_tzone); 
        }
        if (empty($_POST["e_tzone"])) {
            $e_tzone = null;
        } else {
            $e_tzone = $_POST['e_tzone'];
        }
        if (empty($_POST["convert_to"])) {
            $convert_to = null;
        } else {
            $convert_to = $_POST['convert_to'];
        }
        // Check whether the start date is empty. if not store the post value in $start variable
        if (empty($_POST["start"])) {
            $startErr = "Please enter a start date";
        } else {
            $start_date = $_POST['start'];
            $start = strtotime($start_date);
        }
        // Check whether the end date is empty. if not store the post value in $end variable
        if (empty($_POST["end"])) {
            $endErr = "Please enter an end date";
        } else {
            $end_date = $_POST['end'];
            if(empty($_POST["e_tzone"])){
                $end = strtotime($end_date);
            } else{
                // add timezoen conversion to end date
                $end = strtotime($end_date. ' ' . $e_tzone);
            }
        }

        // If there are no validation errors run functions.
        if(empty($startErr) && empty($endErr)){

            if($convert_to != null){
                // Value given to convert the number of days
                $number_of_days = findDateDiff($start, $end,$convert_to)[0];
                $diff_in_convert_value = findDateDiff($start, $end,$convert_to)[1];
            } else {
                $number_of_days = findDateDiff($start, $end, $convert_to);
            }
            $number_of_days = floor($number_of_days); // gets the complete number of days
            $number_of_week_days = findWeekDays($start, $end);
            $number_of_weeks = findNoOfWeeks($start, $end);
            // $message = $s_tzone. $e_tzone;            
        }

    }

    /**
     * Find the complete number of days between two datetime parameters
     * @param $start
     * @param $end
     * @return integer
     */
    function findDateDiff($start, $end, $convert_to=null)
    {        
        $datediff = ($start - $end);
        // 1 day = 24 hours 
        // 24 * 60 * 60 = 86400 seconds 
        $number_of_days = abs($datediff / (60 * 60 * 24)); // gets the positive value
        
        if($convert_to != null) { 
            if($convert_to == 'seconds') {
                $datediffInConvertField = abs( $datediff);
            }
            else if($convert_to == 'minutes') {
                $datediffInConvertField = abs( $datediff / 60 ) ;
            }
            else if($convert_to == 'hours') {
                $datediffInConvertField = abs($datediff / (60 * 60)) ;
            }
            else if($convert_to == 'years') {
                $datediffInConvertField = abs(round($datediff / (60 * 60 * 24 * 365.25))) ;
            }
            // return array as function result
            return array($number_of_days, $datediffInConvertField);
        } else {
            // return only the number of days
            return $number_of_days;
        }
        
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
        $no_days = 0;
        $weekends = 0;

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
        $datediff = ($start - $end);
        // 7 days = 24 hours * 7
        // 24 * 7 * 60 * 60 = 604800 seconds 
        $number_of_weeks = abs($datediff / (60 * 60 * 24 * 7)); // gets the positive value
        $number_of_weeks =  floor($number_of_weeks); // gets the complete number of weeks
        return $number_of_weeks;
    }

    /**
     * Timezones list with GMT offset
     *
     * @return array
     * @link http://stackoverflow.com/a/9328760
     */
    function tz_list() {
        $zones_array = array();
        $timestamp = time();
        foreach(timezone_identifiers_list() as $key => $zone) {
        date_default_timezone_set($zone);
        $zones_array[$key]['zone'] = $zone;
        $zones_array[$key]['diff_from_GMT'] = 'UTC/GMT ' . date('P', $timestamp);
        }
        return $zones_array;
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
                <div class="row mb-5">
                    <div class="col-md-6">
                        <div class="form-check">
                            <label class="form-check-label timezonev">
                                <input id="allow_timezone" class="form-check-input" type="checkbox" name="timezone" value="timezone" <?php if( $timezone == 1 ){ echo "checked"; }?>>
                                Calculate across different timezones
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row mb-5" id="timezone" style="display: none;">
                    <div class="col-md-6">
                        <select class="form-control selectpicker" name="s_tzone">
                            <option value="0">Please, select timezone for start date</option>
                            <?php foreach(tz_list() as $t) { ?>
                            <option value="<?php print $t['zone'] ?>" <?php if( isset($s_tzone) && $s_tzone == $t['zone']){ echo "selected"; }?>>
                                <?php print $t['diff_from_GMT'] . ' - ' . $t['zone'] ?>
                            </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <select class="form-control selectpicker" name="e_tzone">
                            <option value="0">Please, select timezone for end date</option>
                            <?php foreach(tz_list() as $t) { ?>
                            <option value="<?php print $t['zone'] ?>" <?php if( isset($e_tzone) && $e_tzone == $t['zone']){ echo "selected"; }?>>
                                <?php print $t['diff_from_GMT'] . ' - ' . $t['zone'] ?>
                            </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="label-control">Select start date</label>
                            <input type="text" class="form-control datetimepicker_start" name="start" value="<?php if(isset($start_date)){ echo $start_date; } ?>">
                            <span class="error"><?php echo $startErr;?></span>
                        </div>                    
                    </div>                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="label-control">Select end date</label>
                            <input type="text" class="form-control datetimepicker_end" name="end" value="<?php if(isset($end_date)){ echo $end_date; } ?>">
                            <span class="error"><?php echo $endErr;?></span>
                        </div>                    
                    </div>
                </div>

                <?php if(isset($start) && isset($end)) { ?>

                    <div class="title mt-3">
                        <p>From (Not included): <b><?php echo $start_date; ?>  <?php if(isset($s_tzone)){ echo $s_tzone . ' Time' ; } ?> 
                        </b> - To (Included): <b><?php echo $end_date; ?> <?php if(isset($e_tzone)){ echo $e_tzone . ' Time'; } ?> 
                        </b></p>
                    </div>

                    <div class="title mt-0">
                        <table>
                            <tr>
                                <td>Number of days:</td>
                                <td> <?php echo $number_of_days; ?></td>  
                                <td><p class="pt-3 px-3">Get number of days in:</p></td>
                                <td> 
                                    <div class="form-group ml-2 pt-2">
                                        <select class="form-control selectpicker" data-style="btn btn-link" name="convert_to" style="width: 80px;">
                                            <option value="0">Please select</option>
                                            <option value="seconds" <?php if( isset($convert_to) && $convert_to == 'seconds'){ echo "selected"; }?>>seconds</option>
                                            <option value="minutes" <?php if( isset($convert_to) && $convert_to == 'minutes'){ echo "selected"; }?>>minutes</option>
                                            <option value="hours" <?php if( isset($convert_to) && $convert_to == 'hours'){ echo "selected"; }?>>hours</option>
                                            <option value="years" <?php if( isset($convert_to) && $convert_to == 'years'){ echo "selected"; }?>>years</option>
                                        </select>  
                                    </div>                             
                                </td>
                                <td><p class="pt-3 px-3"><?php if(isset($diff_in_convert_value)) { echo $diff_in_convert_value . ' ' . $convert_to; } ?></p></td>
                            </tr>  
                            <tr>
                                <td><p>Number of weekdays:</p></td>
                                <td><p> <?php echo $number_of_week_days; ?></p></td>
                            </tr>  
                            <tr> 
                                <td style="width: 222px;"><p>Number of complete weeks:</p></td>
                                <td><p> <?php echo $number_of_weeks; ?></p></td>  
                            </tr>                                           
                        </table>                     
                    </div>

                <?php } ?>
                
                <div class="row">
                    <div class="col-md-12 mt-3">
                        <button class="btn btn-primary btn-sm" type="submit" name="submit">Find</button>
                    </div>
                </div>

            </form>

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