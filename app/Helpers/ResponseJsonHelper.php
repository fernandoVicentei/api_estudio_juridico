<?php

function responseJson($description,$content,$status)
{
return response([
    'status'=> $status,
    'description'=> $description,
    'content'=> $content,
],$status);
}
