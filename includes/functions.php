<?php

require_once('database-functions.php');

// Constants.
const USER_SESSION_KEY = 'user';

// Always call session_start.
session_start();

// --- Utils ----------------------------------------------------------------------------------
function displayError($errors, $name)
{
    if (isset($errors[$name]))
        echo "<div class='error'>{$errors[$name]}</div>";
}

function displayValue($form, $name)
{
    if (isset($form[$name]))
        echo 'value="' . htmlspecialchars($form[$name]) . '"';
}

function displayChecked($form, $name, $value)
{
    if (isset($form[$name]) && $form[$name] === $value)
        echo 'checked';
}

function redirect($location)
{
    header("Location: $location");
    exit();
}

function trimArray(&$array, $exclude = [])
{
    foreach ($array as $key => &$value) {
        if (is_string($value) && !in_array($key, $exclude))
            $value = trim($value);
    }
}

// --- User -----------------------------------------------------------------------------------
function isUserLoggedIn()
{
    return isset($_SESSION[USER_SESSION_KEY]);
}

function getLoggedInUser()
{
    return isUserLoggedIn() ? $_SESSION[USER_SESSION_KEY] : null;
}

function loginUser($form)
{
    $errors = [];

    $key = 'emailAddress';
    if (!isset($form[$key]) || filter_var($form[$key], FILTER_VALIDATE_EMAIL) === false)
        $errors[$key] = 'Email is invalid.';

    $key = 'password';
    if (!isset($form[$key]) || strlen($form[$key]) < 8 || preg_match('/^([A-Z])([a-zA-Z0-9])*(-|_)([a-zA-Z0-9])*([0-9])$/', $form[$key]) !== 1)
        $errors[$key] = 'Password must contain - or _ at least 8 characters and start with a capital alphabet character.';

    if (count($errors) === 0) {
        $user = getUser($form['emailAddress']);

        if ($user !== false && $form['password'] === $user['password']) {
            // Set session variable to login user.
            $_SESSION[USER_SESSION_KEY] = $user;
            redirect('./services.php');
        } else {
            $errors[$key] = 'Login failed, email and / or password incorrect. Please try again.';
        }
    }

    return $errors;
}

function logoutUser()
{
    // Unset all session variables.
    session_unset();
    session_destroy();
}

function registerUser($form)
{
    $errors = [];

    $key = 'firstName';
    if (!isset($form[$key]) || preg_match('/^\s*$/', $form[$key]) === 1)
        $errors[$key] = 'First name is required.';

    $key = 'lastName';
    if (!isset($form[$key]) || preg_match('/^\s*$/', $form[$key]) === 1)
        $errors[$key] = 'Last name is required.';

    $key = 'emailAddress';
    if (!isset($form[$key]) || filter_var($form[$key], FILTER_VALIDATE_EMAIL) === false)
        $errors[$key] = 'Email is invalid.';
    else if (getUser($form[$key]) !== false)
        $errors[$key] = 'Email is already registered.';

    $key = 'confirmEmail';
    if (isset($form['emailAddress']) && (!isset($form[$key]) || $form['emailAddress'] !== $form[$key]))
        $errors[$key] = 'Email do not match.';

    $key = 'phoneNumber';
    if (!isset($form[$key]) || preg_match('/^\+61 4\d{2} \d{3} \d{3}$/', $form[$key]) !== 1)
        $errors[$key] = 'Phone number is invalid. Must be in the format: +61 4xx xxx xxx';

    $key = 'Age';
    if (!isset($form[$key]) || filter_var($form[$key], FILTER_VALIDATE_INT,
            ['options' => ['min_range' => 16, 'max_range' => 120]]) === false)
        $errors[$key] = 'Minimum age is 16.';

    $key = 'studentStatus';
    if (!isset($form[$key]) || preg_match('/^true|false$/', $form[$key]) !== 1)
        $errors[$key] = 'Must select student status.';

    $key = 'employmentStatus';
    if (!isset($form[$key]) || preg_match('/^true|false$/', $form[$key]) !== 1)
        $errors[$key] = 'Must select employment status.';

    $key = 'password';
    if (!isset($form[$key]) || strlen($form[$key]) < 8 || preg_match('/^([A-Z])([a-zA-Z0-9])*(-|_)([a-zA-Z0-9])*([0-9])$/', $form[$key]) !== 1)
        $errors[$key] = 'Password must contain - or _ at least 8 characters and start with a capital alphabet character.';

    $key = 'confirmPassword';
    if (isset($form['password']) && (!isset($form[$key]) || $form['password'] !== $form[$key]))
        $errors[$key] = 'Passwords do not match.';

    if (count($errors) === 0) {
        // Add user.
        $user = [
            'firstName' => htmlspecialchars(trim($form['firstName'])),
            'lastName' => htmlspecialchars(trim($form['lastName'])),
            'emailAddress' => trim($form['emailAddress']),
            'phoneNumber' => htmlspecialchars(trim($form['phoneNumber'])),
            'Age' => filter_var($form['Age'], FILTER_VALIDATE_INT),
            'studentStatus' => (int)filter_var($form['studentStatus'], FILTER_VALIDATE_BOOLEAN),
            'employmentStatus' => (int)filter_var($form['employmentStatus'], FILTER_VALIDATE_BOOLEAN),
            'password' => $form['password']
        ];

        // Insert user.
        insertUser($user);

        // Auto-login the registered user.
        loginUser([
            'emailAddress' => $user['emailAddress'],
            'password' => $form['password']
        ]);
    }

    return $errors;
}

