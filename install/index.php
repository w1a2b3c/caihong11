<?php

//文件格式
header("Content-type: text/html; charset=utf-8");
//错误级别
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
//初始化
ini_set('display_errors', '1');
//定义web根目录
define('WWW_ROOT', dirname(__FILE__) . DIRECTORY_SEPARATOR);
// $runtimePath = str_replace(DIRECTORY_SEPARATOR . 'public', DIRECTORY_SEPARATOR . 'runtime', WWW_ROOT);
//定义后台名称
$config = [
    'siteName' => "Annie商城系统",
    'siteVersion' => "V4",
    'tablePrefix' => "shua"
];
//错误信息
$msg = '';
//安装文件
$lockFile = "./install.lock";
//数据库
$databaseConfigFile = "../config.php";

session_start();

// 判断文件或目录是否有写的权限
function is_really_writable($file)
{
    if (DIRECTORY_SEPARATOR === '/' and @ ini_get("safe_mode") == false) {
        return is_writable($file);
    }
    if (!is_file($file) or ($fp = @fopen($file, "r+")) === false) {
        return false;
    }
    fclose($fp);
    return true;
}
if (is_file($lockFile)) {
    $msg = "当前已经安装{$config['siteName']}，如果需要重新安装，请手动删除install/install.lock文件";
}
// 同意协议页面
if (@!isset($_GET['s']) || @$_GET['s'] === 'step1') {
    
    require_once './view/step1.html';
}
// 检测环境页面
if (@$_GET['s'] === 'step2') {
    if (version_compare(PHP_VERSION, '7.2.0', '<')) {
        $msg = "当前版本(" . PHP_VERSION . ")过低，请使用PHP7.2.0以上版本";
    } else {
        if (!extension_loaded("PDO")) {
            $msg = "当前未开启PDO，无法进行安装";
        } else {
            if (!is_really_writable($databaseConfigFile)) {
                $open_basedir = ini_get('open_basedir');
                if ($open_basedir) {
                    $dirArr = explode(PATH_SEPARATOR, $open_basedir);
                    if ($dirArr && in_array(__DIR__, $dirArr)) {
                        $msg = '当前服务器因配置了open_basedir，导致无法读取父目录<br>';
                    }
                }
                if (!$msg) {
                    $msg = '当前权限不足，无法写入配置文件config/database.php<br>';
                }
            }
        }
    }
    require_once './view/step2.html';
}
// 安装
if (@$_GET['s'] === 'step3') {
    if ($_GET['s'] === 'step3' && $_SERVER['REQUEST_METHOD'] === 'GET') require_once './view/step3.html';
    if ($_GET['s'] === 'step3' && isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
        if ($msg) {echo $msg;exit;}
        //执行安装
        $host = isset($_POST['hostname']) ? $_POST['hostname'] : '127.0.0.1';
        $port = isset($_POST['port']) ? $_POST['port'] : '3306';
        //判断是否在主机头后面加上了端口号
        $hostData = explode(":", $host);
        if (isset($hostData) && $hostData && is_array($hostData) && count($hostData) > 1) {
            $host = $hostData[0];
            $port = $hostData[1];
        }
        //mysql的账户相关
        $mysqlUserName = isset($_POST['username']) ? $_POST['username'] : 'root';
        $mysqlPassword = isset($_POST['password']) ? $_POST['password'] : 'root';
        $mysqlDatabase = isset($_POST['database']) ? $_POST['database'] : 'pre';
        $mysqlPreFix = isset($_POST['prefix']) ? $_POST['prefix'] : $config['tablePrefix'];
        $mysqlPreFix = rtrim($mysqlPreFix);
        //php 版本
        if (version_compare(PHP_VERSION, '7.4.0', '<')) {
            die("当前版本(" . PHP_VERSION . ")过低，请使用PHP7.4.0以上版本");
        }
        if (!extension_loaded("PDO")) {
            die ("当前未开启PDO，无法进行安装");
        }
        //检测能否读取安装文件
        $sql = @file_get_contents('./database/install.sql');
        if (!$sql) {
            throw new Exception("无法读取/database/install.sql文件，请检查是否有读权限");
        }
        try {
            // 连接数据库
            $link = @new mysqli("{$host}:{$port}", $mysqlUserName, $mysqlPassword);
            $error = $link->connect_error;
            if (!is_null($error)) {// 转义防止和alert中的引号冲突
                $error = addslashes($error);
                exit("数据库链接失败:$error");
            }
            if ($link->server_info < 5.5) {
                exit("MySQL数据库版本不能低于5.5,请将您的MySQL升级到5.5及以上");
            }
            // 创建数据库并选中
            if (!$link->select_db($mysqlDatabase)) {
                $create_sql = 'CREATE DATABASE IF NOT EXISTS ' . $mysqlDatabase . ' DEFAULT CHARACTER SET utf8;';
                $link->query($create_sql) or exit('创建数据库失败');
                $link->select_db($mysqlDatabase);
            }
            $link->query("USE `{$mysqlDatabase}`");//使用数据库
            // 写入数据库
            $date = date("Y-m-d");
            $sqlArr = file('./database/install.sql');
            $sql = '';
            foreach ($sqlArr as $value) {
                if (substr($value, 0, 2) == '--' || $value == '' || substr($value, 0, 2) == '/*')
                    continue;
                $sql .= $value;
                if (substr(trim($value), -1, 1) == ';' and $value != 'COMMIT;') {
                    $sql = str_ireplace("`ZC_", "`{$mysqlPreFix}_", $sql);
                    $sql = str_ireplace('INSERT INTO ', 'INSERT IGNORE INTO ', $sql);
                    try {
                        $link->query($sql);
                    } catch (\PDOException $e) {
                        exit($e->getMessage());
                    }
                    $sql = '';
                }
            }

	        $link->query("INSERT INTO `{$mysqlPreFix}_config` VALUES ('build', '".$date."')");
            $link->query("INSERT INTO `{$mysqlPreFix}_config` VALUES ('syskey', '".md5(time(),'QQ2769693841')."')");
            $link->query("INSERT INTO `{$mysqlPreFix}_config` VALUES ('cronkey', '".mt_rand(100000,999999)."')");
            sleep(2);
            $databaseConfig = @file_get_contents($databaseConfigFile);
            //替换数据库相关配置
            $config= "<?php
\$dbconfig=array(
'host' => '{$host}', //数据库服务器
'port' => {$port}, //数据库端口
'user' => '{$mysqlUserName}', //数据库用户名
'pwd' => '{$mysqlPassword}', //数据库密码
'dbname' => '{$mysqlDatabase}', //数据库名
'dbqz' => '{$mysqlPreFix}', //表前缀
);";
            $putConfig = @file_put_contents($databaseConfigFile, $config);
            if (!$putConfig) {
                exit('安装失败、请确定database.php是否有写入权限！:' . $error);
            }
            $adminName = '';
            $result = @file_put_contents($lockFile, 'ok');
            if (!$result) {
                exit("安装失败、请确定install.lock是否有写入权限！:$error");
            }
            $_SESSION['admin'] = 'admin';
            $_SESSION['password'] = '123456';
            $_SESSION['backend'] = '';
            echo $msg = 'success|' . $adminName;exit();
        } catch (\Exception $e) {
            $errMsg = $e->getMessage();
        }
        echo $errMsg;
        exit();
    }
}
//完成安装
if (@$_GET['s'] === 'step4') {
    require_once './view/step4.html';
}

?>

