PHP клиент для отправки евентов в convead
-------------------

**Пример инициализации библиотеки**
```php
include_once('ConveadTracker.php');
$app_key      = 'API_KEY'; // APP-ключ вашего аккаунта, можно найти здесь: http://take.ms/Ejv3q
$host         = $_SERVER['HTTP_HOST']; // Должен совпадать с вашим доменом, указанным в настройках аккаунта
$visitor_uid  = (is_logged_in() ? $current_user_id : false); // Если юзер авторизован, то подставляется его id, иначе - false
$guest_uid    = (!empty($_COOKIE['convead_guest_uid']) ? $_COOKIE['convead_guest_uid'] : false);

$visitor_info = array(); // Информация о посетителе
if ($name) $visitor_info['first_name'] = $name;
if ($phone) $visitor_info['phone'] = $phone;
if ($email) $visitor_info['email'] = $email;
...

$convead = new ConveadTracker($app_key, $host, $guest_uid, $visitor_uid, $visitor_info);
```

**Пример отправки евента 'update_cart' (передает текущее содержимое корзины)**
```php
$products = $cart->products(); // массив товаров в корзине (массив может быть пустым, если корзина очищена)

$items = array();
foreach ($products as $product) {
  $items[] = array(
    'product_id' => $product['product_id'],
    'qnt' => $product['quantity'],
    'price' => $product['price']
  );
}
$convead->eventUpdateCart($items);
```

**Пример отправки евента 'purchase' (передает информацию о совершенной покупке)**
```php
$order_id   = $order->id; // id заказа
$products   = $order->products(); // массив товаров в заказе
$total_cost = $order->total_cost(); // итоговая стоимость заказа с учетом доставки и скидок

$items = array();
foreach ($products as $product) {
  $items[] = array(
    'product_id' => $product['product_id'],
    'qnt' => $product['quantity'],
    'price' => $product['price']
  );
}
$convead->eventOrder($order_id, $total_cost, $items);
```

**Пример отправки евента 'update_info' (передает только информацию о пользователе)**
```php
$convead->eventUpdateInfo();
```

**Пример отправки кастомного евента**
```php
$key        = 'callback'; // Ключ кастомного евента

$convead->eventCustom($key);
```

**Пример передачи статусов заказов**
```php
include_once('ConveadTracker.php');
$app_key      = 'API_KEY'; // APP-ключ вашего аккаунта, можно найти здесь: http://take.ms/Ejv3q
$host         = $_SERVER['HTTP_HOST']; // Должен совпадать с вашим доменом, указанным в настройках аккаунта

$order_id = 123; // id заказа
$state = 'shipped'; // статус заказа
...

$convead = new ConveadTracker($app_key, $host);
$convead->webHookOrderUpdate($order_id, $state);
```
