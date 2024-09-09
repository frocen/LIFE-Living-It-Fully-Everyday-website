<?php

// Constants.
const SERVER_NAME = '???';
const DB_NAME = '???';
const USERNAME = DB_NAME;
const PASSWORD = '???!';

const DNS = 'mysql:host=' . SERVER_NAME . ';dbname=' . DB_NAME;

function createConnection()
{
    return new PDO(DNS, USERNAME, PASSWORD);
}

function prepareAndExecute($query, $params = null)
{
    $pdo = createConnection();
    $statement = $pdo->prepare($query);
    $statement->execute($params);

    return $statement;
}

function prepareExecuteAndFetchAll($query, $params = null)
{
    $statement = prepareAndExecute($query, $params);

    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function prepareExecuteAndFetch($query, $params = null)
{
    $statement = prepareAndExecute($query, $params);

    return $statement->fetch(PDO::FETCH_ASSOC);
}

// --- User -----------------------------------------------------------------------------------
function getUsers()
{
    return prepareExecuteAndFetchAll('select * from user');
}

function getUser($email)
{
    return prepareExecuteAndFetch('select * from user where email = :email', ['email' => $email]);
}

function insertUser($user)
{
    return prepareAndExecute(
        'insert into user
        (email, password, first_name, last_name, phone, age, is_student, is_employed) values
        (:emailAddress, :password, :firstName, :lastName, :phoneNumber, :Age, :studentStatus, :employmentStatus)',$user);
}

// --- Services -------------------------------------------------------------------------------
function getServices()
{
    return prepareExecuteAndFetchAll('select * from service');
}

function getServicesByEmail($email, $service_id)
{
    return prepareExecuteAndFetchAll('select * from user_service where email = :email and service_id = :service_id',
        ['email' => $email, 'service_id' => $service_id]);
}

function getService($id)
{
    return prepareExecuteAndFetch('select * from service where service_id = :id', ['id' => $id]);
}

function getServiceByName($name)
{
    return prepareExecuteAndFetch('select * from service where name = :name', ['name' => $name]);
}

function getServiceInstructions($id)
{
    return prepareExecuteAndFetchAll('select * from service_instruction where service_id = :id', ['id' => $id]);
}

function getServiceInstruction($id, $type)
{
    return prepareExecuteAndFetch(
        'select * from service_instruction where service_id = :id and service_type = :type',
        ['id' => $id, 'type' => $type]);
}

function insertActivity($activity)
{
    return prepareAndExecute(
        'insert into user_service
        (email, service_id, service_type, date_performed, duration_minutes) values
        (:email, :service_id, :service_type, now(), :duration_minutes)', $activity);
}

// --- Meals -------------------------------------------------------------------------------
function getAnyMeals()
{
    return prepareExecuteAndFetchAll('select * from meal where type != :type', ['type' => "Snack"]);
}

function getSnacks()
{
    return prepareExecuteAndFetchAll('select * from meal where type = :type', ['type' => "Snack"]);
}

function getMeat()
{
    return prepareExecuteAndFetchAll('select * from meal where type = :type', ['type' => "Meat"]);
}

function getVegetarian()
{
    return prepareExecuteAndFetchAll('select * from meal where type = :type', ['type' => "Vegetarian"]);
}

function getUserMeals($email)
{
    return prepareExecuteAndFetchAll('select * from user_meal where email = :email', ['email' => $email]);
}

function getMealOfUser($meal_id)
{
    return prepareExecuteAndFetch(
        'select * from meal where meal_id = :meal_id', ['meal_id' => $meal_id]);
}

function insertMealPlan($plan)
{
    return prepareAndExecute(
        'insert into user_meal
        (email, meal_id, servings) values
        (:email, :meal_id, :servings)', $plan);
}

function deleteMealPlan($email)
{
    return prepareAndExecute(
        'DELETE FROM user_meal WHERE email = :email', ['email' => $email]);
}

function haveUserMeal($email)
{
    return prepareExecuteAndFetchall('select * from user_meal where email = :email', ['email' => $email]);
}