<?php
$example_persons_array = [
    [
        'fullname' => 'Иванов Иван Иванович',
        'job' => 'tester',
    ],
    [
        'fullname' => 'Степанова Наталья Степановна',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Пащенко Владимир Александрович',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Громов Александр Иванович',
        'job' => 'fullstack-developer',
    ],
    [
        'fullname' => 'Славин Семён Сергеевич',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Цой Владимир Антонович',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Быстрая Юлия Сергеевна',
        'job' => 'PR-manager',
    ],
    [
        'fullname' => 'Шматко Антонина Сергеевна',
        'job' => 'HR-manager',
    ],
    [
        'fullname' => 'аль-Хорезми Мухаммад ибн-Муса',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Бардо Жаклин Фёдоровна',
        'job' => 'android-developer',
    ],
    [
        'fullname' => 'Шварцнегер Арнольд Густавович',
        'job' => 'babysitter',
    ],
];
 

function getFullnameFromParts  ($surname, $name, $patronymic){
    return "$name"." "."$surname"." ".$patronymic;

};


function getPartsFromFullname ($fullName){
    return explode(" ", $fullName);
};


function getShortName($fullName){
    $arrayFullName = getPartsFromFullname($fullName);
    $name = $arrayFullName[1];
    $surname = $arrayFullName[0];
    return ($name." ".mb_substr($surname, 0, 1));
};


function getGenderFromName($fullName){
    $arrayFullName = getPartsFromFullname($fullName);
    $name = $arrayFullName[1];
    $surname = $arrayFullName[0];
    $patronymic = $arrayFullName[2];
    $finalSexAttribute = 0;

    (mb_substr ($name,(mb_strlen($name) -1), 1) == "a") ? $finalSexAttribute-- : $finalSexAttribute;
    (mb_substr ($surname,(mb_strlen($surname) -2), 2) == "ва") ? $finalSexAttribute-- : $finalSexAttribute;
    (mb_substr ($patronymic,(mb_strlen($patronymic) -3), 3) == "вна") ? $finalSexAttribute-- : $finalSexAttribute;

    (mb_strstr ($name,(mb_strlen($name) -1), 1) == "н") || (mb_strstr(mb_strlen($name) -1, 1) == "й") ? $finalSexAttribute++ : $finalSexAttribute;
    (mb_substr ($surname,(mb_strlen($surname) - 1), 1) == "в") ? $finalSexAttribute++ : $finalSexAttribute;
    (mb_substr ($patronymic,(mb_strlen($patronymic) -2), 2) == "ич") ? $finalSexAttribute++ : $finalSexAttribute;

    return ($finalSexAttribute <=> 0);
};


function getGenderDescription($someArray){
    $maleSexCount = 0;
    $femaleSexCount = 0;
    $undefinedSexCount = 0;
    $personCount = count($someArray);

    for($i = 0; $i < $personCount; $i++){
        $gender = getGenderFromName($someArray[$i]['fullname']);

        if($gender > 0){
            $maleSexCount++;
        }
        elseif($gender < 0){
            $femaleSexCount++;
        }
        else{
            $undefinedSexCount++;
        }

        }

        $malePercent = round(($maleSexCount * 100) / $personCount, 1, PHP_ROUND_HALF_ODD);
        $femalePercent = round(($femaleSexCount * 100) / $personCount, 1, PHP_ROUND_HALF_ODD);
        $undefinedPercent = round(($undefinedSexCount * 100) / $personCount, 1, PHP_ROUND_HALF_ODD);

        $result = <<<HEREDOCTEXT
        Гендерный состав аудитории:
        ---------------------------
        Мужчины - $malePercent%
        Женщины - $femalePercent%
        Не удалось определить - $undefinedPercent%
HEREDOCTEXT;

        return $result;
    }   

    function getPerfectPartner($surname, $name, $patronymic, $someArray){
        $fullName = getFullnameFromParts($surname, $name, $patronymic);
        $gender = getGenderFromName($fullName);

        $surname = mb_convert_case($surname, MB_CASE_TITLE_SIMPLE);
        $name = mb_convert_case($name , MB_CASE_TITLE_SIMPLE);
        $patronymic = mb_convert_case($patronymic, MB_CASE_TITLE_SIMPLE);

        if($gender != 0){
            $randomPerson = '';
            while(getGenderFromName($randomPerson) == $gender || getGenderFromName($randomPerson) == 0){
                $randomPerson = $someArray[rand(0, count($someArray)-1)]['fullname'];
            }
        }
        else{
            return false;
        };

        $shortName = 'getShortName';
        $randomPercent = round(rand(5000, 10000)/ 100, 2);

        $result = <<<HEREDOCTEXT
        {$shortName($fullName)} + {$shortName($randomPerson)} = 
        ♡ Идеально на $randomPercent% ♡
HEREDOCTEXT;

        return $result;

    }
    

echo getFullnameFromParts("Шматко", "Антонина", "Сергеевна");
print_r(getPartsFromFullname('Шматко Антонина Сергеевна'));
echo getShortName('Шматко Антонина Сергеевна');
echo getGenderFromName('Шматко Антонина Сергеевна');
echo getGenderDescription($example_persons_array);



?>