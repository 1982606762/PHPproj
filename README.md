
![](https://img.shields.io/badge/license-Anti_996-blue.svg)
![](https://img.shields.io/badge/license-MIT-black.svg)
![](https://img.shields.io/badge/language-php-orange.svg)
[![](https://img.shields.io/badge/cnblogs-@Noone-green.svg?colorA=abcdef)](https://blog.csdn.net/weixin_45120915?spm=1010.2135.3001.5113)
![](https://img.shields.io/badge/platform-Windows/MacOS/Linux-lightgrey.svg)
<a href="https://travis-ci.org/onevcat/Kingfisher"><img src="https://img.shields.io/travis/onevcat/Kingfisher/master.svg"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>

# 项目背景
For celebrating the new year, we are holding a 5-days long Carnival. 
Required by the sponsor of the carnival, we need to implement a “Carnival 
Booking System” , which will manage users’ registration, reservation and onsite checking-in of the Carnival. This system will allow people to register a 
user in it, when the user log in, he/she would be able to reserve a ticket for 
him(or her)-self. When the user arrives to the party place, he/she would be 
able to “check-in” offering his/her invitation code and corresponding 
password.

# 使用方法
0. 在本机安装[mamp](https://www.mamp.info/)并启动，打开phpmyadmin数据库管理页面新建名为laravel的数据库

1. clone代码到本地

2. 打开命令行并cd到代码目录

3. 使用 `composer install`

4. 使用 `yarn install`

5. 将 <b>.env.example</b>文件名改为 <b>.env</b>并将

   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=laravel
   DB_USERNAME=root
   DB_PASSWORD=
   ```

   字段改为本地数据库相关信息

6. 命令行中输入`php artisan serve`即可启动服务器
