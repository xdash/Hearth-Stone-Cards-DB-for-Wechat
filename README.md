Hearth-Stone-Cards-DB-for-Wechat
================================

[《炉石传说：魔兽英雄传》](http://www.hearthstone.com.cn/ "炉石传说")是暴雪娱乐开发的首款休闲卡牌游戏，在魔兽的世界观基础上吸纳借鉴了万智牌的玩法思路，并加入了卡牌商店、冶炼铸造、PVP对战、竞技场/天梯系统，并已于1月24日全球同步公测。时值春节期间，宅在家休养的小伙伴中发展出了好几枚炉石玩家，闲来对战，不亦乐乎。

作为十年前就跳入日本集换式卡牌《游戏王》这个无底大坑的IT死宅，此前我曾使用[新浪的应用托管服务（Sina App Engine，SAE）](http://sae.sina.com.cn/ "SAE")+PHP 开发过[微信上的游戏王卡牌查询器](http://www.fanbing.net/yugioh-card-search-tool-for-wechat.html)，其目前已自然积累了近 5000 粉丝，每日活跃查询数百次。在去年年底网易内测炉石传说期间，我第一时间搞到了内测码，并在小玩几把之后为其迷醉，遂通宵一晚在游戏王微信卡查的基础上，新开发了一套微信上的炉石传说卡牌查询器。

**微信搜索账号：myhearthstone，或查找公众号“炉石传说卡牌游戏”即可关注体验。（顺便说一下，游戏王那个的微信账号是ourocg）**

现本人将这套炉石传说微信卡牌查询器的源码托管至 Github 予诸君分享。项目地址：
[https://github.com/xdash/Hearth-Stone-Cards-DB-for-Wechat](https://github.com/xdash/Hearth-Stone-Cards-DB-for-Wechat)

在此简单对目录结构和使用到的 SAE 服务做番介绍。

#### 1、目录结构：

* config.yaml - SAE 的项目配置文件，除了必填的项目名称和版本号外，启用我使用了 SAE 的cron组件。
* wxheartstone.php - 主程序，微信公众平台调用的接口文件，定义了收到消息并处理返回的机制。
* getcardapi.php - 供主程序调用的功能模块，专门负责查询卡片信息。
* config.php - 参数配置文件，独立出来。
* strings.php - 静态文本配置文件，抽取出来。
* duowan_cards_crawl.php - 网站爬虫，独立工作，通过 cron 组件每隔72小时去多玩的炉石传说卡牌数据库抓取最新卡牌数据，并存入 MySQL 数据库。

#### 2、安装/配置方法：

1. 在 SAE 创建应用（假设名为 myapp）。
2. 修改 config.yaml 文件，将 name 值改为上述创建的应用名称（myapp）。
3. 在 SAE 的应用目录下创建名为“1”（不包含引号）的文件夹；将所有文件提交至该文件夹下（Windows 用户可参照SAE官方文档使用 TortoiseSVN 提交，我使用的则是 Mac+svnX）。
4. 注册微信公众平台账号，切换到开发者模式，在接口调用文件处填写 http://myapp.sinaapp.com/wxhearthstone.php（注意此处 myapp 替换成你的应用名称），Token 处填写 config.php 中设定的 Token 值（可随意自定）。
5. 至此微信配置完成。再在浏览器里运行一下 http://myapp.sinaapp.com/duowan_cards_crawl.php 去首次抓取一下卡牌数据吧。
6. 全部OK。在微信上给账号发个关键词，就会返回相应的卡片查询结果。

#### 3、使用的 SAE 服务：

这里除了托管服务之外，使用了 SAE 的 cron 和 MySQL 两个服务。

##### 1）Cron（分布式定时服务）

文档：[http://sae.sina.com.cn/?m=devcenter&catId=195](http://sae.sina.com.cn/?m=devcenter&catId=195)

简单说就是可以每隔一定时间（或等到指定时间触发），自动去执行一个HTTP的脚本任务。使用场景如计算每日排行榜、特定节假日发送祝福等。这里我使用 cron 是因为炉石传说尚在公测期间，存在卡牌 Bug 并不断有新鲜卡牌推出，人工维护这套系统的成本比较高，于是我找到了多玩的卡牌数据库（谢天谢地），让爬虫自己去抓了。

##### 2）MySQL 数据库

文档：[http://sae.sina.com.cn/?m=devcenter&catId=192](http://sae.sina.com.cn/?m=devcenter&catId=192)

SAE 默认并未开启 MySQL 功能，如要使用，需在后台点击初始化从而开启。标准的 phpMyAdmin 后台能快速可视化地创建和查看表结构。5GB的配额也足以满足小型产品的需要（对于我这种更是绰绰有余）。

SAE 提供了基于 MySQL 的 SaeMysql 类，只要是托管到 SAE 的应用，连接数据库时可以不必指定数据库名/用户名/密码，直接使用*预定义常量*即可：

* 用户名　 :  SAE_MYSQL_USER
* 密　　码 :  SAE_MYSQL_PASS
* 主库域名 :  SAE_MYSQL_HOST_M
* 从库域名 :  SAE_MYSQL_HOST_S
* 端　　口 :  SAE_MYSQL_PORT
* 数据库名 :  SAE_MYSQL_DB

#### 4、为什么使用 SAE?

* 轻量级的应用托管和配套常规服务，使用 SAE 只需五分钟即可快速创建，十分钟内完成 Hello World 级的部署，极其简易。
* 支持PHP/Java/Python。
* 免费用户的云豆是有限制的，随着应用的运行会逐渐消耗，云豆用完需要付费充值。但 SAE 提供了开发者认证这套机制，只需达到并不苛刻的条件就能升级到中级/高级甚至资深开发者。获得认证的开发者可享受云豆补助及其他优惠条件。例如我现在已经是高级开发者认证，每月可补助1.5万云豆，完全使用不完了哈哈。

