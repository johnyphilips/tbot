<?php
/**
 * Created by PhpStorm.
 * User: novichkov
 * Date: 16.05.18
 * Time: 10:17
 */
define('ROOT_DIR', str_replace('protected' . DIRECTORY_SEPARATOR . 'cron', '', __DIR__));
define('PROJECT', 'bot');
require_once ROOT_DIR . '/protected/config.php';
require_once CORE_DIR . 'autoload.php';
//1
$count = 0;
for($i = 0; $i <= 10000; $i ++) {
    if(slot_service::getBetPrize(30000, 1)) {
        $count ++;
    }
}
echo $count;
//$res = [];
//$line = 5;
//$prize = 0;
//$potential = 100000000;
//$bet = 1;
//for($i = 1; $i <= $line; $i ++) {
//    if($p = slot_service::getBetPrize($potential, $bet)) {
//        print_r($p);
//        $res['prize_lines'][] = $i;
//        $vars[$i] = $p;
//        $prize += $p['var'];
//    }
//}
//
//if($prize) {
//    $lines = slot_service::lines($vars, $line);
//    $res['numbers'] = $lines;
//
//    print_r($res);
//}

exit;
slot_service::generateScreen([
    [1,2,5,2,5],
    [3,'b',3,3,5],
    [1,9,4,0,5],
],9, [1,2,3,4,5,6,7,8,9]);
exit;
set_time_limit(0);
print_r(slot_service::getBetPrize(10000, 1));
exit;
//1 2 3
//2 4 6
//3 6 9
//4 8 12
//5 10 15
//6 12 18
//7 14 21
//8 16 24
//9 18 27
//10 20 30
//50 100 150
//100 200 300
$max = 10;
$bet = 10;
function varas($potential, $bet) {

//    $potential = 10000;
    $numbers = [
        1,2,3,4,5,6,7,8,9,10,50,100
    ];
    $vars = [];
    $coef = 10;
    for($i = 1; $i <= $potential; $i *= 10) {
        $coef -= 1;
        if($coef == 3) {
            break;
        }
    }
    $coef = rand(1, $coef);
    foreach ($numbers as $k => $number) {
        if(rand(0, $k + 1) === $k) {
            break;
        }
        if(rand(0, $coef) !== $coef) {
            continue;
        }
        if($number * $bet > $potential) {
            break;
        }
        $vars[] = [
            'var' => $number * $bet,
            'number' => $number
        ];
        for($i = 2; $i <= 3; $i ++) {
            if(rand(1, $i) !== 1) {
                break;
            }
            $var = $number * $bet * $i;
            if($var > $potential) {
                break;
            }
            $vars[] = [
                'var' => $var,
                'number' => $number
            ];
        }
    }
    return $vars;
}
$r = [];
$potential = 10000;
$profit = 0;
$max_potential = 0;
$max_win = 0;
for ($i = 0; $i <= 100; $i ++) {
    if(rand(0, 9)) {
        $potential += $bet;
    } else {
        $profit += 1;
    }
    if($potential > $max_potential) {
        $max_potential = $potential;
    }
    $vars =  varas($potential, $bet);
    if($vars) {
        $count = count($vars) - 1;
        $rand = slot_service::getRand(1, $count);
        $res = $vars[$rand];


        if(!$r[$res['var']]) {
            $r[$res['var']] = 1;
        } else {
            $r[$res['var']] += 1;
        }
        $pow = slot_service::getPow($res['var'], $potential);
        $max_x = slot_service::getRandX(count($pow) - 1);
//        $rand = slot_service::getRand(1, $max_x);
        $prize = $res['var'];
        if($max_x) {
            for($i = 0; $i <= $max_x; $i ++ ) {
                $prize = $prize * 2;
            }
        }
        if($prize) {
            $potential -= $prize;
            if($prize > $max_win) {
                $max_win = $prize;
            }
        }


    }
}
ksort($r);
print_r($r);
$sum = 0;
foreach ($r as $k => $item) {
    $sum += $k * $item;
}
echo 'sum: ' . $sum . "\n";
echo 'max_potential: ' . $max_potential . "\n";
echo 'wax_win: ' . $max_win . "\n";
echo 'profit: ' . $profit . "\n";
echo 'sum: ' . $sum . "\n";
exit;


$vars = [];
$lines = [];
for($i = $bet; $i <= $bet * 2; $i += $bet) {
    $vars[] = [
        'number' => 2,
        'var' => $i
    ];
}
$vars[] = [
    'number' => 2,
    'var' => $i * 2
];
$lines = 9;
if(count($res) === 1) {
    $line = rand(1,$lines);
    $b = $res/$bet;
    for($i = 1; $i <= 9; $i ++) {

    }
}

exit;

slot_service::generateScreen([
    [1,2,5,2,5],
    [3,'b',3,3,5],
    [1,9,4,0,5],
],9, [1,6]);
exit;
$img = [];

