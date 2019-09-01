### P2B投资平台源码（由于商业原因，不公开DB）###
	业务流程简介：后台投资项目增加 -> 设置开启可投标区间 -> 投资人账户充值 -> 选择要投资的项目->投标（100的倍数） -> 满标后投资金额冻结 -> 投标人可在个人中心查看投资收益（金额满100元可提现）
	项目运行2年 在安全性，以及高并发的处理都经历了实际的考验。
	并发处理：使用文件锁代替mysql事务，使用起来更灵活，减少对mysql事务的依赖。
	内置大转盘抽奖：概率算法准确，后台可配置。

**PHP Version:**<=5.6
**Mysql Version:**>=5.5

**文件结构：**
----/Class/  工具类库
----/common/ 全局公共函数库
----/Conf/ PC站及后台管理数据库配置
----/lock/ 文件锁存放目录
----/m/ 移动端手机站项目
----/pipei/ 项目匹配系统 （外链 不在本项目中）
----/Pc/ PC端项目模块
----/Manage/ 后台管理模块
----/Public/ 公共资源文件
----/Ueditor/ 百度富文本编辑器
----/Uploads/ 上传文件存储目录
----/VoiceVerify/ 短信平台日志
----/TP/ ThinkPHP核心
----/CCPRestSmsSDK.php 短信平台SDK文件
----/Manage.php 后台管理入口文件
----/SendTemplateSMS.php 短信通知模板文件
----/auto_exec.php 定时任务文件
----/index.php PC端主入口文件


**
本项目为未对接宝义互通（资金托管）版本 
由于商业原因不公开DB
本程序仅供学术交流，禁止用于商业目的
**
