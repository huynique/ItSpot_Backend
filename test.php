<?php
    define('API', 'restAPI.php'); // NICHT VERAENDERN!!!
    $url = "http://localhost/itspot_backend/" . API;
    $filepath = "c:\\xampp\\htdocs\\2022_BEE_PBAT3H19AB\\itspot\\";
    
/*
    Options for get all projetcs
*/
/*
    $defaults = array(
        CURLOPT_URL => $url . '/animal',
        // CURLOPT_COOKIEFILE => $filepath . 'cookie.txt', // set cookie file to given file
        // CURLOPT_COOKIEJAR => $filepath . 'cookie.txt', // set same file as cookie jar
        CURLOPT_CUSTOMREQUEST => "GET"

    );
*/

/**
 * Options for get all tasks
 */
/*
    $defaults = array(
        CURLOPT_URL => $url . '/task',
        CURLOPT_COOKIEFILE => $filepath . 'cookie.txt', // set cookie file to given file
        CURLOPT_COOKIEJAR => $filepath . 'cookie.txt', // set same file as cookie jar
       CURLOPT_CUSTOMREQUEST => "GET"
     );
*/
/**
 * Options for get tasks filtered
 */

     $defaults = array(
         CURLOPT_URL => $url . '/animal/getFilteredAnimal?filter=mammal',
         CURLOPT_COOKIEFILE => $filepath . 'cookie.txt', // set cookie file to given file
         CURLOPT_COOKIEJAR => $filepath . 'cookie.txt', // set same file as cookie jar
         CURLOPT_CUSTOMREQUEST => "GET"
    );
    
    
/**
 * Options for insert new animal
 */
/*
     $params = json_encode(array("animalid" => '187'
         , 'trivialname' => 'Oxolotl'
         , 'sciencename' => 'Pinecest'
         , 'lastseen' => '13.04.2004'
         , 'sightingscount' => '90'
         , 'animalcount' => '90'));
     $defaults = array(
         CURLOPT_URL => $url . '/animal',
         CURLOPT_CUSTOMREQUEST => "POST",
         CURLOPT_COOKIEFILE => $filepath . 'cookie.txt', // set cookie file to given file
         CURLOPT_COOKIEJAR => $filepath . 'cookie.txt', // set same file as cookie jar
         CURLOPT_POSTFIELDS => $params
     );
     */

/**
 * Options for Update Task OHNE Login
 */
    // $params = json_encode(array("done" => '0'));
    // $defaults = array(
    //     CURLOPT_URL => $url . '/task/1b2d4564-3718-11eb-add7-2c4d544f8fe0',
    //     CURLOPT_CUSTOMREQUEST => "PUT",
    //     CURLOPT_POSTFIELDS => $params
    // );


/**
 * Options for Delete Task OHNE Login
 */
    // $defaults = array(
    //     CURLOPT_URL => $url . '/task/01b169aa-3718-11eb-add7-2c4d544f8fe0',
    //     CURLOPT_CUSTOMREQUEST => "DELETE"
    // );

/**
 * Options for Delete Task MIT Login
 */
    // $defaults = array(
    //     CURLOPT_URL => $url . '/task/cbdb169f-e0da-11e7-a056-2c4d544f8fe0',
    //     CURLOPT_COOKIEFILE => $filepath . 'cookie.txt', // set cookie file to given file
    //     CURLOPT_COOKIEJAR => $filepath . 'cookie.txt', // set same file as cookie jar
    //     CURLOPT_CUSTOMREQUEST => "DELETE"
    // );

/**
 * Options for loginUser
 */
    // $defaults = array(
    //     CURLOPT_URL => $url . '/user/loginUser?username=fiona&pw=12345',
    //     CURLOPT_CUSTOMREQUEST => "GET",
    //     CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
    //     CURLOPT_COOKIEFILE => $filepath . 'cookie.txt', // set cookie file to given file
    //     CURLOPT_COOKIEJAR => $filepath . 'cookie.txt' // set same file as cookie jar
    // );

/**
 * Options for isLogin
 */
    // $defaults = array(
    //     CURLOPT_URL => $url . '/user/isLogin',
    //     CURLOPT_CUSTOMREQUEST => "GET",
    //     CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
    //     CURLOPT_COOKIEFILE => $filepath . 'cookie.txt', // set cookie file to given file
    //     CURLOPT_COOKIEJAR => $filepath . 'cookie.txt' // set same file as cookie jar
    // );

/**
 * Options for logout
 */
    // $defaults = array(
    //     CURLOPT_URL => $url . '/user/logout',
    //     CURLOPT_CUSTOMREQUEST => "GET",
    //     CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
    //     CURLOPT_COOKIEFILE => $filepath . 'cookie.txt', // set cookie file to given file
    //     CURLOPT_COOKIEJAR => $filepath . 'cookie.txt' // set same file as cookie jar
    // );
   
  // session_write_close();
   
    $ch = curl_init();
    curl_setopt_array($ch, ($defaults));
    curl_exec($ch);
    if(curl_error($ch)) {
        print(curl_error($ch));
    }
    curl_close($ch);

    // session_start();

?>