<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>Document</title>
<link href="/sncss/css/style.css" rel="stylesheet" type="text/css" />
<script src="/js/jquery.js"></script>
<script type="text/javascript">
    function del(ob){
        //alert(ob);
        var obj = $(ob);
        var id = obj.parent().parent().children().eq(0).html();
        $.post("/admin.php/Shop/Index/delOrderform",{id:id},function(data){
            //alert(data);
            if(data){
                alert("删除成功");
                obj.parent().parent().remove();
            }else{
                alert("删除失败");
            }
        });
    }
    function delivery(ob){
        //alert(ob);
        $.post("/admin.php/Shop/Index/delivery",{id:ob},function(data){
            if(data){
                alert("修改成功");
                history.go(0);
            }else{
                alert("修改失败");
                history.go(0);
            }
        });
    }
</script>   
</head>
<body>
    <div class="place">
    <span>位置：</span>
    <ul class="placeul">
    <li><a href="#">首页</a></li>
    <li><a href="#">分类管理</a></li>
    </ul>
    </div>

   <div class="formbody">
    
    <div class="formtitle"><span>基本信息</span></div>
        
     <table class="tablelist">
        <thead>
        <tr>
        <th>id</th>
        <th>会员编号<i class="sort"><img src="/sncss/images/px.gif" /></i></th>
        <th>姓名</th>
        <th>名称</th>
        <th>产品数量</th>
        <th>产生时间</th>
        <th>收货地址</th>
        <th colspan="4">状态</th>
        
       
        
        </tr>
        
        </thead>
        <tbody>
        
        <?php if(is_array($list)): foreach($list as $key=>$v): ?><tr>
         <td><?php echo ($v["id"]); ?></td>
         <td><?php echo ($v["user"]); ?></td>
         <td><?php echo ($v["username"]); ?></td>
         <td><?php echo ($v["project"]); ?></td>
         <td><?php echo ($v["count"]); ?></td>
         <td><?php echo ($v["addtime"]); ?></td>  
         <td><?php echo ($v["address"]); ?></td>
         <td onclick="delivery(<?php echo ($v["id"]); ?>)" style="margin-left:10px;cursor:pointer;">
            <?php if($v["zt"] == 0): ?>确认发货<?php endif; ?>
            <?php if($v["zt"] == 1): ?>已发货<?php endif; ?>
            <?php if($v["zt"] == 2): ?>用户确认<?php endif; ?>      
         </td>       
<!--          <td>
   <a onclick="" style='margin-left:10px;cursor:pointer;' href="/admin.php/Shop/Index/project/id/<?php echo ($v["id"]); ?>" >修改</a></td> -->
         <td><a onclick="del(this)" style='margin-left:10px;cursor:pointer;'>删除</a></td>
        <!--  <?php if($v["zt"] == 0): ?><td><a onclick="upp()" style='margin-left:10px;cursor:pointer;' href="/admin.php/Shop/Index/ztProject/id/<?php echo ($v["id"]); ?>/zt/1">上架</a></td>
        <?php else: ?>    
           <td><a onclick="down(this)" style='margin-left:10px;cursor:pointer;' href="/admin.php/Shop/Index/ztProject/id/<?php echo ($v["id"]); ?>/zt/0">下架</a></td><?php endif; ?>
        <?php if($v["zt"] == 2): ?><td><a onclick="upVip(this)" style='margin-left:10px;cursor:pointer;'href="/admin.php/Shop/Index/ztProject/id/<?php echo ($v["id"]); ?>/zt/1">取消</a></td>
        <?php else: ?>    
           <td><a onclick="downVip(this)" style='margin-left:10px;cursor:pointer;'href="/admin.php/Shop/Index/ztProject/id/<?php echo ($v["id"]); ?>/zt/2">推荐</a></td><?php endif; ?>           -->
         </tr><?php endforeach; endif; ?>
        </tbody>
    </table>
<style>.pages a,.pages span {
    display:inline-block;
    padding:2px 5px;
    margin:0 1px;
    border:1px solid #f0f0f0;
    -webkit-border-radius:3px;
    -moz-border-radius:3px;
    border-radius:3px;
}
.pages a,.pages li {
    display:inline-block;
    list-style: none;
    text-decoration:none; color:#58A0D3;
}
.pages a.first,.pages a.prev,.pages a.next,.pages a.end{
    margin:0;
}
.pages a:hover{
    border-color:#50A8E6;
}
.pages span.current{
    background:#50A8E6;
    color:#FFF;
    font-weight:700;
    border-color:#50A8E6;
}</style>
   
   <div class="pages"><br />
                <div align="right"><?php echo ($page); ?>
     </div>
   </div>   
    </div>  
</body>
</html>