for($i = 0; $i < 4; $i ++) {
    $img[$i] = new imgresize_class();
    $filename = PROTECTED_DIR . 'assets/images/slots/card_back.jpg';
    $img[$i]->load($filename);

//    $img[1] = new imgresize_class();
//    $filename = PROTECTED_DIR . 'assets/images/slots/card_back.jpg';
//    $img[1]->load($filename);
//    $img[2] = new imgresize_class();
//    $filename = PROTECTED_DIR . 'assets/images/slots/b_stripe.jpg';
//    $img[2]->load($filename);
//    $res = [];
//    $res[$i] = new imgresize_class();


}
$res = new imgresize_class();
$res->joinHorizontal($img);
//$res->resizeToHeight(600);
$res->sharpen();
$res->save(PROTECTED_DIR . 'assets/images/slots/card_backs.jpg');
exit;
$tmp1 = rand() . rand() . time() . '.jpg';
$tmp2 = rand() . rand() . time() . '.jpg';
$tmp3 = rand() . rand() . time() . '.jpg';
$tmp4 = rand() . rand() . time() . '.jpg';
$tmp5 = rand() . rand() . time() . '.jpg';
$res[0]->save(PUBLIC_DIR . 'tmp/' . $tmp1, IMAGETYPE_JPEG, 10);
//exit;
$img = [];
$img[0] = new imgresize_class();
$filename = PROTECTED_DIR . 'assets/images/slots/b_stripe.jpg';
$img[0]->load($filename);
//$img[0]->resizeToWidth(200);
//$img[0]->resampleToWidth(180);
$img[1] = new imgresize_class();
$filename = PROTECTED_DIR . 'assets/images/slots/card_back.jpg';
$img[1]->load($filename);
//$img[1]->resampleToWidth(180);
//$img[1]->resizeToWidth(200);
$img[2] = new imgresize_class();
$filename = PROTECTED_DIR . 'assets/images/slots/b_stripe.jpg';
$img[2]->load($filename);
//$img[2]->resampleToWidth(180);
//$img[2]->resizeToWidth(200);
$res[1] = new imgresize_class();
$res[1]->joinVertical($img);
$res[1]->resizeToHeight(600);
$res[1]->save(PUBLIC_DIR . 'tmp/' . $tmp2, IMAGETYPE_JPEG, 10);

$res[2] = new imgresize_class();
$res[2]->joinVertical($img);
$res[2]->resizeToHeight(600);
$res[2]->save(PUBLIC_DIR . 'tmp/' . $tmp3, IMAGETYPE_JPEG, 10);

$res[3] = new imgresize_class();
$res[3]->joinVertical($img);
$res[3]->resizeToHeight(600);
$res[3]->save(PUBLIC_DIR . 'tmp/' . $tmp4, IMAGETYPE_JPEG, 10);

$res[4] = new imgresize_class();
$res[4]->joinVertical($img);
$res[4]->resizeToHeight(600);
$res[4]->save(PUBLIC_DIR . 'tmp/' . $tmp5, IMAGETYPE_JPEG, 10);
$img = [];
$img[0] = new imgresize_class();
$img[0]->load(PUBLIC_DIR . 'tmp/' . $tmp1);
$img[1] = new imgresize_class();
$img[1]->load(PUBLIC_DIR . 'tmp/' . $tmp2);
$img[2] = new imgresize_class();
$img[2]->load(PUBLIC_DIR . 'tmp/' . $tmp3);
$img[3] = new imgresize_class();
$img[3]->load(PUBLIC_DIR . 'tmp/' . $tmp4);
$img[4] = new imgresize_class();
$img[4]->load(PUBLIC_DIR . 'tmp/' . $tmp5);
$result = new imgresize_class();
$result->joinHorizontal($img);
$result->save(PUBLIC_DIR . 'tmp/2.jpg', IMAGETYPE_JPEG, 10);
//$res->resizeToWidth(200);
//$res->sharpen();
//$res->save(PUBLIC_DIR . 'tmp/2.jpg', IMAGETYPE_JPEG, 10);

exit;
$bot = new bot_class();
$bot->sendPhoto(PUBLIC_DIR . 'tmp/1.jpg', 'aa ksdfnksdgn sdfglksdnf jldksfnjdsk lksdfgklsdlkfj lkdslfkjd kjsdifjdks kjdksfjk', [], '143220442');
exit;
$card = 2;
$img = [];
$img[0] = new imgresize_class();
$filename = PROTECTED_DIR . 'assets/images/slots/card_' . $card . '.jpg';
$img[0]->load($filename);
for($i = 1; $i <= 5; $i ++) {
    $image[0] = new imgresize_class();
    $image[0]->load(PROTECTED_DIR . 'assets/images/slots/b_stripe.jpg');
    $img[$i] = new imgresize_class();
    $img[$i]->load(PROTECTED_DIR . 'assets/images/slots/card_back.jpg');
}
$res = new imgresize_class();
$res->joinHorizontal($img);
$file_name = PROTECTED_DIR . 'assets/images/slots/question_' . $card . '.jpg';
$res->save($file_name, IMAGETYPE_JPEG, 10);
exit;
$res = [];
for($i = 0; $i <= 1000000; $i++) {
    $rand = slot_service::getRand(2, 100);
    if($res[$rand]) {
        $res[$rand] ++;
    } else {
        $res[$rand] = 1;
    }
}
ksort($res);
print_r($res);exit;
$potential = 100000;
$pow = slot_service::getPow(1, $potential);
print_r($pow);
$max_order = slot_service::getRandX(count($pow) - 1);
$min_order = slot_service::getRandX($max_order);
echo $min_order . ' - ' . $max_order;
exit;


$potential = 0;

$res = [];
$bets = 0;
$bet = 2;
$spins = 100000;
for($i = 1; $i <= $spins; $i ++) {
    $potential += $bet - $bet * 0.1;
    $bets += $bet;
    $pow = slot_service::getPow(2, rand(0, $potential));
    $order = slot_service::getRandOrder(count($pow) - 1);
    $potential -= $pow[$order];
    if($res[$pow[$order]]) {
        $res[$pow[$order]] += 1;
    } else {
        $res[$pow[$order]] = 1;
    }
}
ksort($res);

$count = 0;
foreach ($res as $k => $re) {
    $count += $k*$re;
    $res[$k] = $spins/$re;
}
echo $count . "\n";
echo $bets;

print_r($res);
