<?php

return [
    //user type status
    'type' => [
        1 => "Admin",
        2 => "Seller",
        3 => "Customer",
        4 => "Employee"
    ],

    'type_by_name' => [
        "admin" => 1,
        "seller" => 2,
        "customer" => 3,
        "employee" => 4
    ],

    //withdraw status
    'withdraw' => [
        1 => "Pending",
        2 => "Canceled",
        3 => "Approved",
        4 => "Rejected"
    ],
];
