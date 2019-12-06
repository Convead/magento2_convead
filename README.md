Требования к CMS
----------------

Поддерживаются все версии Magento, начиная с 2.0 и более свежие.

Установка модуля в магазин
--------------------------

1. [Скачайте архив модуля](https://github.com/Convead/magento2_convead/archive/master.zip) из нашего репозитория.

2. Разархивируйте содержимое архива в директорию MagentoRoot/app/code/

3. В ssh консоли выполните комманды:

```
php bin/magento maintenance:enable
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento setup:static-content:deploy
php bin/magento maintenance:disable
```

4. Перейдите в настройки модуля и задайте app_key вашего аккаунта convead.
