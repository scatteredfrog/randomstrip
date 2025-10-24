<?php
session_start();
require_once 'configs.php';
error_log("Got here");
/**
 * Make a call to randomDate(). Echo the returned array in JSON format.
 */

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $include_used = empty($_POST['include_used']) ? false : true;
    $randomDate = randomDate($include_used);

    // Now let's see if there's a "fun fact" for this date, from the "rs_funfacts" table.
    $conn = new mysqli($db_host, $db_user, $db_password, $database);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "SELECT * FROM rs_funfacts WHERE date = '" . $randomDate['original_date'] . "'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $randomDate['fun_fact'] = $row['description'];
        }
    }
    echo json_encode($randomDate);
    exit;
}

/**
 * Peanuts debuted on October 2, 1950 and ended on February 13, 2000.
 * Sundays did not appear until January 6, 1952. The last daily strip
 * was published on January 3, 2000, and the last Sunday strip was
 * published on February 13, 2000.
 *
 * By default the function will not include used strips. If the random
 * date is included in the $used_rows array, the function will call
 * itself again until a date is found that is not in the array.
 *
 * @param bool $include_used Whether to include used strips.
 *
 * @return string A random date between the start and end dates.
 */
function randomDate($include_used = false, $is_recalled = '')
{
    $start = strtotime('1950-10-02');
    $end = strtotime('2000-02-13');
    $randomDate = date('Y-m-d', mt_rand($start, $end));
    $return_data = array();

    /**
     * Go through the $_SESSION['used_rows'] array and check if the
     * reason is 'nonexistent'. If it is, or if $include_used is false
     * and the date is in the array, call randomDate() again.
     */
    foreach ($_SESSION['used_rows'] as $row) {
        if ($row['date'] == $randomDate) {
            if (
                    ($row['reason'] == 'nonexistent') ||
                    !$include_used
                ) {
                return randomDate($include_used, 'recalled');
            }
        }
    }

    /**
     * Assign $randomDate to $return_data['date']. If there's a 'reason' in the
     * corresponding row, assign it to $return_data['reason'].
     */
    $return_data['original_date'] = $randomDate;
    $formatted_date = (new DateTime($randomDate))->format('F j, Y');
    $date_parts = explode(' ', $formatted_date);
    $pieced_date = $date_parts[0] . ' ' .
        '<span class="comicSans">' . $date_parts[1] . ' ' .
        $date_parts[2] . '</span>';
    $return_data['date'] = $pieced_date;
    if ($include_used) {
        foreach ($_SESSION['used_rows'] as $row) {
            if ($row['date'] == $randomDate) {
                $return_data['reason'] = $row['reason'];
                break;
            }
        }
    }

    return $return_data;
}
