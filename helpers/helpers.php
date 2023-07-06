<?php
function validPassword(string $password): bool
{
	// $password_regex = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/";
	$password_regex = "/^(?=.*?[#?!@$%^&*-]).{8,}$/";
	return  preg_match($password_regex, $password);
	// return true; 
	// for now the pass=00 
}

function validEmail(string $email): bool
{
	return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function sanitizeEmail(string $email): string
{
	return filter_var($email, FILTER_SANITIZE_EMAIL);
}

function validUrl(string $url): bool
{
	return filter_var($url, FILTER_VALIDATE_URL);
}

function sanitizeUrl(string $url): string
{
	return filter_var($url, FILTER_SANITIZE_URL);
}

function formetDate(string $date): string
{
	return date('l jS \of F Y h:i:s A', strtotime($date));
}