// --- Services -------------------------------------------------------------------------------
function recordActivity($email, $serviceID, $form)
{
    $errors = [];

    $key = 'duration';
    if (!isset($form[$key]) || filter_var($form[$key], FILTER_VALIDATE_INT,
            ['options' => ['min_range' => 1, 'max_range' => 480]]) === false)
        $errors[$key] = 'Duration must be a whole number and not be less than 1 or greater than 480.';

    if (count($errors) === 0) {
        // Prepare activity data.
        $activity = [
            'email' => $email,
            'service_id' => $serviceID,
            'service_type' => $form['type'],
            'duration_minutes' => filter_var($form['duration'], FILTER_VALIDATE_INT)
        ];

        // Insert activity into database.
        insertActivity($activity);
    }

    return $errors;
}

function generate($form)
{
    $numberOfMeals = $form['mealNumber'];
    $snack = getSnacks();
    $typeOfMeals = $form['mealType'];

    if ($numberOfMeals == 3) {
        //calculate each meal calories
        $B = $form['Calories'] * 0.3;
        $L1 = $form['Calories'] * 0.2;
        $L2 = $form['Calories'] * 0.2;
        $D = $form['Calories'] * 0.3;
        if ($typeOfMeals == "Any") {
            $anyMeals = getAnyMeals();
            //get meal but never same
            //while loop return not same meals.
            $randomMeal = $anyMeals[rand(0, 11)];
            $end = true;
            while ($end) {
                $randomMeal2 = $anyMeals[rand(0, 11)];
                if ($randomMeal2 != $randomMeal) {
                    $end = false;
                }
            }
            $end = true;
            while ($end) {
                $randomMeal3 = $anyMeals[rand(0, 11)];
                if ($randomMeal2 != $randomMeal3 && $randomMeal3!= $randomMeal) {
                    $end = false;
                }
            }
            $end = true;
            while ($end) {
                $randomMeal4 = $anyMeals[rand(0, 11)];
                if ($randomMeal2 != $randomMeal4 && $randomMeal4!= $randomMeal && $randomMeal4!= $randomMeal3) {
                    $end = false;
                }
            }
        }
        if ($typeOfMeals == "Vegetarian") {
            $anyMeals = getVegetarian();

            $randomMeal = $anyMeals[rand(0, 5)];
            $end = true;
            while ($end) {
                $randomMeal2 = $anyMeals[rand(0, 5)];
                if ($randomMeal2 != $randomMeal) {
                    $end = false;
                }
            }
            //while loop return not same meals.
            $end = true;
            while ($end) {
                $randomMeal3 = $anyMeals[rand(0, 5)];
                if ($randomMeal2 != $randomMeal3 && $randomMeal3!= $randomMeal) {
                    $end = false;
                }
            }
            $end = true;
            while ($end) {
                $randomMeal4 = $anyMeals[rand(0, 5)];
                if ($randomMeal2 != $randomMeal4 && $randomMeal4!= $randomMeal && $randomMeal4!= $randomMeal3) {
                    $end = false;
                }
            }
        }
        if ($typeOfMeals == "Meat") {
            $anyMeals = getMeat();
            $randomMeal = $anyMeals[rand(0, 5)];
            $end = true;
            while ($end) {
                $randomMeal2 = $anyMeals[rand(0, 5)];
                if ($randomMeal2 != $randomMeal) {
                    $end = false;
                }
            }
            //while loop return not same meals.
            $end = true;
            while ($end) {
                $randomMeal3 = $anyMeals[rand(0, 5)];
                if ($randomMeal2 != $randomMeal3 && $randomMeal3!= $randomMeal) {
                    $end = false;
                }
            }
            $end = true;
            while ($end) {
                $randomMeal4 = $anyMeals[rand(0, 5)];
                if ($randomMeal2 != $randomMeal4 && $randomMeal4!= $randomMeal && $randomMeal4!= $randomMeal3) {
                    $end = false;
                }
            }
        }
        //calculate servings
        $foodSize = $B / $randomMeal['calories'];
        $foodSize = round($foodSize, 1);
        $foodSize2 = $L1 / $randomMeal2['calories'];
        $foodSize2 = round($foodSize2, 1);
        $foodSize3 = $L2 / $randomMeal3['calories'];
        $foodSize3 = round($foodSize3, 1);
        $foodSize4 = $D / $randomMeal4['calories'];
        $foodSize4 = round($foodSize4, 1);
        //generate
        $a = array('<section class="meal-block">
        <h2>
          Breakfast
        </h2>
        <h3>       
          <img src="' . $randomMeal['image_path'] . '" class="iconImage">
          ' . $randomMeal['name'] . ' | ' . $B . ' calories | ' . $foodSize . ' servings</h3>
      </section>
      <section class="meal-block">
        <h2>
          Lunch
        </h2>
        <h3>       
          <img src="' . $randomMeal2['image_path'] . '" class="iconImage">
          ' . $randomMeal2['name'] . ' | ' . $L1 . ' calories | ' . $foodSize2 . ' servings</h3>
          <h3>       
          <img src="' . $randomMeal3['image_path'] . '" class="iconImage">
          ' . $randomMeal3['name'] . ' | ' . $L2 . ' calories | ' . $foodSize3 . ' servings</h3>
      </section>
      <section class="meal-block">
        <h2>
          Dinner
        </h2>
        <h3>       
          <img src="' . $randomMeal4['image_path'] . '" class="iconImage">
          ' . $randomMeal4['name'] . ' | ' . $D . ' calories | ' . $foodSize4 . ' servings</h3>
      </section>', $randomMeal['meal_id'], $foodSize, $randomMeal2['meal_id'], $foodSize2, $randomMeal3['meal_id'], $foodSize3, $randomMeal4['meal_id'], $foodSize4);
    }
    if ($numberOfMeals == 4) {
        $B = $form['Calories'] * 0.3;
        $L1 = $form['Calories'] * 0.2;
        $L2 = $form['Calories'] * 0.2;
        $D = $form['Calories'] * 0.2;
        $S1 = $form['Calories'] * 0.05;
        $S2 = $form['Calories'] * 0.05;
        if ($typeOfMeals == "Any") {
            $anyMeals = getAnyMeals();

            $randomMeal = $anyMeals[rand(0, 11)];
            $end = true;
            while ($end) {
                $randomMeal2 = $anyMeals[rand(0, 11)];
                if ($randomMeal2 != $randomMeal) {
                    $end = false;
                }
            }
            //while loop return not same meals.
            $end = true;
            while ($end) {
                $randomMeal3 = $anyMeals[rand(0, 11)];
                if ($randomMeal2 != $randomMeal3 && $randomMeal3!= $randomMeal) {
                    $end = false;
                }
            }
            $end = true;
            while ($end) {
                $randomMeal4 = $anyMeals[rand(0, 11)];
                if ($randomMeal2 != $randomMeal4 && $randomMeal4!= $randomMeal && $randomMeal4!= $randomMeal3) {
                    $end = false;
                }
            }
            $randomMeal5 = $snack[rand(0, 6)];
            $end = true;
            while ($end) {
                $randomMeal6 = $snack[rand(0, 6)];
                if ($randomMeal5 != $randomMeal6) {
                    $end = false;
                }
            }
        }
        if ($typeOfMeals == "Vegetarian") {
            $anyMeals = getVegetarian();
            $randomMeal = $anyMeals[rand(0, 5)];
            $end = true;
            while ($end) {
                $randomMeal2 = $anyMeals[rand(0, 5)];
                if ($randomMeal2 != $randomMeal) {
                    $end = false;
                }
            }
            //while loop return not same meals.
            $end = true;
            while ($end) {
                $randomMeal3 = $anyMeals[rand(0, 5)];
                if ($randomMeal2 != $randomMeal3 && $randomMeal3!= $randomMeal) {
                    $end = false;
                }
            }
            $end = true;
            while ($end) {
                $randomMeal4 = $anyMeals[rand(0, 5)];
                if ($randomMeal2 != $randomMeal4 && $randomMeal4!= $randomMeal && $randomMeal4!= $randomMeal3) {
                    $end = false;
                }
            }
            $randomMeal5 = $snack[rand(0, 6)];
            $end = true;
            while ($end) {
                $randomMeal6 = $snack[rand(0, 6)];
                if ($randomMeal5 != $randomMeal6) {
                    $end = false;
                }
            }
        }
        if ($typeOfMeals == "Meat") {
            $anyMeals = getMeat();
            $randomMeal = $anyMeals[rand(0, 5)];
            $end = true;
            while ($end) {
                $randomMeal2 = $anyMeals[rand(0, 5)];
                if ($randomMeal2 != $randomMeal) {
                    $end = false;
                }
            }
            //while loop return not same meals.
            $end = true;
            while ($end) {
                $randomMeal3 = $anyMeals[rand(0, 5)];
                if ($randomMeal2 != $randomMeal3 && $randomMeal3!= $randomMeal) {
                    $end = false;
                }
            }
            $end = true;
            while ($end) {
                $randomMeal4 = $anyMeals[rand(0, 5)];
                if ($randomMeal2 != $randomMeal4 && $randomMeal4!= $randomMeal && $randomMeal4!= $randomMeal3) {
                    $end = false;
                }
            }
            $randomMeal5 = $snack[rand(0, 6)];
            $end = true;
            while ($end) {
                $randomMeal6 = $snack[rand(0, 6)];
                if ($randomMeal5 != $randomMeal6) {
                    $end = false;
                }
            }
        }
        $foodSize = $B / $randomMeal['calories'];
        $foodSize = round($foodSize, 1);
        $foodSize2 = $L1 / $randomMeal2['calories'];
        $foodSize2 = round($foodSize2, 1);
        $foodSize3 = $L2 / $randomMeal3['calories'];
        $foodSize3 = round($foodSize3, 1);
        $foodSize4 = $D / $randomMeal4['calories'];
        $foodSize4 = round($foodSize4, 1);
        $foodSize5 = $S1 / $randomMeal5['calories'];
        $foodSize5 = round($foodSize5, 1);
        $foodSize6 = $S2 / $randomMeal6['calories'];
        $foodSize6 = round($foodSize6, 1);
        $a = array('<section class="meal-block">
        <h2>
          Breakfast
        </h2>
        <h3>       
          <img src="' . $randomMeal['image_path'] . '" class="iconImage">
          ' . $randomMeal['name'] . ' | ' . $B . ' calories | ' . $foodSize . ' servings</h3>
          <h2>
          Snack
        </h2>
        <h3>       
          <img src="' . $randomMeal5['image_path'] . '" class="iconImage">
          ' . $randomMeal5['name'] . ' | ' . $S1 . ' calories | ' . $foodSize5 . ' servings</h3>
          <h3>       
          <img src="' . $randomMeal6['image_path'] . '" class="iconImage">
          ' . $randomMeal6['name'] . ' | ' . $S2 . ' calories | ' . $foodSize6 . ' servings</h3>
      </section>
      <section class="meal-block">
        <h2>
          Lunch
        </h2>
        <h3>       
          <img src="' . $randomMeal2['image_path'] . '" class="iconImage">
          ' . $randomMeal2['name'] . ' | ' . $L1 . ' calories | ' . $foodSize2 . ' servings</h3>
          <h3>       
          <img src="' . $randomMeal3['image_path'] . '" class="iconImage">
          ' . $randomMeal3['name'] . ' | ' . $L2 . ' calories | ' . $foodSize3 . ' servings</h3>
      </section>
      <section class="meal-block">
        <h2>
          Dinner
        </h2>
        <h3>       
          <img src="' . $randomMeal4['image_path'] . '" class="iconImage">
          ' . $randomMeal4['name'] . ' | ' . $D . ' calories | ' . $foodSize4 . ' servings</h3>
      </section>', $randomMeal['meal_id'], $foodSize, $randomMeal2['meal_id'], $foodSize2, $randomMeal3['meal_id'], $foodSize3, $randomMeal4['meal_id'], $foodSize4, $randomMeal5['meal_id'], $foodSize5, $randomMeal6['meal_id'], $foodSize6);
    }
    return $a;
}

function recordMeal($email, $meal_id, $servings)
{
    // Prepare data.
    $plan = [
        'email' => $email,
        'meal_id' => $meal_id,
        'servings' => $servings
    ];

    // Insert into database.
    insertMealPlan($plan);
}

function showUserRows()
{
    $allUsers = getUsers();
    $length = count($allUsers);
    $i = 0;
    $rows = "";
    //for each user
    while ($i < $length) {
        //get needed value
        $user = $allUsers[$i];
        //show row number
        $num = $i + 1;
        $is_student = "true";
        $is_employed = "true";
        if ($user['is_student'] == 0) {
            $is_student = "false";
        }
        if ($user['is_employed'] == 0) {
            $is_employed = "false";
        }
        //generate
        $row = '
            <tr class="table-light">
                <th class="w-100 text-nowrap">' . $num . '</th>
                <td class="w-100 text-nowrap">' . $user['email'] . '</td>
                <td class="w-100 text-nowrap">' . $user['first_name'] . '</td>
                <td class="w-100 text-nowrap">' . $user['last_name'] . '</td>
                <td class="w-100 text-nowrap">' . $user['phone'] . '</td>
                <td class="w-100 text-nowrap">' . $user['age'] . '</td>
                <td class="w-100 text-nowrap">' . $is_student . '</td>
                <td class="w-100 text-nowrap">' . $is_employed . '</td>
                <td class="w-100 text-nowrap"><input class="form-check-input" type="radio" value="' . $user['email'] . '" name="selection"></td>
            </tr>
';
        $rows .= $row;
        $i++;
    }
    return $rows;
}

function showServiceRows($email, $service_id)
{
    //get value
    $allServices = getServicesByEmail($email, $service_id);
    $length = count($allServices);
    $i = 0;
    $rows = "";
    //for each service
    while ($i < $length) {
        $Service = $allServices[$i];
        //show row number
        $num = $i + 1;
        //generate
        $row = '
            <tr class="table-light">
                <td class="w-100 text-nowrap">' . $num . '</td>
                <td class="w-100 text-nowrap">' . $Service['service_type'] . '</td>
                <td class="w-100 text-nowrap">' . $Service['date_performed'] . '</td>
                <td class="w-100 text-nowrap">' . $Service['duration_minutes'] . '</td>
            </tr>
';
        $rows .= $row;
        $i++;
    }
    return $rows;
}

function showMealRows($email)
{
    $allMeals = getUserMeals($email);
    $length = count($allMeals);
    $i = 0;
    $rows = "";
    //for each meal
    while ($i < $length) {
        //get needed value
        $mealServing = $allMeals[$i]['servings'];
        $meal_id = $allMeals[$i]['meal_id'];
        $meal = getMealOfUser($meal_id);
        //generate
        $row = '
            <div class="card w-25">
                <img src="../' . $meal['image_path'] . '" class="card-img-top" alt="icon">
                <div class="card-body">
                    <p class="card-text text-dark">name: ' . $meal['name'] . '</p>
                    <p class="card-text text-dark">Servings: ' . $mealServing . '</p>
                    <p class="card-text text-dark">calories per serving: ' . $meal['calories'] . '</p>
                    <p class="card-text text-dark">type: ' . $meal['type'] . '</p>
                </div>
            </div>
';
        $rows .= $row;
        $i++;
    }
    return $rows;
}

function showMealTable($email)
{
    $allMeals = getUserMeals($email);
    $length = count($allMeals);
    $i = 0;
    $rows = "";
    while ($i < $length) {
        $mealServing = $allMeals[$i]['servings'];
        $meal_id = $allMeals[$i]['meal_id'];
        $meal = getMealOfUser($meal_id);
        //generate
        $row = '
            <h3>       
          <img src="' . $meal['image_path'] . '" class="iconImage">
          ' . $meal['name'] . ' | ' . $meal['calories'] . ' calories per serving | ' . $mealServing . ' servings</h3>
';
        $rows .= $row;
        $i++;
    }
    return $rows;
}

//generate chart data and label
function showData($email, $service_id)
{
    $data = array();
    $allServices = getServicesByEmail($email, $service_id);
    $length = count($allServices);
    $i = 0;
    while ($i < $length) {
        $data[$i] = $allServices[$i]['duration_minutes'];
        $i++;
    }
    echo json_encode($data);
}
function showLabel($email, $service_id)
{
    $data = array();
    $allServices = getServicesByEmail($email, $service_id);
    $length = count($allServices);
    $i = 0;
    while ($i < $length) {
        $data[$i] = $allServices[$i]['date_performed'];
        $i++;
    }
    echo json_encode($data);
}
