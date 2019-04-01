<?php


 function autoload_95b0653ecd21d4d01ce4d10e8213c94d($class)
{
    $classes = array(
        'CATaxRateAPI' => __DIR__ .'/CATaxRateAPI.php',
        'Hello' => __DIR__ .'/Hello.php',
        'HelloResponse' => __DIR__ .'/HelloResponse.php',
        'GetRate' => __DIR__ .'/GetRate.php',
        'CARateRequest' => __DIR__ .'/CARateRequest.php',
        'GetRateResponse' => __DIR__ .'/GetRateResponse.php',
        'CARateResponseCollection' => __DIR__ .'/CARateResponseCollection.php',
        'ArrayOfCARateResponse' => __DIR__ .'/ArrayOfCARateResponse.php',
        'CARateResponse' => __DIR__ .'/CARateResponse.php',
        'ArrayOfError' => __DIR__ .'/ArrayOfError.php',
        'ErrorCustom' => __DIR__ .'/ErrorCustom.php',
        'ArrayOfRateInformation' => __DIR__ .'/ArrayOfRateInformation.php',
        'RateInformation' => __DIR__ .'/RateInformation.php',
        'RateDetails' => __DIR__ .'/RateDetails.php'
    );
    if (!empty($classes[$class])) {
        include $classes[$class];
    };
}

spl_autoload_register('autoload_95b0653ecd21d4d01ce4d10e8213c94d');

// Do nothing. The rest is just leftovers from the code generation.
{
}
