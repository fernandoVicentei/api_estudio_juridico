<?php

function responseJson($description, $content, $status)
{
    return response([
        'status' => $status,
        'description' => $description,
        'content' => $content,
    ], $status);
}

function responseUser($user, $token)
{
    return [
        'id' => $user->id,
        'email' => $user->email,
        'token' => $token,
    ];
}
