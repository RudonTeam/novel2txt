<html>
    <head>
        <title>小说TXT生成器</title>
        <meta charset="utf-8"/>
        <link href="https://libs.baidu.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://libs.baidu.com/jquery/2.0.0/jquery.min.js"></script>
        <script src="https://libs.baidu.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
    </head>
    <body style="padding: 30px;">
        <h3 style="margin-bottom: 20px; text-align: center;">
            小说TXT生成器
            
        </h3>
        <form class="form-horizontal" action="" method="post" id="main-form">
            <div class="form-group">
                <label class="col-sm-3 control-label">小说目录网址</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" placeholder="" disabled="disabled" value="" id="url"/>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-3 control-label">小说名称</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" value="<!--novelTitlePlaceHolder-->" disabled="disabled"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">txt文件名称</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" required="required" placeholder="xxxx.txt        (为了避免乱码，请使用英文字母代替 -- <!--novelTitlePlaceHolder-->)" value="<!--txtFileNamePlaceHolder-->" name="txt" disabled="disabled"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">
                    <a href="#bottom" id="top"  class="btn btn-info">
                        <i class="glyphicon glyphicon-arrow-down"></i>
                    </a>
                </label>
                <div class="col-sm-5">
                    <a onclick="$(this).hide();$('#making').show();$('#main-form').submit();"  class="form-control btn btn-primary btn-block">
                        开始制作txt文件
                    </a>
                    <a href="javascript:;" id="making" style="display: none;"  class="form-control btn btn-primary btn-block">
                        正在制作中，预计5分钟，请稍后检查下载链接，本页可关闭。
                    </a>
                    <input type="hidden" name="submitact" class="form-control btn btn-primary btn-block" value="开始制作txt文件" />
                </div>
                <div class="col-sm-1">
                    <a href="javascript:history.go(-1);"  class="btn btn-block btn-danger">
                        返回
                    </a>
                </div>
            </div>
        </form>

        <div class="row">
            <div style="min-height: 100px; margin-top: 50px; cursor: default; font-size: 12px;" class="col-md-7 col-md-offset-2">
                <!--tablePlaceHolder-->
                <!--<table class="table table-bordered">
                    <tr>
                        <td>第一章 我的世界</td>
                        <td>
                            <a href="https://baidu.c/aasdasdasd/1234.html" target="_blank">
                                https://baidu.c/aasdasdasd/1234.html
                            </a>
                        </td>
                    </tr>
                </table>-->
            </div>
        </div>
        <div class="row" style="text-align: center;">
            <a href="#top" id="bottom" class="btn btn-block btn-info">
                <i class="glyphicon glyphicon-arrow-up"></i>
            </a>
        </div>
            
    </body>

</html>